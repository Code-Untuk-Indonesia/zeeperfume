<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'month' => ['nullable', 'integer', 'between:1,12'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'report_date' => ['nullable', 'date_format:Y-m-d'],
            'report_branch' => ['nullable', 'integer', 'exists:branches,id'],
        ]);

        $month = (int) ($validated['month'] ?? now()->month);
        $year = (int) ($validated['year'] ?? now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $monthName = $startDate->translatedFormat('F Y');

        // Ambil semua data transaksi untuk kalkulasi metrik & grafik
        $allTransactions = Transaction::with(['branch', 'details.variant.product'])
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->get();

        // 1. Metrik Utama
        $totalIncome = $allTransactions->sum('total_belanja');
        $totalTrx = $allTransactions->count();
        $avgTransaction = $totalTrx > 0 ? $totalIncome / $totalTrx : 0;

        // 2. Data Grafik Harian (Tren Pendapatan)
        $labels = [];
        $dailyIncome = [];

        for ($day = 1; $day <= $startDate->daysInMonth; $day++) {
            $date = $startDate->copy()->day($day);
            $labels[] = $date->format('d M');
            $dailyIncome[] = (float) $allTransactions->filter(
                fn($trx) => Carbon::parse($trx->tanggal_waktu)->format('Y-m-d') === $date->format('Y-m-d')
            )->sum('total_belanja');
        }
        $chartData = ['labels' => $labels, 'income' => $dailyIncome];

        // 3. Distribusi Metode Pembayaran
        $paymentMethods = $allTransactions->groupBy('metode_bayar')->map(function ($group) {
            return [
                'total_transaksi' => $group->count(),
                'total_nominal' => $group->sum('total_belanja'),
            ];
        })->sortByDesc('total_nominal');

        // 4. Pendapatan per Cabang
        $branchIncomes = Branch::all()->map(function ($branch) use ($allTransactions) {
            $branchTrx = $allTransactions->where('cabang_id', $branch->id);
            return (object) [
                'nama_cabang' => $branch->nama_cabang,
                'total_trx' => $branchTrx->count(),
                'total_income' => $branchTrx->sum('total_belanja')
            ];
        })->sortByDesc('total_income');

        // 5. Rekap pendapatan harian per outlet.
        // Transaksi tetap dijumlahkan berdasarkan tanggal transaksi, bukan waktu laporan dibuka.
        $dailyReportDate = Carbon::createFromFormat(
            'Y-m-d',
            $validated['report_date'] ?? now()->toDateString()
        )->startOfDay();
        $dailyReportEnd = $dailyReportDate->copy()->endOfDay();
        $dailyReportBranches = DB::table('branches')
            ->whereNull('deleted_at')
            ->orderBy('nama_cabang')
            ->get(['id', 'nama_cabang']);

        $dailyOutletReports = DB::table('branches')
            ->leftJoin('transactions', function ($join) use ($dailyReportDate, $dailyReportEnd) {
                $join->on('transactions.cabang_id', '=', 'branches.id')
                    ->whereBetween('transactions.tanggal_waktu', [$dailyReportDate, $dailyReportEnd]);
            })
            ->leftJoin('cash_tempo', 'cash_tempo.transaksi_id', '=', 'transactions.id')
            ->whereNull('branches.deleted_at')
            ->when(
                isset($validated['report_branch']),
                fn ($query) => $query->where('branches.id', $validated['report_branch'])
            )
            ->select([
                'branches.id as cabang_id',
                'branches.nama_cabang',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(transactions.total_belanja), 0) as total_pendapatan'),
                DB::raw("COALESCE(SUM(CASE WHEN transactions.metode_bayar = 'cash_tempo' THEN transactions.total_belanja - COALESCE(cash_tempo.sisa_piutang, transactions.total_belanja) ELSE transactions.total_belanja END), 0) as total_diterima"),
                DB::raw("COALESCE(SUM(CASE WHEN transactions.metode_bayar = 'cash_tempo' THEN COALESCE(cash_tempo.sisa_piutang, transactions.total_belanja) ELSE 0 END), 0) as total_piutang"),
            ])
            ->groupBy('branches.id', 'branches.nama_cabang')
            ->orderByDesc('total_pendapatan')
            ->orderBy('branches.nama_cabang')
            ->get();

        $dailySummary = [
            'total_transaksi' => (int) $dailyOutletReports->sum('total_transaksi'),
            'total_pendapatan' => (float) $dailyOutletReports->sum('total_pendapatan'),
            'total_diterima' => (float) $dailyOutletReports->sum('total_diterima'),
            'total_piutang' => (float) $dailyOutletReports->sum('total_piutang'),
        ];

        // 6. Produk Terlaris (Berdasarkan Omzet)
        $trxIds = $allTransactions->pluck('id');
        $topProducts = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('varian_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // 7. Data Tabel Pemasukan (Income) Dengan Pagination
        $incomeTransactions = Transaction::with(['branch', 'cashier', 'member'])
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->orderByDesc('tanggal_waktu')
            ->paginate(15)
            ->withQueryString();

        return view('owner.income.index', compact(
            'month', 'year', 'monthName',
            'totalIncome', 'totalTrx', 'avgTransaction',
            'chartData', 'paymentMethods', 'branchIncomes', 'topProducts',
            'incomeTransactions', 'dailyReportDate', 'dailyReportBranches',
            'dailyOutletReports', 'dailySummary'
        ));
    }
}
