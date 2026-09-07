<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\BranchStock;
use App\Models\StockHistory;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Memproses Pembayaran (Checkout)
     */
    public function store(Request $request)
    {
        $request->validate([
            'cart'          => 'required|array',
            'metode_bayar'  => 'required|string',
            'nominal_bayar' => 'required|numeric',
            'subtotal'      => 'required|numeric',
            'discount'      => 'required|numeric',
            'total'         => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $user     = $request->user();
            $kasirId  = $user ? $user->id : 3;
            $cabangId = $user ? $user->cabang_id : 1;
            $waktu    = Carbon::now();

            // 1. Generate Nomor Nota
            $lastTrx = Transaction::whereDate('tanggal_waktu', $waktu->toDateString())->count();
            $nomorNota = 'INV-' . $waktu->format('Ymd') . '-' . str_pad($lastTrx + 1, 4, '0', STR_PAD_LEFT);

            // Hitung kembalian
            $kembalian = max(0, $request->nominal_bayar - $request->total);

            // 2. Buat Data Transaksi Induk
            $transaction = Transaction::create([
                'kasir_id'         => $kasirId,
                'member_id'        => $request->member_id,
                'nomor_nota'       => $nomorNota,
                'tanggal_waktu'    => $waktu,
                'subtotal'         => $request->subtotal,
                'diskon_persen'    => $request->member_id ? 10 : 0,
                'diskon_nominal'   => $request->discount,
                'deskripsi_diskon' => $request->member_id ? 'Diskon Member' : null,
                'total_belanja'    => $request->total,
                'nominal_bayar'    => $request->nominal_bayar,
                'kembalian'        => $kembalian,
                'metode_bayar'     => strtolower($request->metode_bayar),
                'cabang_id'        => $cabangId,
            ]);

            // 3. Looping Keranjang untuk Detail Transaksi, Potong Stok, dan History
            foreach ($request->cart as $item) {
                $qtyOrMl = $item['unit'] === 'ml' ? $item['ml'] : $item['qty'];
                $hargaSatuan = $item['unit'] === 'ml' ? $item['pricePerMl'] : $item['price'];
                $subtotalItem = $item['unit'] === 'ml' ? $item['price'] : ($item['price'] * $item['qty']);

                // Insert Detail
                TransactionDetail::create([
                    'transaksi_id'  => $transaction->id,
                    'varian_id'     => $item['variantId'],
                    'qty'           => $qtyOrMl,
                    'harga_satuan'  => $hargaSatuan,
                    'subtotal'      => $subtotalItem,
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
                    'qty'           => -$qtyOrMl,
                    'keterangan'    => 'Penjualan ' . ($item['name'] ?? 'Item') . ($item['unit'] === 'ml' ? " ($qtyOrMl ml)" : ''),
                    'waktu'         => $waktu,
                ]);
            }

            DB::commit();

            // Load relasi agar Flutter langsung mendapat data lengkap untuk cetak struk
            $transaction->load(['details.variant.product', 'member']);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses.',
                'data'    => $transaction
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mencari Member berdasarkan No HP
     */
    public function searchMember(Request $request)
    {
        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        $member = Member::where('no_telp', $phone)
                    ->orWhere('no_telp', 'like', "%{$phone}%")
                    ->first();

        if (!$member) {
            return response()->json([
                'success' => true,
                'found'   => false,
                'message' => 'Member tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'found'   => true,
            'data'    => [
                'id'     => $member->id,
                'name'   => $member->nama,
                'points' => $member->poin ?? 0,
            ]
        ]);
    }

    /**
     * Menampilkan Riwayat Transaksi Hari Ini beserta Statistik
     */
    public function history(Request $request)
    {
        $user = $request->user();
        $kasirId = $user ? $user->id : 3;
        $today = Carbon::today();

        // Query dasar: transaksi oleh kasir ini, pada hari ini
        $baseQuery = Transaction::with('member')
            ->where('kasir_id', $kasirId)
            ->whereDate('tanggal_waktu', $today);

        // 1. Kalkulasi Quick Stats
        $totalPendapatan = (clone $baseQuery)->sum('total_belanja');
        $totalTransaksi = (clone $baseQuery)->count();
        $tunai = (clone $baseQuery)->where('metode_bayar', 'cash')->sum('total_belanja');
        $qrisTransfer = (clone $baseQuery)->whereIn('metode_bayar', ['qris', 'transfer'])->sum('total_belanja');
        $tempo = (clone $baseQuery)->where('metode_bayar', 'tempo')->sum('total_belanja');

        // 2. Terapkan Filter Pencarian
        $query = clone $baseQuery;

        if ($request->filled('search')) {
            $query->where('nomor_nota', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('metode')) {
            if ($request->metode === 'qris_transfer') {
                $query->whereIn('metode_bayar', ['qris', 'transfer']);
            } elseif ($request->metode !== 'semua') {
                $query->where('metode_bayar', $request->metode);
            }
        }

        // 3. Ambil data dengan Pagination
        $transactions = $query->orderBy('tanggal_waktu', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Data riwayat transaksi berhasil diambil.',
            'data' => [
                'stats' => [
                    'total_pendapatan' => (double) $totalPendapatan,
                    'total_transaksi'  => $totalTransaksi,
                    'tunai'            => (double) $tunai,
                    'qris_transfer'    => (double) $qrisTransfer,
                    'tempo'            => (double) $tempo,
                    'today_date'       => $today->toDateString(),
                ],
                'transactions' => $transactions
            ]
        ]);
    }

    /**
     * Menampilkan detail satu transaksi spesifik (Pengganti halaman success web)
     * Dapat dipanggil dari Flutter untuk melihat/cetak ulang struk nota tertentu
     */
    public function show($id)
    {
        $transaction = Transaction::with(['member', 'details.variant.product'])->find($id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail transaksi berhasil diambil.',
            'data'    => $transaction
        ]);
    }
}
