<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        $branches = Branch::all();

        $query = User::with(['role', 'branch']);

        // Filter berdasarkan Role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter berdasarkan Cabang/Outlet
        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        // Pencarian (Search)
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', $search)
                  ->orWhere('username', 'like', $search);
            });
        }

        $employees = $query->orderBy('nama_lengkap', 'asc')->paginate(10);

        // Menghitung Quick Stats
        $totalEmployees = User::count();
        $totalAdmins = User::whereHas('role', function($q) {
            $q->where('nama_role', 'admin');
        })->count();
        $totalCashiers = User::whereHas('role', function($q) {
            $q->where('nama_role', 'kasir');
        })->where('status_aktif', true)->count();

        // Hanya me-return 1 view utama
        return view('owner.employee.index', compact(
            'employees', 'roles', 'branches', 'totalEmployees', 'totalAdmins', 'totalCashiers'
        ));
    }
}
