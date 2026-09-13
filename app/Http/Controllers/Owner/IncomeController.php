<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Expense; // Pastikan model Expense dipanggil
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $validated = $this->validateFilter($request);

        $filterType = $validated['filter_type'] ?? 'month';
        $month = (int) ($validated['month'] ?? now()->month);
        $year = (int) ($validated['year'] ?? now()->year);

        if ($filterType === 'custom' && !empty($validated['start_date']) && !empty($validated['end_date'])) {
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
            $monthName = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
        } else {
            $filterType = 'month';
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $monthName = $startDate->translatedFormat('F Y');
        }

        $branchId = $validated['branch_id'] ?? null;
        $paymentMethod = $validated['payment_method'] ?? null;

        $branches = Branch::whereNull('deleted_at')->orderBy('nama_cabang')->get();

        /*
        |--------------------------------------------------------------------------
        | 1. BASE QUERY & FINANCIAL METRICS
        |--------------------------------------------------------------------------
        */
        $baseQuery = Transaction::query()
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->when($branchId, fn($query) => $query->where('cabang_id', $branchId))
            ->when($paymentMethod, fn($query) => $query->where('metode_bayar', $paymentMethod));

        $trxIds = (clone $baseQuery)->pluck('id');

        $totalIncome = (float) (clone $baseQuery)->sum('total_belanja');
        $totalTrx = (clone $baseQuery)->count();
        $avgTransaction = $totalTrx > 0 ? $totalIncome / $totalTrx : 0;

        // Hitung Total HPP (Modal Barang)
        $totalHpp = TransactionDetail::with('variant')
            ->whereIn('transaksi_id', $trxIds)
            ->get()
            ->sum(function ($detail) {
                return $detail->qty * ($detail->variant->harga_beli ?? 0);
            });

        // Hitung Total Pengeluaran (Biaya Operasional)
        $totalPengeluaran = Expense::whereBetween('tanggal_pengeluaran', [$startDate, $endDate])
            ->when($branchId, fn($q) => $q->where('cabang_id', $branchId))
            ->sum('nominal');

        // Kalkulasi Keuntungan
        $labaKotor = $totalIncome - $totalHpp;
        $totalBeban = $totalHpp + $totalPengeluaran;
        $labaBersih = $labaKotor - $totalPengeluaran;
        $marginPercentage = $totalIncome > 0 ? round(($labaBersih / $totalIncome) * 100, 1) : 0;

        /*
        |--------------------------------------------------------------------------
        | 2. CHART DATA
        |--------------------------------------------------------------------------
        */
        $diffDays = (int) $startDate->diffInDays($endDate) + 1;
        $labels = [];
        $dailyIncome = [];

        if ($diffDays <= 31) {
            $chartResults = (clone $baseQuery)
                ->selectRaw('DATE(tanggal_waktu) as periode, SUM(total_belanja) as total')
                ->groupByRaw('DATE(tanggal_waktu)')
                ->pluck('total', 'periode');

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $labels[] = $date->format('d M');
                $dailyIncome[] = (float) ($chartResults[$date->format('Y-m-d')] ?? 0);
            }
        } else {
            $chartResults = (clone $baseQuery)
                ->selectRaw("DATE_FORMAT(tanggal_waktu, '%Y-%m') as periode, SUM(total_belanja) as total")
                ->groupByRaw("DATE_FORMAT(tanggal_waktu, '%Y-%m')")
                ->get()
                ->pluck('total', 'periode');

            foreach ($chartResults as $ym => $total) {
                $labels[] = Carbon::createFromFormat('Y-m', $ym)->translatedFormat('M Y');
                $dailyIncome[] = (float) $total;
            }
        }
        $chartData = ['labels' => array_values($labels), 'income' => array_values($dailyIncome)];

        /*
        |--------------------------------------------------------------------------
        | 3. HPP DETAILS (PRODUK TERJUAL)
        |--------------------------------------------------------------------------
        */
        $hppDetails = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('varian_id')
            ->get()
            ->map(function ($item) {
                $item->total_modal = ($item->variant->harga_beli ?? 0) * $item->total_qty;
                return $item;
            })
            ->sortByDesc('total_modal')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | 4. OUTLET REPORTS (KEUNTUNGAN & PIUTANG PER CABANG)
        |--------------------------------------------------------------------------
        */
        $branchReports = Branch::whereNull('deleted_at')
            ->when($branchId, fn($q) => $q->where('id', $branchId))
            ->get()
            ->map(function ($branch) use ($startDate, $endDate, $paymentMethod) {
                $branchTrx = Transaction::with('details.variant')
                    ->where('cabang_id', $branch->id)
                    ->whereBetween('tanggal_waktu', [$startDate, $endDate])
                    ->when($paymentMethod, fn($q) => $q->where('metode_bayar', $paymentMethod))
                    ->get();

                $omzet = $branchTrx->sum('total_belanja');
                $hpp = $branchTrx->sum(function ($trx) {
                    return $trx->details->sum(fn($d) => $d->qty * ($d->variant->harga_beli ?? 0));
                });

                $pengeluaran = Expense::where('cabang_id', $branch->id)
                    ->whereBetween('tanggal_pengeluaran', [$startDate, $endDate])
                    ->sum('nominal');

                // Piutang
                $kasDiterima = 0;
                $piutang = 0;
                foreach ($branchTrx as $t) {
                    if (in_array($t->metode_bayar, ['tempo', 'cash_tempo'])) {
                        $sisaPiutang = DB::table('cash_tempo')->where('transaksi_id', $t->id)->value('sisa_piutang') ?? $t->total_belanja;
                        $piutang += $sisaPiutang;
                        $kasDiterima += ($t->total_belanja - $sisaPiutang);
                    } else {
                        $kasDiterima += $t->total_belanja;
                    }
                }

                return (object) [
                    'nama_cabang' => $branch->nama_cabang,
                    'total_transaksi' => $branchTrx->count(),
                    'total_pendapatan' => $omzet,
                    'laba_kotor' => $omzet - $hpp,
                    'pengeluaran' => $pengeluaran,
                    'laba_bersih' => ($omzet - $hpp) - $pengeluaran,
                    'kas_diterima' => $kasDiterima,
                    'piutang' => $piutang,
                ];
            });

        $dailySummary = [
            'total_pendapatan' => $branchReports->sum('total_pendapatan'),
            'total_diterima' => $branchReports->sum('kas_diterima'),
            'total_piutang' => $branchReports->sum('piutang')
        ];

        /*
        |--------------------------------------------------------------------------
        | 5. TRANSACTIONS PAGINATION
        |--------------------------------------------------------------------------
        */
        $incomeTransactions = (clone $baseQuery)
            ->with(['branch', 'cashier', 'member'])
            ->orderByDesc('tanggal_waktu')
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('owner.income.index', compact(
                    'filterType',
                    'month',
                    'year',
                    'monthName',
                    'startDate',
                    'endDate',
                    'branchId',
                    'paymentMethod',
                    'branches',
                    'totalIncome',
                    'totalTrx',
                    'avgTransaction',
                    'chartData',
                    'totalHpp',
                    'totalPengeluaran',
                    'labaKotor',
                    'totalBeban',
                    'labaBersih',
                    'marginPercentage',
                    'hppDetails',
                    'branchReports',
                    'dailySummary',
                    'incomeTransactions'
                ))->renderSections()['content']
            ]);
        }

        return view('owner.income.index', compact(
            'filterType',
            'month',
            'year',
            'monthName',
            'startDate',
            'endDate',
            'branchId',
            'paymentMethod',
            'branches',
            'totalIncome',
            'totalTrx',
            'avgTransaction',
            'chartData',
            'totalHpp',
            'totalPengeluaran',
            'labaKotor',
            'totalBeban',
            'labaBersih',
            'marginPercentage',
            'hppDetails',
            'branchReports',
            'dailySummary',
            'incomeTransactions'
        ));
    }

    private function validateFilter(Request $request): array
    {
        return $request->validate([
            'filter_type' => ['nullable', 'in:month,custom'],
            'month' => ['nullable', 'integer', 'between:1,12'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'payment_method' => ['nullable', 'string', 'in:cash,qris,transfer,tempo,cash_tempo']
        ]);
    }
}
