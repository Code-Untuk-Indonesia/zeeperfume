<?php

namespace App\Http\Controllers;

use App\Models\CashTempo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CashTempoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cashTempo = DB::table('cash_tempo')
            ->when($request->filled('status_tempo'), fn ($query) => $query->where('status_tempo', (string) $request->string('status_tempo')))
            ->when($request->filled('status_verifikasi'), fn ($query) => $query->where('status_verifikasi', (string) $request->string('status_verifikasi')))
            ->when($request->filled('cabang_id'), function ($query) use ($request) {
                $query->whereExists(function ($query) use ($request) {
                    $query->selectRaw('1')
                        ->from('transactions')
                        ->whereColumn('transactions.id', 'cash_tempo.transaksi_id')
                        ->where('transactions.cabang_id', $request->integer('cabang_id'));
                });
            })
            ->orderBy('tanggal_jatuh_tempo')
            ->paginate($request->integer('per_page', 15));

        $cashTempo->setCollection(
            $cashTempo->getCollection()
                ->map(fn (object $item): object => $this->hydrateCashTempo($item))
        );

        return response()->json($cashTempo);
    }

    public function show(CashTempo $cashTempo): JsonResponse
    {
        $item = DB::table('cash_tempo')
            ->where('id', $cashTempo->getKey())
            ->firstOrFail();

        return response()->json($this->hydrateCashTempo($item, withDetails: true));
    }

    public function verify(Request $request, CashTempo $cashTempo): JsonResponse
    {
        $validated = $request->validate([
            'status_verifikasi' => ['required', Rule::in(['disetujui', 'ditolak'])],
            'catatan_penagihan' => ['nullable', 'string'],
        ]);

        DB::table('cash_tempo')
            ->where('id', $cashTempo->getKey())
            ->update([...$validated, 'updated_at' => now()]);

        return response()->json(
            DB::table('cash_tempo')->where('id', $cashTempo->getKey())->firstOrFail()
        );
    }

    public function markAsPaid(CashTempo $cashTempo): JsonResponse
    {
        DB::table('cash_tempo')
            ->where('id', $cashTempo->getKey())
            ->update([
                'sisa_piutang' => 0,
                'status_tempo' => 'lunas',
                'updated_at' => now(),
            ]);

        return response()->json(
            DB::table('cash_tempo')->where('id', $cashTempo->getKey())->firstOrFail()
        );
    }

    private function hydrateCashTempo(object $cashTempo, bool $withDetails = false): object
    {
        $transaction = DB::table('transactions')
            ->where('id', $cashTempo->transaksi_id)
            ->first();

        if ($transaction === null) {
            $cashTempo->transaction = null;

            return $cashTempo;
        }

        $transaction->cashier = DB::table('users')
            ->where('id', $transaction->kasir_id)
            ->first([
                'id',
                'role_id',
                'nama_lengkap',
                'username',
                'status_aktif',
                'cabang_id',
                'created_at',
                'updated_at',
            ]);
        $transaction->member = $transaction->member_id === null
            ? null
            : DB::table('members')
                ->where('id', $transaction->member_id)
                ->whereNull('deleted_at')
                ->first();
        $transaction->branch = DB::table('branches')
            ->where('id', $transaction->cabang_id)
            ->whereNull('deleted_at')
            ->first();

        if ($withDetails) {
            $transaction->details = DB::table('transaction_details')
                ->where('transaksi_id', $transaction->id)
                ->orderBy('id')
                ->get()
                ->map(function (object $detail): object {
                    $detail->variant = DB::table('produk_varian')
                        ->where('id', $detail->varian_id)
                        ->whereNull('deleted_at')
                        ->first();

                    if ($detail->variant !== null) {
                        $detail->variant->product = DB::table('products')
                            ->where('id', $detail->variant->produk_id)
                            ->whereNull('deleted_at')
                            ->first();
                    }

                    return $detail;
                });
        }

        $cashTempo->transaction = $transaction;

        return $cashTempo;
    }
}
