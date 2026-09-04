<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockHistoryController extends Controller
{
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
