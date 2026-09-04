<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['owner', 'admin', 'kasir'] as $roleName) {
            $role = Role::query()
                ->whereRaw('LOWER(nama_role) = ?', [$roleName])
                ->first();

            if ($role === null) {
                Role::create(['nama_role' => $roleName]);
                continue;
            }

            $role->update(['nama_role' => $roleName]);
        }
    }
}
