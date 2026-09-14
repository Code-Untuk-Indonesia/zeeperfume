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

    public function test_item_discount_is_saved_and_printed_as_a_clear_receipt_breakdown(): void
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
            'nominal_bayar' => 76500,
            'subtotal' => 100000,
            'discount' => 8500,
            'diskon_persen' => 10,
            'total' => 76500,
        ]);

        $response->assertOk()->assertJsonPath('success', true);

        $transactionId = $response->json('transaction_id');
        $this->assertDatabaseHas('transactions', [
            'id' => $transactionId,
            'subtotal' => 100000,
            'diskon_persen' => 10,
            'diskon_nominal' => 8500,
            'total_belanja' => 76500,
        ]);

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

        $successReceipt = $this->actingAs($cashier)
            ->get(route('kasir.pos.success', ['trx_id' => $transactionId]))
            ->assertOk();
        $successReceipt
            ->assertSee('1 x 100.000', false)
            ->assertSee('Diskon produk', false)
            ->assertSee('Setelah diskon', false)
            ->assertSee('85.000', false)
            ->assertSee('Subtotal', false)
            ->assertSee('Total diskon produk', false)
            ->assertSee('-15.000', false)
            ->assertSee('Diskon tambahan (10%)', false)
            ->assertSee('-8.500', false)
            ->assertSee('TOTAL BAYAR', false)
            ->assertSee('76.500', false);
        $this->assertSame(1, substr_count($successReceipt->getContent(), '-15.000'));

        $thermalReceipt = $this->actingAs($cashier)
            ->get(route('kasir.pos.receipt', ['trx_id' => $transactionId]))
            ->assertOk();
        $thermalReceipt
            ->assertSee('1 x 100.000', false)
            ->assertSee('Diskon produk', false)
            ->assertSee('Setelah diskon', false)
            ->assertSee('85.000', false)
            ->assertSee('Subtotal', false)
            ->assertSee('Total diskon produk', false)
            ->assertSee('-15.000', false)
            ->assertSee('Diskon tambahan (10%)', false)
            ->assertSee('-8.500', false)
            ->assertSee('TOTAL BAYAR', false)
            ->assertSee('76.500', false);
        $this->assertSame(1, substr_count($thermalReceipt->getContent(), '-15.000'));
    }
}
