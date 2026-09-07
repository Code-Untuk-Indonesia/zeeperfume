<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Default filter diubah menjadi 'today' (Hari Ini)
        $filter = $request->query('filter', 'today');
        $now = Carbon::now();

        // Setup Query Berdasarkan Filter Waktu
        $query = Transaction::query();
        $prevQuery = Transaction::query(); // Untuk membandingkan tren (naik/turun)
        $filterLabel = 'Hari Ini';

        switch ($filter) {
            case 'this_week':
                $query->whereBetween('tanggal_waktu', [(clone $now)->startOfWeek()->toDateString(), (clone $now)->endOfWeek()->toDateString()]);
                $prevQuery->whereBetween('tanggal_waktu', [(clone $now)->subWeek()->startOfWeek()->toDateString(), (clone $now)->subWeek()->endOfWeek()->toDateString()]);
                $filterLabel = 'Minggu Ini';
                break;
            case 'this_month':
                $query->whereMonth('tanggal_waktu', $now->month)->whereYear('tanggal_waktu', $now->year);
                $prevQuery->whereMonth('tanggal_waktu', (clone $now)->subMonth()->month)->whereYear('tanggal_waktu', (clone $now)->subMonth()->year);
                $filterLabel = 'Bulan Ini';
                break;
            case 'this_year':
                $query->whereYear('tanggal_waktu', $now->year);
                $prevQuery->whereYear('tanggal_waktu', (clone $now)->subYear()->year);
                $filterLabel = 'Tahun Ini';
                break;
            case 'today':
            default:
                $query->whereDate('tanggal_waktu', $now->toDateString());
                $prevQuery->whereDate('tanggal_waktu', (clone $now)->subDay()->toDateString());
                $filterLabel = 'Hari Ini';
                break;
        }

        // 1. Kalkulasi Metrik Utama & Tren
        $omzet = (clone $query)->sum('total_belanja');
        $prevOmzet = (clone $prevQuery)->sum('total_belanja');
        $trendOmzet = $prevOmzet > 0 ? (($omzet - $prevOmzet) / $prevOmzet) * 100 : ($omzet > 0 ? 100 : 0);

        $totalTransaksi = (clone $query)->count();
        $prevTotalTransaksi = (clone $prevQuery)->count();
        $trendTransaksi = $prevTotalTransaksi > 0 ? (($totalTransaksi - $prevTotalTransaksi) / $prevTotalTransaksi) * 100 : ($totalTransaksi > 0 ? 100 : 0);

        $pesananOnline = (clone $query)->whereHas('shipment')->count();
        $prevPesananOnline = (clone $prevQuery)->whereHas('shipment')->count();
        $trendOnline = $prevPesananOnline > 0 ? (($pesananOnline - $prevPesananOnline) / $prevPesananOnline) * 100 : ($pesananOnline > 0 ? 100 : 0);

        // 2. Peringatan Stok (Stok di bawah 5 dianggap menipis)
        $stokMenipis = BranchStock::with(['variant.product', 'branch'])->where('stok', '<', 5)->get();
        $jumlahStokMenipis = $stokMenipis->count();

        // 3. Parfum Terlaris (Top Selling) pada periode terpilih
        $trxIds = (clone $query)->pluck('id');
        $topProducts = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('varian_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // 4. Omzet Per Cabang
        $branches = Branch::all();
        $laporanCabang = [];
        foreach ($branches as $branch) {
            $totalSetoran = (clone $query)->where('cabang_id', $branch->id)->sum('total_belanja');
            $laporanCabang[] = [
                'nama' => $branch->nama_cabang,
                'setoran' => $totalSetoran
            ];
        }
        // Urutkan cabang dari pendapatan tertinggi
        usort($laporanCabang, function($a, $b) { return $b['setoran'] <=> $a['setoran']; });

        // 5. Transaksi Terbaru
        $recentTransactions = (clone $query)->with(['cashier', 'branch', 'shipment'])
            ->orderBy('tanggal_waktu', 'desc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'filter', 'filterLabel', 'omzet', 'trendOmzet',
            'totalTransaksi', 'trendTransaksi', 'pesananOnline', 'trendOnline',
            'jumlahStokMenipis', 'topProducts', 'laporanCabang', 'recentTransactions'
        ));
    }
}
