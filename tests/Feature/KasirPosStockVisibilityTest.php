<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KasirPosStockVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_separates_available_and_out_of_stock_variants(): void
    {
        $branch = Branch::create(['nama_cabang' => 'Outlet Kasir']);
        $role = Role::create(['nama_role' => 'kasir']);
        $cashier = User::factory()->create([
            'role_id' => $role->id,
            'cabang_id' => $branch->id,
        ]);
        $category = Category::create(['nama_kategori' => 'Parfum']);

        $this->createVariant($branch, $category, 'Parfum Tersedia', 8);
        $this->createVariant($branch, $category, 'Parfum Habis', 0);

        $response = $this->actingAs($cashier)->get(route('kasir.pos'));
        $content = $response->getContent();

        $response
            ->assertOk()
            ->assertSee('data-name="parfum tersedia"', false)
            ->assertSee('data-stock="8"', false)
            ->assertSee('data-stock-status="available"', false)
            ->assertSee('data-name="parfum habis"', false)
            ->assertSee('data-stock="0"', false)
            ->assertSee('data-stock-status="out-of-stock"', false)
            ->assertSee('id="outOfStockHeading"', false)
            ->assertSee('Stok Habis', false);

        $availablePosition = strpos($content, 'data-name="parfum tersedia"');
        $outOfStockHeadingPosition = strpos($content, 'id="outOfStockHeading"');
        $this->assertNotFalse($availablePosition);
        $this->assertNotFalse($outOfStockHeadingPosition);
        $this->assertLessThan($outOfStockHeadingPosition, $availablePosition);

        $this->assertMatchesRegularExpression(
            '/<div[^>]*data-name="parfum tersedia"[^>]*onclick="[^"]*addPcsToCart/s',
            $content,
        );
        $this->assertDoesNotMatchRegularExpression(
            '/<div[^>]*data-name="parfum habis"[^>]*onclick=/s',
            $content,
        );
    }

    private function createVariant(Branch $branch, Category $category, string $name, int $stock): ProductVariant
    {
        $product = Product::create([
            'kategori_id' => $category->id,
            'nama_produk' => $name,
            'tipe_stok' => 'ada_stok',
        ]);
        $variant = ProductVariant::create([
            'produk_id' => $product->id,
            'sku' => strtoupper(str_replace(' ', '-', $name)),
            'nama_varian' => $name,
            'harga_beli' => 50000,
            'harga_jual' => 85000,
            'satuan' => 'pcs',
        ]);

        BranchStock::create([
            'cabang_id' => $branch->id,
            'varian_id' => $variant->id,
            'stok' => $stock,
        ]);

        return $variant;
    }
}
