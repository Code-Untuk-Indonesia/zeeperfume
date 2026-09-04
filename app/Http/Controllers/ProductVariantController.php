<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveProductVariantRequest;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function store(SaveProductVariantRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $now = now();
        $id = DB::table('produk_varian')->insertGetId([
            ...$validated,
            'harga_beli' => $validated['harga_beli'] ?? 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json($this->findVariant($id), 201);
    }

    public function show(Request $request, ProductVariant $productVariant): JsonResponse
    {
        $stockBranchId = $request->user()?->cabang_id
            ?? ($request->filled('cabang_id') ? $request->integer('cabang_id') : null);

        return response()->json(
            $this->findVariant(
                $productVariant->getKey(),
                withCategory: true,
                withStocks: true,
                stockBranchId: $stockBranchId
            )
        );
    }

    public function update(
        SaveProductVariantRequest $request,
        ProductVariant $productVariant
    ): JsonResponse {
        $validated = $request->validated();
        DB::table('produk_varian')
            ->where('id', $productVariant->getKey())
            ->whereNull('deleted_at')
            ->update([...$validated, 'updated_at' => now()]);

        return response()->json($this->findVariant($productVariant->getKey()));
    }

    public function destroy(ProductVariant $productVariant): JsonResponse
    {
        $variantCount = DB::table('produk_varian')
            ->where('produk_id', $productVariant->produk_id)
            ->whereNull('deleted_at')
            ->count();

        if ($variantCount <= 1) {
            return response()->json(['message' => 'Produk harus memiliki minimal satu varian.'], 422);
        }

        DB::table('produk_varian')
            ->where('id', $productVariant->getKey())
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json(status: 204);
    }

    private function findVariant(
        int $id,
        bool $withCategory = false,
        bool $withStocks = false,
        ?int $stockBranchId = null
    ): object {
        $variant = DB::table('produk_varian')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();
        $variant->product = DB::table('products')
            ->where('id', $variant->produk_id)
            ->whereNull('deleted_at')
            ->first();

        if ($withCategory && $variant->product !== null) {
            $variant->product->category = DB::table('categories')
                ->where('id', $variant->product->kategori_id)
                ->whereNull('deleted_at')
                ->first();
        }

        if ($withStocks) {
            $variant->branch_stocks = DB::table('stok_cabang')
                ->leftJoin('branches', 'branches.id', '=', 'stok_cabang.cabang_id')
                ->where('stok_cabang.varian_id', $variant->id)
                ->when($stockBranchId !== null, fn ($query) => $query->where('stok_cabang.cabang_id', $stockBranchId))
                ->orderBy('stok_cabang.cabang_id')
                ->get([
                    'stok_cabang.*',
                    'branches.nama_cabang',
                    'branches.alamat',
                    'branches.no_telepon',
                    'branches.created_at as branch_created_at',
                    'branches.updated_at as branch_updated_at',
                    'branches.deleted_at as branch_deleted_at',
                ])
                ->map(function (object $stock): object {
                    $stock->branch = $stock->nama_cabang === null ? null : (object) [
                        'id' => $stock->cabang_id,
                        'nama_cabang' => $stock->nama_cabang,
                        'alamat' => $stock->alamat,
                        'no_telepon' => $stock->no_telepon,
                        'created_at' => $stock->branch_created_at,
                        'updated_at' => $stock->branch_updated_at,
                        'deleted_at' => $stock->branch_deleted_at,
                    ];

                    unset(
                        $stock->nama_cabang,
                        $stock->alamat,
                        $stock->no_telepon,
                        $stock->branch_created_at,
                        $stock->branch_updated_at,
                        $stock->branch_deleted_at
                    );

                    return $stock;
                });
        }

        return $variant;
    }
}
