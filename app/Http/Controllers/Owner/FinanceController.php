<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tentukan Rentang Waktu (Default: Bulan Ini)
        $month = $request->input('month', Carbon::now()->format('m'));
        $year  = $request->input('year', Carbon::now()->format('Y'));
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Nama bulan untuk ditampilkan di UI
        $monthName = $startDate->translatedFormat('F Y');

        // 2. Ambil Semua Transaksi di Bulan Tersebut (Untuk Menghitung Omzet Total)
        // Catatan: Jika ada sistem 'belum lunas' pada kasbon, bisa ditambahkan filter where('status_tempo', 'lunas')
        $transactions = Transaction::whereBetween('tanggal_waktu', [$startDate, $endDate])->get();
        
        $totalOmzet = $transactions->sum('total_belanja');

        // 3. Hitung Total HPP (Modal) dari Detail Transaksi
        $details = TransactionDetail::with('variant')
            ->whereHas('transaction', function($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_waktu', [$startDate, $endDate]);
            })->get();

        $totalHpp = 0;
        foreach ($details as $item) {
            $modalSatuan = $item->variant->harga_modal ?? 0;
            $totalHpp += ($modalSatuan * $item->qty);
        }

        // 4. Hitung Laba Bersih & Margin
        $labaBersih = $totalOmzet - $totalHpp;
        $marginPercentage = $totalOmzet > 0 ? round(($labaBersih / $totalOmzet) * 100, 1) : 0;

        // 5. Analisis Per Cabang
        $branches = Branch::all();
        $branchReports = [];

        foreach ($branches as $branch) {
            // Omzet Cabang
            $omzetCabang = Transaction::where('cabang_id', $branch->id)
                ->whereBetween('tanggal_waktu', [$startDate, $endDate])
                ->sum('total_belanja');

            // HPP Cabang
            $hppCabang = 0;
            $detailCabang = TransactionDetail::with('variant')
                ->whereHas('transaction', function($q) use ($branch, $startDate, $endDate) {
                    $q->where('cabang_id', $branch->id)
                      ->whereBetween('tanggal_waktu', [$startDate, $endDate]);
                })->get();

            foreach ($detailCabang as $item) {
                $hppCabang += (($item->variant->harga_modal ?? 0) * $item->qty);
            }

            $labaCabang = $omzetCabang - $hppCabang;
            $marginCabang = $omzetCabang > 0 ? round(($labaCabang / $omzetCabang) * 100, 1) : 0;

            $branchReports[] = (object) [
                'nama_cabang' => $branch->nama_cabang,
                'omzet'       => $omzetCabang,
                'hpp'         => $hppCabang,
                'laba'        => $labaCabang,
                'margin'      => $marginCabang
            ];
        }

        return view('owner.finance.index', compact(
            'monthName', 'totalOmzet', 'totalHpp', 'labaBersih', 'marginPercentage', 'branchReports', 'month', 'year'
        ));
    }
}