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
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

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

        // 5. Produk Terlaris (Berdasarkan Omzet)
        $trxIds = $allTransactions->pluck('id');
        $topProducts = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('varian_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        // 6. Data Tabel Pemasukan (Income) Dengan Pagination
        $incomeTransactions = Transaction::with(['branch', 'cashier', 'member'])
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->orderByDesc('tanggal_waktu')
            ->paginate(15)
            ->withQueryString();

        return view('owner.income.index', compact(
            'month', 'year', 'monthName',
            'totalIncome', 'totalTrx', 'avgTransaction',
            'chartData', 'paymentMethods', 'branchIncomes', 'topProducts',
            'incomeTransactions'
        ));
    }
}
