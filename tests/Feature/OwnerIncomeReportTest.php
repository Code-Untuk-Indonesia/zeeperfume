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

    public function test_owner_can_open_a_full_printable_income_report(): void
    {
        $owner = $this->createUser('owner');
        $kasir = $this->createUser('kasir');
        $outlet = Branch::create(['nama_cabang' => 'Outlet Print']);

        foreach (['INV-PRINT-1', 'INV-PRINT-2'] as $invoice) {
            Transaction::create([
                'kasir_id' => $kasir->id,
                'nomor_nota' => $invoice,
                'tanggal_waktu' => '2026-09-08 10:00:00',
                'subtotal' => 75000,
                'diskon_persen' => 0,
                'diskon_nominal' => 0,
                'total_belanja' => 75000,
                'nominal_bayar' => 75000,
                'kembalian' => 0,
                'metode_bayar' => 'cash',
                'cabang_id' => $outlet->id,
            ]);
        }

        $this->actingAs($owner)
            ->get(route('owner.income.print', [
                'start_date' => '2026-09-08',
                'end_date' => '2026-09-08',
            ]))
            ->assertOk()
            ->assertSee('INV-PRINT-1')
            ->assertSee('INV-PRINT-2')
            ->assertSee('Outlet Print')
            ->assertDontSee('id="sidebar"');
    }

    public function test_owner_can_export_income_report_for_excel(): void
    {
        $owner = $this->createUser('owner');
        $kasir = $this->createUser('kasir');
        $outlet = Branch::create(['nama_cabang' => 'Outlet Excel']);

        Transaction::create([
            'kasir_id' => $kasir->id,
            'nomor_nota' => 'INV-EXCEL-1',
            'tanggal_waktu' => '2026-09-08 10:00:00',
            'subtotal' => 125000,
            'diskon_persen' => 0,
            'diskon_nominal' => 0,
            'total_belanja' => 125000,
            'nominal_bayar' => 125000,
            'kembalian' => 0,
            'metode_bayar' => 'cash',
            'cabang_id' => $outlet->id,
        ]);

        $response = $this->actingAs($owner)->get(route('owner.income.export', [
            'start_date' => '2026-09-08',
            'end_date' => '2026-09-08',
        ]));

        $response->assertDownload('Laporan_Pendapatan_08-09-2026_sampai_08-09-2026.xlsx');
        $this->assertSame(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('Content-Type')
        );

        $temporaryFile = tempnam(sys_get_temp_dir(), 'income_xlsx_test_');
        file_put_contents($temporaryFile, $response->streamedContent());

        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($temporaryFile) === true);
        $this->assertStringContainsString('INV-EXCEL-1', $zip->getFromName('xl/worksheets/sheet1.xml'));
        $zip->close();
        unlink($temporaryFile);
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
