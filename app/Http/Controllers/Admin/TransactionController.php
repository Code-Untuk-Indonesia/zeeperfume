<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\CashTempo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil relasi yang dibutuhkan
        $query = Transaction::with(['cashier', 'branch', 'member', 'details.variant', 'cashTempo']);
        
        // 2. Filter Pencarian (Invoice / Nama Member)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nomor_nota', 'like', "%{$search}%")
                  ->orWhereHas('member', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
        }

        // 3. Filter Outlet
        if ($request->filled('cabang') && $request->cabang !== 'all') {
            $query->where('cabang_id', $request->cabang);
        }

        // 4. Filter Metode Pembayaran
        if ($request->filled('metode') && $request->metode !== 'all') {
            $query->where('metode_bayar', $request->metode);
        }

        // 5. Filter Tanggal
        if ($request->filled('tanggal') && $request->tanggal !== 'all') {
            switch ($request->tanggal) {
                case 'hari_ini':
                    $query->whereDate('tanggal_waktu', Carbon::today());
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal_waktu', Carbon::yesterday());
                    break;
                case '7_hari':
                    $query->where('tanggal_waktu', '>=', Carbon::now()->subDays(7));
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal_waktu', Carbon::now()->month)
                          ->whereYear('tanggal_waktu', Carbon::now()->year);
                    break;
            }
        }

        $transactions = $query->orderBy('tanggal_waktu', 'desc')->paginate(10)->withQueryString();
        $branches = Branch::all();

        return view('admin.transaction.index', compact('transactions', 'branches'));
    }

    /**
     * Memverifikasi PIN/Password Owner via AJAX (Jika masih digunakan untuk fitur lain)
     */
    public function verifyOwner(Request $request)
    {
        $request->validate(['password' => 'required']);

        $owner = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'owner');
        })->first();

        if ($owner && Hash::check($request->password, $owner->password)) {
            session(['owner_authorized' => true]);
            session(['owner_authorized_until' => now()->addMinutes(15)]);
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Password Owner salah! Akses ditolak.']);
    }

    /**
     * Menyimpan alasan pengajuan Edit/Hapus untuk di-review Owner
     */
    public function requestApproval(Request $request)
    {
        $request->validate([
            'trx_id' => 'required|exists:transactions,id',
            'action' => 'required|in:edit,delete',
            'reason' => 'required|string|max:500'
        ]);

        $transaction = Transaction::findOrFail($request->trx_id);
        
        $transaction->update([
            'approval_status' => 'pending_' . $request->action,
            'approval_reason' => $request->reason,
            'approval_by'     => auth()->id() ?? 1 
        ]);

        return response()->json(['success' => true, 'message' => 'Pengajuan berhasil dikirim ke Owner.']);
    }

    /**
     * Menampilkan Halaman Form Edit Transaksi 
     */
    public function edit($id)
    {
        $transaction = Transaction::with(['details.variant.product', 'member', 'branch', 'cashTempo'])->findOrFail($id);

        // Keamanan Lapis 2: Tolak jika status bukan 'approved_edit'
        if ($transaction->approval_status !== 'approved_edit') {
            return redirect()->route('admin.transaction.index')
                ->with('error', 'Akses ditolak! Transaksi ini belum mendapatkan izin edit dari Owner.');
        }

        // Ambil data referensi untuk form edit
        $members = Member::all();
        $branches = Branch::all();

        // Pastikan Anda sudah membuat view ini (resources/views/admin/transaction/edit.blade.php)
        return view('admin.transaction.edit', compact('transaction', 'members', 'branches'));
    }

    /**
     * Menyimpan Perubahan Data Transaksi
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        // Keamanan Lapis 3: Tolak jika ada Bypass POST
        if ($transaction->approval_status !== 'approved_edit') {
            return redirect()->route('admin.transaction.index')
                ->with('error', 'Akses ditolak! Sesi edit tidak valid.');
        }

        $request->validate([
            'metode_bayar'  => 'required|string',
            'nominal_bayar' => 'required|numeric|min:0',
            // Jika ada validasi lain (misal tanggal, kasir), bisa ditambahkan di sini
        ]);

        DB::beginTransaction();
        try {
            // Hitung ulang kembalian
            $kembalian = max(0, $request->nominal_bayar - $transaction->total_belanja);

            // 1. Update Data Transaksi Induk
            $transaction->update([
                'member_id'       => $request->member_id, // Bisa null
                'metode_bayar'    => $request->metode_bayar,
                'nominal_bayar'   => $request->nominal_bayar,
                'kembalian'       => $kembalian,
                
                // PENTING: Kunci kembali transaksi setelah berhasil diedit
                'approval_status' => 'none',
                'approval_reason' => null,
                'approval_by'     => null,
            ]);

            // 2. Logika Khusus Jika Metode Berubah Menjadi/Dari Tempo (Kasbon)
            if ($request->metode_bayar === 'tempo' || $request->metode_bayar === 'cash_tempo') {
                $sisaPiutang = max(0, $transaction->total_belanja - $request->nominal_bayar);
                
                CashTempo::updateOrCreate(
                    ['transaksi_id' => $transaction->id],
                    [
                        'total_hutang'        => $transaction->total_belanja,
                        'jumlah_bayar'        => $request->nominal_bayar,
                        'sisa_piutang'        => $sisaPiutang,
                        'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo ?? Carbon::now()->addDays(30),
                        'status'              => $sisaPiutang > 0 ? 'belum_lunas' : 'lunas'
                    ]
                );
            } else {
                // Jika metode diubah jadi lunas (cash/qris), hapus catatan hutang (jika ada)
                if ($transaction->cashTempo) {
                    $transaction->cashTempo()->delete();
                }
            }

            // Catatan: Jika form edit Anda mengizinkan tambah/kurang produk, 
            // logika update detail & pengembalian stok harus ditambahkan di sini.

            DB::commit();
            return redirect()->route('admin.transaction.index')
                             ->with('success', 'Data Transaksi berhasil diperbarui. Akses edit telah dikunci kembali.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }
}