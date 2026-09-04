<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Str;

class RoleDashboard
{
    public static function routeName(User $user): ?string
    {
        return match (Str::lower((string) $user->role?->nama_role)) {
            'owner' => 'owner.dashboard',
            'admin' => 'admin.dashboard',
            'kasir' => 'kasir.pos',
            default => null,
        };
    }
}
