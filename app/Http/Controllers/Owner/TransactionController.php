<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data yang sedang menunggu persetujuan Owner
        $pendingApprovals = Transaction::with(['requester'])
            ->whereIn('approval_status', ['pending_edit', 'pending_delete'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Ambil data transaksi normal untuk tabel bawah
        $transactions = Transaction::with(['cashier', 'branch', 'member', 'details.variant', 'cashTempo'])
            ->orderBy('tanggal_waktu', 'desc')
            ->paginate(10);
            
        $branches = Branch::all();

        return view('owner.transaction.index', compact('pendingApprovals', 'transactions', 'branches'));
    }

    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->approval_status === 'pending_delete') {
            // Logika penghapusan langsung dari Owner (jika diperlukan kembalikan stok di sini)
            $transaction->delete();
            return back()->with('success', 'Transaksi berhasil dihapus.');
        } elseif ($transaction->approval_status === 'pending_edit') {
            // Ubah status agar gembok terbuka untuk Admin
            $transaction->update(['approval_status' => 'approved_edit']);
            return back()->with('success', 'Izin Edit berhasil diberikan kepada Admin.');
        }

        return back();
    }

    public function reject($id)
    {
        $transaction = Transaction::findOrFail($id);
        // Kembalikan ke status normal (gembok terkunci)
        $transaction->update([
            'approval_status' => 'none',
            'approval_reason' => null
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}