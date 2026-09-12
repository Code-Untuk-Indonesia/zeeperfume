@php
    $variantId = $variant->varian_id ?? $variant->id;
    $variantName = addslashes($variant->nama_varian);
@endphp

<div class="product-card {{ $isOutOfStock ? 'pointer-events-none cursor-not-allowed opacity-60 grayscale-[0.2]' : 'cursor-pointer hover:-translate-y-1 hover:border-[#CC9863]/30 hover:shadow-xl hover:shadow-[#CC9863]/10' }} group flex h-full flex-col rounded-3xl border border-gray-100 bg-white p-4 shadow-sm transition-all"
    data-name="{{ strtolower($variant->nama_varian) }}"
    data-category="{{ $product->kategori_id }}"
    data-stock="{{ $stock }}"
    data-stock-status="{{ $isOutOfStock ? 'out-of-stock' : 'available' }}"
    role="button"
    aria-disabled="{{ $isOutOfStock ? 'true' : 'false' }}"
    @if (!$isOutOfStock)
        onclick="@if ($isRefill) openRefillModal({variantId: '{{ $variantId }}', name: '{{ $variantName }}', mlPrice: {{ $price }}, stockMl: {{ $stock }}}) @else addPcsToCart({variantId: '{{ $variantId }}', name: '{{ $variantName }}', pcsPrice: {{ $price }}, stockPcs: {{ $stock }}}) @endif"
    @endif>

    {{-- Product Image / Icon --}}
    <div
        class="relative mb-4 flex h-32 w-full flex-col items-center justify-center overflow-hidden rounded-2xl sm:h-40 {{ $isRefill ? 'bg-gradient-to-br from-blue-50 to-blue-100/50 text-blue-500' : 'bg-gradient-to-br from-orange-50 to-orange-100/50 text-orange-500' }}">
        <img src="{{ asset('storage/' . $variant->image) }}" alt="Foto Produk">
        <div
            class="absolute right-2 top-2 rounded-lg bg-white/80 px-2 py-1 text-[10px] font-extrabold uppercase text-gray-600 shadow-sm backdrop-blur-sm">
            {{ $product->category->nama_kategori ?? 'Umum' }}
        </div>
        <div
            class="absolute left-2 top-2 rounded-lg px-2 py-1 text-[10px] font-extrabold uppercase shadow-sm {{ $isRefill ? 'bg-blue-500 text-white' : 'bg-[#CC9863] text-white' }}">
            {{ $isRefill ? 'REFILL' : 'PCS' }}
        </div>
        @if ($isOutOfStock)
            <span class="absolute inset-x-3 bottom-3 rounded-lg bg-gray-900/75 px-2 py-1 text-center text-[10px] font-extrabold uppercase tracking-wide text-white">
                Stok Habis
            </span>
        @endif
    </div>

    <div class="mt-auto flex flex-col gap-1">
        <h3 class="text-sm font-extrabold leading-snug text-gray-900 transition-colors group-hover:text-[#CC9863] line-clamp-2">
            {{ $variant->nama_varian }}
        </h3>
        <div class="mt-2 flex items-end justify-between">
            <div>
                <p class="mb-0.5 text-xs font-bold {{ $isOutOfStock ? 'text-red-500' : 'text-gray-400' }}">
                    Stok: {{ $stock }} {{ $isRefill ? 'ml' : 'pcs' }}
                </p>
                <p class="text-base font-extrabold text-gray-900">
                    Rp {{ number_format($price, 0, ',', '.') }}
                    @if ($isRefill)
                        <span class="text-xs font-semibold text-gray-400">/ ml</span>
                    @endif
                </p>
            </div>
            <div
                class="flex h-8 w-8 items-center justify-center rounded-full {{ $isOutOfStock ? 'bg-gray-100 text-gray-300' : 'bg-gray-50 text-gray-400 group-hover:bg-[#1C1D21] group-hover:text-white' }} transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
        </div>
    </div>
</div>
