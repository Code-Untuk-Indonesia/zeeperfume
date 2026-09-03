<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariantController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        return response()->json(ProductVariant::create($validated)->load('product'), 201);
    }

    public function show(ProductVariant $productVariant): JsonResponse
    {
        return response()->json($productVariant->load(['product.category', 'branchStocks.branch']));
    }

    public function update(Request $request, ProductVariant $productVariant): JsonResponse
    {
        $validated = $request->validate($this->rules($productVariant));
        $productVariant->update($validated);

        return response()->json($productVariant->fresh()->load('product'));
    }

    public function destroy(ProductVariant $productVariant): JsonResponse
    {
        if ($productVariant->product->variants()->count() <= 1) {
            return response()->json(['message' => 'Produk harus memiliki minimal satu varian.'], 422);
        }

        $productVariant->delete();

        return response()->json(status: 204);
    }

    private function rules(?ProductVariant $productVariant = null): array
    {
        return [
            'produk_id' => $productVariant === null
                ? ['required', 'integer', Rule::exists('products', 'id')->whereNull('deleted_at')]
                : ['required', 'integer', Rule::in([$productVariant->produk_id])],
            'sku' => [
                'required',
                'string',
                'max:80',
                Rule::unique('product_variants', 'sku')->ignore($productVariant),
            ],
            'nama_varian' => ['required', 'string', 'max:100'],
            'harga_beli' => ['sometimes', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
        ];
    }
}
