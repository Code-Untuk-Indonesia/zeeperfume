<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminMemberCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_members_and_transaction_totals(): void
    {
        $admin = $this->admin();
        $member = $this->member(['nama' => 'Pelanggan Sample', 'poin' => 700]);
        $branchId = DB::table('branches')->insertGetId([
            'nama_cabang' => 'Cabang Test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('transactions')->insert([
            'kasir_id' => $admin->id,
            'member_id' => $member->id,
            'nomor_nota' => 'INV-ADMIN-001',
            'tanggal_waktu' => now(),
            'subtotal' => 100000,
            'diskon_persen' => 0,
            'diskon_nominal' => 0,
            'total_belanja' => 100000,
            'nominal_bayar' => 100000,
            'kembalian' => 0,
            'metode_bayar' => 'cash',
            'cabang_id' => $branchId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.member.index'))
            ->assertOk()
            ->assertSee('Pelanggan Sample')
            ->assertSee('Rp 100.000')
            ->assertSee('VIP Member');
    }

    public function test_admin_can_create_member_with_server_generated_code(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.member.store'), [
                'nama' => 'Member Baru',
                'no_telp' => '081234567890',
                'email' => 'baru@example.test',
                'poin' => 25,
                'tanggal_bergabung' => '2026-09-05',
            ])
            ->assertRedirect(route('admin.member.index'));

        $member = Member::query()->where('nama', 'Member Baru')->firstOrFail();

        $this->assertSame('MBR-0001', $member->kode_member);
        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'email' => 'baru@example.test',
            'poin' => 25,
        ]);
    }

    public function test_admin_can_edit_member(): void
    {
        $admin = $this->admin();
        $member = $this->member();

        $this->actingAs($admin)
            ->get(route('admin.member.edit', $member->id))
            ->assertOk()
            ->assertSee($member->nama);

        $this->actingAs($admin)
            ->put(route('admin.member.update', $member->id), [
                'nama' => 'Nama Diperbarui',
                'no_telp' => '089876543210',
                'email' => null,
                'poin' => 500,
                'tanggal_bergabung' => '2026-09-01',
            ])
            ->assertRedirect(route('admin.member.index'));

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'nama' => 'Nama Diperbarui',
            'poin' => 500,
        ]);
    }

    public function test_admin_can_soft_delete_member(): void
    {
        $admin = $this->admin();
        $member = $this->member();

        $this->actingAs($admin)
            ->delete(route('admin.member.destroy', $member->id))
            ->assertRedirect(route('admin.member.index'));

        $this->assertSoftDeleted('members', ['id' => $member->id]);
        $this->actingAs($admin)
            ->get(route('admin.member.index'))
            ->assertOk()
            ->assertDontSee($member->nama);
    }

    public function test_non_admin_cannot_manage_members(): void
    {
        $owner = $this->userWithRole('owner');

        $this->actingAs($owner)
            ->get(route('admin.member.index'))
            ->assertForbidden();
    }

    private function admin(): User
    {
        return $this->userWithRole('admin');
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::create(['nama_role' => $roleName]);

        return User::factory()->create([
            'role_id' => $role->id,
            'status_aktif' => true,
        ]);
    }

    private function member(array $attributes = []): Member
    {
        return Member::query()->create(array_merge([
            'kode_member' => 'MBR-'.fake()->unique()->numerify('###'),
            'nama' => 'Member Test',
            'no_telp' => '081200000000',
            'email' => 'member@example.test',
            'poin' => 0,
            'tanggal_bergabung' => '2026-09-01',
        ], $attributes));
    }
}
