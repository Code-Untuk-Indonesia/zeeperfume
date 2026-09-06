<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveEmployeeRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    private const EMPLOYEE_ROLES = ['admin', 'kasir'];

    public function index(Request $request): View
    {
        $employees = $this->employeeQuery()
            ->when($request->filled('role_id'), fn (Builder $query) => $query->where('users.role_id', $request->integer('role_id')))
            ->when($request->filled('cabang_id'), fn (Builder $query) => $query->where('users.cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $search = '%'.$request->string('search').'%';
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('users.nama_lengkap', 'like', $search)
                        ->orWhere('users.username', 'like', $search);
                });
            })
            ->orderBy('users.nama_lengkap')
            ->paginate(10)
            ->withQueryString();

        $roles = $this->roleOptions();
        $branches = $this->branchOptions();

        $totalEmployees = $this->employeeCount();
        $totalAdmins = $this->employeeCount('admin');
        $totalCashiers = $this->employeeCount('kasir', activeOnly: true);

        return view('owner.employee.index', compact(
            'employees',
            'roles',
            'branches',
            'totalEmployees',
            'totalAdmins',
            'totalCashiers',
        ));
    }

    public function create(): View
    {
        return view('owner.employee.create', [
            'roles' => $this->roleOptions(),
            'branches' => $this->branchOptions(),
        ]);
    }

    public function store(SaveEmployeeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $now = now();

        DB::table('users')->insert([
            'role_id' => $validated['role_id'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'status_aktif' => $validated['status_aktif'] ?? true,
            'cabang_id' => $validated['cabang_id'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect()
            ->route('owner.employee.index')
            ->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(int $employee): View
    {
        return view('owner.employee.edit', [
            'employee' => $this->findEmployee($employee),
            'roles' => $this->roleOptions(),
            'branches' => $this->branchOptions(),
        ]);
    }

    public function update(SaveEmployeeRequest $request, int $employee): RedirectResponse
    {
        $currentEmployee = $this->findEmployee($employee);
        $validated = $request->validated();

        $data = [
            'role_id' => $validated['role_id'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'status_aktif' => array_key_exists('status_aktif', $validated)
                ? (bool) $validated['status_aktif']
                : (bool) $currentEmployee->status_aktif,
            'cabang_id' => array_key_exists('cabang_id', $validated)
                ? $validated['cabang_id']
                : $currentEmployee->cabang_id,
            'updated_at' => now(),
        ];

        if (filled($validated['password'] ?? null)) {
            $data['password'] = Hash::make($validated['password']);
        }

        DB::table('users')
            ->where('id', $employee)
            ->update($data);

        return redirect()
            ->route('owner.employee.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(int $employee): RedirectResponse
    {
        $this->findEmployee($employee);

        DB::table('users')
            ->where('id', $employee)
            ->update([
                'status_aktif' => false,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('owner.employee.index')
            ->with('success', 'Pegawai berhasil dinonaktifkan.');
    }

    public function restore(int $employee): RedirectResponse
    {
        $this->findEmployee($employee);

        DB::table('users')
            ->where('id', $employee)
            ->update([
                'status_aktif' => true,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('owner.employee.index')
            ->with('success', 'Pegawai berhasil diaktifkan kembali.');
    }

    private function employeeQuery(): Builder
    {
        return DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->leftJoin('branches', function ($join): void {
                $join->on('branches.id', '=', 'users.cabang_id')
                    ->whereNull('branches.deleted_at');
            })
            ->whereIn('roles.nama_role', self::EMPLOYEE_ROLES)
            ->select([
                'users.id',
                'users.role_id',
                'users.nama_lengkap',
                'users.username',
                'users.status_aktif',
                'users.cabang_id',
                'roles.nama_role',
                'branches.nama_cabang',
            ]);
    }

    private function findEmployee(int $id): object
    {
        return $this->employeeQuery()
            ->where('users.id', $id)
            ->firstOrFail();
    }

    private function roleOptions(): Collection
    {
        return DB::table('roles')
            ->whereIn('nama_role', self::EMPLOYEE_ROLES)
            ->orderBy('nama_role')
            ->get(['id', 'nama_role']);
    }

    private function branchOptions(): Collection
    {
        return DB::table('branches')
            ->whereNull('deleted_at')
            ->orderBy('nama_cabang')
            ->get(['id', 'nama_cabang']);
    }

    private function employeeCount(?string $role = null, bool $activeOnly = false): int
    {
        return DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->whereIn('roles.nama_role', $role ? [$role] : self::EMPLOYEE_ROLES)
            ->when($activeOnly, fn (Builder $query) => $query->where('users.status_aktif', true))
            ->count('users.id');
    }
}
