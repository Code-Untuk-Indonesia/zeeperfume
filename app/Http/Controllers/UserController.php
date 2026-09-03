<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->with(['role', 'branch'])
            ->when($request->filled('role_id'), fn ($query) => $query->where('role_id', $request->integer('role_id')))
            ->when($request->filled('cabang_id'), fn ($query) => $query->where('cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->string('search').'%';
                $query->where(function ($query) use ($search) {
                    $query->where('nama_lengkap', 'like', $search)
                        ->orWhere('username', 'like', $search);
                });
            })
            ->orderBy('nama_lengkap')
            ->paginate($request->integer('per_page', 15));

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        return response()->json(User::create($validated)->load(['role', 'branch']), 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user->load(['role.permissions', 'branch']));
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate($this->rules($user));

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json($user->fresh()->load(['role', 'branch']));
    }

    public function destroy(User $user): JsonResponse
    {
        $user->update(['status_aktif' => false]);

        return response()->json($user->fresh());
    }

    public function toggleStatus(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'status_aktif' => ['required', 'boolean'],
        ]);

        $user->update($validated);

        return response()->json($user->fresh());
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
}
