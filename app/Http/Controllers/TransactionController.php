<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userBranchId = $request->user()?->cabang_id;

        $transactions = DB::table('transactions')
            ->select('transactions.*')
            ->selectSub(
                DB::table('transaction_details')
                    ->selectRaw('count(*)')
                    ->whereColumn('transaction_details.transaksi_id', 'transactions.id'),
                'details_count'
            )
            ->when($userBranchId !== null, fn ($query) => $query->where('cabang_id', $userBranchId))
            ->when($userBranchId === null && $request->filled('cabang_id'), fn ($query) => $query->where('cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('metode_bayar'), fn ($query) => $query->where('metode_bayar', (string) $request->string('metode_bayar')))
            ->when($request->filled('tanggal_mulai'), fn ($query) => $query->whereDate('tanggal_waktu', '>=', $request->date('tanggal_mulai')))
            ->when($request->filled('tanggal_selesai'), fn ($query) => $query->whereDate('tanggal_waktu', '<=', $request->date('tanggal_selesai')))
            ->latest('tanggal_waktu')
            ->paginate($request->integer('per_page', 20));

        $transactions->setCollection(
            $this->hydrateTransactions($transactions->getCollection())
        );

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
            DB::table('branches')
                ->where('id', $branchId)
                ->whereNull('deleted_at')
                ->lockForUpdate()
                ->firstOrFail();

            $items = collect($validated['items'])->sortBy('varian_id')->values();
            $variants = DB::table('produk_varian')
                ->join('products', 'products.id', '=', 'produk_varian.produk_id')
                ->whereIn('produk_varian.id', $items->pluck('varian_id'))
                ->whereNull('produk_varian.deleted_at')
                ->whereNull('products.deleted_at')
                ->orderBy('produk_varian.id')
                ->select([
                    'produk_varian.*',
                    'products.id as product_id',
                    'products.kategori_id as product_kategori_id',
                    'products.nama_produk as product_nama_produk',
                    'products.deskripsi as product_deskripsi',
                    'products.tipe_stok as product_tipe_stok',
                    'products.image as product_image',
                    'products.created_at as product_created_at',
                    'products.updated_at as product_updated_at',
                    'products.deleted_at as product_deleted_at',
                ])
                ->get()
                ->map(function (object $variant): object {
                    $variant->product = (object) [
                        'id' => $variant->product_id,
                        'kategori_id' => $variant->product_kategori_id,
                        'nama_produk' => $variant->product_nama_produk,
                        'deskripsi' => $variant->product_deskripsi,
                        'tipe_stok' => $variant->product_tipe_stok,
                        'image' => $variant->product_image,
                        'created_at' => $variant->product_created_at,
                        'updated_at' => $variant->product_updated_at,
                        'deleted_at' => $variant->product_deleted_at,
                    ];

                    foreach ([
                        'product_id',
                        'product_kategori_id',
                        'product_nama_produk',
                        'product_deskripsi',
                        'product_tipe_stok',
                        'product_image',
                        'product_created_at',
                        'product_updated_at',
                        'product_deleted_at',
                    ] as $column) {
                        unset($variant->{$column});
                    }

                    return $variant;
                })
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
                    $stock = DB::table('stok_cabang')
                        ->where('cabang_id', $branchId)
                        ->where('varian_id', $variant->id)
                        ->lockForUpdate()
                        ->first();

                    if ($stock === null || $stock->stok < $quantity) {
                        throw ValidationException::withMessages([
                            'items' => "Stok SKU {$variant->sku} tidak mencukupi.",
                        ]);
                    }

                    $lockedStocks[$variant->id] = $stock;
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

            $now = now();
            $receiptNumber = $this->nextReceiptNumber($branchId);
            $transactionId = DB::table('transactions')->insertGetId([
                'kasir_id' => $user->getKey(),
                'member_id' => $validated['member_id'] ?? null,
                'nomor_nota' => $receiptNumber,
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
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($preparedItems as $preparedItem) {
                $variant = $preparedItem['variant'];
                unset($preparedItem['variant']);
                DB::table('transaction_details')->insert([
                    'transaksi_id' => $transactionId,
                    'varian_id' => $variant->id,
                    ...$preparedItem,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                if (! isset($lockedStocks[$variant->id])) {
                    continue;
                }

                $stock = $lockedStocks[$variant->id];
                DB::table('stok_cabang')
                    ->where('id', $stock->id)
                    ->decrement('stok', $preparedItem['qty'], ['updated_at' => $now]);
                DB::table('stock_histories')->insert([
                    'cabang_id' => $branchId,
                    'varian_id' => $variant->id,
                    'user_id' => $user->getKey(),
                    'transaksi_id' => $transactionId,
                    'jenis_riwayat' => 'penjualan',
                    'qty' => -$preparedItem['qty'],
                    'keterangan' => 'Penjualan '.$receiptNumber,
                    'waktu' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if ($validated['metode_bayar'] === 'cash_tempo') {
                DB::table('cash_tempo')->insert([
                    'transaksi_id' => $transactionId,
                    'tanggal_jatuh_tempo' => $validated['cash_tempo']['tanggal_jatuh_tempo'],
                    'jumlah_piutang' => $this->toDecimal($totalCents),
                    'sisa_piutang' => $this->toDecimal($totalCents - $paidCents),
                    'status_tempo' => 'belum_lunas',
                    'status_verifikasi' => 'menunggu',
                    'catatan_penagihan' => $validated['cash_tempo']['catatan_penagihan'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            if (isset($validated['shipment'])) {
                DB::table('shipments')->insert([
                    ...$validated['shipment'],
                    'transaksi_id' => $transactionId,
                    'biaya_kirim' => $validated['shipment']['biaya_kirim'] ?? 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            return $transactionId;
        }, attempts: 3);

        return response()->json($this->findTransaction($transaction), 201);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        return response()->json($this->findTransaction($transaction->getKey()));
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
            'items.*.varian_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('produk_varian', 'id')->whereNull('deleted_at'),
            ],
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
        $sequence = DB::table('transactions')
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

    private function findTransaction(int $id): object
    {
        $transaction = DB::table('transactions')->where('id', $id)->firstOrFail();

        return $this->hydrateTransactions(collect([$transaction]), withDetails: true)->first();
    }

    private function hydrateTransactions(
        Collection $transactions,
        bool $withDetails = false
    ): Collection {
        if ($transactions->isEmpty()) {
            return $transactions;
        }

        $cashiers = DB::table('users')
            ->whereIn('id', $transactions->pluck('kasir_id')->filter()->unique())
            ->get([
                'id',
                'role_id',
                'nama_lengkap',
                'username',
                'status_aktif',
                'cabang_id',
                'created_at',
                'updated_at',
            ])
            ->keyBy('id');
        $members = DB::table('members')
            ->whereIn('id', $transactions->pluck('member_id')->filter()->unique())
            ->whereNull('deleted_at')
            ->get()
            ->keyBy('id');
        $branches = DB::table('branches')
            ->whereIn('id', $transactions->pluck('cabang_id')->unique())
            ->whereNull('deleted_at')
            ->get()
            ->keyBy('id');
        $cashTempo = DB::table('cash_tempo')
            ->whereIn('transaksi_id', $transactions->pluck('id'))
            ->get()
            ->keyBy('transaksi_id');
        $details = collect();
        $shipments = collect();

        if ($withDetails) {
            $details = DB::table('transaction_details')
                ->whereIn('transaksi_id', $transactions->pluck('id'))
                ->orderBy('id')
                ->get();
            $variants = DB::table('produk_varian')
                ->whereIn('id', $details->pluck('varian_id')->unique())
                ->whereNull('deleted_at')
                ->get()
                ->keyBy('id');
            $products = DB::table('products')
                ->whereIn('id', $variants->pluck('produk_id')->unique())
                ->whereNull('deleted_at')
                ->get()
                ->keyBy('id');

            $variants->each(function (object $variant) use ($products) {
                $variant->product = $products->get($variant->produk_id);
            });
            $details = $details
                ->map(function (object $detail) use ($variants): object {
                    $detail->variant = $variants->get($detail->varian_id);

                    return $detail;
                })
                ->groupBy('transaksi_id');
            $shipments = DB::table('shipments')
                ->whereIn('transaksi_id', $transactions->pluck('id'))
                ->get()
                ->keyBy('transaksi_id');
        }

        return $transactions->map(function (object $transaction) use (
            $branches,
            $cashiers,
            $cashTempo,
            $details,
            $members,
            $shipments,
            $withDetails
        ): object {
            $transaction->cashier = $cashiers->get($transaction->kasir_id);
            $transaction->member = $members->get($transaction->member_id);
            $transaction->branch = $branches->get($transaction->cabang_id);
            $transaction->cash_tempo = $cashTempo->get($transaction->id);

            if ($withDetails) {
                $transaction->details = $details->get($transaction->id, collect())->values();
                $transaction->shipment = $shipments->get($transaction->id);
            }

            return $transaction;
        });
    }
}
