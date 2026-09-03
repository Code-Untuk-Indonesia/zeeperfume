<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_page(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Masuk ke ruang kerja');
    }

    public function test_active_user_can_login_with_username_and_is_redirected_by_role(): void
    {
        $owner = $this->createUser('owner');

        $this->post('/login', [
            'username' => $owner->username,
            'password' => 'password',
        ])->assertRedirect('/owner/dashboard');

        $this->assertAuthenticatedAs($owner);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = $this->createUser('admin', false);

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_dashboard_requires_authentication_and_correct_role(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');

        $owner = $this->createUser('owner');

        $this->actingAs($owner)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $cashier = $this->createUser('kasir');

        $this->actingAs($cashier)
            ->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    private function createUser(string $roleName, bool $active = true): User
    {
        $role = Role::create(['nama_role' => $roleName]);

        return User::create([
            'role_id' => $role->getKey(),
            'nama_lengkap' => ucfirst($roleName).' Zee Perfume',
            'username' => $roleName,
            'password' => 'password',
            'status_aktif' => $active,
            'cabang_id' => null,
        ]);
    }
}
