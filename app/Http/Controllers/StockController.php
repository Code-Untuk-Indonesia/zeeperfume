<?php

namespace App\Http\Controllers;

use App\Models\BranchStock;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $stocks = BranchStock::query()
            ->with(['branch', 'variant.product.category'])
            ->when($request->filled('cabang_id'), fn ($query) => $query->where('cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('varian_id'), fn ($query) => $query->where('varian_id', $request->integer('varian_id')))
            ->when($request->boolean('stok_menipis'), fn ($query) => $query->whereColumn('stok', '<=', 'stok_minimum'))
            ->paginate($request->integer('per_page', 15));

        return response()->json($stocks);
    }

    public function adjust(Request $request): JsonResponse
    {
        abort_if($request->user() === null, 401);

        $validated = $request->validate([
            'cabang_id' => [
                'required',
                'integer',
                Rule::exists('branches', 'id')->whereNull('deleted_at'),
            ],
            'varian_id' => [
                'required',
                'integer',
                Rule::exists('product_variants', 'id')->whereNull('deleted_at'),
            ],
            'jenis_riwayat' => ['required', Rule::in(['masuk', 'keluar', 'rusak'])],
            'qty' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $stock = DB::transaction(function () use ($request, $validated) {
            $variant = ProductVariant::query()->with('product')->findOrFail($validated['varian_id']);

            if ($variant->product->tipe_stok !== 'ada_stok') {
                throw ValidationException::withMessages([
                    'varian_id' => 'Stok tidak dapat disesuaikan untuk produk tanpa stok.',
                ]);
            }

            $stock = BranchStock::query()
                ->where('cabang_id', $validated['cabang_id'])
                ->where('varian_id', $validated['varian_id'])
                ->lockForUpdate()
                ->first();

            if ($stock === null) {
                $stock = BranchStock::create([
                    'cabang_id' => $validated['cabang_id'],
                    'varian_id' => $validated['varian_id'],
                    'stok' => 0,
                    'stok_minimum' => 0,
                ]);
            }

            $delta = $validated['jenis_riwayat'] === 'masuk'
                ? $validated['qty']
                : -$validated['qty'];
            $newStock = $stock->stok + $delta;

            if ($newStock < 0) {
                throw ValidationException::withMessages([
                    'qty' => 'Stok tidak mencukupi untuk penyesuaian ini.',
                ]);
            }

            $stock->update(['stok' => $newStock]);
            $stock->variant->stockHistories()->create([
                'cabang_id' => $validated['cabang_id'],
                'user_id' => $request->user()->getKey(),
                'jenis_riwayat' => $validated['jenis_riwayat'],
                'qty' => $delta,
                'keterangan' => $validated['keterangan'] ?? null,
                'waktu' => now(),
            ]);

            return $stock;
        });

        return response()->json($stock->fresh()->load(['branch', 'variant.product']));
    }
}
