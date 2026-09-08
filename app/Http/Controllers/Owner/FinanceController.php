<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
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
        // 1. LOGIKA PERHITUNGAN KEUANGAN
        // =========================================================

        $totalOmzet = $transactions->sum('total_belanja');

        // Total Modal Barang Terjual (HPP) ditarik dari harga_modal di tabel produk_varian
        $totalHpp = $transactions->sum(function ($trx) {
            return $trx->details->sum(function ($detail) {
                return ($detail->variant->harga_modal ?? 0) * $detail->qty;
            });
        });

        $labaKotor = $totalOmzet - $totalHpp;
        $totalPengeluaran = $expenses->sum('nominal');
        $labaBersih = $labaKotor - $totalPengeluaran;

        $totalBeban = $totalHpp + $totalPengeluaran;
        $marginPercentage = $totalOmzet > 0 ? round(($labaBersih / $totalOmzet) * 100, 1) : 0;

        // =========================================================
        // 2. RINCIAN MODAL BARANG TERJUAL (TABEL HPP)
        // =========================================================
        $trxIds = $transactions->pluck('id');
        $hppDetails = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('varian_id')
            ->get()
            ->map(function ($item) {
                // Ambil harga modal dari produk varian
                $modalSatuan = $item->variant->harga_modal ?? 0;
                $item->modal_satuan = $modalSatuan;
                $item->total_modal = $modalSatuan * $item->total_qty;
                return $item;
            })
            // Saring agar hanya menampilkan yang modalnya lebih dari 0 jika diinginkan
            ->sortByDesc('total_modal');


        // =========================================================
        // 3. DAILY CHART
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
                    fn($detail) => ($detail->variant->harga_modal ?? 0) * $detail->qty
                );
            });

            $labels[] = $date->format('d M');
            $income[] = (float) $dailyTransactions->sum('total_belanja');
            $expenseChart[] = (float) ($dailyHpp + $dailyOperational->sum('nominal'));
        }

        $chartData = ['labels' => $labels, 'income' => $income, 'expense' => $expenseChart];

        // =========================================================
        // 4. REPORT PER CABANG
        // =========================================================
        $branchReports = Branch::all()->map(function ($branch) use ($transactions, $expenses) {
            $branchTransactions = $transactions->where('cabang_id', $branch->id);

            $omzet = $branchTransactions->sum('total_belanja');
            $hpp = $branchTransactions->sum(function ($trx) {
                return $trx->details->sum(
                    fn($detail) => ($detail->variant->harga_modal ?? 0) * $detail->qty
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

        return view('owner.finance.index', compact(
            'month',
            'year',
            'monthName',
            'totalOmzet',
            'totalHpp',
            'labaKotor',
            'totalPengeluaran',
            'totalBeban',
            'labaBersih',
            'marginPercentage',
            'branchReports',
            'chartData',
            'expenses',
            'hppDetails'
        ));
    }
}
