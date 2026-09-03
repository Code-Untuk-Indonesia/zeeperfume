<?php

namespace App\Http\Controllers;

use App\Models\CashTempo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CashTempoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cashTempo = CashTempo::query()
            ->with(['transaction.cashier', 'transaction.member', 'transaction.branch'])
            ->when($request->filled('status_tempo'), fn ($query) => $query->where('status_tempo', $request->string('status_tempo')))
            ->when($request->filled('status_verifikasi'), fn ($query) => $query->where('status_verifikasi', $request->string('status_verifikasi')))
            ->when($request->filled('cabang_id'), function ($query) use ($request) {
                $query->whereHas('transaction', fn ($query) => $query->where('cabang_id', $request->integer('cabang_id')));
            })
            ->orderBy('tanggal_jatuh_tempo')
            ->paginate($request->integer('per_page', 15));

        return response()->json($cashTempo);
    }

    public function show(CashTempo $cashTempo): JsonResponse
    {
        return response()->json($cashTempo->load(['transaction.details.variant.product', 'transaction.member']));
    }

    public function verify(Request $request, CashTempo $cashTempo): JsonResponse
    {
        $validated = $request->validate([
            'status_verifikasi' => ['required', Rule::in(['disetujui', 'ditolak'])],
            'catatan_penagihan' => ['nullable', 'string'],
        ]);

        $cashTempo->update($validated);

        return response()->json($cashTempo->fresh());
    }

    public function markAsPaid(CashTempo $cashTempo): JsonResponse
    {
        $cashTempo->update([
            'sisa_piutang' => 0,
            'status_tempo' => 'lunas',
        ]);

        return response()->json($cashTempo->fresh());
    }
}
