<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data yang sedang menunggu persetujuan Owner
        $pendingApprovals = Transaction::with(['requester'])
            ->whereIn('approval_status', ['pending_edit', 'pending_delete'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // 2. Siapkan Query Dasar untuk Tabel Riwayat
        $query = Transaction::with(['cashier', 'branch', 'member', 'details.variant', 'cashTempo']);

        // --- FILTER: PENCARIAN (Invoice / Nama Pelanggan) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_nota', 'like', "%{$search}%")
                    ->orWhereHas('member', function ($qMember) use ($search) {
                        $qMember->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // --- FILTER: CABANG ---
        if ($request->filled('cabang') && $request->cabang !== 'all') {
            $query->where('cabang_id', $request->cabang);
        }

        // --- FILTER: METODE PEMBAYARAN ---
        if ($request->filled('metode') && $request->metode !== 'all') {
            $query->where('metode_bayar', $request->metode);
        }

        // --- FILTER: TANGGAL ---
        if ($request->filled('tanggal') && $request->tanggal !== 'all') {
            $now = Carbon::now();

            if ($request->tanggal === 'custom' && $request->filled('tanggal_spesifik')) {
                // Jika pilih tanggal spesifik dari kalender
                $query->whereDate('tanggal_waktu', $request->tanggal_spesifik);
            } else {
                // Jika pakai dropdown rentang waktu
                switch ($request->tanggal) {
                    case 'hari_ini':
                        $query->whereDate('tanggal_waktu', $now->toDateString());
                        break;
                    case 'kemarin':
                        $query->whereDate('tanggal_waktu', $now->subDay()->toDateString());
                        break;
                    case '7_hari':
                        $query->whereBetween('tanggal_waktu', [$now->subDays(7)->startOfDay(), Carbon::now()->endOfDay()]);
                        break;
                    case 'bulan_ini':
                        $query->whereMonth('tanggal_waktu', $now->month)->whereYear('tanggal_waktu', $now->year);
                        break;
                }
            }
        }

        // Eksekusi Query dengan Pagination (tambahkan withQueryString agar filter tidak hilang saat pindah page)
        $transactions = $query->orderBy('tanggal_waktu', 'desc')->paginate(10)->withQueryString();

        $branches = Branch::all();

        return view('owner.transaction.index', compact('pendingApprovals', 'transactions', 'branches'));
    }

    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->approval_status === 'pending_delete') {
            $transaction->delete();
            return back()->with('success', 'Transaksi berhasil dihapus.');
        } elseif ($transaction->approval_status === 'pending_edit') {
            $transaction->update(['approval_status' => 'approved_edit']);
            return back()->with('success', 'Izin Edit berhasil diberikan kepada Admin.');
        }

        return back();
    }

    public function reject($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'approval_status' => 'none',
            'approval_reason' => null
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
