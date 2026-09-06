<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerMemberCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_member_management(): void
    {
        $owner = $this->owner();
        $member = $this->member();

        $this->actingAs($owner)
            ->get(route('owner.member.index'))
            ->assertOk()
            ->assertSee($member->nama)
            ->assertSee(route('owner.member.edit', $member->id));
    }

    public function test_owner_can_create_member(): void
    {
        $owner = $this->owner();

        $this->actingAs($owner)
            ->post(route('owner.member.store'), [
                'nama' => 'Member Owner Baru',
                'no_telp' => '081234567890',
                'email' => 'owner-member@example.test',
                'poin' => 50,
                'tanggal_bergabung' => '2026-09-05',
            ])
            ->assertRedirect(route('owner.member.index'));

        $this->assertDatabaseHas('members', [
            'nama' => 'Member Owner Baru',
            'email' => 'owner-member@example.test',
        ]);
    }

    public function test_owner_can_update_member(): void
    {
        $owner = $this->owner();
        $member = $this->member();

        $this->actingAs($owner)
            ->put(route('owner.member.update', $member->id), [
                'nama' => 'Member Owner Diperbarui',
                'no_telp' => null,
                'email' => null,
                'poin' => 500,
                'tanggal_bergabung' => '2026-09-02',
            ])
            ->assertRedirect(route('owner.member.index'));

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'nama' => 'Member Owner Diperbarui',
            'poin' => 500,
        ]);
    }

    public function test_owner_can_soft_delete_member(): void
    {
        $owner = $this->owner();
        $member = $this->member();

        $this->actingAs($owner)
            ->delete(route('owner.member.destroy', $member->id))
            ->assertRedirect(route('owner.member.index'));

        $this->assertSoftDeleted('members', ['id' => $member->id]);
    }

    private function owner(): User
    {
        $role = Role::create(['nama_role' => 'owner']);

        return User::factory()->create([
            'role_id' => $role->id,
            'status_aktif' => true,
        ]);
    }

    private function member(): Member
    {
        return Member::query()->create([
            'kode_member' => 'MBR-'.fake()->unique()->numerify('###'),
            'nama' => 'Member Owner Test',
            'no_telp' => '081200000000',
            'email' => 'owner-test@example.test',
            'poin' => 0,
            'tanggal_bergabung' => '2026-09-01',
        ]);
    }
}
