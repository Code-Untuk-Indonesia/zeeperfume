<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockHistoryController extends Controller
{
    public function page(Request $request): View
    {
        $histories = DB::table('stock_histories')
            ->join('branches', 'branches.id', '=', 'stock_histories.cabang_id')
            ->join('produk_varian', 'produk_varian.id', '=', 'stock_histories.varian_id')
            ->join('products', 'products.id', '=', 'produk_varian.produk_id')
            ->leftJoin('users', 'users.id', '=', 'stock_histories.user_id')
            ->leftJoin('transactions', 'transactions.id', '=', 'stock_histories.transaksi_id')
            ->whereNull('branches.deleted_at')
            ->whereNull('produk_varian.deleted_at')
            ->whereNull('products.deleted_at')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.$request->string('search').'%';
                $query->where(function ($query) use ($search): void {
                    $query->where('branches.nama_cabang', 'like', $search)
                        ->orWhere('products.nama_produk', 'like', $search)
                        ->orWhere('produk_varian.nama_varian', 'like', $search)
                        ->orWhere('produk_varian.sku', 'like', $search)
                        ->orWhere('users.nama_lengkap', 'like', $search)
                        ->orWhere('stock_histories.keterangan', 'like', $search)
                        ->orWhere('transactions.nomor_nota', 'like', $search);
                });
            })
            ->when($request->filled('cabang_id'), fn ($query) => $query->where('stock_histories.cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('varian_id'), fn ($query) => $query->where('stock_histories.varian_id', $request->integer('varian_id')))
            ->when($request->filled('jenis_riwayat'), fn ($query) => $query->where('stock_histories.jenis_riwayat', (string) $request->string('jenis_riwayat')))
            ->when($request->filled('tanggal_mulai'), fn ($query) => $query->whereDate('stock_histories.waktu', '>=', $request->date('tanggal_mulai')))
            ->when($request->filled('tanggal_selesai'), fn ($query) => $query->whereDate('stock_histories.waktu', '<=', $request->date('tanggal_selesai')))
            ->select([
                'stock_histories.id',
                'stock_histories.cabang_id',
                'stock_histories.varian_id',
                'stock_histories.transaksi_id',
                'stock_histories.jenis_riwayat',
                'stock_histories.qty',
                'stock_histories.keterangan',
                'stock_histories.waktu',
                'branches.nama_cabang',
                'products.nama_produk',
                'produk_varian.nama_varian',
                'produk_varian.sku',
                'produk_varian.satuan',
                'users.nama_lengkap',
                'transactions.nomor_nota',
            ])
            ->orderByDesc('stock_histories.waktu')
            ->paginate(20)
            ->withQueryString();

        $branches = DB::table('branches')
            ->whereNull('deleted_at')
            ->orderBy('nama_cabang')
            ->get(['id', 'nama_cabang']);
        $variants = DB::table('produk_varian')
            ->join('products', 'products.id', '=', 'produk_varian.produk_id')
            ->whereNull('produk_varian.deleted_at')
            ->whereNull('products.deleted_at')
            ->orderBy('products.nama_produk')
            ->orderBy('produk_varian.nama_varian')
            ->get([
                'produk_varian.id',
                'produk_varian.sku',
                'produk_varian.nama_varian',
                'products.nama_produk',
            ]);
        $movementTypes = [
            'masuk' => 'Stok masuk',
            'keluar' => 'Stok keluar',
            'rusak' => 'Rusak',
            'penyesuaian' => 'Penyesuaian',
            'penjualan' => 'Penjualan',
        ];
        $rolePrefix = strtolower((string) ($request->user()?->role?->nama_role ?? 'owner')) === 'admin'
            ? 'admin'
            : 'owner';

        return view('stock.history.index', compact(
            'histories',
            'branches',
            'variants',
            'movementTypes',
            'rolePrefix',
        ));
    }

    public function index(Request $request): JsonResponse
    {
        $histories = DB::table('stock_histories')
            ->when($request->filled('cabang_id'), fn ($query) => $query->where('cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('varian_id'), fn ($query) => $query->where('varian_id', $request->integer('varian_id')))
            ->when($request->filled('jenis_riwayat'), fn ($query) => $query->where('jenis_riwayat', (string) $request->string('jenis_riwayat')))
            ->when($request->filled('tanggal_mulai'), fn ($query) => $query->whereDate('waktu', '>=', $request->date('tanggal_mulai')))
            ->when($request->filled('tanggal_selesai'), fn ($query) => $query->whereDate('waktu', '<=', $request->date('tanggal_selesai')))
            ->latest('waktu')
            ->paginate($request->integer('per_page', 25));

        $histories->setCollection(
            $histories->getCollection()
                ->map(fn (object $history): object => $this->hydrateHistory($history))
        );

        return response()->json($histories);
    }

    public function show(StockHistory $stockHistory): JsonResponse
    {
        $history = DB::table('stock_histories')
            ->where('id', $stockHistory->getKey())
            ->firstOrFail();

        return response()->json($this->hydrateHistory($history));
    }

    private function hydrateHistory(object $history): object
    {
        $history->branch = DB::table('branches')
            ->where('id', $history->cabang_id)
            ->whereNull('deleted_at')
            ->first();
        $history->variant = DB::table('produk_varian')
            ->where('id', $history->varian_id)
            ->whereNull('deleted_at')
            ->first();

        if ($history->variant !== null) {
            $history->variant->product = DB::table('products')
                ->where('id', $history->variant->produk_id)
                ->whereNull('deleted_at')
                ->first();
        }

        $history->user = DB::table('users')
            ->where('id', $history->user_id)
            ->first([
                'id',
                'role_id',
                'nama_lengkap',
                'username',
                'status_aktif',
                'cabang_id',
                'created_at',
                'updated_at',
            ]);
        $history->transaction = $history->transaksi_id === null
            ? null
            : DB::table('transactions')->where('id', $history->transaksi_id)->first();

        return $history;
    }
}
