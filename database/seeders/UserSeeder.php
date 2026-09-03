<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = (string) config('seeders.user_password');

        if ($password === '') {
            throw new RuntimeException('SEED_USER_PASSWORD tidak boleh kosong.');
        }

        if (app()->isProduction() && $password === 'password') {
            throw new RuntimeException('Atur SEED_USER_PASSWORD sebelum menjalankan seeder di production.');
        }

        $roles = Role::query()->get()->keyBy('nama_role');
        $centralBranch = Branch::query()->where('nama_cabang', 'Outlet Pusat')->firstOrFail();
        $users = [
            [
                'role' => 'owner',
                'nama_lengkap' => 'Owner Zee Perfume',
                'username' => (string) config('seeders.owner_username'),
                'cabang_id' => null,
            ],
            [
                'role' => 'admin',
                'nama_lengkap' => 'Admin Zee Perfume',
                'username' => (string) config('seeders.admin_username'),
                'cabang_id' => null,
            ],
            [
                'role' => 'kasir',
                'nama_lengkap' => 'Kasir Outlet Pusat',
                'username' => (string) config('seeders.cashier_username'),
                'cabang_id' => $centralBranch->getKey(),
            ],
        ];

        foreach ($users as $account) {
            $role = $roles->get($account['role']);

            if ($role === null || $account['username'] === '') {
                throw new RuntimeException('Konfigurasi akun seeder tidak lengkap.');
            }

            User::query()->updateOrCreate(
                ['username' => $account['username']],
                [
                    'role_id' => $role->getKey(),
                    'nama_lengkap' => $account['nama_lengkap'],
                    'password' => Hash::make($password),
                    'status_aktif' => true,
                    'cabang_id' => $account['cabang_id'],
                ],
            );
        }
    }
}
