<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = DB::table('categories')
            ->select('categories.*')
            ->selectSub(
                DB::table('products')
                    ->selectRaw('count(*)')
                    ->whereColumn('products.kategori_id', 'categories.id')
                    ->whereNull('products.deleted_at'),
                'products_count'
            )
            ->whereNull('categories.deleted_at')
            ->when($request->filled('search'), fn ($query) => $query->where('nama_kategori', 'like', '%'.$request->string('search').'%'))
            ->orderBy('nama_kategori')
            ->paginate($request->integer('per_page', 15));

        return response()->json($categories);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:categories,nama_kategori'],
        ]);

        $now = now();
        $id = DB::table('categories')->insertGetId([
            ...$validated,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json($this->findCategory($id), 201);
    }

    public function show(Category $category): JsonResponse
    {
        $categoryData = $this->findCategory($category->getKey());
        $products = DB::table('products')
            ->where('kategori_id', $category->getKey())
            ->whereNull('deleted_at')
            ->orderBy('nama_produk')
            ->get();
        $variants = DB::table('produk_varian')
            ->whereIn('produk_id', $products->pluck('id'))
            ->whereNull('deleted_at')
            ->orderBy('nama_varian')
            ->get()
            ->groupBy('produk_id');

        $categoryData->products = $products
            ->map(function (object $product) use ($variants): object {
                $product->variants = $variants->get($product->id, collect())->values();

                return $product;
            })
            ->values();

        return response()->json($categoryData);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'nama_kategori')->ignore($category),
            ],
        ]);

        DB::table('categories')
            ->where('id', $category->getKey())
            ->whereNull('deleted_at')
            ->update([...$validated, 'updated_at' => now()]);

        return response()->json($this->findCategory($category->getKey()));
    }

    public function destroy(Category $category): JsonResponse
    {
        if (DB::table('products')->where('kategori_id', $category->getKey())->exists()) {
            return response()->json(['message' => 'Kategori masih digunakan oleh produk.'], 422);
        }

        DB::table('categories')
            ->where('id', $category->getKey())
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json(status: 204);
    }

    private function findCategory(int $id): object
    {
        return DB::table('categories')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();
    }
}
