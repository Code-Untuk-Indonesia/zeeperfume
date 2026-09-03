<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        return response()->json(Shipment::create($validated)->load('transaction'), 201);
    }

    public function show(Shipment $shipment): JsonResponse
    {
        return response()->json($shipment->load('transaction'));
    }

    public function update(Request $request, Shipment $shipment): JsonResponse
    {
        $validated = $request->validate($this->rules($shipment));
        $shipment->update($validated);

        return response()->json($shipment->fresh()->load('transaction'));
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
}
