<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\BranchStock;
use App\Models\Category;
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
            'subtotal'      => 'required|numeric',
            'discount'      => 'required|numeric',
            'total'         => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $kasirId  = auth()->id() ?? 3; // Fallback ke kasir ID 3 jika testing
            $cabangId = auth()->user()->cabang_id ?? 1;
            $waktu    = Carbon::now();

            // 1. Generate Nomor Nota (Format: INV-YYYYMMDD-XXXX)
            $lastTrx = Transaction::whereDate('tanggal_waktu', $waktu->toDateString())->count();
            $nomorNota = 'INV-' . $waktu->format('Ymd') . '-' . str_pad($lastTrx + 1, 4, '0', STR_PAD_LEFT);

            // Hitung kembalian
            $kembalian = max(0, $request->nominal_bayar - $request->total);

            // 2. Buat Data Transaksi Induk
            $transaction = Transaction::create([
                'kasir_id'         => $kasirId,
                'member_id'        => $request->member_id, // Bisa null
                'nomor_nota'       => $nomorNota,
                'tanggal_waktu'    => $waktu,
                'subtotal'         => $request->subtotal,
                'diskon_persen'    => $request->member_id ? 10 : 0, // Asumsi diskon member 10%
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
                    'qty'           => -$qtyOrMl, // Minus karena keluar
                    'keterangan'    => 'Penjualan ' . $item['name'] . ($item['unit'] === 'ml' ? " ($qtyOrMl ml)" : ''),
                    'waktu'         => $waktu,
                ]);
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
        $tempo = (clone $baseQuery)->where('metode_bayar', 'tempo')->sum('total_belanja');

        // 2. Terapkan Filter Pencarian & Dropdown untuk Tabel
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

        return view('kasir.pos.history', compact(
            'transactions', 'totalPendapatan', 'totalTransaksi',
            'tunai', 'qrisTransfer', 'tempo', 'today'
        ));
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
        $transaction = Transaction::with('member')->findOrFail($trxId);

        return view('kasir.pos.success', compact('transaction'));
    }
}


