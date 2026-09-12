<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchStock;
use App\Models\Member;
use App\Models\StockHistory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    /**
     * Memproses dan menyimpan transaksi ke Database (Checkout)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cart'          => 'required|array',
            'metode_bayar'  => ['required', Rule::in(['cash', 'qris', 'transfer', 'cash_tempo'])],
            'nominal_bayar' => 'required|numeric|min:0',
            'subtotal'      => 'required|numeric',
            'discount'      => 'required|numeric',
            'total'         => 'required|numeric',
            'member_id'     => [
                'nullable',
                'integer',
                Rule::exists('members', 'id')->whereNull('deleted_at'),
            ],
            'is_point_used' => 'sometimes|boolean',
            'used_points'   => 'sometimes|integer|min:0',
            'diskon_persen' => 'sometimes|numeric|min:0|max:100',
            'cash_tempo'    => 'nullable|required_if:metode_bayar,cash_tempo|array',
            'cash_tempo.tanggal_jatuh_tempo' => 'required_if:metode_bayar,cash_tempo|date|after_or_equal:today',
            'cash_tempo.catatan_penagihan' => 'nullable|string|max:1000',
        ]);

        $metodeBayar = strtolower($validated['metode_bayar']);
        $totalBelanja = (float) $validated['total'];
        $nominalBayar = (float) $validated['nominal_bayar'];

        // Validasi Logika Bisnis Pembayaran
        if ($metodeBayar === 'cash' && $nominalBayar < $totalBelanja) {
            return response()->json(['success' => false, 'message' => 'Nominal uang tunai kurang dari total tagihan.'], 400);
        }

        if ($metodeBayar === 'cash_tempo' && $nominalBayar > $totalBelanja) {
            return response()->json(['success' => false, 'message' => 'Pembayaran awal cash tempo tidak boleh melebihi total tagihan.'], 400);
        }

        DB::beginTransaction();
        try {
            $user     = $request->user();
            $kasirId  = $user ? $user->id : 3;
            $cabangId = $user ? $user->cabang_id : 1;
            $waktu    = Carbon::now();

            // 1. Generate Nomor Nota
            $lastTrx = Transaction::whereDate('tanggal_waktu', $waktu->toDateString())->count();
            $nomorNota = 'INV-' . $waktu->format('Ymd') . '-' . str_pad($lastTrx + 1, 4, '0', STR_PAD_LEFT);

            // Hitung kembalian (hanya jika tunai)
            $kembalian = $metodeBayar === 'cash' ? max(0, $nominalBayar - $totalBelanja) : 0;

            // Jika metode non-cash, nominal bayar dianggap pas
            $nominalBayarAsli = ! in_array($metodeBayar, ['cash', 'cash_tempo'], true)
                ? $totalBelanja
                : $nominalBayar;

            // 2. Buat Data Transaksi Induk
            $transaction = Transaction::create([
                'kasir_id'         => $kasirId,
                'member_id'        => $validated['member_id'] ?? null,
                'nomor_nota'       => $nomorNota,
                'tanggal_waktu'    => $waktu,
                'subtotal'         => $validated['subtotal'],
                'diskon_persen'    => $validated['diskon_persen'] ?? 0,
                'diskon_nominal'   => $validated['discount'],
                'deskripsi_diskon' => ($validated['is_point_used'] ?? false) ? 'Tukar Poin Member' : ($validated['discount'] > 0 ? 'Diskon Manual/Persen' : null),
                'total_belanja'    => $totalBelanja,
                'nominal_bayar'    => $nominalBayarAsli,
                'kembalian'        => $kembalian,
                'metode_bayar'     => $metodeBayar,
                'cabang_id'        => $cabangId,
            ]);

            // 3. Looping Keranjang
            foreach ($validated['cart'] as $item) {
                $qtyOrMl = $item['unit'] === 'ml' ? $item['ml'] : $item['qty'];
                $hargaSatuan = $item['unit'] === 'ml' ? $item['pricePerMl'] : $item['price'];

                $subtotalItemDasar = $item['unit'] === 'ml' ? $item['price'] : ($hargaSatuan * $item['qty']);
                $diskonItem = $item['itemDiscount'] ?? 0;
                $subtotalFinalItem = max(0, $subtotalItemDasar - $diskonItem);

                $discountType = $item['discountType'] ?? 'rupiah';
                $discountInput = (float) ($item['discountInput'] ?? 0);

                // Insert Detail Transaksi
                TransactionDetail::create([
                    'transaksi_id'   => $transaction->id,
                    'varian_id'      => $item['variantId'],
                    'qty'            => $qtyOrMl,
                    'harga_satuan'   => $hargaSatuan,
                    'diskon_persen'  => $discountType === 'percent' ? $discountInput : 0,
                    'diskon_satuan'  => $diskonItem,
                    'catatan_diskon' => $diskonItem > 0 ? 'Diskon item' : null,
                    'subtotal'       => $subtotalFinalItem,
                ]);

                // Kurangi Stok Cabang
                $stock = BranchStock::where('cabang_id', $cabangId)->where('varian_id', $item['variantId'])->first();
                if ($stock) {
                    $stock->decrement('stok', $qtyOrMl);
                }

                // Catat di Stock History
                StockHistory::create([
                    'cabang_id'     => $cabangId,
                    'varian_id'     => $item['variantId'],
                    'user_id'       => $kasirId,
                    'transaksi_id'  => $transaction->id,
                    'jenis_riwayat' => 'penjualan',
                    'qty'           => -$qtyOrMl,
                    'keterangan'    => 'Penjualan ' . ($item['name'] ?? 'Item') . ($item['unit'] === 'ml' ? " ($qtyOrMl ml)" : ''),
                    'waktu'         => $waktu,
                ]);
            }

            // 4. Catatan Piutang jika Metode Cash Tempo
            if ($metodeBayar === 'cash_tempo') {
                $sisaPiutang = max(0, $totalBelanja - $nominalBayar);
                DB::table('cash_tempo')->insert([
                    'transaksi_id'        => $transaction->id,
                    'tanggal_jatuh_tempo' => $validated['cash_tempo']['tanggal_jatuh_tempo'],
                    'jumlah_piutang'      => $totalBelanja,
                    'sisa_piutang'        => $sisaPiutang,
                    'status_tempo'        => $sisaPiutang > 0 ? 'belum_lunas' : 'lunas',
                    'status_verifikasi'   => 'menunggu',
                    'catatan_penagihan'   => $validated['cash_tempo']['catatan_penagihan'] ?? null,
                    'created_at'          => $waktu,
                    'updated_at'          => $waktu,
                ]);
            }

            // 5. Potong Poin Member
            if (($validated['member_id'] ?? null) && ($validated['is_point_used'] ?? false) && ($validated['used_points'] ?? 0) > 0) {
                $member = Member::find($validated['member_id']);
                if ($member) {
                    $member->decrement('poin', $validated['used_points']);
                }
            }

            DB::commit();

            // Load data lengkap untuk di-return
            $transaction->load(['details.variant.product', 'member', 'cashTempo']);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses.',
                'data'    => $transaction,
                // Arahkan ke endpoint API yang baru
                'receipt_url' => url("api/transactions/{$transaction->id}/receipt")
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan riwayat transaksi harian dengan filter
     */
    public function history(Request $request)
    {
        $user = $request->user();
        $kasirId = $user ? $user->id : 3;
        $today = Carbon::today();

        $baseQuery = Transaction::with('member')
            ->where('kasir_id', $kasirId)
            ->whereDate('tanggal_waktu', $today);

        // 1. Kalkulasi Quick Stats
        $totalPendapatan = (clone $baseQuery)->sum('total_belanja');
        $totalTransaksi = (clone $baseQuery)->count();
        $tunai = (clone $baseQuery)->where('metode_bayar', 'cash')->sum('total_belanja');
        $qrisTransfer = (clone $baseQuery)->whereIn('metode_bayar', ['qris', 'transfer'])->sum('total_belanja');
        $tempo = (clone $baseQuery)->whereIn('metode_bayar', ['tempo', 'cash_tempo'])->sum('total_belanja');

        // 2. Terapkan Filter Pencarian
        $query = clone $baseQuery;

        if ($request->filled('search')) {
            $query->where('nomor_nota', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('metode')) {
            if ($request->metode === 'qris_transfer') {
                $query->whereIn('metode_bayar', ['qris', 'transfer']);
            } elseif ($request->metode === 'tempo') {
                $query->whereIn('metode_bayar', ['tempo', 'cash_tempo']);
            } elseif ($request->metode !== 'semua') {
                $query->where('metode_bayar', $request->metode);
            }
        }

        $transactions = $query->orderBy('tanggal_waktu', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_pendapatan' => (float) $totalPendapatan,
                    'total_transaksi'  => $totalTransaksi,
                    'tunai'            => (float) $tunai,
                    'qris_transfer'    => (float) $qrisTransfer,
                    'tempo'            => (float) $tempo,
                    'today_date'       => $today->toDateString(),
                ],
                'transactions' => $transactions
            ]
        ]);
    }

    /**
     * Mengambil detail satu transaksi spesifik milik kasir
     */
    public function show(Request $request, int $transactionId)
    {
        $user = $request->user();
        $kasirId = $user ? $user->id : 3;

        $transaction = DB::table('transactions')
            ->where('transactions.id', $transactionId)
            ->where('transactions.kasir_id', $kasirId)
            ->first();

        if ($transaction === null) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan atau bukan milik kasir ini.',
            ], 404);
        }

        $transaction->member = $transaction->member_id === null
            ? null
            : DB::table('members')
            ->where('id', $transaction->member_id)
            ->whereNull('deleted_at')
            ->first(['id', 'kode_member', 'nama', 'no_telp']);

        $transaction->cash_tempo = DB::table('cash_tempo')->where('transaksi_id', $transaction->id)->first();
        $transaction->shipment = DB::table('shipments')->where('transaksi_id', $transaction->id)->first();

        $transaction->details = DB::table('transaction_details')
            ->leftJoin('produk_varian', 'produk_varian.id', '=', 'transaction_details.varian_id')
            ->leftJoin('products', 'products.id', '=', 'produk_varian.produk_id')
            ->where('transaction_details.transaksi_id', $transaction->id)
            ->orderBy('transaction_details.id')
            ->select([
                'transaction_details.id',
                'transaction_details.varian_id',
                'transaction_details.qty',
                'transaction_details.harga_satuan',
                'transaction_details.diskon_persen',
                'transaction_details.diskon_satuan',
                'transaction_details.catatan_diskon',
                'transaction_details.subtotal',
                'produk_varian.sku',
                'produk_varian.nama_varian',
                'produk_varian.satuan',
                'products.nama_produk',
            ])
            ->get()
            ->map(function ($item) {
                $diskonNominal = (float) ($item->diskon_satuan ?? $item->diskon_item ?? $item->diskon_nominal ?? $item->item_discount ?? 0);
                $diskonPersen = (float) ($item->diskon_persen ?? $item->diskon_item_persen ?? $item->persen_diskon ?? 0);
                $qty = (int) ($item->qty ?? 0);
                $hargaSatuan = (float) ($item->harga_satuan ?? 0);
                $subtotal = (float) ($item->subtotal ?? max(0, ($hargaSatuan * $qty) - $diskonNominal));

                return [
                    'id' => $item->id,
                    'varian_id' => $item->varian_id,
                    'qty' => $qty,
                    'harga_satuan' => $hargaSatuan,
                    'diskon_persen' => $diskonPersen,
                    'diskon_satuan' => $diskonNominal,
                    'diskon_item' => $diskonNominal,
                    'item_discount' => $diskonNominal,
                    'diskon_item_nominal' => $diskonNominal,
                    'diskon_nominal' => $diskonNominal,
                    'diskon_per_item' => $diskonPersen,
                    'catatan_diskon' => $item->catatan_diskon,
                    'subtotal' => $subtotal,
                    'sku' => $item->sku,
                    'nama_varian' => $item->nama_varian,
                    'satuan' => $item->satuan,
                    'nama_produk' => $item->nama_produk,
                    'variant' => [
                        'id' => $item->varian_id,
                        'sku' => $item->sku,
                        'nama_varian' => $item->nama_varian,
                        'satuan' => $item->satuan,
                        'product' => [
                            'id' => $item->produk_id ?? null,
                            'nama_produk' => $item->nama_produk,
                        ],
                    ],
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $transaction,
            'receipt_url' => url("kasir/pos/receipt/{$transaction->id}")
        ]);
    }

    /**
     * Tampilan Resi HTML untuk Webview Mobile
     */
    public function receipt(Request $request, int $trx_id)
    {
        // Anda bisa menambahkan proteksi tambahan (opsional)
        // $kasirId = $request->user()->id;

        $transaction = Transaction::with([
            'member',
            'cashier',
            'branch',
            'details.variant.product',
            'cashTempo',
        ])->findOrFail($trx_id);

        // Mengembalikan HTML langsung, bukan JSON
        return view('kasir.pos.receipt', compact('transaction'));
    }
}
