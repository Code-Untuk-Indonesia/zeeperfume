<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OwnerEmployeeCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_employee_management(): void
    {
        $owner = $this->userWithRole('owner');
        $employee = $this->employee('kasir');

        $this->actingAs($owner)
            ->get(route('owner.employee.index'))
            ->assertOk()
            ->assertSee($employee->nama_lengkap)
            ->assertSee(route('owner.employee.edit', $employee->id));
    }

    public function test_owner_can_view_create_and_edit_employee_forms(): void
    {
        $owner = $this->userWithRole('owner');
        $employee = $this->employee('kasir');

        $this->actingAs($owner)
            ->get(route('owner.employee.create'))
            ->assertOk()
            ->assertSee('name="nama_lengkap"', false)
            ->assertSee('name="role_id"', false);

        $this->actingAs($owner)
            ->get(route('owner.employee.edit', $employee->id))
            ->assertOk()
            ->assertSee($employee->nama_lengkap)
            ->assertSee('name="status_aktif"', false);
    }

    public function test_owner_can_create_employee_with_hashed_password(): void
    {
        $owner = $this->userWithRole('owner');
        $adminRole = Role::create(['nama_role' => 'admin']);
        $branch = $this->branch();

        $this->actingAs($owner)
            ->post(route('owner.employee.store'), [
                'role_id' => $adminRole->id,
                'nama_lengkap' => 'Admin Baru',
                'username' => 'admin.baru',
                'password' => 'password-baru',
                'cabang_id' => $branch->id,
            ])
            ->assertRedirect(route('owner.employee.index'));

        $employee = User::query()->where('username', 'admin.baru')->firstOrFail();

        $this->assertSame('Admin Baru', $employee->nama_lengkap);
        $this->assertSame($branch->id, $employee->cabang_id);
        $this->assertTrue($employee->status_aktif);
        $this->assertTrue(Hash::check('password-baru', $employee->password));
    }

    public function test_owner_can_update_employee_and_keep_password_when_blank(): void
    {
        $owner = $this->userWithRole('owner');
        $employee = $this->employee('kasir');
        $oldPassword = $employee->password;
        $adminRole = Role::create(['nama_role' => 'admin']);
        $branch = $this->branch();

        $this->actingAs($owner)
            ->put(route('owner.employee.update', $employee->id), [
                'role_id' => $adminRole->id,
                'nama_lengkap' => 'Pegawai Diperbarui',
                'username' => 'pegawai.diperbarui',
                'password' => '',
                'status_aktif' => '0',
                'cabang_id' => $branch->id,
            ])
            ->assertRedirect(route('owner.employee.index'));

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'role_id' => $adminRole->id,
            'nama_lengkap' => 'Pegawai Diperbarui',
            'username' => 'pegawai.diperbarui',
            'status_aktif' => 0,
            'cabang_id' => $branch->id,
            'password' => $oldPassword,
        ]);
    }

    public function test_owner_can_deactivate_and_restore_employee(): void
    {
        $owner = $this->userWithRole('owner');
        $employee = $this->employee('kasir');

        $this->actingAs($owner)
            ->delete(route('owner.employee.destroy', $employee->id))
            ->assertRedirect(route('owner.employee.index'));

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'status_aktif' => 0,
        ]);

        $this->actingAs($owner)
            ->patch(route('owner.employee.restore', $employee->id))
            ->assertRedirect(route('owner.employee.index'));

        $this->assertDatabaseHas('users', [
            'id' => $employee->id,
            'status_aktif' => 1,
        ]);
    }

    public function test_owner_cannot_assign_owner_role_to_employee(): void
    {
        $owner = $this->userWithRole('owner');
        $ownerRole = Role::query()->where('nama_role', 'owner')->firstOrFail();

        $this->actingAs($owner)
            ->post(route('owner.employee.store'), [
                'role_id' => $ownerRole->id,
                'nama_lengkap' => 'Owner Tambahan',
                'username' => 'owner.tambahan',
                'password' => 'password-baru',
            ])
            ->assertSessionHasErrors('role_id');

        $this->assertDatabaseMissing('users', ['username' => 'owner.tambahan']);
    }

    public function test_non_owner_cannot_manage_employees(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->get(route('owner.employee.index'))
            ->assertForbidden();
    }

    private function employee(string $roleName): User
    {
        $role = Role::firstOrCreate(['nama_role' => $roleName]);

        return User::factory()->create([
            'role_id' => $role->id,
            'nama_lengkap' => 'Pegawai '.$roleName,
            'username' => 'pegawai.'.$roleName,
            'cabang_id' => null,
            'status_aktif' => true,
        ]);
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['nama_role' => $roleName]);

        return User::factory()->create([
            'role_id' => $role->id,
            'status_aktif' => true,
        ]);
    }

    private function branch(): Branch
    {
        return Branch::query()->create([
            'nama_cabang' => 'Cabang Test',
            'alamat' => 'Jl. Test No. 1',
            'no_telepon' => '081200000000',
        ]);
    }
}
