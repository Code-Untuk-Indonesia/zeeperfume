<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $query = Expense::with(['branch', 'user']);

        // 1. FILTER: Rentang Tanggal (Prioritas jika diisi)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_pengeluaran', [$request->start_date, $request->end_date]);
        } else {
            // Default filter pakai bulan & tahun
            $query->whereMonth('tanggal_pengeluaran', $month)
                ->whereYear('tanggal_pengeluaran', $year);
        }

        // 2. FILTER: Pencarian (Nama Pengeluaran)
        if ($request->filled('search')) {
            $query->where('nama_pengeluaran', 'like', '%' . $request->search . '%');
        }

        // 3. FILTER: Berdasarkan Outlet / Cabang
        if ($request->filled('cabang_id') && $request->cabang_id !== 'all') {
            if ($request->cabang_id === 'general') {
                $query->whereNull('cabang_id'); // Menampilkan pengeluaran yang tidak di-assign ke cabang
            } else {
                $query->where('cabang_id', $request->cabang_id);
            }
        }

        // 4. JIKA TOMBOL EXPORT DIKLIK
        if ($request->has('export') && $request->export == '1') {
            $expensesExport = $query->orderBy('tanggal_pengeluaran', 'desc')->get();
            return $this->exportCsv($expensesExport);
        }

        // Total Pengeluaran Dinamis mengikuti Filter
        $totalPengeluaran = (clone $query)->sum('nominal');

        // Data untuk tabel dengan Pagination
        $expenses = $query->orderBy('tanggal_pengeluaran', 'desc')
            ->paginate(15)
            ->withQueryString();

        $branches = Branch::all();

        return view('admin.expense.index', compact('expenses', 'branches', 'month', 'year', 'totalPengeluaran'));
    }

    /**
     * Fungsi Internal untuk Export Data ke CSV
     */
    private function exportCsv($expenses)
    {
        $fileName = 'Laporan_Pengeluaran_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Kategori', 'Nama Pengeluaran', 'Lokasi (Cabang)', 'Diinput Oleh', 'Nominal (Rp)', 'Keterangan'];

        $callback = function () use ($expenses, $columns) {
            $file = fopen('php://output', 'w');

            // Tambahkan BOM agar file CSV terbaca rapi (tidak rusak karakter) di Microsoft Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns);

            foreach ($expenses as $exp) {
                // Jika cabang null, tulis "General / Semua Cabang"
                $lokasi = $exp->cabang_id ? ($exp->branch->nama_cabang ?? 'Pusat') : 'General / Semua Cabang';

                fputcsv($file, [
                    Carbon::parse($exp->tanggal_pengeluaran)->format('Y-m-d'),
                    $exp->kategori_pengeluaran,
                    $exp->nama_pengeluaran,
                    $lokasi,
                    $exp->user->nama_lengkap ?? 'Unknown',
                    $exp->nominal,
                    $exp->keterangan
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $request->validate([
            // Ubah cabang_id menjadi nullable agar bisa kosong jika tidak di-assign ke cabang manapun
            'cabang_id' => 'nullable|exists:branches,id',
            'kategori_pengeluaran' => 'required',
            'nama_pengeluaran' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'tanggal_pengeluaran' => 'required|date',
        ]);

        Expense::create([
            // Jika dikosongkan pada form, nilainya akan menjadi null (General/Pusat)
            'cabang_id' => $request->cabang_id,
            'user_id' => auth()->id(), // Admin yang sedang login
            'kategori_pengeluaran' => $request->kategori_pengeluaran,
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'nominal' => $request->nominal,
            'tanggal_pengeluaran' => $request->tanggal_pengeluaran,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Data pengeluaran berhasil dicatat!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cabang_id' => 'nullable|exists:branches,id',
            'kategori_pengeluaran' => 'required',
            'nama_pengeluaran' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'tanggal_pengeluaran' => 'required|date',
        ]);

        $expense = Expense::findOrFail($id);
        $expense->update($request->all());

        return back()->with('success', 'Data pengeluaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return back()->with('success', 'Data pengeluaran berhasil dihapus!');
    }
}
