<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockHistoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $histories = StockHistory::query()
            ->with(['branch', 'variant.product', 'user', 'transaction'])
            ->when($request->filled('cabang_id'), fn ($query) => $query->where('cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('varian_id'), fn ($query) => $query->where('varian_id', $request->integer('varian_id')))
            ->when($request->filled('jenis_riwayat'), fn ($query) => $query->where('jenis_riwayat', $request->string('jenis_riwayat')))
            ->when($request->filled('tanggal_mulai'), fn ($query) => $query->whereDate('waktu', '>=', $request->date('tanggal_mulai')))
            ->when($request->filled('tanggal_selesai'), fn ($query) => $query->whereDate('waktu', '<=', $request->date('tanggal_selesai')))
            ->latest('waktu')
            ->paginate($request->integer('per_page', 25));

        return response()->json($histories);
    }

    public function show(StockHistory $stockHistory): JsonResponse
    {
        return response()->json($stockHistory->load(['branch', 'variant.product', 'user', 'transaction']));
    }
}
