<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerTransactionExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_print_and_export_filtered_transaction_history(): void
    {
        $branch = Branch::create(['nama_cabang' => 'Outlet Export']);
        $ownerRole = Role::create(['nama_role' => 'owner']);
        $adminRole = Role::create(['nama_role' => 'admin']);
        $owner = User::factory()->create(['role_id' => $ownerRole->id]);
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
            'cabang_id' => $branch->id,
        ]);

        $transaction = Transaction::create([
            'kasir_id' => $admin->id,
            'nomor_nota' => 'INV-OWNER-EXPORT',
            'tanggal_waktu' => '2026-09-09 10:00:00',
            'subtotal' => 125000,
            'diskon_persen' => 0,
            'diskon_nominal' => 0,
            'total_belanja' => 125000,
            'nominal_bayar' => 125000,
            'kembalian' => 0,
            'metode_bayar' => 'cash',
            'cabang_id' => $branch->id,
            'approval_status' => 'pending_edit',
            'approval_reason' => 'Koreksi nominal pembayaran',
            'approval_by' => $admin->id,
        ]);

        $filters = ['search' => $transaction->nomor_nota];

        $this->actingAs($owner)
            ->get(route('owner.transaction.print', $filters))
            ->assertOk()
            ->assertSee('INV-OWNER-EXPORT')
            ->assertSee('Koreksi nominal pembayaran')
            ->assertDontSee('id="sidebar"');

        $response = $this->actingAs($owner)
            ->get(route('owner.transaction.export', $filters));

        $response->assertDownload();
        $this->assertSame(
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $response->headers->get('Content-Type')
        );

        $temporaryFile = tempnam(sys_get_temp_dir(), 'owner_transaction_xlsx_');
        file_put_contents($temporaryFile, $response->streamedContent());
        $zip = new \ZipArchive();

        $this->assertTrue($zip->open($temporaryFile) === true);
        $this->assertStringContainsString('INV-OWNER-EXPORT', $zip->getFromName('xl/worksheets/sheet1.xml'));
        $this->assertStringContainsString('Menunggu Persetujuan Edit', $zip->getFromName('xl/worksheets/sheet1.xml'));

        $zip->close();
        unlink($temporaryFile);
    }
}
