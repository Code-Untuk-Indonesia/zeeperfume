<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = $this->userQuery()
            ->when($request->filled('role_id'), fn ($query) => $query->where('users.role_id', $request->integer('role_id')))
            ->when($request->filled('cabang_id'), fn ($query) => $query->where('users.cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->string('search').'%';
                $query->where(function ($query) use ($search) {
                    $query->where('users.nama_lengkap', 'like', $search)
                        ->orWhere('users.username', 'like', $search);
                });
            })
            ->orderBy('users.nama_lengkap')
            ->paginate($request->integer('per_page', 15))
            ->through(fn (object $user): object => $this->formatUser($user));

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $now = now();
        $id = DB::table('users')->insertGetId([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'status_aktif' => $validated['status_aktif'] ?? true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json($this->findUser($id), 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($this->findUser($user->getKey(), withPermissions: true));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate($this->rules($user));

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        DB::table('users')
            ->where('id', $user->getKey())
            ->update([...$validated, 'updated_at' => now()]);

        return response()->json($this->findUser($user->getKey()));
    }

    public function destroy(User $user): JsonResponse
    {
        DB::table('users')
            ->where('id', $user->getKey())
            ->update([
                'status_aktif' => false,
                'updated_at' => now(),
            ]);

        return response()->json($this->findUser($user->getKey()));
    }

    public function toggleStatus(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'status_aktif' => ['required', 'boolean'],
        ]);

        DB::table('users')
            ->where('id', $user->getKey())
            ->update([...$validated, 'updated_at' => now()]);

        return response()->json($this->findUser($user->getKey()));
    }

    private function rules(?User $user = null): array
    {
        return [
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'username' => [
                'required',
                'string',
                'max:80',
                Rule::unique('users', 'username')->ignore($user),
            ],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
            'status_aktif' => ['sometimes', 'boolean'],
            'cabang_id' => ['nullable', 'integer', 'exists:branches,id'],
        ];
    }

    private function userQuery(): Builder
    {
        return DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('branches', 'branches.id', '=', 'users.cabang_id')
            ->select([
                'users.id',
                'users.role_id',
                'users.nama_lengkap',
                'users.username',
                'users.status_aktif',
                'users.cabang_id',
                'users.created_at',
                'users.updated_at',
                'roles.nama_role as role_nama_role',
                'roles.created_at as role_created_at',
                'roles.updated_at as role_updated_at',
                'branches.nama_cabang as branch_nama_cabang',
                'branches.alamat as branch_alamat',
                'branches.no_telepon as branch_no_telepon',
                'branches.created_at as branch_created_at',
                'branches.updated_at as branch_updated_at',
                'branches.deleted_at as branch_deleted_at',
            ]);
    }

    private function findUser(int $id, bool $withPermissions = false): object
    {
        $user = $this->formatUser(
            $this->userQuery()->where('users.id', $id)->firstOrFail()
        );

        if ($withPermissions) {
            $user->role->permissions = DB::table('role_permission')
                ->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
                ->where('role_permission.role_id', $user->role_id)
                ->orderBy('permissions.nama_permission')
                ->get([
                    'permissions.id',
                    'permissions.nama_permission',
                    'permissions.deskripsi',
                    'permissions.created_at',
                    'permissions.updated_at',
                ]);
        }

        return $user;
    }

    private function formatUser(object $row): object
    {
        $row->status_aktif = (bool) $row->status_aktif;
        $row->role = (object) [
            'id' => $row->role_id,
            'nama_role' => $row->role_nama_role,
            'created_at' => $row->role_created_at,
            'updated_at' => $row->role_updated_at,
        ];
        $row->branch = $row->cabang_id === null ? null : (object) [
            'id' => $row->cabang_id,
            'nama_cabang' => $row->branch_nama_cabang,
            'alamat' => $row->branch_alamat,
            'no_telepon' => $row->branch_no_telepon,
            'created_at' => $row->branch_created_at,
            'updated_at' => $row->branch_updated_at,
            'deleted_at' => $row->branch_deleted_at,
        ];

        foreach ([
            'role_nama_role',
            'role_created_at',
            'role_updated_at',
            'branch_nama_cabang',
            'branch_alamat',
            'branch_no_telepon',
            'branch_created_at',
            'branch_updated_at',
            'branch_deleted_at',
        ] as $column) {
            unset($row->{$column});
        }

        return $row;
    }
}
