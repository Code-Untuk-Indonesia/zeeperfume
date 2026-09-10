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
            'month'         => ['nullable', 'integer', 'between:1,12'],
            'year'          => ['nullable', 'integer', 'between:2000,2100'],
            'start_date'    => ['nullable', 'date'],
            'end_date'      => [
                'nullable',
                'date',
                'required_with:start_date',
                'after_or_equal:start_date'
            ],
            'branch_id'      => ['nullable', 'integer', 'exists:branches,id'],
            'payment_method' => ['nullable', 'string', 'in:cash,qris,transfer,tempo,cash_tempo'],
        ]);

        $month = (int) ($validated['month'] ?? now()->month);
        $year = (int) ($validated['year'] ?? now()->year);

        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
            $monthName = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
        } else {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $monthName = $startDate->translatedFormat('F Y');
        }

        $branchId = $validated['branch_id'] ?? null;
        $paymentMethod = $validated['payment_method'] ?? null;

        $baseQuery = Transaction::query()
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->when($branchId, fn ($query) => $query->where('cabang_id', $branchId))
            ->when($paymentMethod, fn ($query) => $query->where('metode_bayar', $paymentMethod));

        // 1. Metrik Utama
        $totalIncome = (clone $baseQuery)->sum('total_belanja');
        $totalTrx = (clone $baseQuery)->count();
        $avgTransaction = $totalTrx > 0 ? $totalIncome / $totalTrx : 0;

        // 2. Grafik Pendapatan
        $diffDays = $startDate->diffInDays($endDate) + 1;
        if ($diffDays <= 31) {
            $chartResults = (clone $baseQuery)
                ->selectRaw('DATE(tanggal_waktu) as periode')
                ->selectRaw('SUM(total_belanja) as total')
                ->groupByRaw('DATE(tanggal_waktu)')
                ->orderBy('periode')
                ->pluck('total', 'periode');

            $labels = [];
            $dailyIncome = [];

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateKey = $date->format('Y-m-d');
                $labels[] = $date->format('d M');
                $dailyIncome[] = (float) ($chartResults[$dateKey] ?? 0);
            }
        } else {
            $chartResults = (clone $baseQuery)
                ->selectRaw("DATE_FORMAT(tanggal_waktu, '%Y-%m') as periode")
                ->selectRaw('SUM(total_belanja) as total')
                ->groupByRaw("DATE_FORMAT(tanggal_waktu, '%Y-%m')")
                ->orderBy('periode')
                ->get();

            $labels = $chartResults->map(fn ($item) => Carbon::createFromFormat('Y-m', $item->periode)->translatedFormat('M Y'))->values()->toArray();
            $dailyIncome = $chartResults->pluck('total')->map(fn ($value) => (float) $value)->toArray();
        }
        $chartData = ['labels' => $labels, 'income' => $dailyIncome];

        // 3. Distribusi Metode Pembayaran
        $paymentMethods = (clone $baseQuery)
            ->select('metode_bayar', DB::raw('COUNT(*) as total_transaksi'), DB::raw('SUM(total_belanja) as total_nominal'))
            ->groupBy('metode_bayar')
            ->orderByDesc('total_nominal')
            ->get();

        // 4. Pendapatan per Cabang
        $branchIncomes = Transaction::query()
            ->join('branches', 'branches.id', '=', 'transactions.cabang_id')
            ->whereBetween('transactions.tanggal_waktu', [$startDate, $endDate])
            ->when($branchId, fn ($query) => $query->where('transactions.cabang_id', $branchId))
            ->when($paymentMethod, fn ($query) => $query->where('transactions.metode_bayar', $paymentMethod))
            ->select('branches.id', 'branches.nama_cabang', DB::raw('COUNT(transactions.id) as total_trx'), DB::raw('COALESCE(SUM(transactions.total_belanja), 0) as total_income'))
            ->groupBy('branches.id', 'branches.nama_cabang')
            ->orderByDesc('total_income')
            ->get();

        // 5. Rekap Outlet
        $dailyOutletReports = DB::table('branches')
            ->leftJoin('transactions', function ($join) use ($startDate, $endDate, $paymentMethod) {
                $join->on('transactions.cabang_id', '=', 'branches.id')
                    ->whereBetween('transactions.tanggal_waktu', [$startDate, $endDate]);
                if ($paymentMethod) {
                    $join->where('transactions.metode_bayar', $paymentMethod);
                }
            })
            ->leftJoin('cash_tempo', 'cash_tempo.transaksi_id', '=', 'transactions.id')
            ->whereNull('branches.deleted_at')
            ->when($branchId, fn ($query) => $query->where('branches.id', $branchId))
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
            'total_transaksi'  => (int) $dailyOutletReports->sum('total_transaksi'),
            'total_pendapatan' => (float) $dailyOutletReports->sum('total_pendapatan'),
            'total_diterima'   => (float) $dailyOutletReports->sum('total_diterima'),
            'total_piutang'    => (float) $dailyOutletReports->sum('total_piutang'),
        ];

        // 6. Produk Terlaris
        $trxIds = (clone $baseQuery)->pluck('id');
        $topProducts = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('varian_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        $branches = Branch::query()->orderBy('nama_cabang')->get();

        // 7. Tabel Transaksi
        $incomeTransactions = (clone $baseQuery)
            ->with(['branch', 'cashier', 'member'])
            ->orderByDesc('tanggal_waktu')
            ->paginate(15)
            ->withQueryString();

        return view('owner.income.index', compact(
            'month', 'year', 'monthName', 'startDate', 'endDate',
            'branchId', 'paymentMethod', 'branches',
            'totalIncome', 'totalTrx', 'avgTransaction',
            'chartData', 'paymentMethods', 'branchIncomes', 'topProducts',
            'incomeTransactions', 'dailyOutletReports', 'dailySummary'
        ));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'month'          => ['nullable', 'integer', 'between:1,12'],
            'year'           => ['nullable', 'integer', 'between:2000,2100'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
            'branch_id'      => ['nullable', 'integer', 'exists:branches,id'],
            'payment_method' => ['nullable', 'string', 'in:cash,qris,transfer,tempo,cash_tempo'],
        ]);

        $month = (int) ($validated['month'] ?? now()->month);
        $year = (int) ($validated['year'] ?? now()->year);

        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
            $periodName = $startDate->format('d-m-Y') . '_sampai_' . $endDate->format('d-m-Y');
        } else {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $periodName = $startDate->format('F_Y');
        }

        $branchId = $validated['branch_id'] ?? null;
        $paymentMethod = $validated['payment_method'] ?? null;

        $query = Transaction::query()
            ->with(['branch', 'cashier', 'member'])
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->when($branchId, fn ($q) => $q->where('cabang_id', $branchId))
            ->when($paymentMethod, fn ($q) => $q->where('metode_bayar', $paymentMethod))
            ->orderBy('tanggal_waktu');

        $fileName = 'Laporan_Pendapatan_' . $periodName . '.csv';

        return response()->streamDownload(
            function () use ($query) {
                $file = fopen('php://output', 'w');

                // UTF-8 BOM agar Excel membaca karakter dengan benar
                fwrite($file, "\xEF\xBB\xBF");

                fputcsv($file, [
                    'Tanggal & Waktu', 'Nomor Invoice', 'Pelanggan', 'Cabang', 'Kasir', 'Metode Pembayaran', 'Total Belanja (Rp)'
                ]);

                $totalIncome = 0;
                $totalTransaction = 0;

                foreach ($query->cursor() as $trx) {
                    $amount = (float) $trx->total_belanja;

                    fputcsv($file, [
                        Carbon::parse($trx->tanggal_waktu)->format('d-m-Y H:i'),
                        $trx->nomor_nota,
                        $trx->member->nama ?? 'Umum',
                        $trx->branch->nama_cabang ?? 'Pusat',
                        $trx->cashier->nama_lengkap ?? 'Unknown',
                        strtoupper(str_replace('_', ' ', $trx->metode_bayar)),
                        $amount,
                    ]);

                    $totalIncome += $amount;
                    $totalTransaction++;
                }

                fputcsv($file, []);
                fputcsv($file, ['TOTAL TRANSAKSI', $totalTransaction]);
                fputcsv($file, ['TOTAL PENDAPATAN', '', '', '', '', '', $totalIncome]);

                fclose($file);
            },
            $fileName,
            [
                'Content-Type'  => 'text/csv; charset=UTF-8',
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
            ]
        );
    }
}
