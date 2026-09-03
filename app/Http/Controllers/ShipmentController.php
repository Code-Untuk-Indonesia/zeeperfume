<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $now = now();
        $id = DB::table('shipments')->insertGetId([
            ...$validated,
            'biaya_kirim' => $validated['biaya_kirim'] ?? 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json($this->findShipment($id), 201);
    }

    public function show(Shipment $shipment): JsonResponse
    {
        return response()->json($this->findShipment($shipment->getKey()));
    }

    public function update(Request $request, Shipment $shipment): JsonResponse
    {
        $validated = $request->validate($this->rules($shipment));
        DB::table('shipments')
            ->where('id', $shipment->getKey())
            ->update([...$validated, 'updated_at' => now()]);

        return response()->json($this->findShipment($shipment->getKey()));
    }

    private function rules(?Shipment $shipment = null): array
    {
        return [
            'transaksi_id' => [
                'required',
                'integer',
                Rule::unique('shipments', 'transaksi_id')->ignore($shipment),
                'exists:transactions,id',
            ],
            'no_resi' => ['nullable', 'string', 'max:100'],
            'biaya_kirim' => ['sometimes', 'numeric', 'min:0'],
            'jenis_pengiriman' => ['nullable', 'string', 'max:50'],
            'nama_penerima' => ['required', 'string', 'max:150'],
            'no_telepon_penerima' => ['nullable', 'string', 'max:30'],
            'alamat_tujuan' => ['required', 'string'],
            'catatan_kurir' => ['nullable', 'string'],
        ];
    }

    private function findShipment(int $id): object
    {
        $shipment = DB::table('shipments')->where('id', $id)->firstOrFail();
        $shipment->transaction = DB::table('transactions')
            ->where('id', $shipment->transaksi_id)
            ->first();

        return $shipment;
    }
}
