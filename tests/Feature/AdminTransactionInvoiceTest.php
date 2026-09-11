<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Shipment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTransactionInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_online_transaction_uses_the_same_numeric_invoice_format_as_cashier(): void
    {
        $branch = Branch::create(['nama_cabang' => 'Outlet Admin']);
        $adminRole = Role::create(['nama_role' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'cabang_id' => $branch->id,
        ]);
        $category = Category::create(['nama_kategori' => 'Parfum']);
        $product = Product::create([
            'kategori_id' => $category->id,
            'nama_produk' => 'Parfum Online',
            'tipe_stok' => 'ada_stok',
        ]);
        $variant = ProductVariant::create([
            'produk_id' => $product->id,
            'sku' => 'ONLINE-001',
            'nama_varian' => '50 ml',
            'harga_beli' => 50000,
            'harga_jual' => 100000,
            'satuan' => 'pcs',
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.transaction.store_online'), [
                'cabang_id' => $branch->id,
                'nama_pelanggan' => 'Pelanggan Online',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Mawar No. 1',
                'sumber_pesanan' => 'shopee',
                'kurir' => 'JNE',
                'metode_pembayaran' => 'cash',
                'status_lunas' => true,
                'cart' => [[
                    'id' => $variant->id,
                    'price' => 100000,
                    'qty' => 1,
                ]],
                'ongkir' => 0,
                'diskon' => 0,
                'catatan' => null,
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $transaction = Transaction::query()->latest('id')->firstOrFail();

        $this->assertMatchesRegularExpression(
            '/^INV-\d{8}-\d{4}$/',
            $transaction->nomor_nota
        );
        $this->assertStringNotContainsString('-O', $transaction->nomor_nota);
    }

    public function test_admin_history_uses_shipment_relation_to_identify_online_transactions(): void
    {
        $branch = Branch::create(['nama_cabang' => 'Outlet Admin']);
        $adminRole = Role::create(['nama_role' => 'admin']);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'cabang_id' => $branch->id,
        ]);
        $transaction = Transaction::create([
            'kasir_id' => $admin->id,
            'nomor_nota' => 'INV-20260909-0003',
            'tanggal_waktu' => '2026-09-09 10:00:00',
            'subtotal' => 100000,
            'diskon_persen' => 0,
            'diskon_nominal' => 0,
            'total_belanja' => 100000,
            'nominal_bayar' => 100000,
            'kembalian' => 0,
            'metode_bayar' => 'cash',
            'cabang_id' => $branch->id,
        ]);
        Shipment::create([
            'transaksi_id' => $transaction->id,
            'nama_penerima' => 'Pelanggan Online',
            'alamat_tujuan' => 'Jl. Mawar No. 1',
            'catatan_kurir' => 'Sumber: SHOPEE | Catatan: -',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.transaction.index'))
            ->assertOk()
            ->assertSee('INV-20260909-0003')
            ->assertSee('Pesanan Online');
    }
}
