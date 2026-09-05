<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BranchStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Member;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama Dashboard Owner
     */
    public function index()
    {
        $totalRevenue = Transaction::sum('total_belanja');
        $totalTransactions = Transaction::count();
        $totalMembers = Member::count();

        $paymentMethods = Transaction::select('metode_bayar', DB::raw('count(*) as total_transaksi'))
            ->groupBy('metode_bayar')
            ->get();

        // DIPERBARUI: Menggunakan 'variant' dan 'branch' sesuai Model BranchStock
        $lowStocks = BranchStock::with(['variant.product', 'branch'])
            ->where('stok', '<=', 10)
            ->get();

        // Catatan: Pastikan Model Transaction Anda memiliki relasi 'kasir' dan 'cabang'.
        // Jika menggunakan bahasa Inggris di model Transaction, ubah menjadi ['user', 'branch']
        $recentTransactions = Transaction::with(['cashier', 'branch'])
            ->orderBy('tanggal_waktu', 'desc')
            ->limit(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalRevenue',
            'totalTransactions',
            'totalMembers',
            'paymentMethods',
            'lowStocks',
            'recentTransactions'
        ));
    }
}
