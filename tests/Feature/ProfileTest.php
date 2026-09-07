<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_admin_and_cashier_can_view_their_own_profile(): void
    {
        foreach (['owner', 'admin', 'kasir'] as $roleName) {
            $user = $this->createUser($roleName);

            $this->actingAs($user)
                ->get(route('profile.edit'))
                ->assertOk()
                ->assertViewIs('profile.edit')
                ->assertSee($user->nama_lengkap);
        }
    }

    public function test_guest_is_redirected_from_profile(): void
    {
        $this->get(route('profile.edit'))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_update_profile_without_changing_password_or_access(): void
    {
        $user = $this->createUser('admin');
        $oldPassword = DB::table('users')->where('id', $user->id)->value('password');
        $role = Role::create(['nama_role' => 'owner']);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'nama_lengkap' => 'Admin Diperbarui',
                'username' => 'admin.diperbarui',
                'role_id' => $role->id,
                'status_aktif' => false,
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nama_lengkap' => 'Admin Diperbarui',
            'username' => 'admin.diperbarui',
            'role_id' => $user->role_id,
            'status_aktif' => true,
        ]);
        $this->assertSame($oldPassword, DB::table('users')->where('id', $user->id)->value('password'));
    }

    public function test_password_change_requires_current_password_and_updates_hash(): void
    {
        $user = $this->createUser('kasir');
        $oldPassword = DB::table('users')->where('id', $user->id)->value('password');

        $this->actingAs($user)
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'nama_lengkap' => $user->nama_lengkap,
                'username' => $user->username,
                'current_password' => 'password-salah',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasErrors('current_password');

        $this->assertSame($oldPassword, DB::table('users')->where('id', $user->id)->value('password'));

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'nama_lengkap' => $user->nama_lengkap,
                'username' => $user->username,
                'current_password' => 'password',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect(route('profile.edit'));

        $newPassword = DB::table('users')->where('id', $user->id)->value('password');

        $this->assertNotSame($oldPassword, $newPassword);
        $this->assertTrue(Hash::check('password-baru', $newPassword));
    }

    private function createUser(string $roleName): User
    {
        $role = Role::create(['nama_role' => $roleName]);

        return User::create([
            'role_id' => $role->id,
            'nama_lengkap' => ucfirst($roleName).' Zee Perfume',
            'username' => $roleName.'-'.Str::lower(Str::random(8)),
            'password' => 'password',
            'status_aktif' => true,
            'cabang_id' => null,
        ]);
    }
}
