<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Admin (Data Realtime)
     */
    public function index()
    {
        $today = Carbon::today();

        // 1. Rekap Harian (Semua Cabang)
        $transactionsToday = Transaction::whereDate('tanggal_waktu', $today);

        $totalTransaksi = (clone $transactionsToday)->count();
        $omzetHariIni   = (clone $transactionsToday)->sum('total_belanja');

        // Pesanan Online (berdasarkan relasi shipment)
        $pesananOnline = (clone $transactionsToday)->whereHas('shipment')->count();
        $perluCetakResi = (clone $transactionsToday)->whereHas('shipment', function ($q) {
            $q->whereNull('no_resi'); // Anggap pesanan yang resinya null = belum dicetak/diinput
        })->count();

        // 2. Peringatan Stok Menipis (Di bawah 5)
        $stokMenipis = BranchStock::with(['variant.product', 'branch'])
            ->where('stok', '<', 5)
            ->get();
        $jumlahStokMenipis = $stokMenipis->count();

        // 3. Omzet Per Cabang (Untuk Progress Bar Laporan Pendapatan)
        // Kita hitung pendapatan kotor per cabang hari ini
        $branches = Branch::with(['users' => function ($q) {
            // Ubah 'roles' menjadi 'role' dan 'name' menjadi 'nama_role'
            $q->whereHas('role', function ($r) {
                $r->where('nama_role', 'kasir');
            });
        }])->get();

        $laporanCabang = [];
        foreach ($branches as $branch) {
            $totalSetoran = Transaction::where('cabang_id', $branch->id)
                ->whereDate('tanggal_waktu', $today)
                ->sum('total_belanja');

            // Asumsi target harian = Rp 5.000.000 (Bisa disesuaikan nanti)
            $targetHarian = 5000000;
            $persentase = $totalSetoran > 0 ? min(100, round(($totalSetoran / $targetHarian) * 100)) : 0;

            // Ambil kasir yang terdaftar di cabang tersebut
            $kasirAktif = $branch->users->first() ? $branch->users->first()->nama_lengkap : 'System/Admin';

            $laporanCabang[] = [
                'id' => $branch->id,
                'nama' => $branch->nama_cabang,
                'kasir' => $kasirAktif,
                'setoran' => $totalSetoran,
                'persentase' => $persentase
            ];
        }

        // 4. Aktivitas Live (5 Transaksi Terakhir)
        $transaksiLive = Transaction::with(['cashier', 'branch', 'shipment'])
            ->orderBy('tanggal_waktu', 'desc')
            ->take(5)
            ->get();

        // Lempar data ke view admin/dashboard.blade.php
        return view('admin.dashboard', compact(
            'today',
            'totalTransaksi',
            'omzetHariIni',
            'pesananOnline',
            'perluCetakResi',
            'stokMenipis',
            'jumlahStokMenipis',
            'laporanCabang',
            'transaksiLive'
        ));
    }
}
