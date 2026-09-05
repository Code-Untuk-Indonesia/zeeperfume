@extends('template.kasir')
@section('title', 'Transaksi Kasir')

@section('content')

    <div class="flex flex-col lg:flex-row h-full w-full relative overflow-hidden bg-[#FAFAFA]">

        {{-- ========================================================= --}}
        {{-- LEFT : PRODUCT AREA --}}
        {{-- ========================================================= --}}
        <section class="flex-1 min-w-0 flex flex-col h-full overflow-hidden p-3 sm:p-4 lg:p-6">

            {{-- HEADER & FILTERS --}}
            <div class="shrink-0">
                <div class="mb-5">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Transaksi Penjualan</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Pilih produk parfum langsung dari katalog di bawah ini.
                    </p>
                </div>

                {{-- SEARCH --}}
                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="searchProduct" autofocus
                            class="block w-full pl-12 pr-4 py-3 sm:py-3.5 bg-white border border-gray-100 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#CC9863]/30 focus:border-[#CC9863] text-sm font-semibold transition"
                            placeholder="Cari nama parfum atau varian...">
                    </div>
                </div>

                {{-- CATEGORY FILTER DINAMIS --}}
                <div class="flex gap-2 overflow-x-auto pb-2 mb-3 scrollbar-hide">
                    <button type="button" onclick="filterCategory('all')"
                        class="cat-filter px-4 sm:px-5 py-2 sm:py-2.5 bg-[#CC9863] text-white rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap shadow-sm"
                        id="cat-all">Semua</button>
                    @if (isset($categories))
                        @foreach ($categories as $category)
                            <button type="button" onclick="filterCategory('{{ $category->id }}')"
                                class="cat-filter px-4 sm:px-5 py-2 sm:py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs sm:text-sm font-semibold whitespace-nowrap shadow-sm transition hover:border-[#CC9863] hover:text-[#CC9863]"
                                id="cat-{{ $category->id }}">
                                {{ $category->nama_kategori }}
                            </button>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- PRODUCT GRID --}}
            <div class="flex-1 min-h-0 overflow-y-auto pr-1 pb-24 lg:pb-6">
                <div id="productGrid" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3 sm:gap-4">

                    @if (isset($products))
                        @foreach ($products as $product)
                            @foreach ($product->variants as $variant)
                                @php
                                    $isRefill = strtolower($variant->satuan) === 'ml';
                                    $stock = $variant->branchStocks->first()->stok ?? 0;
                                    $price = $variant->harga_jual;
                                @endphp

                                <div class="product-card bg-white rounded-2xl p-3 sm:p-4 border border-gray-100 shadow-sm hover:shadow-md hover:border-[#CC9863]/50 transition cursor-pointer group flex flex-col h-full"
                                    data-name="{{ strtolower($variant->nama_varian) }}"
                                    data-category="{{ $product->kategori_id }}"
                                    onclick="
                                        @if ($isRefill) openRefillModal({
                                                variantId: '{{ $variant->id }}',
                                                name: '{{ addslashes($variant->nama_varian) }}',
                                                mlPrice: {{ $price }},
                                                stockMl: {{ $stock }}
                                            })
                                        @else
                                            addPcsToCart({
                                                variantId: '{{ $variant->id }}',
                                                name: '{{ addslashes($variant->nama_varian) }}',
                                                pcsPrice: {{ $price }},
                                                stockPcs: {{ $stock }}
                                            }) @endif
                                    ">

                                    <div
                                        class="h-24 sm:h-28 rounded-xl {{ $isRefill ? 'bg-blue-50 text-blue-400' : 'bg-orange-50 text-orange-400' }} flex items-center justify-center mb-3 relative overflow-hidden group-hover:scale-105 transition-transform duration-300">
                                        <svg class="w-9 h-9 sm:w-11 sm:h-11" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                            </path>
                                        </svg>

                                        <span
                                            class="absolute top-2 right-2 px-2 py-1 rounded-md text-[9px] font-bold bg-white text-gray-500 shadow-sm uppercase">
                                            {{ $product->category->nama_kategori ?? 'Umum' }}
                                        </span>

                                        <span
                                            class="absolute top-2 left-2 px-2 py-1 rounded-md text-[9px] font-bold shadow-sm uppercase {{ $isRefill ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600' }}">
                                            {{ $isRefill ? 'REFILL' : 'PCS' }}
                                        </span>
                                    </div>

                                    <div class="mt-auto">
                                        <h3 class="text-sm font-bold text-gray-900 leading-tight line-clamp-2">
                                            {{ $variant->nama_varian }}</h3>

                                        <div class="mt-2">
                                            <p class="text-[10px] text-gray-400">Stok: {{ $stock }}
                                                {{ $isRefill ? 'ml' : 'Pcs' }}</p>
                                            <p class="text-sm font-extrabold text-[#CC9863] mt-0.5">
                                                Rp {{ number_format($price, 0, ',', '.') }}
                                                @if ($isRefill)
                                                    <span class="text-[10px] font-semibold text-gray-400">/ ml</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    @endif

                </div>
            </div>
        </section>

        {{-- ========================================================= --}}
        {{-- CART AREA --}}
        {{-- ========================================================= --}}
        <aside id="mobile-cart"
            class="w-full lg:w-[390px] xl:w-[420px] h-[88vh] lg:h-full bg-white border-l border-gray-200 flex flex-col shrink-0 absolute lg:relative bottom-0 z-[60] rounded-t-3xl lg:rounded-none shadow-[0_-10px_40px_rgba(0,0,0,0.12)] lg:shadow-none transform translate-y-[86%] lg:translate-y-0 transition-transform duration-300">

            {{-- MOBILE HANDLE --}}
            <button type="button" onclick="toggleMobileCart()"
                class="lg:hidden h-11 flex items-center justify-center border-b border-gray-100 relative w-full bg-white rounded-t-3xl cursor-pointer hover:bg-gray-50">
                <div class="w-12 h-1.5 rounded-full bg-gray-300"></div>
                <span id="mobile-cart-hint" class="absolute right-4 text-[10px] font-bold text-[#CC9863]">Buka
                    Keranjang</span>
            </button>

            {{-- CUSTOMER INPUT (DIPERBARUI) --}}
            <div class="px-4 sm:px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-2">Pelanggan</p>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                        <input type="text" id="member-phone"
                            class="w-full pl-9 pr-3 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#CC9863] transition"
                            placeholder="Nomor HP member">
                    </div>
                    <button type="button" onclick="checkMember()"
                        class="w-11 bg-[#1C1D21] text-white rounded-xl flex items-center justify-center hover:bg-black transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>

                {{-- MEMBER OPTIONS (MUNCUL SETELAH CHECK) --}}
                <div id="member-info"
                    class="hidden mt-3 p-3 bg-green-50 border border-green-100 rounded-xl flex flex-col gap-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p id="member-name" class="text-sm font-bold text-gray-900">Member Name</p>
                            <p id="member-points" class="text-[10px] text-green-600 font-bold mt-0.5">Poin Tersedia: 0</p>
                        </div>
                        <button type="button" onclick="removeMember()"
                            class="text-xs font-bold text-red-400 hover:text-red-600">Hapus</button>
                    </div>

                    <div class="border-t border-green-200/50 pt-2">
                        <label class="text-[10px] font-bold text-gray-600 block mb-1">Pilih Benefit Member:</label>
                        <select id="member-reward-select" onchange="applyMemberReward()"
                            class="w-full border border-green-200 bg-white rounded-lg p-2 text-xs font-bold focus:outline-none focus:border-green-400 text-gray-700">
                            <option value="none">Hanya Kumpulkan Poin (Tanpa Diskon)</option>
                            <option value="discount_10">Gunakan Diskon Promo 10%</option>
                            <option value="use_points">Tukar Poin (Rp 1.000 / Poin)</option>
                            <option value="manual">Potongan Nominal Manual</option>
                        </select>
                    </div>

                    <div id="manual-discount-area" class="hidden">
                        <input type="number" id="manual-discount-input" oninput="applyMemberReward()"
                            class="w-full border border-green-200 bg-white rounded-lg p-2 text-xs focus:outline-none focus:border-green-400"
                            placeholder="Masukkan nominal (Rp)...">
                    </div>
                </div>
            </div>

            {{-- CART LIST --}}
            <div id="cart-container" class="flex-1 min-h-0 overflow-y-auto px-4 sm:px-5 py-3">
                <div class="h-full flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-14 h-14 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <p class="text-sm font-semibold">Keranjang masih kosong</p>
                    <p class="text-[11px] mt-1">Pilih produk atau refill</p>
                </div>
            </div>

            {{-- PAYMENT --}}
            <div
                class="shrink-0 border-t border-gray-100 px-4 sm:px-5 py-4 bg-white pb-8 lg:pb-4 shadow-[0_-5px_20px_rgba(0,0,0,0.03)] lg:shadow-none">
                <div class="space-y-2 pb-4 border-b border-gray-100">
                    <div class="flex justify-between text-sm">
                        <span id="subtotal-label" class="text-gray-500">Subtotal (0 item)</span>
                        <span id="subtotal-val" class="font-bold text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Diskon</span>
                        <span id="discount-val" class="font-semibold text-red-500">- Rp 0</span>
                    </div>
                </div>
                <div class="flex justify-between items-end py-4">
                    <span class="font-bold text-gray-900">Total Tagihan</span>
                    <span id="total-val" class="text-2xl sm:text-3xl font-extrabold text-[#CC9863]">Rp 0</span>
                </div>

                <button type="button" onclick="openPaymentModal()"
                    class="w-full min-h-[52px] bg-[#CC9863] text-white rounded-2xl font-bold flex items-center justify-center hover:bg-[#B58555] transition shadow-md shadow-[#CC9863]/20">
                    Bayar Sekarang
                </button>
            </div>
        </aside>

    </div>

    {{-- ========================================================= --}}
    {{-- REFILL MODAL --}}
    {{-- ========================================================= --}}
    <div id="refillModal"
        class="hidden fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm items-end sm:items-center justify-center p-0 sm:p-4">
        <div
            class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden animate-[slideUp_0.3s_ease-out]">
            <div class="p-5 border-b border-gray-100">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-[#CC9863] tracking-wider">Refill Parfum</p>
                        <h3 id="refillProductName" class="text-xl font-bold text-gray-900 mt-1">-</h3>
                        <p id="refillPriceText" class="text-sm text-gray-500 mt-1">-</p>
                    </div>
                    <button type="button" onclick="closeRefillModal()"
                        class="w-9 h-9 rounded-xl bg-gray-100 text-gray-500 font-bold hover:bg-gray-200 transition">×</button>
                </div>
            </div>
            <div class="p-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Refill</label>
                <div
                    class="flex items-center border border-gray-200 rounded-2xl overflow-hidden bg-gray-50 transition focus-within:border-[#CC9863] focus-within:ring-1 focus-within:ring-[#CC9863]">
                    <button type="button" onclick="changeRefillMl(-5)"
                        class="w-14 h-14 text-xl font-bold text-gray-500 hover:bg-gray-200 transition">−</button>
                    <div class="flex-1 relative">
                        <input type="number" id="refillMl" value="10" min="1" step="1"
                            oninput="calculateRefill()"
                            class="w-full h-14 bg-transparent text-center text-xl font-bold focus:outline-none">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">ML</span>
                    </div>
                    <button type="button" onclick="changeRefillMl(5)"
                        class="w-14 h-14 text-xl font-bold text-gray-500 hover:bg-gray-200 transition">+</button>
                </div>

                <div class="grid grid-cols-4 gap-2 mt-3">
                    <button type="button" onclick="setRefillMl(5)"
                        class="py-2 border border-gray-200 rounded-xl text-xs font-bold hover:bg-gray-50 transition">5
                        ml</button>
                    <button type="button" onclick="setRefillMl(10)"
                        class="py-2 border border-gray-200 rounded-xl text-xs font-bold hover:bg-gray-50 transition">10
                        ml</button>
                    <button type="button" onclick="setRefillMl(20)"
                        class="py-2 border border-gray-200 rounded-xl text-xs font-bold hover:bg-gray-50 transition">20
                        ml</button>
                    <button type="button" onclick="setRefillMl(30)"
                        class="py-2 border border-gray-200 rounded-xl text-xs font-bold hover:bg-gray-50 transition">30
                        ml</button>
                </div>

                <div class="mt-5 p-4 rounded-2xl bg-[#F6F5F2] border border-gray-100">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Harga per ml</span>
                        <span id="refillUnitPrice" class="font-bold text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-end mt-3">
                        <span class="font-bold text-gray-900">Total</span>
                        <span id="refillTotal" class="text-2xl font-extrabold text-[#CC9863]">Rp 0</span>
                    </div>
                </div>

                <button type="button" onclick="addRefillToCart()"
                    class="mt-5 w-full py-4 bg-[#CC9863] text-white rounded-2xl font-bold hover:bg-[#B58555] transition shadow-lg shadow-[#CC9863]/30">Tambah
                    Refill ke Keranjang</button>
            </div>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- PAYMENT CHECKOUT MODAL --}}
    {{-- ========================================================= --}}
    <div id="paymentModal"
        class="hidden fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm items-center justify-center p-4">
        <div
            class="bg-white w-full sm:max-w-md rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col animate-[scaleIn_0.2s_ease-out]">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50 shrink-0">
                <div>
                    <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Selesaikan Pembayaran</p>
                    <h3 class="text-lg font-bold text-gray-900 mt-0.5">Detail Transaksi</h3>
                </div>
                <button type="button" onclick="closePaymentModal()"
                    class="w-8 h-8 rounded-xl bg-gray-200 text-gray-600 font-bold hover:bg-gray-300 transition">×</button>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                <div class="flex justify-between items-end mb-6 bg-[#F6F5F2] p-4 rounded-xl border border-gray-100">
                    <span class="font-bold text-gray-700">Total Tagihan</span>
                    <span id="modal-total-val" class="text-2xl sm:text-3xl font-extrabold text-[#CC9863]">Rp 0</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Metode Pembayaran</label>
                        <select id="pay-method"
                            class="w-full border border-gray-200 rounded-xl p-3 sm:p-3.5 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] bg-gray-50 font-bold transition"
                            onchange="toggleCashInput()">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="tempo">Tempo / Kasbon</option>
                        </select>
                    </div>

                    <div id="cash-input-area" class="transition-all duration-300">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Uang Diterima (Rp)</label>
                        <input type="number" id="pay-amount"
                            class="w-full border border-gray-200 rounded-xl p-3 sm:p-4 text-lg sm:text-xl font-extrabold focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] bg-white text-gray-900 transition"
                            placeholder="0" oninput="calculateChange()">

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-3" id="quick-cash-btns">
                            <!-- Diisi oleh JS -->
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-5 border-t border-gray-100">
                        <span class="font-bold text-gray-500">Kembalian</span>
                        <span id="modal-change-val" class="text-xl sm:text-2xl font-extrabold text-green-500">Rp 0</span>
                    </div>
                </div>

                <button type="button" id="btn-process-payment" onclick="submitTransaction()"
                    class="mt-8 w-full py-4 bg-[#1C1D21] text-white rounded-2xl font-bold hover:bg-black transition flex items-center justify-center gap-2 shadow-lg shadow-black/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Proses Pembayaran
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
    </style>

    <script>
        /* STATE GLOBALS */
        let cart = [];
        let activeCategory = 'all';
        let memberId = null;
        let memberPoints = 0;

        let currentSubtotal = 0;
        let currentDiscount = 0;
        let currentTotal = 0;
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
        }

        function filterCategory(catId) {
            activeCategory = catId;
            document.querySelectorAll('.cat-filter').forEach(btn => {
                btn.classList.remove('bg-[#CC9863]', 'text-white');
                btn.classList.add('bg-white', 'text-gray-600');
            });
            document.getElementById('cat-' + catId).classList.add('bg-[#CC9863]', 'text-white');
            document.getElementById('cat-' + catId).classList.remove('bg-white', 'text-gray-600');
            triggerSearch();
        }

        document.getElementById('searchProduct').addEventListener('input', triggerSearch);

        /* MEMBER LOGIC (UPDATE FLEKSIBEL) */
        function checkMember() {
            const phone = document.getElementById('member-phone').value;
            if (phone.length < 6) return alert('Masukkan nomor HP yang valid.');

            // Dummy Data simulasi database
            memberId = 1;
            memberPoints = 120; // Contoh poin

            document.getElementById('member-info').classList.remove('hidden');
            document.getElementById('member-name').innerText = "Pelanggan Member VIP";
            document.getElementById('member-points').innerText = "Poin Tersedia: " + memberPoints;

            // Set ke default (Tanpa Diskon)
            document.getElementById('member-reward-select').value = 'none';
            document.getElementById('manual-discount-input').value = '';

            applyMemberReward();
        }

        function removeMember() {
            memberId = null;
            memberPoints = 0;
            document.getElementById('member-phone').value = '';
            document.getElementById('member-info').classList.add('hidden');

            applyMemberReward(); // Update ulang harga menjadi normal
        }

        function applyMemberReward() {
            // Kalkulasi subtotal barang mentah
            currentSubtotal = cart.reduce((sum, item) => sum + (item.type === 'pcs' ? item.price * item.qty : item.price),
                0);

            // Logika perhitungan diskon
            currentDiscount = 0;
            const rewardSelect = document.getElementById('member-reward-select');
            const manualArea = document.getElementById('manual-discount-area');
            const manualInput = document.getElementById('manual-discount-input');

            // Hide/Show manual input
            if (rewardSelect && rewardSelect.value === 'manual') {
                manualArea.classList.remove('hidden');
            } else if (manualArea) {
                manualArea.classList.add('hidden');
            }

            if (memberId !== null && rewardSelect) {
                if (rewardSelect.value === 'discount_10') {
                    currentDiscount = currentSubtotal * 0.10;
                } else if (rewardSelect.value === 'use_points') {
                    // Asumsi 1 poin = Rp 1.000
                    currentDiscount = memberPoints * 1000;
                } else if (rewardSelect.value === 'manual') {
                    currentDiscount = parseFloat(manualInput.value) || 0;
                }
            }

            // Pastikan diskon tidak melebihi harga subtotal
            if (currentDiscount > currentSubtotal) {
                currentDiscount = currentSubtotal;
            }

            currentTotal = currentSubtotal - currentDiscount;

            // Render ke UI Sidebar
            document.getElementById('subtotal-val').innerText = formatRupiah(currentSubtotal);
            document.getElementById('discount-val').innerText = '- ' + formatRupiah(currentDiscount);
            document.getElementById('total-val').innerText = formatRupiah(currentTotal);
        }

        /* ADD PCS */
        function addPcsToCart(product) {
            if (product.stockPcs <= 0) return alert('Stok produk botol habis.');

            const cartId = `pcs-${product.variantId}`;
            const existing = cart.find(item => item.cartId === cartId);

            if (existing) {
                if (existing.qty + 1 > product.stockPcs) return alert('Stok tidak mencukupi.');
                existing.qty += 1;
            } else {
                cart.push({
                    cartId,
                    variantId: product.variantId,
                    name: product.name,
                    type: 'pcs',
                    unit: 'pcs',
                    price: product.pcsPrice,
                    qty: 1,
                    maxStock: product.stockPcs
                });
            }
            renderCart();
            openMobileCart();
        }

        /* REFILL MODAL */
        function openRefillModal(product) {
            if (product.stockMl <= 0) return alert('Stok biang refill habis.');

            selectedRefillProduct = product;
            document.getElementById('refillProductName').innerText = product.name;
            document.getElementById('refillPriceText').innerText =
                `${formatRupiah(product.mlPrice)} / ml • Stok ${product.stockMl} ml`;
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

            if (ml <= 0) return alert('Jumlah refill harus lebih dari 0 ml.');
            if (ml > selectedRefillProduct.stockMl) return alert('Jumlah refill melebihi stok parfum.');

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
                qty: 1
            });

            closeRefillModal();
            renderCart();
            openMobileCart();
        }

        /* CART QTY */
        function updateQty(cartId, change) {
            const item = cart.find(item => item.cartId === cartId);
            if (!item || item.type === 'refill') return;

            if (change > 0 && item.qty + change > item.maxStock) {
                return alert('Stok tidak mencukupi.');
            }

            item.qty += change;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.cartId !== cartId);
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
                <div class="h-full flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-14 h-14 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p class="text-sm font-semibold">Keranjang masih kosong</p>
                    <p class="text-[11px] mt-1">Pilih produk atau refill</p>
                </div>
            `;
            }

            cart.forEach(item => {
                let itemTotal = 0;

                if (item.type === 'pcs') {
                    itemTotal = item.price * item.qty;
                    totalItems += item.qty;
                    container.innerHTML += `
                    <div class="py-4 border-b border-gray-100">
                        <div class="flex justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-600 text-[9px] font-bold">PCS</span>
                                    <h4 class="font-bold text-sm text-gray-900 truncate">${item.name}</h4>
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5">${formatRupiah(item.price)} / pcs</p>
                                <p class="text-sm font-bold text-[#CC9863] mt-1">${formatRupiah(itemTotal)}</p>
                            </div>
                            <button type="button" onclick="removeCartItem('${item.cartId}')" class="w-8 h-8 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 shrink-0 transition">×</button>
                        </div>
                        <div class="flex items-center gap-2 mt-3 w-fit bg-gray-50 border border-gray-200 rounded-xl p-1">
                            <button type="button" onclick="updateQty('${item.cartId}', -1)" class="w-8 h-8 bg-white rounded-lg shadow-sm font-bold text-gray-600 hover:bg-gray-100 transition">−</button>
                            <span class="w-7 text-center text-sm font-bold">${item.qty}</span>
                            <button type="button" onclick="updateQty('${item.cartId}', 1)" class="w-8 h-8 bg-[#CC9863] text-white rounded-lg font-bold hover:bg-[#b58555] transition">+</button>
                        </div>
                    </div>
                `;
                }

                if (item.type === 'refill') {
                    itemTotal = item.price;
                    totalItems += 1;
                    container.innerHTML += `
                    <div class="py-4 border-b border-gray-100">
                        <div class="flex justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 text-[9px] font-bold">REFILL</span>
                                    <h4 class="font-bold text-sm text-gray-900 truncate">${item.name}</h4>
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5">${item.ml} ml × ${formatRupiah(item.pricePerMl)}</p>
                                <p class="text-sm font-bold text-[#CC9863] mt-1">${formatRupiah(itemTotal)}</p>
                            </div>
                            <button type="button" onclick="removeCartItem('${item.cartId}')" class="w-8 h-8 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 shrink-0 transition">×</button>
                        </div>
                    </div>
                `;
                }
            });

            document.getElementById('subtotal-label').innerText = `Subtotal (${totalItems} item)`;
            // Kalkulasi ulang total ketika keranjang di render
            applyMemberReward();
        }


        /* MOBILE CART */
        function toggleMobileCart() {
            const cart = document.getElementById('mobile-cart');
            const hint = document.getElementById('mobile-cart-hint');
            cart.classList.toggle('translate-y-[86%]');
            cart.classList.toggle('translate-y-0');
            hint.innerText = cart.classList.contains('translate-y-0') ? 'Tutup' : 'Buka Keranjang';
        }

        function openMobileCart() {
            if (window.innerWidth >= 1024) return;
            const cart = document.getElementById('mobile-cart');
            cart.classList.remove('translate-y-[86%]');
            cart.classList.add('translate-y-0');
            document.getElementById('mobile-cart-hint').innerText = 'Tutup';
        }

        /* PAYMENT MODAL & SUBMIT TRANSACTION */
        function openPaymentModal() {
            if (cart.length === 0) return alert('Keranjang masih kosong!');

            document.getElementById('modal-total-val').innerText = formatRupiah(currentTotal);
            document.getElementById('pay-amount').value = '';
            document.getElementById('modal-change-val').innerText = 'Rp 0';

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
            const payInput = document.getElementById('pay-amount');

            if (method === 'cash') {
                cashArea.classList.remove('hidden');
                payInput.value = '';
            } else {
                cashArea.classList.add('hidden');
                payInput.value = currentTotal; // Auto fill total for QRIS/Transfer
            }
            calculateChange();
        }

        function setupQuickCash(total) {
            const btns = document.getElementById('quick-cash-btns');
            btns.innerHTML = `
                <button type="button" onclick="setCashAmount(${total})" class="py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-100 transition">Uang Pas</button>
                <button type="button" onclick="setCashAmount(${Math.ceil(total/50000)*50000})" class="py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-100 transition">${formatRupiah(Math.ceil(total/50000)*50000)}</button>
                <button type="button" onclick="setCashAmount(${Math.ceil(total/100000)*100000})" class="py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-100 transition">${formatRupiah(Math.ceil(total/100000)*100000)}</button>
            `;
        }

        function setCashAmount(amount) {
            document.getElementById('pay-amount').value = amount;
            calculateChange();
        }

        function calculateChange() {
            const paid = parseFloat(document.getElementById('pay-amount').value) || 0;
            const change = Math.max(0, paid - currentTotal);

            const changeEl = document.getElementById('modal-change-val');
            changeEl.innerText = formatRupiah(change);

            if (paid < currentTotal && document.getElementById('pay-method').value === 'cash') {
                changeEl.classList.replace('text-green-500', 'text-red-500');
                changeEl.innerText = 'Kurang ' + formatRupiah(currentTotal - paid);
            } else {
                changeEl.classList.replace('text-red-500', 'text-green-500');
            }
        }

        function submitTransaction() {
            const method = document.getElementById('pay-method').value;
            const paid = parseFloat(document.getElementById('pay-amount').value) || 0;

            if (method === 'cash' && paid < currentTotal) {
                return alert('Nominal uang diterima kurang dari total tagihan!');
            }

            const btn = document.getElementById('btn-process-payment');
            btn.innerHTML = 'Memproses...';
            btn.disabled = true;

            const payload = {
                cart: cart,
                metode_bayar: method,
                nominal_bayar: paid,
                subtotal: currentSubtotal,
                discount: currentDiscount,
                total: currentTotal,
                member_id: memberId, // Sesuai ID member yang ter-set
                _token: '{{ csrf_token() }}'
            };

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
                        alert('Gagal memproses transaksi: ' + data.message);
                        btn.innerHTML =
                            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Proses Pembayaran';
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem.');
                    btn.innerHTML =
                        '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Proses Pembayaran';
                    btn.disabled = false;
                });
        }
    </script>
@endsection
