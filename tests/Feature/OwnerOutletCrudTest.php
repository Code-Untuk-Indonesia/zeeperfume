<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerOutletCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_outlets_and_employee_counts(): void
    {
        $owner = $this->owner();
        $branch = $this->branch();

        $this->actingAs($owner)
            ->get(route('owner.outlet.index'))
            ->assertOk()
            ->assertSee($branch->nama_cabang)
            ->assertSee(route('owner.outlet.edit', $branch->id));
    }

    public function test_owner_can_create_outlet(): void
    {
        $owner = $this->owner();

        $this->actingAs($owner)
            ->post(route('owner.outlet.store'), [
                'nama_cabang' => 'Cabang Baru',
                'alamat' => 'Jl. Baru No. 1',
                'no_telepon' => '081234567890',
            ])
            ->assertRedirect(route('owner.outlet.index'));

        $this->assertDatabaseHas('branches', [
            'nama_cabang' => 'Cabang Baru',
            'alamat' => 'Jl. Baru No. 1',
        ]);
    }

    public function test_owner_can_update_outlet(): void
    {
        $owner = $this->owner();
        $branch = $this->branch();

        $this->actingAs($owner)
            ->put(route('owner.outlet.update', $branch->id), [
                'nama_cabang' => 'Cabang Diperbarui',
                'alamat' => 'Jl. Update No. 2',
                'no_telepon' => null,
            ])
            ->assertRedirect(route('owner.outlet.index'));

        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'nama_cabang' => 'Cabang Diperbarui',
            'alamat' => 'Jl. Update No. 2',
        ]);
    }

    public function test_owner_can_deactivate_and_restore_outlet(): void
    {
        $owner = $this->owner();
        $branch = $this->branch();

        $this->actingAs($owner)
            ->delete(route('owner.outlet.destroy', $branch->id))
            ->assertRedirect(route('owner.outlet.index'));

        $this->assertSoftDeleted('branches', ['id' => $branch->id]);

        $this->actingAs($owner)
            ->patch(route('owner.outlet.restore', $branch->id))
            ->assertRedirect(route('owner.outlet.index'));

        $this->assertDatabaseHas('branches', ['id' => $branch->id, 'deleted_at' => null]);
    }

    public function test_non_owner_cannot_manage_outlets(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->get(route('owner.outlet.index'))
            ->assertForbidden();
    }

    private function owner(): User
    {
        return $this->userWithRole('owner');
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::create(['nama_role' => $roleName]);

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
