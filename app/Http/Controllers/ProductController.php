<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->with(['category', 'variants.branchStocks'])
            ->when($request->filled('kategori_id'), fn ($query) => $query->where('kategori_id', $request->integer('kategori_id')))
            ->when($request->filled('tipe_stok'), fn ($query) => $query->where('tipe_stok', $request->string('tipe_stok')))
            ->when($request->filled('sku'), function ($query) use ($request) {
                $query->whereHas('variants', fn ($query) => $query->where('sku', 'like', '%'.$request->string('sku').'%'));
            })
            ->when($request->filled('search'), fn ($query) => $query->where('nama_produk', 'like', '%'.$request->string('search').'%'))
            ->orderBy('nama_produk')
            ->paginate($request->integer('per_page', 15));

        return response()->json($products);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', Rule::exists('categories', 'id')->whereNull('deleted_at')],
            'nama_produk' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tipe_stok' => ['required', Rule::in(['ada_stok', 'tanpa_stok'])],
            'image' => ['nullable', 'string', 'max:255'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.sku' => ['required', 'string', 'max:80', 'distinct', 'unique:product_variants,sku'],
            'variants.*.nama_varian' => ['required', 'string', 'max:100'],
            'variants.*.harga_beli' => ['sometimes', 'numeric', 'min:0'],
            'variants.*.harga_jual' => ['required', 'numeric', 'min:0'],
        ]);

        $product = DB::transaction(function () use ($validated) {
            $variants = $validated['variants'];
            unset($validated['variants']);

            $product = Product::create($validated);
            $product->variants()->createMany($variants);

            return $product;
        });

        return response()->json($product->load(['category', 'variants']), 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product->load(['category', 'variants.branchStocks.branch']));
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'kategori_id' => ['required', 'integer', Rule::exists('categories', 'id')->whereNull('deleted_at')],
            'nama_produk' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tipe_stok' => ['required', Rule::in(['ada_stok', 'tanpa_stok'])],
            'image' => ['nullable', 'string', 'max:255'],
        ]);

        $product->update($validated);

        return response()->json($product->fresh()->load(['category', 'variants']));
    }

    public function destroy(Product $product): JsonResponse
    {
        DB::transaction(function () use ($product) {
            $product->variants()->delete();
            $product->delete();
        });

        return response()->json(status: 204);
    }
}
