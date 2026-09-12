<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Parameter Filter Cerdas
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

        // Ambil Data Transaksi
        $transactions = Transaction::with('details.variant')
            ->when($startDate, fn($q) => $q->whereBetween('tanggal_waktu', [$startDate, $endDate]))
            ->when(!$startDate, fn($q) => $q->where('tanggal_waktu', '<=', $endDate))
            ->get();

        // Ambil Data Pengeluaran (Expense)
        $expenses = Expense::with(['branch', 'user'])
            ->when($startDate, fn($q) => $q->whereBetween('tanggal_pengeluaran', [$startDate, $endDate]))
            ->when(!$startDate, fn($q) => $q->where('tanggal_pengeluaran', '<=', $endDate))
            ->orderByDesc('tanggal_pengeluaran')
            ->get();

        // =========================================================
        // 1. LOGIKA PERHITUNGAN KEUANGAN
        // =========================================================

        $totalOmzet = $transactions->sum('total_belanja');

        // Total Modal Barang Terjual (HPP) ditarik dari 'harga_beli' di tabel produk_varian
        $totalHpp = $transactions->sum(function ($trx) {
            return $trx->details->sum(function ($detail) {
                return ($detail->variant->harga_beli ?? 0) * $detail->qty;
            });
        });

        $labaKotor = $totalOmzet - $totalHpp;
        $totalPengeluaran = $expenses->sum('nominal');
        $labaBersih = $labaKotor - $totalPengeluaran;

        $totalBeban = $totalHpp + $totalPengeluaran;
        $marginPercentage = $totalOmzet > 0 ? round(($labaBersih / $totalOmzet) * 100, 1) : 0;

        // =========================================================
        // 2. MENGHITUNG TOTAL ASET MODAL BARANG SAAT INI (REALTIME)
        // =========================================================
        $totalAsetModal = BranchStock::with('variant')->get()->sum(function ($stock) {
            return $stock->stok * ($stock->variant->harga_beli ?? 0);
        });

        // =========================================================
        // 3. RINCIAN MODAL BARANG TERJUAL (TABEL HPP)
        // =========================================================
        $trxIds = $transactions->pluck('id');
        $hppDetails = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('varian_id')
            ->get()
            ->map(function ($item) {
                $modalSatuan = $item->variant->harga_beli ?? 0;
                $item->modal_satuan = $modalSatuan;
                $item->total_modal = $modalSatuan * $item->total_qty;
                return $item;
            })
            ->sortByDesc('total_modal');

        // =========================================================
        // 4. PREPARE CHART DATA DYNAMICALLY
        // =========================================================
        $labels = [];
        $income = [];
        $expenseChart = [];

        if ($startDate === null || $startDate->diffInDays($endDate) > 31) {
            // GRAFIK BULANAN
            $monthsGroup = collect();

            // Kelompokkan Transaksi Per Bulan
            $trxByMonth = $transactions->groupBy(fn($t) => Carbon::parse($t->tanggal_waktu)->format('Y-m'));
            // Kelompokkan Expense Per Bulan
            $expByMonth = $expenses->groupBy(fn($e) => Carbon::parse($e->tanggal_pengeluaran)->format('Y-m'));

            // Gabungkan key bulan dari keduanya agar tidak ada yang terlewat
            $allMonths = $trxByMonth->keys()->merge($expByMonth->keys())->unique()->sort();

            foreach ($allMonths as $ym) {
                $labels[] = Carbon::createFromFormat('Y-m', $ym)->translatedFormat('M Y');

                $monthlyTrx = $trxByMonth->get($ym) ?? collect();
                $monthlyExp = $expByMonth->get($ym) ?? collect();

                $monthlyOmzet = $monthlyTrx->sum('total_belanja');
                $monthlyHpp = $monthlyTrx->sum(fn($trx) => $trx->details->sum(fn($d) => ($d->variant->harga_beli ?? 0) * $d->qty));
                $monthlyOps = $monthlyExp->sum('nominal');

                $income[] = (float) $monthlyOmzet;
                $expenseChart[] = (float) ($monthlyHpp + $monthlyOps);
            }
        } elseif ($startDate->diffInDays($endDate) == 0) {
            // GRAFIK PER JAM
            for ($i = 8; $i <= 22; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';

                $hourlyTrx = $transactions->filter(fn($t) => (int) Carbon::parse($t->tanggal_waktu)->format('H') === $i);
                $hourlyExp = $expenses->filter(fn($e) => (int) Carbon::parse($e->tanggal_pengeluaran)->format('H') === $i);

                $hourlyOmzet = $hourlyTrx->sum('total_belanja');
                $hourlyHpp = $hourlyTrx->sum(fn($trx) => $trx->details->sum(fn($d) => ($d->variant->harga_beli ?? 0) * $d->qty));
                $hourlyOps = $hourlyExp->sum('nominal');

                $income[] = (float) $hourlyOmzet;
                $expenseChart[] = (float) ($hourlyHpp + $hourlyOps);
            }
        } else {
            // GRAFIK HARIAN
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateKey = $date->format('Y-m-d');
                $labels[] = $date->format('d M');

                $dailyTrx = $transactions->filter(fn($trx) => Carbon::parse($trx->tanggal_waktu)->format('Y-m-d') === $dateKey);
                $dailyExp = $expenses->filter(fn($exp) => Carbon::parse($exp->tanggal_pengeluaran)->format('Y-m-d') === $dateKey);

                $dailyOmzet = $dailyTrx->sum('total_belanja');
                $dailyHpp = $dailyTrx->sum(fn($trx) => $trx->details->sum(fn($d) => ($d->variant->harga_beli ?? 0) * $d->qty));
                $dailyOps = $dailyExp->sum('nominal');

                $income[] = (float) $dailyOmzet;
                $expenseChart[] = (float) ($dailyHpp + $dailyOps);
            }
        }

        // Fallback
        if (empty($labels)) {
            $labels[] = now()->translatedFormat('d M Y');
            $income[] = 0;
            $expenseChart[] = 0;
        }

        $chartData = ['labels' => array_values($labels), 'income' => array_values($income), 'expense' => array_values($expenseChart)];

        // =========================================================
        // 5. REPORT PER CABANG
        // =========================================================
        $branchReports = Branch::all()->map(function ($branch) use ($transactions, $expenses) {
            $branchTransactions = $transactions->where('cabang_id', $branch->id);

            $omzet = $branchTransactions->sum('total_belanja');
            $hpp = $branchTransactions->sum(function ($trx) {
                return $trx->details->sum(fn($detail) => ($detail->variant->harga_beli ?? 0) * $detail->qty);
            });

            $labaKotorCabang = $omzet - $hpp;
            $pengeluaran = $expenses->where('cabang_id', $branch->id)->sum('nominal');
            $labaBersihCabang = $labaKotorCabang - $pengeluaran;

            return (object) [
                'nama_cabang' => $branch->nama_cabang,
                'omzet'       => $omzet,
                'hpp'         => $hpp,
                'laba_kotor'  => $labaKotorCabang,
                'pengeluaran' => $pengeluaran,
                'laba_bersih' => $labaBersihCabang,
                'margin'      => $omzet > 0 ? round(($labaBersihCabang / $omzet) * 100, 1) : 0,
            ];
        });

        $data = compact(
            'period', 'periodLabel',
            'totalOmzet', 'totalHpp', 'labaKotor', 'totalPengeluaran',
            'totalBeban', 'labaBersih', 'marginPercentage', 'totalAsetModal',
            'branchReports', 'chartData', 'expenses', 'hppDetails'
        );

        if ($request->ajax()) {
            return response()->json([
                'html' => view('owner.finance.index', $data)->renderSections()['content'],
                'chart' => $chartData
            ]);
        }

        return view('owner.finance.index', $data);
    }
}
