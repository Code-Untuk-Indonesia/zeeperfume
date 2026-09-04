<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $stockBranchId = $request->user()?->cabang_id
            ?? ($request->filled('cabang_id') ? $request->integer('cabang_id') : null);

        $products = $this->productQuery()
            ->when($request->filled('kategori_id'), fn ($query) => $query->where('products.kategori_id', $request->integer('kategori_id')))
            ->when($request->filled('tipe_stok'), fn ($query) => $query->where('products.tipe_stok', (string) $request->string('tipe_stok')))
            ->when($request->filled('sku'), function ($query) use ($request) {
                $query->whereExists(function ($query) use ($request) {
                    $query->selectRaw('1')
                        ->from('produk_varian')
                        ->whereColumn('produk_varian.produk_id', 'products.id')
                        ->whereNull('produk_varian.deleted_at')
                        ->where('produk_varian.sku', 'like', '%'.$request->string('sku').'%');
                });
            })
            ->when($request->filled('search'), fn ($query) => $query->where('products.nama_produk', 'like', '%'.$request->string('search').'%'))
            ->orderBy('products.nama_produk')
            ->paginate($request->integer('per_page', 15));

        $products->setCollection(
            $this->hydrateProducts($products->getCollection(), $stockBranchId)
        );

        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $productId = DB::transaction(function () use ($validated) {
            $variants = $validated['variants'];
            unset($validated['variants']);

            $now = now();
            $productId = DB::table('products')->insertGetId([
                ...$validated,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('produk_varian')->insert(
                collect($variants)
                    ->map(fn (array $variant): array => [
                        ...$variant,
                        'produk_id' => $productId,
                        'harga_beli' => $variant['harga_beli'] ?? 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                    ->all()
            );

            return $productId;
        });

        return response()->json($this->findProduct($productId, withStocks: false), 201);
    }

    public function show(Request $request, Product $product): JsonResponse
    {
        $stockBranchId = $request->user()?->cabang_id
            ?? ($request->filled('cabang_id') ? $request->integer('cabang_id') : null);

        return response()->json($this->findProduct($product->getKey(), $stockBranchId));
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

        DB::table('products')
            ->where('id', $product->getKey())
            ->whereNull('deleted_at')
            ->update([...$validated, 'updated_at' => now()]);

        return response()->json(
            $this->findProduct($product->getKey(), withStocks: false)
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        DB::transaction(function () use ($product) {
            $now = now();

            DB::table('produk_varian')
                ->where('produk_id', $product->getKey())
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => $now,
                    'updated_at' => $now,
                ]);

            DB::table('products')
                ->where('id', $product->getKey())
                ->whereNull('deleted_at')
                ->update([
                    'deleted_at' => $now,
                    'updated_at' => $now,
                ]);
        });

        return response()->json(status: 204);
    }

    private function productQuery(): Builder
    {
        return DB::table('products')
            ->join('categories', 'categories.id', '=', 'products.kategori_id')
            ->whereNull('products.deleted_at')
            ->select([
                'products.*',
                'categories.nama_kategori as category_nama_kategori',
                'categories.created_at as category_created_at',
                'categories.updated_at as category_updated_at',
                'categories.deleted_at as category_deleted_at',
            ]);
    }

    private function findProduct(
        int $id,
        ?int $stockBranchId = null,
        bool $withStocks = true
    ): object {
        $products = $this->productQuery()->where('products.id', $id)->get();

        abort_if($products->isEmpty(), 404);

        return $this->hydrateProducts($products, $stockBranchId, $withStocks)->first();
    }

    private function hydrateProducts(
        Collection $products,
        ?int $stockBranchId = null,
        bool $withStocks = true
    ): Collection {
        if ($products->isEmpty()) {
            return $products;
        }

        $variants = DB::table('produk_varian')
            ->whereIn('produk_id', $products->pluck('id'))
            ->whereNull('deleted_at')
            ->orderBy('nama_varian')
            ->get();
        $stocks = collect();

        if ($withStocks && $variants->isNotEmpty()) {
            $stocks = DB::table('stok_cabang')
                ->leftJoin('branches', 'branches.id', '=', 'stok_cabang.cabang_id')
                ->whereIn('stok_cabang.varian_id', $variants->pluck('id'))
                ->when($stockBranchId !== null, fn ($query) => $query->where('stok_cabang.cabang_id', $stockBranchId))
                ->orderBy('stok_cabang.cabang_id')
                ->get([
                    'stok_cabang.*',
                    'branches.nama_cabang as branch_nama_cabang',
                    'branches.alamat as branch_alamat',
                    'branches.no_telepon as branch_no_telepon',
                    'branches.created_at as branch_created_at',
                    'branches.updated_at as branch_updated_at',
                    'branches.deleted_at as branch_deleted_at',
                ])
                ->map(function (object $stock): object {
                    $stock->branch = $stock->branch_nama_cabang === null ? null : (object) [
                        'id' => $stock->cabang_id,
                        'nama_cabang' => $stock->branch_nama_cabang,
                        'alamat' => $stock->branch_alamat,
                        'no_telepon' => $stock->branch_no_telepon,
                        'created_at' => $stock->branch_created_at,
                        'updated_at' => $stock->branch_updated_at,
                        'deleted_at' => $stock->branch_deleted_at,
                    ];

                    foreach ([
                        'branch_nama_cabang',
                        'branch_alamat',
                        'branch_no_telepon',
                        'branch_created_at',
                        'branch_updated_at',
                        'branch_deleted_at',
                    ] as $column) {
                        unset($stock->{$column});
                    }

                    return $stock;
                })
                ->groupBy('varian_id');
        }

        $variants = $variants
            ->map(function (object $variant) use ($stocks, $withStocks): object {
                if ($withStocks) {
                    $variant->branch_stocks = $stocks->get($variant->id, collect())->values();
                }

                return $variant;
            })
            ->groupBy('produk_id');

        return $products->map(function (object $product) use ($variants): object {
            $product->category = (object) [
                'id' => $product->kategori_id,
                'nama_kategori' => $product->category_nama_kategori,
                'created_at' => $product->category_created_at,
                'updated_at' => $product->category_updated_at,
                'deleted_at' => $product->category_deleted_at,
            ];
            $product->variants = $variants->get($product->id, collect())->values();

            foreach ([
                'category_nama_kategori',
                'category_created_at',
                'category_updated_at',
                'category_deleted_at',
            ] as $column) {
                unset($product->{$column});
            }

            return $product;
        });
    }
}
