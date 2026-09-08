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
        $filter = $request->query('filter', 'today');
        $now = Carbon::now();

        $query = Transaction::query();
        $prevQuery = Transaction::query();
        $filterLabel = 'Hari Ini';

        if ($filter === 'custom' && $request->filled('tanggal_spesifik')) {
            // FILTER TANGGAL PILIHAN KUSTOM
            $specificDate = Carbon::parse($request->tanggal_spesifik);
            $query->whereDate('tanggal_waktu', $specificDate->toDateString());
            $prevQuery->whereDate('tanggal_waktu', $specificDate->copy()->subDay()->toDateString());
            $filterLabel = $specificDate->translatedFormat('d M Y');
        } else {
            // FILTER DEFAULT (Dropdown biasa)
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
        }

        // Kalkulasi Metrik
        $omzet = (clone $query)->sum('total_belanja');
        $prevOmzet = (clone $prevQuery)->sum('total_belanja');
        $trendOmzet = $prevOmzet > 0 ? (($omzet - $prevOmzet) / $prevOmzet) * 100 : ($omzet > 0 ? 100 : 0);

        $totalTransaksi = (clone $query)->count();
        $prevTotalTransaksi = (clone $prevQuery)->count();
        $trendTransaksi = $prevTotalTransaksi > 0 ? (($totalTransaksi - $prevTotalTransaksi) / $prevTotalTransaksi) * 100 : ($totalTransaksi > 0 ? 100 : 0);

        $pesananOnline = (clone $query)->whereHas('shipment')->count();
        $prevPesananOnline = (clone $prevQuery)->whereHas('shipment')->count();
        $trendOnline = $prevPesananOnline > 0 ? (($pesananOnline - $prevPesananOnline) / $prevPesananOnline) * 100 : ($pesananOnline > 0 ? 100 : 0);

        $stokMenipis = BranchStock::with(['variant.product', 'branch'])->where('stok', '<', 5)->get();
        $jumlahStokMenipis = $stokMenipis->count();

        $trxIds = (clone $query)->pluck('id');
        $topProducts = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('varian_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // PERBAIKAN: Hanya panggil Branch::all() tanpa with('role')
        $branches = Branch::all();

        $laporanCabang = [];
        foreach ($branches as $branch) {
            $totalSetoran = (clone $query)->where('cabang_id', $branch->id)->sum('total_belanja');
            $laporanCabang[] = [
                'nama' => $branch->nama_cabang,
                'setoran' => $totalSetoran
            ];
        }

        usort($laporanCabang, function($a, $b) {
            return $b['setoran'] <=> $a['setoran'];
        });

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
