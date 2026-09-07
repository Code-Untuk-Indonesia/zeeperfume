<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $profile = $this->profileQuery($request->user()->getAuthIdentifier());

        abort_if($profile === null, 404);

        $layout = in_array(strtolower((string) $profile->nama_role), ['owner', 'admin'], true)
            ? 'template.sidebar'
            : 'template.kasir';

        return view('profile.edit', compact('profile', 'layout'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $data = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'updated_at' => now(),
        ];

        if (filled($validated['password'] ?? null)) {
            $data['password'] = Hash::make($validated['password']);
        }

        DB::table('users')
            ->where('id', $request->user()->getAuthIdentifier())
            ->update($data);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    private function profileQuery(int|string $userId): ?object
    {
        return DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('branches', function ($join): void {
                $join->on('branches.id', '=', 'users.cabang_id')
                    ->whereNull('branches.deleted_at');
            })
            ->where('users.id', $userId)
            ->select([
                'users.id',
                'users.nama_lengkap',
                'users.username',
                'users.role_id',
                'users.cabang_id',
                'users.status_aktif',
                'roles.nama_role',
                'branches.nama_cabang',
            ])
            ->first();
    }
}
