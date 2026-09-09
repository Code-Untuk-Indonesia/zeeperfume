<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SidebarResponsiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_and_admin_receive_desktop_sidebar_controls(): void
    {
        foreach (['owner', 'admin'] as $roleName) {
            $user = $this->createUser($roleName);

            $this->actingAs($user)
                ->get(route('profile.edit'))
                ->assertOk()
                ->assertSee('id="desktop-sidebar-toggle"', false)
                ->assertSee('id="sidebar-open-button"', false)
                ->assertSee('id="sidebar-open-button" type="button" onclick="toggleDesktopSidebar()"', false)
                ->assertSee('sidebar-layout', false)
                ->assertSee('sidebar-hidden', false)
                ->assertSee('aria-label="Buka menu samping"', false);
        }
    }

    public function test_cashier_keeps_the_existing_cashier_layout(): void
    {
        $cashier = $this->createUser('kasir');

        $this->actingAs($cashier)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertDontSee('id="desktop-sidebar-toggle"', false)
            ->assertDontSee('id="sidebar-open-button"', false)
            ->assertDontSee('sidebar-layout', false);
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
