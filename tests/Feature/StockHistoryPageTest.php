<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\StockHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockHistoryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_filtered_stock_history(): void
    {
        $owner = $this->createUser('owner');
        $history = $this->createHistory($owner);

        $this->actingAs($owner)
            ->get(route('owner.stock.history', [
                'cabang_id' => $history->cabang_id,
                'jenis_riwayat' => 'masuk',
                'tanggal_mulai' => '2026-09-08',
                'tanggal_selesai' => '2026-09-08',
            ]))
            ->assertOk()
            ->assertSee('Riwayat Perpindahan Stok')
            ->assertSee('Parfum Floral')
            ->assertSee('Outlet Utama')
            ->assertSee('+25 pcs');
    }

    public function test_admin_can_view_stock_history(): void
    {
        $admin = $this->createUser('admin');
        $this->createHistory($admin);

        $this->actingAs($admin)
            ->get(route('admin.stock.history'))
            ->assertOk()
            ->assertSee('Aktivitas stok')
            ->assertSee('Parfum Floral');
    }

    public function test_admin_stock_history_highlights_only_the_history_menu(): void
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)
            ->get(route('admin.stock.history'))
            ->assertOk()
            ->assertSee('class="block py-2 text-sm transition-colors text-gray-500 hover:text-gray-300">Kelola', false)
            ->assertSee('class="block py-2 text-sm transition-colors text-[#CC9863] font-bold">Riwayat Perpindahan Stok', false);
    }

    public function test_cashier_cannot_view_stock_history(): void
    {
        $cashier = $this->createUser('kasir');

        $this->actingAs($cashier)
            ->get(route('owner.stock.history'))
            ->assertForbidden();
    }

    private function createHistory(User $user): StockHistory
    {
        $branch = Branch::create(['nama_cabang' => 'Outlet Utama']);
        $category = Category::create(['nama_kategori' => 'Parfum']);
        $product = Product::create([
            'kategori_id' => $category->id,
            'nama_produk' => 'Parfum Floral',
            'tipe_stok' => 'ada_stok',
        ]);
        $variant = ProductVariant::create([
            'produk_id' => $product->id,
            'sku' => 'FLR-25',
            'nama_varian' => '25 ml',
            'harga_beli' => 50000,
            'harga_jual' => 85000,
            'satuan' => 'pcs',
        ]);

        return StockHistory::create([
            'cabang_id' => $branch->id,
            'varian_id' => $variant->id,
            'user_id' => $user->id,
            'jenis_riwayat' => 'masuk',
            'qty' => 25,
            'keterangan' => 'Stok awal outlet',
            'waktu' => '2026-09-08 10:00:00',
        ]);
    }

    private function createUser(string $roleName): User
    {
        $role = Role::create(['nama_role' => $roleName]);

        return User::factory()->create([
            'role_id' => $role->id,
            'status_aktif' => true,
        ]);
    }
}
