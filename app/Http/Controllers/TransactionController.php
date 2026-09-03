<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\ProductVariant;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userBranchId = $request->user()?->cabang_id;

        $transactions = Transaction::query()
            ->with(['cashier', 'member', 'branch', 'cashTempo'])
            ->withCount('details')
            ->when($userBranchId !== null, fn ($query) => $query->where('cabang_id', $userBranchId))
            ->when($userBranchId === null && $request->filled('cabang_id'), fn ($query) => $query->where('cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('metode_bayar'), fn ($query) => $query->where('metode_bayar', $request->string('metode_bayar')))
            ->when($request->filled('tanggal_mulai'), fn ($query) => $query->whereDate('tanggal_waktu', '>=', $request->date('tanggal_mulai')))
            ->when($request->filled('tanggal_selesai'), fn ($query) => $query->whereDate('tanggal_waktu', '<=', $request->date('tanggal_selesai')))
            ->latest('tanggal_waktu')
            ->paginate($request->integer('per_page', 20));

        return response()->json($transactions);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_if($user === null, 401);

        if (! $user->status_aktif) {
            return response()->json(['message' => 'Akun kasir tidak aktif.'], 403);
        }

        $validated = $request->validate($this->storeRules());
        $branchId = $user->cabang_id ?? ($validated['cabang_id'] ?? null);

        if ($branchId === null) {
            throw ValidationException::withMessages([
                'cabang_id' => 'Cabang wajib dipilih untuk user dengan akses global.',
            ]);
        }

        if ($user->cabang_id !== null && isset($validated['cabang_id']) && $validated['cabang_id'] !== $user->cabang_id) {
            return response()->json(['message' => 'Kasir tidak dapat membuat transaksi untuk cabang lain.'], 403);
        }

        $transaction = DB::transaction(function () use ($user, $validated, $branchId) {
            Branch::query()->whereKey($branchId)->lockForUpdate()->firstOrFail();

            $items = collect($validated['items'])->sortBy('varian_id')->values();
            $variants = ProductVariant::query()
                ->with('product')
                ->whereIn('id', $items->pluck('varian_id'))
                ->orderBy('id')
                ->get()
                ->keyBy('id');

            if ($variants->count() !== $items->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Satu atau lebih varian tidak tersedia.',
                ]);
            }

            $preparedItems = [];
            $lockedStocks = [];
            $subtotalCents = 0;

            foreach ($items as $item) {
                $variant = $variants->get($item['varian_id']);
                $quantity = (int) $item['qty'];

                if ($variant->product->tipe_stok === 'ada_stok') {
                    $stock = BranchStock::query()
                        ->where('cabang_id', $branchId)
                        ->where('varian_id', $variant->getKey())
                        ->lockForUpdate()
                        ->first();

                    if ($stock === null || $stock->stok < $quantity) {
                        throw ValidationException::withMessages([
                            'items' => "Stok SKU {$variant->sku} tidak mencukupi.",
                        ]);
                    }

                    $lockedStocks[$variant->getKey()] = $stock;
                }

                $priceCents = $this->toCents($variant->harga_jual);
                $percentageDiscountCents = $this->percentageOf(
                    $priceCents,
                    $item['diskon_persen'] ?? 0,
                );
                $fixedDiscountCents = $this->toCents($item['diskon_satuan'] ?? 0);
                $unitDiscountCents = $percentageDiscountCents + $fixedDiscountCents;

                if ($unitDiscountCents > $priceCents) {
                    throw ValidationException::withMessages([
                        'items' => "Diskon SKU {$variant->sku} melebihi harga satuan.",
                    ]);
                }

                $lineSubtotalCents = ($priceCents - $unitDiscountCents) * $quantity;
                $subtotalCents += $lineSubtotalCents;
                $preparedItems[] = [
                    'variant' => $variant,
                    'qty' => $quantity,
                    'harga_satuan' => $this->toDecimal($priceCents),
                    'diskon_persen' => $item['diskon_persen'] ?? 0,
                    'diskon_satuan' => $this->toDecimal($unitDiscountCents),
                    'catatan_diskon' => $item['catatan_diskon'] ?? null,
                    'subtotal' => $this->toDecimal($lineSubtotalCents),
                ];
            }

            $notePercentageDiscountCents = $this->percentageOf(
                $subtotalCents,
                $validated['diskon_persen'] ?? 0,
            );
            $noteFixedDiscountCents = $this->toCents($validated['diskon_nominal'] ?? 0);
            $noteDiscountCents = $notePercentageDiscountCents + $noteFixedDiscountCents;

            if ($noteDiscountCents > $subtotalCents) {
                throw ValidationException::withMessages([
                    'diskon_nominal' => 'Total diskon nota melebihi subtotal.',
                ]);
            }

            $totalCents = $subtotalCents - $noteDiscountCents;
            $paidCents = $this->toCents($validated['nominal_bayar'] ?? 0);
            $changeCents = $this->validatePayment($validated['metode_bayar'], $paidCents, $totalCents);

            $transaction = Transaction::create([
                'kasir_id' => $user->getKey(),
                'member_id' => $validated['member_id'] ?? null,
                'nomor_nota' => $this->nextReceiptNumber($branchId),
                'tanggal_waktu' => now(),
                'subtotal' => $this->toDecimal($subtotalCents),
                'diskon_persen' => $validated['diskon_persen'] ?? 0,
                'diskon_nominal' => $this->toDecimal($noteDiscountCents),
                'deskripsi_diskon' => $validated['deskripsi_diskon'] ?? null,
                'total_belanja' => $this->toDecimal($totalCents),
                'nominal_bayar' => $this->toDecimal($paidCents),
                'kembalian' => $this->toDecimal($changeCents),
                'metode_bayar' => $validated['metode_bayar'],
                'cabang_id' => $branchId,
            ]);

            foreach ($preparedItems as $preparedItem) {
                $variant = $preparedItem['variant'];
                unset($preparedItem['variant']);
                $transaction->details()->create([
                    'varian_id' => $variant->getKey(),
                    ...$preparedItem,
                ]);

                if (! isset($lockedStocks[$variant->getKey()])) {
                    continue;
                }

                $stock = $lockedStocks[$variant->getKey()];
                $stock->decrement('stok', $preparedItem['qty']);
                $transaction->stockHistories()->create([
                    'cabang_id' => $branchId,
                    'varian_id' => $variant->getKey(),
                    'user_id' => $user->getKey(),
                    'jenis_riwayat' => 'penjualan',
                    'qty' => -$preparedItem['qty'],
                    'keterangan' => 'Penjualan '.$transaction->nomor_nota,
                    'waktu' => now(),
                ]);
            }

            if ($validated['metode_bayar'] === 'cash_tempo') {
                $transaction->cashTempo()->create([
                    'tanggal_jatuh_tempo' => $validated['cash_tempo']['tanggal_jatuh_tempo'],
                    'jumlah_piutang' => $this->toDecimal($totalCents),
                    'sisa_piutang' => $this->toDecimal($totalCents - $paidCents),
                    'status_tempo' => 'belum_lunas',
                    'status_verifikasi' => 'menunggu',
                    'catatan_penagihan' => $validated['cash_tempo']['catatan_penagihan'] ?? null,
                ]);
            }

            if (isset($validated['shipment'])) {
                $transaction->shipment()->create($validated['shipment']);
            }

            return $transaction;
        }, attempts: 3);

        return response()->json($transaction->load($this->detailRelations()), 201);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        return response()->json($transaction->load($this->detailRelations()));
    }

    private function storeRules(): array
    {
        return [
            'cabang_id' => ['nullable', 'integer', Rule::exists('branches', 'id')->whereNull('deleted_at')],
            'member_id' => ['nullable', 'integer', Rule::exists('members', 'id')->whereNull('deleted_at')],
            'metode_bayar' => ['required', Rule::in(['cash', 'qris', 'transfer', 'cash_tempo'])],
            'diskon_persen' => ['sometimes', 'numeric', 'between:0,100'],
            'diskon_nominal' => ['sometimes', 'numeric', 'min:0'],
            'deskripsi_diskon' => ['nullable', 'string', 'max:255'],
            'nominal_bayar' => ['sometimes', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.varian_id' => ['required', 'integer', 'distinct'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.diskon_persen' => ['sometimes', 'numeric', 'between:0,100'],
            'items.*.diskon_satuan' => ['sometimes', 'numeric', 'min:0'],
            'items.*.catatan_diskon' => ['nullable', 'string', 'max:255'],
            'cash_tempo' => ['nullable', 'required_if:metode_bayar,cash_tempo', 'array'],
            'cash_tempo.tanggal_jatuh_tempo' => ['required_if:metode_bayar,cash_tempo', 'date', 'after_or_equal:today'],
            'cash_tempo.catatan_penagihan' => ['nullable', 'string'],
            'shipment' => ['nullable', 'array'],
            'shipment.no_resi' => ['nullable', 'string', 'max:100'],
            'shipment.biaya_kirim' => ['sometimes', 'numeric', 'min:0'],
            'shipment.jenis_pengiriman' => ['nullable', 'string', 'max:50'],
            'shipment.nama_penerima' => ['required_with:shipment', 'string', 'max:150'],
            'shipment.no_telepon_penerima' => ['nullable', 'string', 'max:30'],
            'shipment.alamat_tujuan' => ['required_with:shipment', 'string'],
            'shipment.catatan_kurir' => ['nullable', 'string'],
        ];
    }

    private function validatePayment(string $method, int $paidCents, int $totalCents): int
    {
        if ($method === 'cash_tempo') {
            if ($paidCents > $totalCents) {
                throw ValidationException::withMessages([
                    'nominal_bayar' => 'Pembayaran awal cash tempo tidak boleh melebihi total belanja.',
                ]);
            }

            return 0;
        }

        if ($paidCents < $totalCents) {
            throw ValidationException::withMessages([
                'nominal_bayar' => 'Nominal bayar kurang dari total belanja.',
            ]);
        }

        return $method === 'cash' ? $paidCents - $totalCents : 0;
    }

    private function nextReceiptNumber(int $branchId): string
    {
        $date = now()->format('Ymd');
        $sequence = Transaction::query()
            ->where('cabang_id', $branchId)
            ->whereDate('tanggal_waktu', now()->toDateString())
            ->count() + 1;

        return sprintf('ZP-%s-%s-%04d', $branchId, $date, $sequence);
    }

    private function percentageOf(int $amountCents, int|float|string $percentage): int
    {
        $basisPoints = (int) round((float) $percentage * 100);

        return (int) round($amountCents * $basisPoints / 10_000);
    }

    private function toCents(int|float|string $amount): int
    {
        return (int) round((float) $amount * 100);
    }

    private function toDecimal(int $cents): string
    {
        return number_format($cents / 100, 2, '.', '');
    }

    private function detailRelations(): array
    {
        return [
            'cashier',
            'member',
            'branch',
            'details.variant.product',
            'cashTempo',
            'shipment',
        ];
    }
}
