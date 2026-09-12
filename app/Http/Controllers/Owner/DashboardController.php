<?php

namespace App\Http\Controllers\Owner;

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
        // 1. Ambil Parameter Filter
        $period = $request->query('period', 'this_month');
        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');

        // Kalkulasi Rentang Tanggal
        if ($period === 'custom' && $startDateInput && $endDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
            $periodLabel = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
        } elseif ($period === 'today') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
            $periodLabel = 'Hari Ini (' . $startDate->translatedFormat('d M Y') . ')';
        } elseif ($period === 'this_week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
            $periodLabel = 'Minggu Ini (' . $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M Y') . ')';
        } elseif ($period === 'this_year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfDay();
            $periodLabel = 'Tahun Ini (' . $startDate->translatedFormat('Y') . ')';
        } elseif ($period === 'all') {
            $startDate = null;
            $endDate = Carbon::now()->endOfDay();
            $periodLabel = 'Keseluruhan Waktu';
        } else {
            // Default: this_month
            $period = 'this_month';
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfDay();
            $periodLabel = 'Bulan Ini (' . $startDate->translatedFormat('F Y') . ')';
        }

        // Query Dasar Transaksi dengan Filter Waktu
        $trxQuery = Transaction::with(['cashTempo', 'details.variant.product', 'branch', 'member', 'kasir'])
            ->when($startDate, fn($q) => $q->whereBetween('tanggal_waktu', [$startDate, $endDate]))
            ->when(!$startDate, fn($q) => $q->where('tanggal_waktu', '<=', $endDate));

        $allTransactions = $trxQuery->get();

        // 2. Kalkulasi Kas & Piutang
        $totalOmzet = $allTransactions->sum('total_belanja');
        $totalPiutang = 0;

        foreach ($allTransactions as $trx) {
            if (in_array(strtolower($trx->metode_bayar), ['tempo', 'cash_tempo'])) {
                if ($trx->cashTempo && $trx->cashTempo->sisa_piutang > 0) {
                    $totalPiutang += $trx->cashTempo->sisa_piutang;
                } elseif (!$trx->cashTempo) {
                    $totalPiutang += $trx->total_belanja;
                }
            }
        }

        $kasDiterima = $totalOmzet - $totalPiutang;
        $totalTransactions = $allTransactions->count();

        // 3. Metrik Lainnya & Aset Modal
        $paymentMethods = $allTransactions->groupBy('metode_bayar')->map(function ($group) {
            return (object) [
                'metode_bayar' => $group->first()->metode_bayar,
                'total_transaksi' => $group->count()
            ];
        })->values();

        $lowStocks = BranchStock::with(['variant.product', 'branch'])
            ->where('stok', '<=', 10)
            ->get();

        $recentTransactions = $allTransactions->sortByDesc('tanggal_waktu')->take(5);

        // ASET MODAL (Tidak terpengaruh filter waktu, karena ini sisa stok di gudang riil saat ini)
        $totalAsetModal = BranchStock::with('variant')->get()->sum(function ($stock) {
            return $stock->stok * ($stock->variant->harga_modal ?? 0);
        });

        // 4. Persiapan Data Grafik Dinamis
        $chartLabels = [];
        $chartOmzet = [];
        $chartHpp = [];

        if ($startDate === null || $startDate->diffInDays($endDate) > 31) {
            // GRAFIK BULANAN (Untuk "Keseluruhan" atau "Tahun Ini")
            $monthsGroup = $allTransactions->groupBy(fn($t) => Carbon::parse($t->tanggal_waktu)->format('Y-m'))->sortKeys();
            foreach ($monthsGroup as $ym => $monthlyTrx) {
                $chartLabels[] = Carbon::createFromFormat('Y-m', $ym)->translatedFormat('M Y');
                $chartOmzet[] = $monthlyTrx->sum('total_belanja');
                $chartHpp[] = $monthlyTrx->sum(function ($trx) {
                    return $trx->details->sum(fn($d) => ($d->variant->harga_modal ?? 0) * $d->qty);
                });
            }
        } elseif ($startDate->diffInDays($endDate) == 0) {
            // GRAFIK JAM (Untuk "Hari Ini") - Jam buka toko asumsikan 08:00 - 22:00
            for ($i = 8; $i <= 22; $i++) {
                $chartLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $hourlyTrx = $allTransactions->filter(fn($t) => (int) Carbon::parse($t->tanggal_waktu)->format('H') === $i);

                $chartOmzet[] = $hourlyTrx->sum('total_belanja');
                $chartHpp[] = $hourlyTrx->sum(function ($trx) {
                    return $trx->details->sum(fn($d) => ($d->variant->harga_modal ?? 0) * $d->qty);
                });
            }
        } else {
            // GRAFIK HARIAN (Untuk "Minggu Ini", "Bulan Ini", atau Custom <= 31 Hari)
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $chartLabels[] = $date->format('d M');
                $dailyTrx = $allTransactions->filter(fn($t) => Carbon::parse($t->tanggal_waktu)->format('Y-m-d') === $date->format('Y-m-d'));

                $chartOmzet[] = $dailyTrx->sum('total_belanja');
                $chartHpp[] = $dailyTrx->sum(function ($trx) {
                    return $trx->details->sum(fn($d) => ($d->variant->harga_modal ?? 0) * $d->qty);
                });
            }
        }

        // Fallback jika tidak ada data
        if (empty($chartLabels)) {
            $chartLabels[] = now()->translatedFormat('d M Y');
            $chartOmzet[] = 0;
            $chartHpp[] = 0;
        }

        $chartData = [
            'labels' => array_values($chartLabels),
            'omzet' => array_values($chartOmzet),
            'hpp' => array_values($chartHpp)
        ];

        // 5. TOP PRODUCTS & CABANG
        $trxIds = $allTransactions->pluck('id');
        $topProducts = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('varian_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        $branchIncomes = Branch::all()->map(function ($branch) use ($allTransactions) {
            $branchTrx = $allTransactions->where('cabang_id', $branch->id);
            return (object) [
                'nama_cabang' => $branch->nama_cabang,
                'total_trx' => $branchTrx->count(),
                'total_income' => $branchTrx->sum('total_belanja')
            ];
        })->sortByDesc('total_income');

        $data = compact(
            'period', 'periodLabel',
            'kasDiterima', 'totalPiutang', 'totalTransactions', 'totalAsetModal',
            'paymentMethods', 'lowStocks', 'recentTransactions',
            'chartData', 'topProducts', 'branchIncomes'
        );

        // Jika Request dari AJAX, Return JSON HTML
        if ($request->ajax()) {
            return response()->json([
                'html' => view('owner.dashboard', $data)->renderSections()['content'],
                'chart' => $chartData
            ]);
        }

        return view('owner.dashboard', $data);
    }
}
