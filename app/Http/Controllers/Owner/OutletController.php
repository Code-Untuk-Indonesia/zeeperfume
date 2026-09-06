<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveBranchRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OutletController extends Controller
{
    public function index(Request $request): View
    {
        $outlets = $this->branchQuery()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.$request->string('search').'%';
                $query->where(function ($query) use ($search): void {
                    $query->where('branches.nama_cabang', 'like', $search)
                        ->orWhere('branches.alamat', 'like', $search)
                        ->orWhere('branches.no_telepon', 'like', $search);
                });
            })
            ->when($request->input('status') === 'aktif', fn ($query) => $query->whereNull('branches.deleted_at'))
            ->when($request->input('status') === 'tutup', fn ($query) => $query->whereNotNull('branches.deleted_at'))
            ->orderBy('branches.nama_cabang')
            ->paginate(10)
            ->withQueryString();

        $totalOutlets = DB::table('branches')->count();
        $activeOutlets = DB::table('branches')->whereNull('deleted_at')->count();
        $totalEmployees = DB::table('users')->whereNotNull('cabang_id')->count();

        return view('owner.outlet.index', compact(
            'outlets',
            'totalOutlets',
            'activeOutlets',
            'totalEmployees',
        ));
    }

    public function create(): View
    {
        return view('owner.outlet.create');
    }

    public function store(SaveBranchRequest $request): RedirectResponse
    {
        $now = now();

        DB::table('branches')->insert([
            ...$request->validated(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect()
            ->route('owner.outlet.index')
            ->with('success', 'Outlet berhasil ditambahkan.');
    }

    public function edit(int $outlet): View
    {
        return view('owner.outlet.edit', ['outlet' => $this->findBranch($outlet)]);
    }

    public function update(SaveBranchRequest $request, int $outlet): RedirectResponse
    {
        $this->findBranch($outlet);

        DB::table('branches')
            ->where('id', $outlet)
            ->update([
                ...$request->validated(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('owner.outlet.index')
            ->with('success', 'Data outlet berhasil diperbarui.');
    }

    public function destroy(int $outlet): RedirectResponse
    {
        $this->findBranch($outlet, onlyActive: true);

        DB::table('branches')
            ->where('id', $outlet)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('owner.outlet.index')
            ->with('success', 'Outlet berhasil dinonaktifkan.');
    }

    public function restore(int $outlet): RedirectResponse
    {
        $this->findBranch($outlet);

        DB::table('branches')
            ->where('id', $outlet)
            ->whereNotNull('deleted_at')
            ->update([
                'deleted_at' => null,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('owner.outlet.index')
            ->with('success', 'Outlet berhasil diaktifkan kembali.');
    }

    private function branchQuery()
    {
        $employeeCounts = DB::table('users')
            ->select('cabang_id')
            ->selectRaw('COUNT(*) as users_count')
            ->whereNotNull('cabang_id')
            ->groupBy('cabang_id');

        return DB::table('branches')
            ->leftJoinSub($employeeCounts, 'employee_counts', function ($join): void {
                $join->on('employee_counts.cabang_id', '=', 'branches.id');
            })
            ->select(
                'branches.*',
                DB::raw('COALESCE(employee_counts.users_count, 0) as users_count'),
            );
    }

    private function findBranch(int $id, bool $onlyActive = false): object
    {
        $query = $this->branchQuery()->where('branches.id', $id);

        if ($onlyActive) {
            $query->whereNull('branches.deleted_at');
        }

        return $query->firstOrFail();
    }
}
