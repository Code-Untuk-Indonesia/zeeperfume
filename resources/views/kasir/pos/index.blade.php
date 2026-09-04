@extends('template.kasir')
@section('title', 'Transaksi Kasir')

@section('content')
    <!-- Container utama mengambil sisa tinggi layar (h-full) -->
    <div class="flex flex-col xl:flex-row h-full w-full pb-16 md:pb-0 relative overflow-hidden">

        <!-- ================= KIRI: KATALOG PRODUK ================= -->
        <div class="flex-1 flex flex-col h-full overflow-hidden p-4 lg:p-6">

            <!-- Search & Custom Product -->
            <div class="flex flex-col sm:flex-row gap-3 mb-5">
                <!-- Search Bar -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" autofocus
                        class="block w-full pl-11 pr-4 py-3.5 border-none rounded-2xl shadow-sm bg-white focus:ring-2 focus:ring-[#CC9863] sm:text-sm font-medium transition"
                        placeholder="Scan Barcode atau Cari Nama Parfum (F2)...">
                </div>

                <!-- Button Produk Custom -->
                <a href="{{ url('kasir/pos/custom') }}"
                    class="bg-orange-50 border border-orange-100 text-[#CC9863] px-5 py-3.5 rounded-2xl font-bold hover:bg-orange-100 transition shadow-sm flex items-center justify-center gap-2 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Produk Custom
                </a>
            </div>

            <!-- Kategori (Scroll horizontal) -->
            <div class="flex gap-2 overflow-x-auto pb-2 mb-4 scrollbar-hide shrink-0">
                <button
                    class="bg-[#CC9863] text-white px-5 py-2 rounded-xl text-sm font-bold whitespace-nowrap shadow-sm">Semua</button>
                <button
                    class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-5 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition">Eau
                    de Parfum (EDP)</button>
                <button
                    class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-5 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition">Eau
                    de Toilette (EDT)</button>
                <button
                    class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-5 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition">Body
                    Mist</button>
                <button
                    class="bg-white text-gray-600 hover:bg-gray-50 border border-gray-200 px-5 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition">Biang
                    Refill</button>
            </div>

            <!-- Grid Produk -->
            <div class="flex-1 overflow-y-auto pr-2 pb-6">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">

                    <!-- Product Card 1 -->
                    <div onclick="addToCart('P001', 'Midnight Amber 50ml', 250000)"
                        class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md hover:border-[#CC9863]/50 transition cursor-pointer flex flex-col h-full active:scale-95 group">
                        <div
                            class="w-full h-28 bg-orange-50 rounded-xl mb-3 flex items-center justify-center text-orange-400 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <div class="mt-auto">
                            <p class="text-[11px] text-gray-400 mb-1 font-medium">EDP • Stok: 45</p>
                            <h3 class="font-bold text-gray-900 text-sm leading-tight mb-2 line-clamp-2">Midnight Amber 50ml
                            </h3>
                            <p class="text-[#CC9863] font-extrabold text-sm">Rp 250.000</p>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div onclick="addToCart('P002', 'Vanilla Clouds 100ml', 85000)"
                        class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md hover:border-[#CC9863]/50 transition cursor-pointer flex flex-col h-full active:scale-95 group">
                        <div
                            class="w-full h-28 bg-blue-50 rounded-xl mb-3 flex items-center justify-center text-blue-400 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <div class="mt-auto">
                            <p class="text-[11px] text-gray-400 mb-1 font-medium">Body Mist • Stok: 12</p>
                            <h3 class="font-bold text-gray-900 text-sm leading-tight mb-2 line-clamp-2">Vanilla Clouds 100ml
                            </h3>
                            <p class="text-[#CC9863] font-extrabold text-sm">Rp 85.000</p>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div onclick="addToCart('P003', 'OXTA Poseidon', 325000)"
                        class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-md hover:border-[#CC9863]/50 transition cursor-pointer flex flex-col h-full active:scale-95 group">
                        <div
                            class="w-full h-28 bg-purple-50 rounded-xl mb-3 flex items-center justify-center text-purple-400 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <div class="mt-auto">
                            <p class="text-[11px] text-gray-400 mb-1 font-medium">EDP • Stok: 30</p>
                            <h3 class="font-bold text-gray-900 text-sm leading-tight mb-2 line-clamp-2">OXTA Poseidon</h3>
                            <p class="text-[#CC9863] font-extrabold text-sm">Rp 325.000</p>
                        </div>
                    </div>

                    <!-- Product Card 4 (Habis) - Di-disable event js-nya -->
                    <div
                        class="bg-gray-100 rounded-2xl p-4 border border-gray-200 shadow-sm opacity-60 cursor-not-allowed flex flex-col h-full">
                        <div
                            class="w-full h-28 bg-gray-200 rounded-xl mb-3 flex items-center justify-center text-gray-400 relative">
                            <span
                                class="absolute bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-md top-2 right-2 shadow-sm">HABIS</span>
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <div class="mt-auto">
                            <p class="text-[11px] text-gray-400 mb-1 font-medium">EDT • Stok: 0</p>
                            <h3 class="font-bold text-gray-900 text-sm leading-tight mb-2 line-clamp-2">Ocean Breeze 30ml
                            </h3>
                            <p class="text-gray-500 font-extrabold text-sm">Rp 145.000</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ================= KANAN: KERANJANG / CART ================= -->
        <div
            class="w-full xl:w-[420px] bg-white border-l border-gray-200 flex flex-col h-[85vh] xl:h-full shadow-2xl xl:shadow-none z-20 shrink-0 absolute bottom-0 xl:relative rounded-t-3xl xl:rounded-none transition-transform duration-300 transform xl:translate-y-0 translate-y-[90%] hover:translate-y-0 xl:hover:translate-y-0">

            <!-- Handle Drag untuk Mobile -->
            <div
                class="w-full h-8 flex items-center justify-center xl:hidden cursor-pointer bg-gray-50 rounded-t-3xl border-b border-gray-100">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            </div>

            <!-- ================= INPUT PELANGGAN / MEMBER ================= -->
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Data Pelanggan</label>
                <div class="flex items-center gap-2">
                    <!-- Input No HP -->
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <input type="text" id="member-phone"
                            class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-[#CC9863] focus:border-[#CC9863] transition"
                            placeholder="Cari No. HP...">
                    </div>

                    <!-- Tombol Cari -->
                    <button type="button" onclick="checkMember()"
                        class="bg-[#1C1D21] text-white p-2.5 rounded-xl hover:bg-gray-800 transition shadow-sm"
                        title="Cari Member">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    <!-- Tombol Tambah Member Baru -->
                    <a href="{{ url('kasir/member/create') }}"
                        class="p-2.5 bg-orange-50 text-[#CC9863] rounded-xl hover:bg-orange-100 transition shadow-sm border border-orange-100"
                        title="Daftarkan Member Baru">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </a>
                </div>

                <!-- Tampilan Member Aktif (Disembunyikan secara default) -->
                <div id="member-info"
                    class="hidden mt-3 p-3 bg-green-50 border border-green-100 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-900" id="member-name">Budi Santoso</p>
                        <p class="text-[10px] text-green-600 font-semibold mt-0.5">Member VIP (Diskon 10%)</p>
                    </div>
                    <button type="button" onclick="removeMember()"
                        class="text-red-400 hover:text-red-600 transition p-1" title="Hapus Pelanggan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- ================= LIST PESANAN (CART) ================= -->
            <div id="cart-container" class="flex-1 overflow-y-auto px-5 py-2 space-y-1">
                <!-- Keranjang kosong (akan ditimpa JS jika ada barang) -->
                <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <p class="text-sm font-semibold">Keranjang masih kosong</p>
                </div>
            </div>

            <!-- ================= PAYMENT SECTION ================= -->
            <div
                class="bg-white border-t border-gray-200 px-5 py-5 pb-8 xl:pb-5 shrink-0 z-30 shadow-[0_-10px_30px_rgba(0,0,0,0.03)]">
                <!-- Rincian -->
                <div class="space-y-1.5 mb-4 border-b border-gray-100 pb-4">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span id="subtotal-label">Subtotal (0 Item)</span>
                        <span id="subtotal-val" class="font-semibold text-gray-900">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Diskon Member</span>
                        <span id="discount-val" class="font-semibold text-red-500">- Rp 0</span>
                    </div>
                </div>

                <div class="flex justify-between items-end mb-5">
                    <span class="text-sm font-bold text-gray-900">Total Tagihan</span>
                    <span id="total-val" class="text-3xl font-extrabold text-[#CC9863]">Rp 0</span>
                </div>

                <!-- Metode Pembayaran -->
                <div class="grid grid-cols-4 gap-2 mb-5">
                    <!-- Tambahkan class 'active-payment' via JS jika diklik (disini dummy UI saja) -->
                    <button type="button"
                        class="flex flex-col items-center justify-center gap-1.5 p-2 bg-[#1C1D21] text-white rounded-xl border border-transparent shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="text-[10px] font-bold">Tunai</span>
                    </button>
                    <button type="button"
                        class="flex flex-col items-center justify-center gap-1.5 p-2 bg-white text-gray-600 rounded-xl border border-gray-200 hover:bg-gray-100 hover:border-[#CC9863] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                            </path>
                        </svg>
                        <span class="text-[10px] font-bold">QRIS</span>
                    </button>
                    <button type="button"
                        class="flex flex-col items-center justify-center gap-1.5 p-2 bg-white text-gray-600 rounded-xl border border-gray-200 hover:bg-gray-100 hover:border-[#CC9863] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        <span class="text-[10px] font-bold">Transfer</span>
                    </button>
                    <button type="button"
                        class="flex flex-col items-center justify-center gap-1.5 p-2 bg-white text-gray-600 rounded-xl border border-gray-200 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-[10px] font-bold">Tempo</span>
                    </button>
                </div>

                <!-- Tombol Aksi Utama -->
                <a href="{{ url('kasir/pos/success') }}"
                    class="w-full bg-[#CC9863] text-white py-4 rounded-2xl font-bold text-lg hover:bg-[#b58555] transition shadow-lg shadow-[#CC9863]/30 flex justify-center items-center gap-2 text-center">
                    Bayar Sekarang
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </a>
            </div>
        </div>

    </div>

    <!-- ================= JAVASCRIPT LOGIC ================= -->
    <script>
        // Inisialisasi State
        let cart = [];
        let discountRate = 0; // 0 artinya tidak ada diskon (Bisa diubah jadi 0.1 untuk 10%)

        // Fungsi Format Uang
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // Fungsi Tambah Barang ke Keranjang
        function addToCart(id, name, price) {
            let existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.qty += 1; // Jika barang sudah ada, tambah jumlahnya
            } else {
                cart.push({
                    id,
                    name,
                    price,
                    qty: 1
                }); // Jika belum, masukkan data baru
            }
            renderCart(); // Perbarui tampilan
        }

        // Fungsi Update Jumlah Barang (+ atau -)
        function updateQty(id, change) {
            let item = cart.find(item => item.id === id);
            if (item) {
                item.qty += change;
                if (item.qty <= 0) {
                    // Hapus barang jika jumlahnya 0
                    cart = cart.filter(i => i.id !== id);
                }
            }
            renderCart();
        }

        // Fungsi Perbarui Tampilan HTML Keranjang & Kalkulasi Total
        function renderCart() {
            const container = document.getElementById('cart-container');
            container.innerHTML = '';

            let subtotal = 0;
            let totalItems = 0;

            if (cart.length === 0) {
                container.innerHTML = `
                <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p class="text-sm font-semibold">Keranjang masih kosong</p>
                </div>
            `;
            }

            // Looping data cart untuk dibuat HTML-nya
            cart.forEach(item => {
                subtotal += item.price * item.qty;
                totalItems += item.qty;

                container.innerHTML += `
                <div class="flex gap-3 items-center py-3 border-b border-gray-50 last:border-0">
                    <div class="flex-1">
                        <h4 class="font-bold text-sm text-gray-900 line-clamp-1">${item.name}</h4>
                        <p class="text-[#CC9863] text-sm font-semibold mt-1">${formatRupiah(item.price)}</p>
                    </div>
                    <div class="flex items-center gap-2 bg-gray-50 p-1 rounded-xl border border-gray-200 shrink-0">
                        <button type="button" onclick="updateQty('${item.id}', -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-600 hover:text-red-500 hover:bg-red-50 transition font-bold">-</button>
                        <span class="text-sm font-bold w-5 text-center">${item.qty}</span>
                        <button type="button" onclick="updateQty('${item.id}', 1)" class="w-8 h-8 flex items-center justify-center bg-[#CC9863] rounded-lg shadow-sm text-white hover:bg-[#b58555] transition font-bold">+</button>
                    </div>
                </div>
            `;
            });

            // Hitung Diskon dan Total
            let discountAmount = subtotal * discountRate;
            let grandTotal = subtotal - discountAmount;

            // Tampilkan ke layar
            document.getElementById('subtotal-label').innerText = `Subtotal (${totalItems} Item)`;
            document.getElementById('subtotal-val').innerText = formatRupiah(subtotal);
            document.getElementById('discount-val').innerText = '- ' + formatRupiah(discountAmount);
            document.getElementById('total-val').innerText = formatRupiah(grandTotal);
        }

        // Fungsi Simulasi Pencarian Member
        function checkMember() {
            let phone = document.getElementById('member-phone').value;
            if (phone.length > 5) {
                // Simulasi jika member ditemukan
                document.getElementById('member-info').classList.remove('hidden');
                discountRate = 0.10; // Memberikan diskon 10%
                renderCart(); // Perbarui hitungan total
            } else {
                alert('Masukkan nomor HP yang valid (minimal 6 angka)!');
            }
        }

        // Fungsi Simulasi Hapus Pelanggan
        function removeMember() {
            document.getElementById('member-phone').value = '';
            document.getElementById('member-info').classList.add('hidden');
            discountRate = 0; // Hapus diskon
            renderCart(); // Perbarui hitungan total
        }
    </script>
@endsection
