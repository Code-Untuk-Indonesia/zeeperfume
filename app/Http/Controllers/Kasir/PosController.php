<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\BranchStock;
use App\Models\Category;
use App\Models\Member;
use App\Models\Product;
use App\Models\StockHistory;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Menampilkan halaman transaksi (POS) Kasir
     */
    public function index()
    {
        // Ambil ID Cabang dari Kasir yang sedang login (Fallback ke 1 jika null)
        $cabangId = auth()->user()->cabang_id ?? 1;

        // Ambil semua kategori untuk tombol filter
        $categories = Category::all();

        // Ambil produk (yang fisiknya ada), beserta varian dan stok di cabang kasir ini
        $products = Product::with(['category', 'variants' => function ($query) use ($cabangId) {
            $query->with(['branchStocks' => function ($stockQuery) use ($cabangId) {
                $stockQuery->where('cabang_id', $cabangId);
            }]);
        }])
        ->where('tipe_stok', 'ada_stok')
        ->get();

        return view('kasir.pos.index', compact('categories', 'products'));
    }

    /**
     * Memproses dan menyimpan transaksi ke Database
     */
    public function store(Request $request)
    {
        $request->validate([
            'cart'          => 'required|array',
            'metode_bayar'  => 'required|string',
            'nominal_bayar' => 'required|numeric',
            'subtotal'      => 'required|numeric', // Subtotal dasar (sebelum diskon global)
            'discount'      => 'required|numeric', // Diskon global / tambahan
            'total'         => 'required|numeric', // Total akhir
        ]);

        DB::beginTransaction();
        try {
            $kasirId  = auth()->id() ?? 3; // Fallback ke kasir ID 3 jika testing
            $cabangId = auth()->user()->cabang_id ?? 1;
            $waktu    = Carbon::now();

            // 1. Generate Nomor Nota (Format: INV-YYYYMMDD-XXXX)
            $lastTrx = Transaction::whereDate('tanggal_waktu', $waktu->toDateString())->count();
            $nomorNota = 'INV-' . $waktu->format('Ymd') . '-' . str_pad($lastTrx + 1, 4, '0', STR_PAD_LEFT);

            // Hitung kembalian (hanya jika tunai)
            $kembalian = strtolower($request->metode_bayar) === 'cash'
                            ? max(0, $request->nominal_bayar - $request->total)
                            : 0;

            // Jika metode non-cash, nominal bayar dianggap pas (sama dengan total tagihan)
            $nominalBayarAsli = strtolower($request->metode_bayar) !== 'cash' && !in_array(strtolower($request->metode_bayar), ['tempo', 'cash_tempo'])
                                ? $request->total
                                : $request->nominal_bayar;

            // 2. Buat Data Transaksi Induk
            $transaction = Transaction::create([
                'kasir_id'         => $kasirId,
                'member_id'        => $request->member_id, // Bisa null
                'nomor_nota'       => $nomorNota,
                'tanggal_waktu'    => $waktu,
                'subtotal'         => $request->subtotal, // Subtotal sebelum diskon global
                'diskon_persen'    => $request->diskon_persen ?? 0,
                'diskon_nominal'   => $request->discount, // Total diskon tambahan / poin
                'deskripsi_diskon' => $request->is_point_used ? 'Tukar Poin Member' : ($request->discount > 0 ? 'Diskon Manual/Persen' : null),
                'total_belanja'    => $request->total,
                'nominal_bayar'    => $nominalBayarAsli,
                'kembalian'        => $kembalian,
                'metode_bayar'     => strtolower($request->metode_bayar),
                'cabang_id'        => $cabangId,
                // Status approval (default dari migration adalah 'none')
            ]);

            // 3. Looping Keranjang untuk Detail Transaksi, Potong Stok, dan History
            foreach ($request->cart as $item) {
                $qtyOrMl = $item['unit'] === 'ml' ? $item['ml'] : $item['qty'];
                $hargaSatuan = $item['unit'] === 'ml' ? $item['pricePerMl'] : $item['price'];

                // Hitung Harga Dasar Item
                $subtotalItemDasar = $item['unit'] === 'ml' ? $item['price'] : ($hargaSatuan * $item['qty']);

                // Potong dengan diskon per item (jika ada input dari kasir)
                $diskonItem = $item['itemDiscount'] ?? 0;
                $subtotalFinalItem = max(0, $subtotalItemDasar - $diskonItem);

                // Insert Detail Transaksi
                TransactionDetail::create([
                    'transaksi_id'  => $transaction->id,
                    'varian_id'     => $item['variantId'],
                    'qty'           => $qtyOrMl,
                    'harga_satuan'  => $hargaSatuan,
                    // Opsional: Jika Anda punya kolom 'diskon' di tabel transaction_details, simpan $diskonItem di sana.
                    'subtotal'      => $subtotalFinalItem,
                ]);

                // Kurangi Stok Cabang
                $stock = BranchStock::where('cabang_id', $cabangId)
                                    ->where('varian_id', $item['variantId'])
                                    ->first();
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
                    'qty'           => -$qtyOrMl, // Minus karena keluar
                    'keterangan'    => 'Penjualan ' . $item['name'] . ($item['unit'] === 'ml' ? " ($qtyOrMl ml)" : ''),
                    'waktu'         => $waktu,
                ]);
            }

            // 4. Catatan Piutang jika Metode Kasbon/Tempo
            if (in_array(strtolower($request->metode_bayar), ['tempo', 'cash_tempo'])) {
                $sisaPiutang = max(0, $request->total - $request->nominal_bayar);
                $tanggalJatuhTempo = $request->cash_tempo['tanggal_jatuh_tempo'] ?? Carbon::now()->addDays(7)->format('Y-m-d');

                \App\Models\CashTempo::create([
                    'transaksi_id'        => $transaction->id,
                    'total_hutang'        => $request->total,
                    'jumlah_bayar'        => $request->nominal_bayar,
                    'sisa_piutang'        => $sisaPiutang,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                    'status'              => $sisaPiutang > 0 ? 'belum_lunas' : 'lunas'
                ]);
            }

            // 5. Potong Poin Member (Jika menggunakan opsi Tukar Poin)
            if ($request->member_id && $request->is_point_used && $request->used_points > 0) {
                $member = Member::find($request->member_id);
                if ($member) {
                    $member->decrement('poin', $request->used_points);
                }
            }

            DB::commit();

            // Kembalikan response sukses beserta ID transaksi untuk dicetak di halaman success
            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'redirect_url' => url('kasir/pos/success?trx_id=' . $transaction->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    /**
     * Mencari Member berdasarkan No HP via AJAX
     */
    public function searchMember(Request $request)
    {
        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        $member = \App\Models\Member::where('no_telp', $phone)
                    ->orWhere('no_telp', 'like', "%{$phone}%")
                    ->first();

        if (!$member) {
            return response()->json(['success' => true, 'found' => false]);
        }

        return response()->json([
            'success' => true,
            'found'   => true,
            'member'  => [
                'id'     => $member->id,
                'name'   => $member->nama,
                'points' => $member->poin ?? 0, // Pastikan kolom poin ada di tabel members
            ]
        ]);
    }

    /**
     * Menampilkan form tambah member dari kasir
     */
    public function createMember()
    {
        return view('kasir.member.create');
    }

    /**
     * Memproses penyimpanan data member baru
     */
    public function storeMember(Request $request)
    {
        // 1. Validasi Input (Menyesuaikan dengan "name" di HTML Anda)
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:members,no_telp', // Nomor tidak boleh kembar
        ], [
            'phone.unique' => 'Nomor HP ini sudah terdaftar sebagai member.',
        ]);

        // 2. Buat ID Member (Contoh: MEM-20260906-001)
        $lastMember = Member::latest('id')->first();
        $nextId = $lastMember ? $lastMember->id + 1 : 1;
        $kodeMember = 'MEM-' . date('Ymd') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // 3. Simpan ke Database
        Member::create([
            'kode_member' => $kodeMember,
            'nama'        => $request->name,
            'no_telp'     => $request->phone,
            'poin'        => 0, // Member baru poinnya 0
            'status'      => 'aktif'
        ]);

        // 4. Arahkan kembali ke halaman POS dengan pesan sukses
        // NOTE: Kasir bisa menangkap session 'success' ini menggunakan Javascript alert jika diperlukan
        return redirect()->route('kasir.pos')->with('success', 'Member baru berhasil didaftarkan!');
    }

    public function history(Request $request)
    {
        $kasirId = auth()->id() ?? 3; // Ganti 3 dengan ID kasir default jika auth kosong saat testing
        $today = Carbon::today();

        // Query dasar: transaksi oleh kasir ini, pada hari ini
        $baseQuery = Transaction::with('member')
            ->where('kasir_id', $kasirId)
            ->whereDate('tanggal_waktu', $today);

        // 1. Kalkulasi Quick Stats (Dihitung sebelum filter pencarian diterapkan)
        $totalPendapatan = (clone $baseQuery)->sum('total_belanja');
        $totalTransaksi = (clone $baseQuery)->count();
        $tunai = (clone $baseQuery)->where('metode_bayar', 'cash')->sum('total_belanja');
        $qrisTransfer = (clone $baseQuery)->whereIn('metode_bayar', ['qris', 'transfer'])->sum('total_belanja');
        $tempo = (clone $baseQuery)->whereIn('metode_bayar', ['tempo', 'cash_tempo'])->sum('total_belanja');

        // 2. Terapkan Filter Pencarian & Dropdown untuk Tabel
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

        // 3. Ambil data dengan Pagination
        $transactions = $query->orderBy('tanggal_waktu', 'desc')->paginate(10);

        return view('kasir.pos.history', compact(
            'transactions', 'totalPendapatan', 'totalTransaksi',
            'tunai', 'qrisTransfer', 'tempo', 'today'
        ));
    }

    /**
     * Mengambil detail satu transaksi milik kasir yang sedang login.
     */
    public function detail(int $transactionId)
    {
        $kasirId = auth()->id() ?? 3;

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

        $transaction->cash_tempo = DB::table('cash_tempo')
            ->where('transaksi_id', $transaction->id)
            ->first();

        $transaction->shipment = DB::table('shipments')
            ->where('transaksi_id', $transaction->id)
            ->first();

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
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }

    /**
     * Menampilkan halaman sukses setelah pembayaran
     */
    public function success(Request $request)
    {
        $trxId = $request->query('trx_id');

        // Jika tidak ada ID transaksi, kembalikan ke kasir
        if (!$trxId) {
            return redirect()->route('kasir.pos');
        }

        // Ambil data transaksi beserta data member (jika ada)
        $kasirId = auth()->id() ?? 3;
        $transaction = Transaction::with([
            'member',
            'cashier',
            'branch',
            'details.variant.product',
            'cashTempo',
            'shipment',
        ])->where('kasir_id', $kasirId)->findOrFail($trxId);

        return view('kasir.pos.success', compact('transaction'));
    }


}
