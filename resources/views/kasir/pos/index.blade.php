@extends('template.kasir')
@section('title', 'Point of Sales (POS)')

@section('content')

    <div class="flex flex-col lg:flex-row h-screen w-full bg-[#FAFAFA] overflow-hidden font-sans">

        {{-- ========================================================= --}}
        {{-- LEFT : PRODUCT AREA --}}
        {{-- ========================================================= --}}
        <section class="flex-1 flex flex-col h-full min-w-0 relative">

            {{-- HEADER & SEARCH --}}
            <div class="px-4 sm:px-6 pt-5 pb-2 bg-white border-b border-gray-100 z-10 shadow-sm shrink-0">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Transaksi POS</h1>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">Pilih produk untuk ditambahkan ke keranjang.</p>
                    </div>

                    {{-- Search Input --}}
                    <div class="relative w-full md:w-80 lg:w-96 shrink-0">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchProduct" autofocus
                            class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-[#CC9863] focus:ring-4 focus:ring-[#CC9863]/10 text-sm font-semibold text-gray-900 transition-all placeholder-gray-400"
                            placeholder="Cari parfum atau varian...">
                    </div>
                </div>

                {{-- CATEGORY FILTER --}}
                <div class="flex gap-2.5 overflow-x-auto pb-3 scrollbar-hide snap-x">
                    <button type="button" onclick="filterCategory('all')" id="cat-all"
                        class="cat-filter snap-start px-5 py-2.5 bg-[#1C1D21] text-white rounded-xl text-sm font-bold whitespace-nowrap shadow-md transition-all transform hover:scale-105">
                        Semua Produk
                    </button>
                    @if (isset($categories))
                        @foreach ($categories as $category)
                            <button type="button" onclick="filterCategory('{{ $category->id }}')"
                                id="cat-{{ $category->id }}"
                                class="cat-filter snap-start px-5 py-2.5 bg-gray-50 border border-gray-100 text-gray-600 rounded-xl text-sm font-bold whitespace-nowrap transition-all transform hover:scale-105 hover:bg-gray-100 hover:text-gray-900">
                                {{ $category->nama_kategori }}
                            </button>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- PRODUCT GRID --}}
            <div class="flex-1 overflow-y-auto p-4 sm:px-6 pb-32 lg:pb-6">
                <div id="productGrid"
                    class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 lg:gap-5">
                    @php
                        $availableCards = collect();
                        $outOfStockCards = collect();

                        foreach ($products ?? [] as $product) {
                            foreach ($product->variants as $variant) {
                                $stock = (int) ($variant->branchStocks->first()->stok ?? 0);
                                $card = [
                                    'product' => $product,
                                    'variant' => $variant,
                                    'stock' => $stock,
                                    'isRefill' => strtolower((string) $variant->satuan) === 'ml',
                                    'price' => $variant->harga_jual,
                                    'isOutOfStock' => $stock <= 0,
                                ];

                                if ($card['isOutOfStock']) {
                                    $outOfStockCards->push($card);
                                } else {
                                    $availableCards->push($card);
                                }
                            }
                        }
                    @endphp

                    @foreach ($availableCards as $card)
                        @include('kasir.pos.partials.product-card', $card)
                    @endforeach

                    @if ($outOfStockCards->isNotEmpty())
                        <div id="outOfStockHeading" class="out-of-stock-heading col-span-full mt-2 border-t border-dashed border-gray-200 pt-4 text-xs font-extrabold uppercase tracking-wider text-gray-400">
                            Stok Habis
                        </div>
                        @foreach ($outOfStockCards as $card)
                            @include('kasir.pos.partials.product-card', $card)
                        @endforeach
                    @endif
                </div>
            </div>
        </section>

        {{-- ========================================================= --}}
        {{-- RIGHT : CART & CHECKOUT AREA --}}
        {{-- ========================================================= --}}

        {{-- Overlay Transparan Mobile --}}
        <div id="cart-overlay" onclick="toggleMobileCart()"
            class="fixed inset-0 bg-black/40 z-[55] hidden lg:hidden backdrop-blur-sm transition-opacity opacity-0"></div>

        <aside id="cart-sidebar"
            class="fixed lg:static inset-y-0 right-0 z-[60] w-full md:w-[420px] lg:w-[400px] xl:w-[420px] bg-white border-l border-gray-100 flex flex-col shadow-2xl lg:shadow-none transform translate-x-full lg:translate-x-0 transition-transform duration-300 ease-out">

            {{-- Header Sidebar --}}
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-white shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-gray-900">Pesanan Saat Ini</h2>
                        <p class="text-xs font-semibold text-gray-500" id="cart-count-subtitle">0 Item</p>
                    </div>
                </div>
                <button type="button" onclick="toggleMobileCart()"
                    class="lg:hidden w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-500 rounded-xl hover:bg-red-50 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            {{-- MEMBER & REWARD SECTION --}}
            <div class="p-5 border-b border-gray-100 bg-gray-50/30 shrink-0">
                <div class="flex justify-between items-end mb-2">
                    <label class="text-xs font-extrabold text-gray-700 uppercase tracking-wide">Data Pelanggan</label>
                    <a href="{{ route('kasir.member.create') }}" target="_blank"
                        class="text-xs font-bold text-[#CC9863] hover:text-[#B58555] flex items-center gap-1">
                        + Daftar Member
                    </a>
                </div>

                {{-- Form Pencarian Member --}}
                <div id="member-search-area" class="flex gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="number" id="member-phone"
                            class="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl text-sm font-semibold focus:outline-none focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/10 transition-all bg-white"
                            placeholder="Cari No. HP (Opsional)">
                    </div>
                    <button type="button" onclick="checkMember(this)"
                        class="px-5 bg-[#1C1D21] text-white rounded-xl text-sm font-bold hover:bg-black transition-colors shadow-sm">
                        Cari
                    </button>
                </div>

                {{-- Member Info Card --}}
                <div id="member-info"
                    class="hidden mt-2 p-4 bg-gradient-to-br from-green-50 to-green-100/50 border border-green-200 rounded-2xl relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 opacity-10 pointer-events-none">
                        <svg class="w-24 h-24 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p id="member-name" class="text-sm font-extrabold text-green-900">-</p>
                            <p id="member-points" class="text-xs font-bold text-green-700 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2l2.5 5.5L18 8l-4 4 1 6-5-3-5 3 1-6-4-4 5.5-.5z" />
                                </svg>
                                <span>Poin Tersedia: <span id="member-points-val">0</span></span>
                            </p>
                        </div>
                        <button type="button" onclick="removeMember()"
                            class="px-3 py-1 bg-white text-red-500 rounded-lg text-[10px] uppercase tracking-wider font-bold border border-red-100 hover:bg-red-50 transition-colors shadow-sm">
                            Batal
                        </button>
                    </div>
                </div>

                {{-- DISKON GLOBAL AREA --}}
                <div class="mt-3 pt-3 border-t border-gray-200/80 space-y-2">
                    <select id="discount-type-select" onchange="toggleDiscountInputs()"
                        class="w-full bg-white border border-gray-200 rounded-xl p-2.5 text-xs font-bold focus:outline-none focus:border-[#CC9863] text-gray-700">
                        <option value="none">Tanpa Diskon Tambahan (Global)</option>
                        <option value="percent">Beri Diskon Persen (%)</option>
                        <option value="manual">Potongan Nominal (Rp)</option>
                        <option value="points" id="opt-points" class="hidden font-bold text-green-600">Tukar Poin Member
                        </option>
                    </select>

                    <div id="discount-input-area" class="hidden grid grid-cols-2 gap-2">
                        <input type="number" id="discount-val-input" oninput="calculateTotal()"
                            class="col-span-2 border border-gray-200 bg-white rounded-xl p-2.5 text-xs font-bold focus:outline-none focus:border-[#CC9863] placeholder-gray-400"
                            placeholder="Masukkan Angka...">
                        <p id="point-conversion-helper"
                            class="col-span-2 text-[10px] text-green-600 font-bold hidden italic mt-1">* 1 Poin memotong Rp
                            1.000</p>
                    </div>
                </div>
            </div>

            {{-- CART LIST --}}
            <div id="cart-container" class="flex-1 overflow-y-auto px-5 py-2 bg-white">
                <!-- Data Keranjang (Dibuat lewat JS) -->
            </div>

            {{-- PAYMENT SUMMARY --}}
            <div class="shrink-0 bg-white border-t border-gray-100 p-5 z-20">
                <div class="space-y-2.5 mb-5">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-semibold">Subtotal</span>
                        <span id="subtotal-val" class="font-bold text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-semibold">Diskon Produk</span>
                        <span id="item-discount-val" class="font-bold text-orange-500">- Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-semibold">Diskon Tambahan</span>
                        <span id="discount-amount-display" class="font-bold text-red-500">- Rp 0</span>
                    </div>
                    <div class="h-px bg-gray-100 w-full my-2"></div>
                    <div class="flex justify-between items-end">
                        <span class="font-bold text-gray-800 text-sm">Total Tagihan</span>
                        <span id="total-val" class="text-3xl font-black text-[#CC9863]">Rp 0</span>
                    </div>
                </div>
                <button type="button" onclick="openPaymentModal()"
                    class="w-full py-4 bg-[#CC9863] text-white rounded-2xl font-bold text-base hover:bg-[#B58555] transform transition-all shadow-lg shadow-[#CC9863]/25 active:scale-[0.98]">
                    Bayar Sekarang
                </button>
            </div>
        </aside>

        {{-- Mobile Bottom Bar --}}
        <div
            class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 pb-safe z-40 shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
            <div class="flex justify-between items-center gap-4">
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-gray-500" id="mobile-cart-item-count">Keranjang Kosong</span>
                    <span class="text-xl font-black text-gray-900" id="mobile-cart-total">Rp 0</span>
                </div>
                <button type="button" onclick="toggleMobileCart()"
                    class="flex-1 bg-[#1C1D21] text-white py-3.5 px-4 rounded-xl font-bold text-sm shadow-md flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Lihat Keranjang
                </button>
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- REFILL MODAL --}}
    {{-- ========================================================= --}}
    <div id="refillModal"
        class="hidden fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm items-end sm:items-center justify-center p-0 sm:p-4 transition-opacity">
        <div
            class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden animate-[slideUp_0.3s_ease-out]">
            <div class="p-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/50">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-blue-500 tracking-wider mb-1">Input Refill (ml)</p>
                    <h3 id="refillProductName" class="text-lg font-extrabold text-gray-900 leading-tight">-</h3>
                    <p id="refillPriceText" class="text-sm font-semibold text-gray-500 mt-1">-</p>
                </div>
                <button type="button" onclick="closeRefillModal()"
                    class="w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500 font-bold hover:bg-red-50 hover:text-red-500 hover:border-red-200 flex items-center justify-center transition-colors">×</button>
            </div>

            <div class="p-6">
                <div
                    class="flex items-center justify-between border-2 border-gray-100 rounded-2xl overflow-hidden bg-white mb-4 shadow-sm focus-within:border-[#CC9863] transition-colors">
                    <button type="button" onclick="changeRefillMl(-5)"
                        class="w-16 h-16 text-2xl font-light text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors">−</button>
                    <div class="flex-1 relative border-x border-gray-100 h-16 flex items-center">
                        <input type="number" id="refillMl" value="10" min="1" step="1"
                            oninput="calculateRefill()"
                            class="w-full bg-transparent text-center text-3xl font-black text-gray-900 focus:outline-none">
                        <span class="absolute right-4 text-xs font-bold text-gray-400">ML</span>
                    </div>
                    <button type="button" onclick="changeRefillMl(5)"
                        class="w-16 h-16 text-2xl font-light text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-colors">+</button>
                </div>

                <div class="grid grid-cols-4 gap-2 mb-6">
                    <button type="button" onclick="setRefillMl(10)"
                        class="py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:bg-[#CC9863] hover:text-white hover:border-[#CC9863] transition-colors shadow-sm">10
                        ml</button>
                    <button type="button" onclick="setRefillMl(20)"
                        class="py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:bg-[#CC9863] hover:text-white hover:border-[#CC9863] transition-colors shadow-sm">20
                        ml</button>
                    <button type="button" onclick="setRefillMl(30)"
                        class="py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:bg-[#CC9863] hover:text-white hover:border-[#CC9863] transition-colors shadow-sm">30
                        ml</button>
                    <button type="button" onclick="setRefillMl(50)"
                        class="py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:bg-[#CC9863] hover:text-white hover:border-[#CC9863] transition-colors shadow-sm">50
                        ml</button>
                </div>

                <div class="p-4 rounded-2xl bg-[#F6F5F2] border border-gray-200">
                    <div class="flex justify-between items-center text-sm mb-2">
                        <span class="font-semibold text-gray-500">Harga Satuan</span>
                        <span id="refillUnitPrice" class="font-bold text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-end border-t border-gray-200 pt-2 mt-2">
                        <span class="font-bold text-gray-800">Subtotal</span>
                        <span id="refillTotal" class="text-2xl font-black text-[#CC9863]">Rp 0</span>
                    </div>
                </div>

                <button type="button" onclick="addRefillToCart()"
                    class="mt-6 w-full py-4 bg-[#1C1D21] text-white rounded-2xl font-bold text-base hover:bg-black transform transition-all active:scale-[0.98] shadow-lg shadow-black/20 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Simpan ke Keranjang
                </button>
            </div>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- PAYMENT CHECKOUT MODAL --}}
    {{-- ========================================================= --}}
    <div id="paymentModal"
        class="hidden fixed inset-0 z-[110] bg-black/60 backdrop-blur-sm items-center justify-center p-4">
        <div
            class="bg-white w-full sm:max-w-md rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col animate-[scaleIn_0.2s_ease-out]">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50 shrink-0">
                <div>
                    <p class="text-[10px] uppercase font-extrabold text-gray-500 tracking-wider">Konfirmasi</p>
                    <h3 class="text-xl font-black text-gray-900 mt-0.5">Selesaikan Pembayaran</h3>
                </div>
                <button type="button" onclick="closePaymentModal()"
                    class="w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-500 font-bold hover:bg-red-50 hover:text-red-500 hover:border-red-200 flex items-center justify-center transition-colors">×</button>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                <div
                    class="flex justify-between items-end mb-6 bg-orange-50 border border-[#CC9863]/30 p-5 rounded-2xl shadow-inner">
                    <div>
                        <p class="text-xs font-bold text-orange-700 uppercase mb-1">Total Tagihan</p>
                        <p class="text-sm font-semibold text-orange-600" id="modal-item-count">0 Item</p>
                    </div>
                    <span id="modal-total-val" class="text-3xl font-black text-[#CC9863]">Rp 0</span>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-extrabold text-gray-900 mb-2">Metode Pembayaran</label>
                        <select id="pay-method"
                            class="w-full border-2 border-gray-100 rounded-xl p-4 text-base font-bold text-gray-800 focus:outline-none focus:border-[#CC9863] focus:ring-4 focus:ring-[#CC9863]/10 bg-gray-50 transition-all cursor-pointer"
                            onchange="toggleCashInput()">
                            <option value="cash">💵 Tunai (Cash)</option>
                            <option value="qris">📱 QRIS</option>
                            <option value="transfer">💳 Transfer Bank</option>
                            <option value="cash_tempo">⏳ Tempo / Kasbon</option>
                        </select>
                    </div>

                    <div id="cash-input-area" class="transition-all duration-300">
                        <label class="block text-sm font-extrabold text-gray-900 mb-2">Uang Diterima (Rp)</label>
                        <input type="number" id="pay-amount"
                            class="w-full border-2 border-gray-200 rounded-xl p-4 text-2xl font-black focus:outline-none focus:border-[#CC9863] focus:ring-4 focus:ring-[#CC9863]/10 bg-white text-gray-900 transition-all placeholder-gray-300"
                            placeholder="0" oninput="calculateChange()">
                        <div class="grid grid-cols-3 gap-2 mt-3" id="quick-cash-btns">
                            <!-- Diisi oleh JS -->
                        </div>
                    </div>

                    <div id="tempo-input-area" class="hidden space-y-4 rounded-2xl border border-red-100 bg-red-50/60 p-4">
                        <div>
                            <label for="tempo-paid-amount" class="block text-sm font-extrabold text-gray-900 mb-2">Pembayaran Awal (Rp)</label>
                            <input type="number" id="tempo-paid-amount" min="0" step="1000"
                                class="w-full border-2 border-red-100 rounded-xl p-4 text-2xl font-black focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-100 bg-white text-gray-900 transition-all placeholder-gray-300"
                                placeholder="0" oninput="updateTempoBalance()">
                            <p id="tempo-balance-hint" class="mt-2 text-xs font-semibold text-red-600">Sisa piutang akan dihitung dari total tagihan.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="tempo-due-date" class="block text-sm font-extrabold text-gray-900 mb-2">Tanggal Jatuh Tempo</label>
                                <input type="date" id="tempo-due-date"
                                    class="w-full border-2 border-red-100 rounded-xl p-3.5 font-bold focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-100 bg-white text-gray-900 transition-all">
                            </div>
                            <div>
                                <label for="tempo-note" class="block text-sm font-extrabold text-gray-900 mb-2">Catatan Penagihan</label>
                                <textarea id="tempo-note" rows="1" maxlength="1000"
                                    class="w-full resize-none border-2 border-red-100 rounded-xl p-3.5 font-medium focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-100 bg-white text-gray-900 transition-all placeholder-gray-400"
                                    placeholder="Contoh: ditagih saat pengambilan"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-5 border-t border-dashed border-gray-200">
                        <span id="payment-result-label" class="font-extrabold text-gray-500 uppercase text-xs tracking-wider">Kembalian</span>
                        <span id="modal-change-val"
                            class="text-2xl font-black text-green-500 bg-green-50 px-3 py-1 rounded-lg border border-green-100">Rp
                            0</span>
                    </div>
                </div>

                <button type="button" id="btn-process-payment" onclick="submitTransaction()"
                    class="mt-8 w-full py-4 bg-[#CC9863] text-white rounded-2xl font-extrabold text-lg hover:bg-[#B58555] transition-all transform active:scale-[0.98] shadow-xl shadow-[#CC9863]/30 flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    Proses Transaksi
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Style to hide number input arrows */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 1rem);
        }
    </style>

    <script>
        /* === AREA JAVASCRIPT === */

        /* STATE GLOBALS */
        let cart = [];
        let activeCategory = 'all';
        let memberId = null;
        let memberPoints = 0;
        let currentSubtotal = 0;
        let currentDiscount = 0;
        let currentTotal = 0;
        let usedPointsAmount = 0;
        let selectedRefillProduct = null;

        /* CURRENCY FORMATTER */
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number || 0);
        }

        /* SEARCH & FILTER LOGIC */
        function triggerSearch() {
            const search = document.getElementById('searchProduct').value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const isMatchSearch = name.includes(search);
                let isMatchCategory = true;
                if (activeCategory !== 'all' && card.dataset.category !== activeCategory) isMatchCategory = false;
                card.classList.toggle('hidden', !(isMatchSearch && isMatchCategory));
            });

            const outOfStockHeading = document.getElementById('outOfStockHeading');
            if (outOfStockHeading) {
                const visibleOutOfStock = Array.from(document.querySelectorAll('.product-card[data-stock-status="out-of-stock"]'))
                    .some(card => !card.classList.contains('hidden'));
                outOfStockHeading.classList.toggle('hidden', !visibleOutOfStock);
            }
        }

        function filterCategory(catId) {
            activeCategory = catId;
            document.querySelectorAll('.cat-filter').forEach(btn => {
                btn.classList.remove('bg-[#1C1D21]', 'text-white', 'shadow-md');
                btn.classList.add('bg-gray-50', 'text-gray-600');
            });
            const activeBtn = document.getElementById('cat-' + catId);
            activeBtn.classList.remove('bg-gray-50', 'text-gray-600');
            activeBtn.classList.add('bg-[#1C1D21]', 'text-white', 'shadow-md');
            triggerSearch();
        }

        document.getElementById('searchProduct').addEventListener('input', triggerSearch);

        /* MEMBER LOGIC */
        function checkMember(btnElement) {
            const phone = document.getElementById('member-phone').value;
            if (phone.length < 6) return AppFeedback.warning('Masukkan nomor HP yang valid.');

            const originalText = btnElement.innerHTML;
            btnElement.innerHTML =
                '<svg class="animate-spin h-5 w-5 text-white mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>';
            btnElement.disabled = true;

            fetch(`{{ url('kasir/pos/search-member') }}?phone=${phone}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.found) {
                        memberId = data.member.id;
                        memberPoints = data.member.points || 0;

                        document.getElementById('member-search-area').classList.add('hidden');
                        document.getElementById('member-info').classList.remove('hidden');
                        document.getElementById('member-name').innerText = data.member.name;
                        document.getElementById('member-points-val').innerText = memberPoints;

                        document.getElementById('opt-points').classList.remove('hidden');
                    } else {
                        AppFeedback.warning('Member tidak ditemukan. Pastikan nomor HP sudah terdaftar.');
                        removeMember();
                    }
                })
                .catch(error => {
                    AppFeedback.error('Terjadi kesalahan pencarian.');
                })
                .finally(() => {
                    btnElement.innerHTML = originalText;
                    btnElement.disabled = false;
                });
        }

        function removeMember() {
            memberId = null;
            memberPoints = 0;
            document.getElementById('member-phone').value = '';
            document.getElementById('member-search-area').classList.remove('hidden');
            document.getElementById('member-info').classList.add('hidden');

            document.getElementById('opt-points').classList.add('hidden');
            document.getElementById('discount-type-select').value = 'none';
            toggleDiscountInputs();
        }

        /* DISCOUNT & REWARD CALCULATION LOGIC */
        function toggleDiscountInputs() {
            const type = document.getElementById('discount-type-select').value;
            const inputArea = document.getElementById('discount-input-area');
            const inputField = document.getElementById('discount-val-input');
            const helper = document.getElementById('point-conversion-helper');

            if (type === 'none') {
                inputArea.classList.add('hidden');
                inputField.value = '';
            } else {
                inputArea.classList.remove('hidden');
                inputField.value = '';

                if (type === 'percent') {
                    inputField.placeholder = "Diskon Persen (Contoh: 10)";
                    helper.classList.add('hidden');
                } else if (type === 'manual') {
                    inputField.placeholder = "Potongan Nominal (Rp)";
                    helper.classList.add('hidden');
                } else if (type === 'points') {
                    inputField.placeholder = "Jml Poin (Max: " + memberPoints + ")";
                    helper.classList.remove('hidden');
                }
            }
            calculateTotal();
        }

        // FUNGSI UPDATE DISKON PER ITEM
        function updateItemDiscount(cartId, value) {
            const item = cart.find(i => i.cartId === cartId);
            if (!item) return;

            let discValue = parseFloat(value) || 0;
            if (discValue < 0) discValue = 0;

            let maxVal = (item.type === 'pcs' ? item.price * item.qty : item.price);

            // Hitung nilai diskon berdasarkan tipe input (Rp atau %)
            let discountNominal = 0;
            if (item.discountType === 'percent') {
                if (discValue > 100) {
                    AppFeedback.warning('Diskon maksimal 100%');
                    discValue = 100;
                }
                item.discountInput = discValue; // simpan input persen
                discountNominal = maxVal * (discValue / 100);
            } else {
                if (discValue > maxVal) {
                    AppFeedback.warning('Diskon melebihi harga!');
                    discValue = maxVal;
                }
                item.discountInput = discValue; // simpan input rupiah
                discountNominal = discValue;
            }

            item.itemDiscount = discountNominal;
            calculateTotal();
        }

        // FUNGSI GANTI TIPE DISKON PER ITEM (Toggle Rp / %)
        function toggleItemDiscountType(cartId) {
            const item = cart.find(i => i.cartId === cartId);
            if (!item) return;

            // Toggle tipe
            item.discountType = item.discountType === 'rupiah' ? 'percent' : 'rupiah';

            // Reset input value saat diganti agar tidak membingungkan
            item.discountInput = 0;
            item.itemDiscount = 0;

            // Render ulang khusus item tersebut (atau panggil renderCart)
            renderCart();
            document.getElementById(`disc-input-${cartId}`).focus();
        }

        // FUNGSI KALKULASI TOTAL AKHIR
        function calculateTotal() {
            let baseSubtotal = 0;
            let totalItemDiscount = 0;

            cart.forEach(item => {
                let lineBase = (item.type === 'pcs' ? item.price * item.qty : item.price);

                // Pastikan nilai diskon tidak melebihi harga item
                let lineDisc = item.itemDiscount || 0;
                if (lineDisc > lineBase) lineDisc = lineBase;
                item.itemDiscount = lineDisc;

                baseSubtotal += lineBase;
                totalItemDiscount += lineDisc;
            });

            currentSubtotal = baseSubtotal - totalItemDiscount;
            currentDiscount = 0;
            usedPointsAmount = 0;

            const type = document.getElementById('discount-type-select').value;
            const inputValue = parseFloat(document.getElementById('discount-val-input').value) || 0;

            if (type === 'percent') {
                let pct = Math.min(100, Math.max(0, inputValue));
                currentDiscount = currentSubtotal * (pct / 100);
            } else if (type === 'manual') {
                currentDiscount = inputValue;
            } else if (type === 'points') {
                usedPointsAmount = Math.min(memberPoints, Math.max(0, inputValue));
                currentDiscount = usedPointsAmount * 1000;
                if (inputValue > memberPoints) {
                    document.getElementById('discount-val-input').value = memberPoints;
                }
            }

            if (currentDiscount > currentSubtotal) currentDiscount = currentSubtotal;
            currentTotal = currentSubtotal - currentDiscount;

            // UPDATE UI TEXT
            document.getElementById('subtotal-val').innerText = formatRupiah(baseSubtotal);

            const itemDiscEl = document.getElementById('item-discount-val');
            if (itemDiscEl) itemDiscEl.innerText = '- ' + formatRupiah(totalItemDiscount);

            document.getElementById('discount-amount-display').innerText = '- ' + formatRupiah(currentDiscount);
            document.getElementById('total-val').innerText = formatRupiah(currentTotal);
            document.getElementById('mobile-cart-total').innerText = formatRupiah(currentTotal);
        }

        /* ADD PCS */
        function addPcsToCart(product) {
            if (product.stockPcs <= 0) return AppFeedback.warning('Stok produk botol (Pcs) habis.');

            const cartId = `pcs-${product.variantId}`;
            const existing = cart.find(item => item.cartId === cartId);

            if (existing) {
                if (existing.qty + 1 > product.stockPcs) return AppFeedback.warning('Stok tidak mencukupi.');
                existing.qty += 1;
                // Hitung ulang diskon jika tipe = persen agar menyesuaikan qty baru
                if (existing.discountType === 'percent') {
                    updateItemDiscount(cartId, existing.discountInput);
                }
            } else {
                cart.push({
                    cartId,
                    variantId: product.variantId,
                    name: product.name,
                    type: 'pcs',
                    unit: 'pcs',
                    price: product.pcsPrice,
                    qty: 1,
                    maxStock: product.stockPcs,
                    itemDiscount: 0,
                    discountType: 'rupiah', // Default: Rupiah
                    discountInput: ''
                });
            }
            renderCart();
            openSidebarCartOnDesktop();
        }

        /* REFILL MODAL */
        function openRefillModal(product) {
            if (product.stockMl <= 0) return AppFeedback.warning('Stok biang refill habis.');
            selectedRefillProduct = product;
            document.getElementById('refillProductName').innerText = product.name;
            document.getElementById('refillPriceText').innerText =
                `${formatRupiah(product.mlPrice)} / ml • Stok Sisa: ${product.stockMl} ml`;
            document.getElementById('refillMl').value = 10;
            calculateRefill();

            const modal = document.getElementById('refillModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeRefillModal() {
            const modal = document.getElementById('refillModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            selectedRefillProduct = null;
        }

        function setRefillMl(ml) {
            document.getElementById('refillMl').value = ml;
            calculateRefill();
        }

        function changeRefillMl(change) {
            const input = document.getElementById('refillMl');
            let value = parseFloat(input.value) || 1;
            value = Math.max(1, value + change);
            input.value = value;
            calculateRefill();
        }

        function calculateRefill() {
            if (!selectedRefillProduct) return;
            const ml = parseFloat(document.getElementById('refillMl').value) || 0;
            const total = ml * selectedRefillProduct.mlPrice;
            document.getElementById('refillUnitPrice').innerText = formatRupiah(selectedRefillProduct.mlPrice);
            document.getElementById('refillTotal').innerText = formatRupiah(total);
        }

        /* ADD REFILL */
        function addRefillToCart() {
            if (!selectedRefillProduct) return;
            const ml = parseFloat(document.getElementById('refillMl').value) || 0;

            if (ml <= 0) return AppFeedback.warning('Jumlah refill harus lebih dari 0 ml.');
            if (ml > selectedRefillProduct.stockMl) return AppFeedback.warning('Jumlah refill melebihi stok parfum.');

            const cartId = `refill-${selectedRefillProduct.variantId}-${Date.now()}`;
            cart.push({
                cartId,
                variantId: selectedRefillProduct.variantId,
                name: selectedRefillProduct.name,
                type: 'refill',
                unit: 'ml',
                ml: ml,
                pricePerMl: selectedRefillProduct.mlPrice,
                price: selectedRefillProduct.mlPrice * ml,
                qty: 1,
                itemDiscount: 0,
                discountType: 'rupiah',
                discountInput: ''
            });

            closeRefillModal();
            renderCart();
            openSidebarCartOnDesktop();
        }

        /* CART QTY */
        function updateQty(cartId, change) {
            const item = cart.find(item => item.cartId === cartId);
            if (!item || item.type === 'refill') return;
            if (change > 0 && item.qty + change > item.maxStock) return AppFeedback.warning('Stok toko tidak mencukupi.');

            item.qty += change;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.cartId !== cartId);
            } else {
                // Sesuaikan kembali kalkulasi diskon persen jika qty berubah
                if (item.discountType === 'percent') {
                    updateItemDiscount(cartId, item.discountInput);
                }
            }
            renderCart();
        }

        function removeCartItem(cartId) {
            cart = cart.filter(item => item.cartId !== cartId);
            renderCart();
        }

        /* RENDER CART */
        function renderCart() {
            const container = document.getElementById('cart-container');
            container.innerHTML = '';
            let totalItems = 0;

            if (cart.length === 0) {
                container.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-gray-300">
                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <p class="text-base font-bold text-gray-400">Keranjang Kosong</p>
                    <p class="text-xs font-semibold text-gray-400 mt-1">Pilih produk di sebelah kiri</p>
                </div>`;
            } else {
                cart.forEach(item => {
                    let itemTotal = 0;

                    // Siapkan elemen input diskon yang responsif
                    let discInputPlaceholder = item.discountType === 'percent' ? '%' : 'Rp';
                    let discToggleText = item.discountType === 'percent' ? '%' : 'Rp';
                    let discToggleClass = item.discountType === 'percent' ? 'bg-[#CC9863] text-white' :
                        'bg-orange-100 text-orange-700';

                    if (item.type === 'pcs') {
                        itemTotal = (item.price * item.qty) - (item.itemDiscount || 0);
                        totalItems += item.qty;
                        container.innerHTML += `
                        <div class="py-4 border-b border-gray-100/70 group">
                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-[#CC9863] text-white tracking-wide">PCS</span>
                                            <h4 class="font-bold text-sm text-gray-900 truncate">${item.name}</h4>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <div class="flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-lg p-1">
                                                <button type="button" onclick="updateQty('${item.cartId}', -1)" class="w-7 h-7 bg-white rounded shadow-sm font-bold text-gray-600 hover:bg-gray-100 transition-colors">−</button>
                                                <span class="w-6 text-center text-xs font-extrabold text-gray-800">${item.qty}</span>
                                                <button type="button" onclick="updateQty('${item.cartId}', 1)" class="w-7 h-7 bg-gray-200 rounded font-bold hover:bg-gray-300 transition-colors">+</button>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] text-gray-400 font-semibold line-through decoration-red-400">${item.itemDiscount > 0 ? formatRupiah(item.price * item.qty) : ''}</p>
                                                <p class="text-sm font-extrabold text-[#CC9863]">${formatRupiah(itemTotal)}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeCartItem('${item.cartId}')" class="w-8 h-8 rounded-lg text-gray-300 hover:bg-red-50 hover:text-red-500 shrink-0 transition-colors flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between bg-orange-50/50 p-2 rounded-lg border border-orange-100/50 mt-1">
                                    <label class="text-[10px] font-bold text-orange-700 ml-1">Diskon Item</label>
                                    <div class="flex items-center">
                                        <button onclick="toggleItemDiscountType('${item.cartId}')" class="px-2.5 py-1.5 text-[10px] font-black rounded-l border border-r-0 border-orange-200 transition-colors ${discToggleClass}">
                                            ${discToggleText}
                                        </button>
                                        <input type="number" id="disc-input-${item.cartId}" value="${item.discountInput || ''}" placeholder="0" oninput="updateItemDiscount('${item.cartId}', this.value)" class="w-20 px-2 py-1 text-xs font-bold border border-orange-200 rounded-r text-right focus:outline-none focus:border-[#CC9863] bg-white text-orange-700 placeholder-orange-300 transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    } else if (item.type === 'refill') {
                        itemTotal = item.price - (item.itemDiscount || 0);
                        totalItems += 1;
                        container.innerHTML += `
                        <div class="py-4 border-b border-gray-100/70 group">
                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-blue-500 text-white tracking-wide">REFILL</span>
                                            <h4 class="font-bold text-sm text-gray-900 truncate">${item.name}</h4>
                                        </div>
                                        <div class="flex justify-between items-end mt-3">
                                            <span class="text-xs font-bold px-2.5 py-1 bg-gray-50 border border-gray-200 rounded-lg text-gray-600">${item.ml} ml</span>
                                            <div class="text-right">
                                                <p class="text-[10px] text-gray-400 font-semibold line-through decoration-red-400">${item.itemDiscount > 0 ? formatRupiah(item.price) : ''}</p>
                                                <p class="text-sm font-extrabold text-[#CC9863]">${formatRupiah(itemTotal)}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeCartItem('${item.cartId}')" class="w-8 h-8 rounded-lg text-gray-300 hover:bg-red-50 hover:text-red-500 shrink-0 transition-colors flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between bg-orange-50/50 p-2 rounded-lg border border-orange-100/50 mt-1">
                                    <label class="text-[10px] font-bold text-orange-700 ml-1">Diskon Item</label>
                                    <div class="flex items-center">
                                        <button onclick="toggleItemDiscountType('${item.cartId}')" class="px-2.5 py-1.5 text-[10px] font-black rounded-l border border-r-0 border-orange-200 transition-colors ${discToggleClass}">
                                            ${discToggleText}
                                        </button>
                                        <input type="number" id="disc-input-${item.cartId}" value="${item.discountInput || ''}" placeholder="0" oninput="updateItemDiscount('${item.cartId}', this.value)" class="w-20 px-2 py-1 text-xs font-bold border border-orange-200 rounded-r text-right focus:outline-none focus:border-[#CC9863] bg-white text-orange-700 placeholder-orange-300 transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    }
                });
            }

            document.getElementById('cart-count-subtitle').innerText = `${totalItems} Item`;
            document.getElementById('mobile-cart-item-count').innerText = `${totalItems} Item di Keranjang`;
            calculateTotal(); // Hitung total setelah update view keranjang
        }

        /* MOBILE CART TOGGLE */
        function toggleMobileCart() {
            const cartPanel = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');

            if (cartPanel.classList.contains('translate-x-full')) {
                cartPanel.classList.remove('translate-x-full');
                cartPanel.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                cartPanel.classList.remove('translate-x-0');
                cartPanel.classList.add('translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        function openSidebarCartOnDesktop() {
            if (window.innerWidth >= 1024) {}
        }

        /* PAYMENT MODAL & SUBMIT TRANSACTION */
        function openPaymentModal() {
            if (cart.length === 0) return AppFeedback.warning('Keranjang masih kosong!');

            const totalItems = cart.reduce((sum, item) => sum + (item.type === 'pcs' ? item.qty : 1), 0);
            document.getElementById('modal-item-count').innerText = `${totalItems} Item`;
            document.getElementById('modal-total-val').innerText = formatRupiah(currentTotal);
            document.getElementById('pay-amount').value = '';
            document.getElementById('tempo-paid-amount').value = '';
            document.getElementById('tempo-due-date').value = getDefaultTempoDate();
            document.getElementById('tempo-due-date').min = getTodayDate();
            document.getElementById('tempo-note').value = '';
            document.getElementById('modal-change-val').innerText = 'Rp 0';
            document.getElementById('pay-method').value = 'cash';

            setupQuickCash(currentTotal);
            toggleCashInput();

            const modal = document.getElementById('paymentModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

    function toggleCashInput() {
        const method = document.getElementById('pay-method').value;
        const cashArea = document.getElementById('cash-input-area');
        const tempoArea = document.getElementById('tempo-input-area');
        const payInput = document.getElementById('pay-amount');
        const tempoPaidInput = document.getElementById('tempo-paid-amount');
        const resultLabel = document.getElementById('payment-result-label');
        const changeVal = document.getElementById('modal-change-val');

        if (method === 'cash') {
            cashArea.classList.remove('hidden');
            tempoArea.classList.add('hidden');
            payInput.value = '';
            resultLabel.innerText = 'Kembalian';
            changeVal.innerText = 'Rp 0';
            changeVal.classList.replace('text-red-500', 'text-green-500');
            changeVal.classList.replace('bg-red-50', 'bg-green-50');
            changeVal.classList.replace('border-red-100', 'border-green-100');
        } else if (method === 'cash_tempo') {
            cashArea.classList.add('hidden');
            tempoArea.classList.remove('hidden');
            payInput.value = '';
            tempoPaidInput.value = '';
            resultLabel.innerText = 'Sisa Piutang';
            updateTempoBalance();
        } else {
            cashArea.classList.add('hidden');
            tempoArea.classList.add('hidden');
            payInput.value = currentTotal;
            resultLabel.innerText = 'Kembalian';
            changeVal.innerText = 'LUNAS (Otomatis)';
            changeVal.classList.replace('text-red-500', 'text-green-500');
            changeVal.classList.replace('bg-red-50', 'bg-green-50');
            changeVal.classList.replace('border-red-100', 'border-green-100');
        }
        if (method === 'cash') calculateChange();
    }

    function getTodayDate() {
        const date = new Date();
        const offset = date.getTimezoneOffset() * 60000;
        return new Date(date.getTime() - offset).toISOString().split('T')[0];
    }

    function getDefaultTempoDate() {
        const date = new Date();
        date.setDate(date.getDate() + 7);
        const offset = date.getTimezoneOffset() * 60000;
        return new Date(date.getTime() - offset).toISOString().split('T')[0];
    }

    function updateTempoBalance() {
        const paid = parseFloat(document.getElementById('tempo-paid-amount').value) || 0;
        const remaining = Math.max(0, currentTotal - paid);
        const changeVal = document.getElementById('modal-change-val');
        const hint = document.getElementById('tempo-balance-hint');

        changeVal.innerText = formatRupiah(remaining);
        hint.innerText = paid > currentTotal
            ? 'Pembayaran awal tidak boleh melebihi total tagihan.'
            : `Sisa piutang: ${formatRupiah(remaining)}`;
        hint.classList.toggle('text-red-600', paid > currentTotal);
        hint.classList.toggle('text-gray-500', paid <= currentTotal);
        changeVal.classList.toggle('text-red-500', paid > currentTotal);
        changeVal.classList.toggle('text-green-500', paid <= currentTotal);
        changeVal.classList.toggle('bg-red-50', paid > currentTotal);
        changeVal.classList.toggle('bg-green-50', paid <= currentTotal);
        changeVal.classList.toggle('border-red-100', paid > currentTotal);
        changeVal.classList.toggle('border-green-100', paid <= currentTotal);
    }

        function setupQuickCash(total) {
            const btns = document.getElementById('quick-cash-btns');
            btns.innerHTML = `
                <button type="button" onclick="setCashAmount(${total})" class="py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-extrabold text-gray-700 hover:bg-[#CC9863] hover:text-white hover:border-[#CC9863] transition-colors shadow-sm">Uang Pas</button>
                <button type="button" onclick="setCashAmount(${Math.ceil(total/50000)*50000})" class="py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-extrabold text-gray-700 hover:bg-[#CC9863] hover:text-white hover:border-[#CC9863] transition-colors shadow-sm">${formatRupiah(Math.ceil(total/50000)*50000)}</button>
                <button type="button" onclick="setCashAmount(${Math.ceil(total/100000)*100000})" class="py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-extrabold text-gray-700 hover:bg-[#CC9863] hover:text-white hover:border-[#CC9863] transition-colors shadow-sm">${formatRupiah(Math.ceil(total/100000)*100000)}</button>
            `;
        }

        function setCashAmount(amount) {
            document.getElementById('pay-amount').value = amount;
            calculateChange();
        }

        function calculateChange() {
            const method = document.getElementById('pay-method').value;
            if (method !== 'cash') return;

            const paid = parseFloat(document.getElementById('pay-amount').value) || 0;
            const change = Math.max(0, paid - currentTotal);

            const changeEl = document.getElementById('modal-change-val');
            changeEl.innerText = formatRupiah(change);

            if (paid < currentTotal) {
                changeEl.classList.replace('text-green-500', 'text-red-500');
                changeEl.classList.replace('bg-green-50', 'bg-red-50');
                changeEl.classList.replace('border-green-100', 'border-red-100');
                changeEl.innerText = 'Kurang ' + formatRupiah(currentTotal - paid);
            } else {
                changeEl.classList.replace('text-red-500', 'text-green-500');
                changeEl.classList.replace('bg-red-50', 'bg-green-50');
                changeEl.classList.replace('border-red-100', 'border-green-100');
            }
        }

        /* FUNGSI SUBMIT TRANSAKSI KE DATABASE */
    function submitTransaction() {
            const method = document.getElementById('pay-method').value;
            const paidInput = method === 'cash_tempo' ? document.getElementById('tempo-paid-amount') : document.getElementById('pay-amount');
            const paid = parseFloat(paidInput.value) || 0;

            if (method === 'cash' && paid < currentTotal) {
                return AppFeedback.warning('Nominal uang tunai diterima kurang dari total tagihan!');
            }

            if (method === 'cash_tempo' && paid > currentTotal) {
                return AppFeedback.warning('Pembayaran awal cash tempo tidak boleh melebihi total tagihan!');
            }

            if (method === 'cash_tempo' && !document.getElementById('tempo-due-date').value) {
                return AppFeedback.warning('Tanggal jatuh tempo wajib diisi.');
            }

            const btn = document.getElementById('btn-process-payment');
            const originalBtnHtml = btn.innerHTML;
            btn.innerHTML =
                '<svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Memproses...';
            btn.disabled = true;

            const payload = {
                cart: cart,
                metode_bayar: method,
                nominal_bayar: paid,
                subtotal: currentSubtotal,
                discount: currentDiscount,
                total: currentTotal,
                member_id: memberId,

                // Tambahan metadata diskon untuk Controller
                is_point_used: document.getElementById('discount-type-select').value === 'points',
                used_points: usedPointsAmount,
                diskon_persen: document.getElementById('discount-type-select').value === 'percent' ? parseFloat(document
                    .getElementById('discount-val-input').value) || 0 : 0,

                _token: '{{ csrf_token() }}'
            };

            if (method === 'cash_tempo') {
                payload.cash_tempo = {
                    tanggal_jatuh_tempo: document.getElementById('tempo-due-date').value,
                    catatan_penagihan: document.getElementById('tempo-note').value.trim() || null
                };
            }

            fetch('{{ route('kasir.pos.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cart = [];
                        window.location.href = data.redirect_url;
                    } else {
                        let errorMsg = data.message;
                        if (data.errors) errorMsg = Object.values(data.errors).flat().join('\n');
                        AppFeedback.error('Gagal memproses transaksi:\n' + errorMsg, { duration: 0 });
                        btn.innerHTML = originalBtnHtml;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    AppFeedback.error('Terjadi kesalahan sistem server.', { duration: 0 });
                    btn.innerHTML = originalBtnHtml;
                    btn.disabled = false;
                });
        }
    </script>
@endsection
