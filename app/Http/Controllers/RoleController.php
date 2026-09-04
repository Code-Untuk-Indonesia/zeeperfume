<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = DB::table('roles')
            ->select('roles.*')
            ->selectSub(
                DB::table('users')
                    ->selectRaw('count(*)')
                    ->whereColumn('users.role_id', 'roles.id'),
                'users_count'
            )
            ->orderBy('nama_role')
            ->get();

        $permissions = $this->permissionsByRole($roles->pluck('id')->all());
        $roles->each(function (object $role) use ($permissions) {
            $role->permissions = $permissions->get($role->id, collect())->values();
        });

        return response()->json($roles);
    }

    public function updatePermissions(Request $request, Role $role): JsonResponse
    {
        $validated = $request->validate([
            'permission_ids' => ['required', 'array'],
            'permission_ids.*' => ['integer', 'distinct', 'exists:permissions,id'],
        ]);

        DB::transaction(function () use ($role, $validated) {
            DB::table('role_permission')->where('role_id', $role->getKey())->delete();

            $rows = collect($validated['permission_ids'])
                ->map(fn (int $permissionId): array => [
                    'role_id' => $role->getKey(),
                    'permission_id' => $permissionId,
                ])
                ->all();

            if ($rows !== []) {
                DB::table('role_permission')->insert($rows);
            }
        });

        $roleData = DB::table('roles')->where('id', $role->getKey())->firstOrFail();
        $roleData->permissions = $this->permissionsByRole([$role->getKey()])
            ->get($role->getKey(), collect())
            ->values();

        return response()->json($roleData);
    }

    private function permissionsByRole(array $roleIds): Collection
    {
        if ($roleIds === []) {
            return collect();
        }

        return DB::table('role_permission')
            ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
            ->whereIn('role_permission.role_id', $roleIds)
            ->orderBy('permissions.nama_permission')
            ->get([
                'role_permission.role_id',
                'permissions.id',
                'permissions.nama_permission',
                'permissions.deskripsi',
                'permissions.created_at',
                'permissions.updated_at',
            ])
            ->groupBy('role_id');
    }
}
