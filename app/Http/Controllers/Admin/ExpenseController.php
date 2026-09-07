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

        $expenses = Expense::with(['branch', 'user'])
            ->whereMonth('tanggal_pengeluaran', $month)
            ->whereYear('tanggal_pengeluaran', $year)
            ->orderBy('tanggal_pengeluaran', 'desc')
            ->paginate(15);

        $totalPengeluaran = Expense::whereMonth('tanggal_pengeluaran', $month)
            ->whereYear('tanggal_pengeluaran', $year)
            ->sum('nominal');

        $branches = Branch::all();

        return view('admin.expense.index', compact('expenses', 'branches', 'month', 'year', 'totalPengeluaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required',
            'kategori_pengeluaran' => 'required',
            'nama_pengeluaran' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'tanggal_pengeluaran' => 'required|date',
        ]);

        Expense::create([
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
            'cabang_id' => 'required',
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
