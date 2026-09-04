<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdjustStockRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $stocks = DB::table('stok_cabang')
            ->when($request->filled('cabang_id'), fn ($query) => $query->where('cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('varian_id'), fn ($query) => $query->where('varian_id', $request->integer('varian_id')))
            ->paginate($request->integer('per_page', 15));

        $stocks->setCollection(
            $stocks->getCollection()->map(fn (object $stock): object => $this->findStock($stock->id))
        );

        return response()->json($stocks);
    }

    public function adjust(AdjustStockRequest $request): JsonResponse
    {
        abort_if($request->user() === null, 401);

        $validated = $request->validated();

        $stock = DB::transaction(function () use ($request, $validated) {
            DB::table('branches')
                ->where('id', $validated['cabang_id'])
                ->whereNull('deleted_at')
                ->lockForUpdate()
                ->firstOrFail();

            $variant = DB::table('produk_varian')
                ->join('products', 'products.id', '=', 'produk_varian.produk_id')
                ->where('produk_varian.id', $validated['varian_id'])
                ->whereNull('produk_varian.deleted_at')
                ->whereNull('products.deleted_at')
                ->select([
                    'produk_varian.id',
                    'produk_varian.produk_id',
                    'products.tipe_stok',
                ])
                ->firstOrFail();

            if ($variant->tipe_stok !== 'ada_stok') {
                throw ValidationException::withMessages([
                    'varian_id' => 'Stok tidak dapat disesuaikan untuk produk tanpa stok.',
                ]);
            }

            $stock = DB::table('stok_cabang')
                ->where('cabang_id', $validated['cabang_id'])
                ->where('varian_id', $validated['varian_id'])
                ->lockForUpdate()
                ->first();

            if ($stock === null) {
                $stockId = DB::table('stok_cabang')->insertGetId([
                    'cabang_id' => $validated['cabang_id'],
                    'varian_id' => $validated['varian_id'],
                    'stok' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $stock = DB::table('stok_cabang')->where('id', $stockId)->firstOrFail();
            }

            if ($validated['jenis_riwayat'] === 'penyesuaian') {
                $newStock = $validated['stok'];
                $delta = $newStock - $stock->stok;
            } else {
                $delta = $validated['jenis_riwayat'] === 'masuk'
                    ? $validated['stok']
                    : -$validated['stok'];
                $newStock = $stock->stok + $delta;
            }

            if ($newStock < 0) {
                throw ValidationException::withMessages([
                    'stok' => 'Stok tidak mencukupi untuk perubahan ini.',
                ]);
            }

            DB::table('stok_cabang')
                ->where('id', $stock->id)
                ->update([
                    'stok' => $newStock,
                    'updated_at' => now(),
                ]);

            $now = now();
            DB::table('stock_histories')->insert([
                'cabang_id' => $validated['cabang_id'],
                'varian_id' => $validated['varian_id'],
                'user_id' => $request->user()->getKey(),
                'transaksi_id' => null,
                'jenis_riwayat' => $validated['jenis_riwayat'],
                'qty' => $delta,
                'keterangan' => $validated['keterangan'] ?? null,
                'waktu' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return $stock->id;
        });

        return response()->json($this->findStock($stock));
    }

    private function findStock(int $id): object
    {
        $stock = DB::table('stok_cabang')->where('id', $id)->firstOrFail();
        $stock->branch = DB::table('branches')
            ->where('id', $stock->cabang_id)
            ->whereNull('deleted_at')
            ->first();
        $stock->variant = DB::table('produk_varian')
            ->where('id', $stock->varian_id)
            ->whereNull('deleted_at')
            ->first();

        if ($stock->variant !== null) {
            $stock->variant->product = DB::table('products')
                ->where('id', $stock->variant->produk_id)
                ->whereNull('deleted_at')
                ->first();

            if ($stock->variant->product !== null) {
                $stock->variant->product->category = DB::table('categories')
                    ->where('id', $stock->variant->product->kategori_id)
                    ->whereNull('deleted_at')
                    ->first();
            }
        }

        return $stock;
    }
}
