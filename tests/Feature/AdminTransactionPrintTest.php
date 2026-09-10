<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTransactionPrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_transaction_receipt_removes_rounded_corners_when_printed(): void
    {
        $branch = Branch::create(['nama_cabang' => 'Outlet Admin']);
        $adminRole = Role::create(['nama_role' => 'admin']);
        $cashierRole = Role::create(['nama_role' => 'kasir']);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'cabang_id' => $branch->id,
        ]);
        $cashier = User::factory()->create([
            'role_id' => $cashierRole->id,
            'cabang_id' => $branch->id,
        ]);
        $category = Category::create(['nama_kategori' => 'Parfum']);
        $product = Product::create([
            'kategori_id' => $category->id,
            'nama_produk' => 'Parfum Admin',
            'tipe_stok' => 'ada_stok',
        ]);
        $variant = ProductVariant::create([
            'produk_id' => $product->id,
            'sku' => 'ADM-001',
            'nama_varian' => '50 ml',
            'harga_beli' => 50000,
            'harga_jual' => 100000,
            'satuan' => 'pcs',
        ]);
        $transaction = Transaction::create([
            'kasir_id' => $cashier->id,
            'nomor_nota' => 'INV-PRINT-001',
            'tanggal_waktu' => now(),
            'subtotal' => 100000,
            'diskon_persen' => 0,
            'diskon_nominal' => 5000,
            'deskripsi_diskon' => 'Diskon Transaksi',
            'total_belanja' => 95000,
            'nominal_bayar' => 95000,
            'kembalian' => 0,
            'metode_bayar' => 'cash',
            'cabang_id' => $branch->id,
        ]);
        TransactionDetail::create([
            'transaksi_id' => $transaction->id,
            'varian_id' => $variant->id,
            'qty' => 1,
            'harga_satuan' => 100000,
            'diskon_persen' => 10,
            'diskon_satuan' => 10000,
            'catatan_diskon' => 'Diskon item',
            'subtotal' => 90000,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.transaction.show', $transaction->id))
            ->assertOk()
            ->assertSee('print:rounded-none', false)
            ->assertSee('.print-struk-only *', false)
            ->assertSee('Diskon item', false)
            ->assertSee('(10%)', false)
            ->assertSee('- Rp 10.000', false)
            ->assertSee('Diskon', false)
            ->assertSee('Diskon Transaksi', false)
            ->assertSee('5.000', false);
    }
}
