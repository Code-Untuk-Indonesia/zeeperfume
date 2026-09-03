<?php

namespace Tests\Feature;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class InventoryStructureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::post('/_test/products', [ProductController::class, 'store']);
        Route::post('/_test/stocks/adjust', [StockController::class, 'adjust']);
        Route::post('/_test/transactions', [TransactionController::class, 'store']);
    }

    public function test_final_variant_and_branch_stock_schema_is_used(): void
    {
        $this->assertTrue(Schema::hasTable('produk_varian'));
        $this->assertTrue(Schema::hasColumns('produk_varian', [
            'id',
            'produk_id',
            'sku',
            'nama_varian',
            'harga_beli',
            'harga_jual',
            'image',
            'satuan',
        ]));
        $this->assertFalse(Schema::hasColumn('produk_varian', 'stok'));
        $this->assertFalse(Schema::hasTable('product_variants'));

        $this->assertTrue(Schema::hasTable('stok_cabang'));
        $this->assertTrue(Schema::hasColumns('stok_cabang', [
            'id',
            'cabang_id',
            'varian_id',
            'stok',
        ]));
        $this->assertFalse(Schema::hasColumn('stok_cabang', 'stok_minimum'));
        $this->assertFalse(Schema::hasTable('branch_stocks'));
        $this->assertTrue(
            Schema::hasIndex('stok_cabang', ['cabang_id', 'varian_id'], 'unique')
        );
    }

    public function test_product_variant_is_created_with_unit_and_without_stock_column(): void
    {
        $category = Category::create(['nama_kategori' => 'Parfum']);

        $response = $this->postJson('/_test/products', [
            'kategori_id' => $category->getKey(),
            'nama_produk' => 'Zee Floral',
            'tipe_stok' => 'ada_stok',
            'variants' => [[
                'sku' => 'ZEE-FLR-30',
                'nama_varian' => '30 ml',
                'harga_beli' => 50000,
                'harga_jual' => 75000,
                'satuan' => 'botol',
            ]],
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('produk_varian', [
            'sku' => 'ZEE-FLR-30',
            'satuan' => 'botol',
        ]);

        $variant = ProductVariant::query()->where('sku', 'ZEE-FLR-30')->firstOrFail();
        $this->assertArrayNotHasKey('stok', $variant->getAttributes());
    }

    public function test_one_variant_can_have_different_stock_in_multiple_branches(): void
    {
        $variant = $this->createVariant();
        $firstBranch = $this->createBranch('Cabang Barat');
        $secondBranch = $this->createBranch('Cabang Timur');

        BranchStock::create([
            'cabang_id' => $firstBranch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 12,
        ]);
        BranchStock::create([
            'cabang_id' => $secondBranch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 4,
        ]);

        $this->assertDatabaseHas('stok_cabang', [
            'cabang_id' => $firstBranch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 12,
        ]);
        $this->assertDatabaseHas('stok_cabang', [
            'cabang_id' => $secondBranch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 4,
        ]);
    }

    public function test_unique_constraint_rejects_duplicate_variant_for_same_branch(): void
    {
        $variant = $this->createVariant();
        $branch = $this->createBranch();
        $attributes = [
            'cabang_id' => $branch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 5,
        ];

        BranchStock::create($attributes);

        $this->expectException(QueryException::class);
        BranchStock::create($attributes);
    }

    public function test_stock_adjustment_uses_branch_stock_and_records_history(): void
    {
        $branch = $this->createBranch();
        $user = $this->createUser($branch);
        $variant = $this->createVariant();

        $response = $this->actingAs($user)->postJson('/_test/stocks/adjust', [
            'cabang_id' => $branch->getKey(),
            'varian_id' => $variant->getKey(),
            'jenis_riwayat' => 'penyesuaian',
            'stok' => 7,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('stok_cabang', [
            'cabang_id' => $branch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 7,
        ]);
        $this->assertDatabaseHas('stock_histories', [
            'cabang_id' => $branch->getKey(),
            'varian_id' => $variant->getKey(),
            'user_id' => $user->getKey(),
            'transaksi_id' => null,
            'jenis_riwayat' => 'penyesuaian',
            'qty' => 7,
        ]);
    }

    public function test_stock_adjustment_cannot_make_stock_negative(): void
    {
        $branch = $this->createBranch();
        $user = $this->createUser($branch);
        $variant = $this->createVariant();
        BranchStock::create([
            'cabang_id' => $branch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 2,
        ]);

        $response = $this->actingAs($user)->postJson('/_test/stocks/adjust', [
            'cabang_id' => $branch->getKey(),
            'varian_id' => $variant->getKey(),
            'jenis_riwayat' => 'keluar',
            'stok' => 3,
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors('stok');
        $this->assertDatabaseHas('stok_cabang', [
            'cabang_id' => $branch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 2,
        ]);
        $this->assertDatabaseCount('stock_histories', 0);
    }

    public function test_transaction_reduces_only_the_transaction_branch_stock(): void
    {
        $salesBranch = $this->createBranch('Cabang Penjualan');
        $otherBranch = $this->createBranch('Cabang Lain');
        $user = $this->createUser($salesBranch);
        $variant = $this->createVariant();

        BranchStock::create([
            'cabang_id' => $salesBranch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 10,
        ]);
        BranchStock::create([
            'cabang_id' => $otherBranch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 3,
        ]);

        $response = $this->actingAs($user)->postJson(
            '/_test/transactions',
            $this->transactionPayload($variant, 4)
        );

        $response->assertCreated();
        $transactionId = $response->json('id');

        $this->assertDatabaseHas('stok_cabang', [
            'cabang_id' => $salesBranch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 6,
        ]);
        $this->assertDatabaseHas('stok_cabang', [
            'cabang_id' => $otherBranch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 3,
        ]);
        $this->assertDatabaseHas('stock_histories', [
            'cabang_id' => $salesBranch->getKey(),
            'varian_id' => $variant->getKey(),
            'user_id' => $user->getKey(),
            'transaksi_id' => $transactionId,
            'jenis_riwayat' => 'penjualan',
            'qty' => -4,
        ]);
    }

    public function test_transaction_fails_when_branch_stock_is_insufficient(): void
    {
        $branch = $this->createBranch();
        $user = $this->createUser($branch);
        $variant = $this->createVariant();

        BranchStock::create([
            'cabang_id' => $branch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 2,
        ]);

        $response = $this->actingAs($user)->postJson(
            '/_test/transactions',
            $this->transactionPayload($variant, 3)
        );

        $response->assertUnprocessable()->assertJsonValidationErrors('items');
        $this->assertDatabaseHas('stok_cabang', [
            'cabang_id' => $branch->getKey(),
            'varian_id' => $variant->getKey(),
            'stok' => 2,
        ]);
        $this->assertDatabaseCount('transactions', 0);
        $this->assertDatabaseCount('stock_histories', 0);
    }

    public function test_product_without_stock_does_not_create_or_reduce_branch_stock(): void
    {
        $branch = $this->createBranch();
        $user = $this->createUser($branch);
        $variant = $this->createVariant('tanpa_stok');

        $response = $this->actingAs($user)->postJson(
            '/_test/transactions',
            $this->transactionPayload($variant, 2)
        );

        $response->assertCreated();
        $this->assertDatabaseCount('stok_cabang', 0);
        $this->assertDatabaseCount('stock_histories', 0);
        $this->assertDatabaseHas('transaction_details', [
            'varian_id' => $variant->getKey(),
            'qty' => 2,
        ]);
    }

    private function createBranch(string $name = 'Cabang Utama'): Branch
    {
        return Branch::create(['nama_cabang' => $name]);
    }

    private function createUser(Branch $branch): User
    {
        $role = Role::firstOrCreate(['nama_role' => 'Kasir']);

        return User::create([
            'role_id' => $role->getKey(),
            'nama_lengkap' => 'Kasir Test',
            'username' => 'kasir_'.Str::lower(Str::random(8)),
            'password' => 'password',
            'status_aktif' => true,
            'cabang_id' => $branch->getKey(),
        ]);
    }

    private function createVariant(string $stockType = 'ada_stok'): ProductVariant
    {
        $category = Category::firstOrCreate(['nama_kategori' => 'Parfum']);
        $product = Product::create([
            'kategori_id' => $category->getKey(),
            'nama_produk' => 'Produk '.Str::random(6),
            'tipe_stok' => $stockType,
        ]);

        return ProductVariant::create([
            'produk_id' => $product->getKey(),
            'sku' => 'SKU-'.Str::upper(Str::random(10)),
            'nama_varian' => '50 ml',
            'harga_beli' => 15000,
            'harga_jual' => 25000,
            'satuan' => 'botol',
        ]);
    }

    private function transactionPayload(ProductVariant $variant, int $quantity): array
    {
        return [
            'metode_bayar' => 'cash',
            'nominal_bayar' => 100000,
            'items' => [[
                'varian_id' => $variant->getKey(),
                'qty' => $quantity,
            ]],
        ];
    }
}
