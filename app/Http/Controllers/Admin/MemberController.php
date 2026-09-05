<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query Utama Member (Hitung Transaksi & Total Belanja per Member)
        $query = Member::withCount('transactions')
            ->withSum('transactions as total_belanja', 'total_belanja');

        // 2. Fitur Pencarian (Nama, No HP, Kode Member)
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', $search)
                  ->orWhere('no_telp', 'like', $search)
                  ->orWhere('kode_member', 'like', $search);
            });
        }

        // 3. Filter Tipe Member (berdasarkan jumlah poin sbg simulasi, atau bisa dimodif sesuai DB Anda)
        if ($request->filled('tipe')) {
            if ($request->tipe === 'vip') {
                $query->where('poin', '>=', 500); // Misal: VIP jika poin >= 500
            } elseif ($request->tipe === 'reguler') {
                $query->where('poin', '<', 500);
            }
        }

        // Eksekusi data dgn pagination
        $members = $query->orderBy('created_at', 'desc')->paginate(10);

        // Jika request dari AJAX (Search/Filter)
        if ($request->ajax()) {
            return view('owner.member.partials.table-rows', compact('members'))->render();
        }

        // 4. Hitung Quick Stats untuk Card Atas
        $totalMembers = Member::count();
        $vipMembers = Member::where('poin', '>=', 500)->count(); // Simulasi VIP
        $totalBelanjaSemua = Transaction::whereNotNull('member_id')->sum('total_belanja');

        return view('admin.member.index', compact(
            'members', 'totalMembers', 'vipMembers', 'totalBelanjaSemua'
        ));
    }
}
