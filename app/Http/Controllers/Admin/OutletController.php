<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data dengan hitungan relasi 'users' (Pegawai) dan tampilkan juga yg di-soft delete
        $query = Branch::withCount('users')->withTrashed();

        // 2. Fitur Pencarian (Berdasarkan Nama atau Alamat)
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nama_cabang', 'like', $search)
                  ->orWhere('alamat', 'like', $search);
            });
        }

        // 3. Fitur Filter Status (Aktif / Tutup)
        if ($request->filled('status')) {
            if ($request->status == 'aktif') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'tutup') {
                $query->whereNotNull('deleted_at');
            }
        }

        // Eksekusi Query dengan Pagination
        $outlets = $query->orderBy('nama_cabang', 'asc')->paginate(10);

        // 4. Quick Stats
        $totalOutlets = Branch::withTrashed()->count();
        $activeOutlets = Branch::count(); // Yang tidak di-soft delete
        $totalEmployees = User::whereNotNull('cabang_id')->count();

        return view('admin.outlet.index', compact(
            'outlets', 'totalOutlets', 'activeOutlets', 'totalEmployees'
        ));
    }
}
