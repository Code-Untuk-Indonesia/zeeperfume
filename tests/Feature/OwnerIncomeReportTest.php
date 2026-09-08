<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\CashTempo;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerIncomeReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_daily_income_by_outlet(): void
    {
        $owner = $this->createUser('owner');
        $kasir = $this->createUser('kasir');
        $outletA = Branch::create(['nama_cabang' => 'Outlet A']);
        $outletB = Branch::create(['nama_cabang' => 'Outlet B']);

        Transaction::create([
            'kasir_id' => $kasir->id,
            'nomor_nota' => 'INV-DAILY-A',
            'tanggal_waktu' => '2026-09-08 10:00:00',
            'subtotal' => 100000,
            'diskon_persen' => 0,
            'diskon_nominal' => 0,
            'total_belanja' => 100000,
            'nominal_bayar' => 100000,
            'kembalian' => 0,
            'metode_bayar' => 'cash',
            'cabang_id' => $outletA->id,
        ]);

        $tempoTransaction = Transaction::create([
            'kasir_id' => $kasir->id,
            'nomor_nota' => 'INV-DAILY-B',
            'tanggal_waktu' => '2026-09-08 11:00:00',
            'subtotal' => 200000,
            'diskon_persen' => 0,
            'diskon_nominal' => 0,
            'total_belanja' => 200000,
            'nominal_bayar' => 150000,
            'kembalian' => 0,
            'metode_bayar' => 'cash_tempo',
            'cabang_id' => $outletB->id,
        ]);

        CashTempo::create([
            'transaksi_id' => $tempoTransaction->id,
            'tanggal_jatuh_tempo' => '2026-09-15',
            'jumlah_piutang' => 200000,
            'sisa_piutang' => 50000,
            'status_tempo' => 'belum_lunas',
            'status_verifikasi' => 'menunggu',
        ]);

        $this->actingAs($owner)
            ->get(route('owner.income.index', ['report_date' => '2026-09-08']))
            ->assertOk()
            ->assertSee('Outlet A')
            ->assertSee('Outlet B')
            ->assertSee('Rp 300.000')
            ->assertSee('Rp 250.000')
            ->assertSee('Rp 50.000');
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
