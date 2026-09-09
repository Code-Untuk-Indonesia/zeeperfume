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

class KasirTransactionDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_discount_is_saved_for_cashier_transaction_detail(): void
    {
        $branch = Branch::create(['nama_cabang' => 'Outlet Kasir']);
        $role = Role::create(['nama_role' => 'kasir']);
        $cashier = User::factory()->create([
            'role_id' => $role->id,
            'cabang_id' => $branch->id,
        ]);
        $category = Category::create(['nama_kategori' => 'Parfum']);
        $product = Product::create([
            'kategori_id' => $category->id,
            'nama_produk' => 'Parfum Diskon',
            'tipe_stok' => 'ada_stok',
        ]);
        $variant = ProductVariant::create([
            'produk_id' => $product->id,
            'sku' => 'DSK-001',
            'nama_varian' => '30 ml',
            'harga_beli' => 50000,
            'harga_jual' => 100000,
            'satuan' => 'pcs',
        ]);
        BranchStock::create([
            'cabang_id' => $branch->id,
            'varian_id' => $variant->id,
            'stok' => 5,
        ]);

        $response = $this->actingAs($cashier)->postJson(route('kasir.pos.store'), [
            'cart' => [[
                'variantId' => $variant->id,
                'name' => '30 ml',
                'unit' => 'pcs',
                'price' => 100000,
                'qty' => 1,
                'itemDiscount' => 15000,
                'discountType' => 'rupiah',
                'discountInput' => 15000,
            ]],
            'metode_bayar' => 'cash',
            'nominal_bayar' => 85000,
            'subtotal' => 100000,
            'discount' => 0,
            'total' => 85000,
        ]);

        $response->assertOk()->assertJsonPath('success', true);

        $transactionId = $response->json('transaction_id');
        $this->assertDatabaseHas('transaction_details', [
            'transaksi_id' => $transactionId,
            'varian_id' => $variant->id,
            'diskon_persen' => 0,
            'diskon_satuan' => 15000,
            'catatan_diskon' => 'Diskon item',
            'subtotal' => 85000,
        ]);

        $this->actingAs($cashier)
            ->get(route('kasir.transaction.detail', $transactionId))
            ->assertOk()
            ->assertJsonPath('data.details.0.diskon_satuan', 15000)
            ->assertJsonPath('data.details.0.catatan_diskon', 'Diskon item');

        $this->actingAs($cashier)
            ->get(route('kasir.transaction.index'))
            ->assertOk()
            ->assertSee('Diskon item', false);

        $this->actingAs($cashier)
            ->get(route('kasir.pos.success', ['trx_id' => $transactionId]))
            ->assertOk()
            ->assertSee('Diskon item', false)
            ->assertSee('-15.000', false);
    }
}
