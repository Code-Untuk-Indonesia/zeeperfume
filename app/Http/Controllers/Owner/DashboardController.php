<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Kalkulasi Kas & Piutang
        $allTransactions = Transaction::with('cashTempo')->get();

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

        // 2. Metrik Lainnya
        $paymentMethods = Transaction::select('metode_bayar', DB::raw('count(*) as total_transaksi'))
            ->groupBy('metode_bayar')
            ->get();

        $lowStocks = BranchStock::with(['variant.product', 'branch'])
            ->where('stok', '<=', 10)
            ->get();

        $recentTransactions = Transaction::with(['cashier', 'branch', 'member'])
            ->orderByDesc('tanggal_waktu')
            ->limit(5)
            ->get();

        // 3. Persiapan Data Grafik
        $startOfWeek = Carbon::now()->startOfWeek();
        $chartLabels = [];
        $chartOmzet = [];
        $chartHpp = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $chartLabels[] = $date->translatedFormat('D');

            $dailyTrx = Transaction::with('details.variant')
                ->whereDate('tanggal_waktu', $date->format('Y-m-d'))
                ->get();

            $dailyOmzet = $dailyTrx->sum('total_belanja');
            $dailyHpp = $dailyTrx->sum(function($trx) {
                return $trx->details->sum(function($detail) {
                    return ($detail->variant->harga_beli ?? 0) * $detail->qty;
                });
            });

            $chartOmzet[] = $dailyOmzet;
            $chartHpp[] = $dailyHpp;
        }

        $chartData = [
            'labels' => $chartLabels,
            'omzet' => $chartOmzet,
            'hpp' => $chartHpp
        ];

        // 4. TOP PRODUCTS & CABANG (DIPINDAHKAN KE SINI)
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

        return view('owner.dashboard', compact(
            'kasDiterima',
            'totalPiutang',
            'totalTransactions',
            'paymentMethods',
            'lowStocks',
            'recentTransactions',
            'chartData',
            'topProducts',
            'branchIncomes'
        ));
    }
}
