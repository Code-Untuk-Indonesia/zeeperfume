<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'role_id' => fn () => Role::query()->firstOrCreate(['nama_role' => 'kasir'])->getKey(),
            'nama_lengkap' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'password' => static::$password ??= Hash::make('password'),
            'status_aktif' => true,
            'cabang_id' => null,
            'remember_token' => Str::random(10),
        ];
    }
}
