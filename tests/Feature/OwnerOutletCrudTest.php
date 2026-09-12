<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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

    public function test_owner_outlet_table_displays_opening_and_closing_hours(): void
    {
        $owner = $this->owner();
        $branch = $this->branch();
        DB::table('branches')->where('id', $branch->id)->update([
            'jam_buka' => '08:00',
            'jam_tutup' => '22:00',
        ]);

        $this->actingAs($owner)
            ->get(route('owner.outlet.index'))
            ->assertOk()
            ->assertSee('Jam Operasional')
            ->assertSee('Buka')
            ->assertSee('08:00')
            ->assertSee('Tutup')
            ->assertSee('22:00')
            ->assertSee('overflow-x-auto', false);
    }

    public function test_owner_outlet_table_identifies_unconfigured_hours(): void
    {
        $owner = $this->owner();
        $branch = $this->branch();

        $this->actingAs($owner)
            ->get(route('owner.outlet.index'))
            ->assertOk()
            ->assertSee($branch->nama_cabang)
            ->assertSee('Jam Operasional')
            ->assertSee('Belum diatur');
    }

    public function test_owner_can_set_outlet_hours_in_create_and_edit_forms(): void
    {
        $owner = $this->owner();
        $branch = $this->branch();
        DB::table('branches')->where('id', $branch->id)->update([
            'jam_buka' => '08:00',
            'jam_tutup' => '22:00',
        ]);

        $this->actingAs($owner)
            ->get(route('owner.outlet.create'))
            ->assertOk()
            ->assertSee('name="jam_buka"', false)
            ->assertSee('name="jam_tutup"', false)
            ->assertSee('type="time"', false)
            ->assertSee('grid-cols-1 gap-4 sm:grid-cols-2', false);

        $this->actingAs($owner)
            ->get(route('owner.outlet.edit', $branch->id))
            ->assertOk()
            ->assertSee('name="jam_buka"', false)
            ->assertSee('value="08:00"', false)
            ->assertSee('name="jam_tutup"', false)
            ->assertSee('value="22:00"', false);
    }

    public function test_owner_can_create_outlet(): void
    {
        $owner = $this->owner();

        $this->actingAs($owner)
            ->post(route('owner.outlet.store'), [
                'nama_cabang' => 'Cabang Baru',
                'alamat' => 'Jl. Baru No. 1',
                'no_telepon' => '081234567890',
                'jam_buka' => '08:00',
                'jam_tutup' => '22:00',
            ])
            ->assertRedirect(route('owner.outlet.index'));

        $this->assertDatabaseHas('branches', [
            'nama_cabang' => 'Cabang Baru',
            'alamat' => 'Jl. Baru No. 1',
        ]);
        $this->assertSame(
            '08:00',
            substr(DB::table('branches')->where('nama_cabang', 'Cabang Baru')->value('jam_buka'), 0, 5),
        );
        $this->assertSame(
            '22:00',
            substr(DB::table('branches')->where('nama_cabang', 'Cabang Baru')->value('jam_tutup'), 0, 5),
        );
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
                'jam_buka' => '09:00',
                'jam_tutup' => '21:30',
            ])
            ->assertRedirect(route('owner.outlet.index'));

        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'nama_cabang' => 'Cabang Diperbarui',
            'alamat' => 'Jl. Update No. 2',
        ]);
        $this->assertSame(
            '09:00',
            substr(DB::table('branches')->where('id', $branch->id)->value('jam_buka'), 0, 5),
        );
        $this->assertSame(
            '21:30',
            substr(DB::table('branches')->where('id', $branch->id)->value('jam_tutup'), 0, 5),
        );
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
