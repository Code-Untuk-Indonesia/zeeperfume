<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $monthName = $startDate->translatedFormat('F Y');

        $transactions = Transaction::with('details.variant')
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->get();

        $expenses = Expense::with(['branch', 'user'])
            ->whereBetween('tanggal_pengeluaran', [$startDate, $endDate])
            ->orderByDesc('tanggal_pengeluaran')
            ->get();

        // =========================================================
        // 1. LOGIKA PERHITUNGAN SESUAI "UNTUNG.JPEG"
        // =========================================================

        // 1. Total Omzet / Pendapatan Kotor
        $totalOmzet = $transactions->sum('total_belanja');

        // 2. Total Modal Barang Terjual (HPP)
        $totalHpp = $transactions->sum(function ($trx) {
            return $trx->details->sum(function ($detail) {
                // Asumsi harga modal disimpan di 'harga_beli'
                return ($detail->variant->harga_beli ?? 0) * $detail->qty;
            });
        });

        // 3. Laba Kotor (Gross Profit)
        $labaKotor = $totalOmzet - $totalHpp;

        // 4. Total Pengeluaran Operasional
        $totalPengeluaran = $expenses->sum('nominal');

        // 5. Keuntungan Bersih (Net Profit)
        $labaBersih = $labaKotor - $totalPengeluaran;

        // Variabel tambahan untuk kebutuhan UI View
        $totalBeban = $totalHpp + $totalPengeluaran;
        $marginPercentage = $totalOmzet > 0 ? round(($labaBersih / $totalOmzet) * 100, 1) : 0;


        // =========================================================
        // 2. DAILY CHART (Untuk Grafik Line)
        // =========================================================
        $labels = [];
        $income = [];
        $expenseChart = [];

        for ($day = 1; $day <= $startDate->daysInMonth; $day++) {
            $date = $startDate->copy()->day($day);
            $dateKey = $date->format('Y-m-d');

            $dailyTransactions = $transactions->filter(
                fn($trx) => Carbon::parse($trx->tanggal_waktu)->format('Y-m-d') === $dateKey
            );

            $dailyOperational = $expenses->filter(
                fn($exp) => Carbon::parse($exp->tanggal_pengeluaran)->format('Y-m-d') === $dateKey
            );

            $dailyHpp = $dailyTransactions->sum(function ($trx) {
                return $trx->details->sum(
                    fn($detail) => ($detail->variant->harga_beli ?? 0) * $detail->qty
                );
            });

            $labels[] = $date->format('d M');
            $income[] = (float) $dailyTransactions->sum('total_belanja');
            // Biaya harian = HPP harian + Operasional harian
            $expenseChart[] = (float) ($dailyHpp + $dailyOperational->sum('nominal'));
        }

        $chartData = [
            'labels'  => $labels,
            'income'  => $income,
            'expense' => $expenseChart
        ];


        // =========================================================
        // 3. REPORT PER CABANG
        // =========================================================
        $branchReports = Branch::all()->map(function ($branch) use ($transactions, $expenses) {
            $branchTransactions = $transactions->where('cabang_id', $branch->id);

            $omzet = $branchTransactions->sum('total_belanja');

            $hpp = $branchTransactions->sum(function ($trx) {
                return $trx->details->sum(
                    fn($detail) => ($detail->variant->harga_beli ?? 0) * $detail->qty
                );
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

        // =========================================================
        // 4. LEMPAR SEMUA DATA KE VIEW
        // =========================================================
        return view('owner.finance.index', compact(
            'month',
            'year',
            'monthName',
            'totalOmzet',
            'totalHpp',
            'labaKotor',          // Pastikan labaKotor dimasukkan
            'totalPengeluaran',
            'totalBeban',
            'labaBersih',
            'marginPercentage',
            'branchReports',
            'chartData',
            'expenses'            // Pastikan expenses dimasukkan
        ));
    }
}
