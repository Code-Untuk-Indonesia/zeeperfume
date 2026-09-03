<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->withCount('products')
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

        return response()->json(Category::create($validated), 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category->load('products.variants'));
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

        $category->update($validated);

        return response()->json($category->fresh());
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->products()->withTrashed()->exists()) {
            return response()->json(['message' => 'Kategori masih digunakan oleh produk.'], 422);
        }

        $category->delete();

        return response()->json(status: 204);
    }
}
