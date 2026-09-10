@extends('template.sidebar')
@section('title', 'Buat Pesanan Online')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.transaction.index') }}" class="hover:text-[#CC9863] transition font-semibold">Riwayat Transaksi</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-bold">Pesanan Online Baru</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Buat Pesanan Online</h1>
        <p class="text-gray-500 text-sm mt-1">Input pesanan yang masuk melalui WhatsApp, E-Commerce, atau platform digital lainnya.</p>
    </div>

    <!-- Main Form -->
    <div class="flex flex-col xl:flex-row gap-6">

        <!-- ================= KOLOM KIRI ================= -->
        <div class="flex-1 space-y-6">

            <!-- Card 1: Informasi Pelanggan -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-5 border-b border-gray-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#CC9863] flex items-center justify-center shadow-inner">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Informasi Pembeli & Pengirim</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Cabang Asal -->
                    <div class="md:col-span-2 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <label class="block text-xs font-extrabold text-gray-800 uppercase tracking-wide mb-1.5">Cabang Pengirim (Sumber Stok) <span class="text-red-500">*</span></label>
                        <select id="input_cabang" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-white font-bold text-gray-900 focus:outline-none focus:border-[#CC9863]" onchange="clearCart()">
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->nama_cabang }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-500 mt-1">*Jika cabang diubah, keranjang akan dikosongkan.</p>
                    </div>

                    <!-- Pelanggan -->
                    <div class="md:col-span-2 relative">
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Pencarian Member Cepat</label>
                        <div class="flex gap-2">
                            <input type="number" id="search-member-input" class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-semibold text-sm placeholder-gray-400" placeholder="Ketik No. HP Member...">
                            <button type="button" onclick="checkMember()" class="px-5 py-3 bg-[#1C1D21] text-white font-bold rounded-xl hover:bg-black transition-colors shadow-sm shrink-0">Cari Data</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Nama Penerima <span class="text-red-500">*</span></label>
                        <input type="text" id="input_nama" class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-900" placeholder="Nama Lengkap">
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">No. WhatsApp / HP <span class="text-red-500">*</span></label>
                        <input type="number" id="input_telp" class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-900" placeholder="08...">
                    </div>
                </div>
            </div>

            <!-- Card 2: Pengiriman -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex items-center gap-3 mb-5 border-b border-gray-100 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center shadow-inner shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Detail Pengiriman & Platform</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Sumber Pesanan <span class="text-red-500">*</span></label>
                        <select id="input_sumber" class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800">
                            <option value="whatsapp">WhatsApp</option>
                            <option value="shopee">Shopee</option>
                            <option value="tokopedia">Tokopedia</option>
                            <option value="tiktok">TikTok Shop</option>
                            <option value="instagram">Instagram / DM</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Kurir Ekspedisi <span class="text-red-500">*</span></label>
                        <select id="input_kurir" class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800">
                            <option value="jnt">J&T Express</option>
                            <option value="jne">JNE</option>
                            <option value="sicepat">Sicepat</option>
                            <option value="gojek">Gojek / Grab (Instan)</option>
                            <option value="custom">Kurir Pribadi Toko</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Alamat Lengkap Pengiriman <span class="text-red-500">*</span></label>
                        <textarea id="input_alamat" rows="3" class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-white focus:outline-none focus:border-[#CC9863] transition font-medium text-sm text-gray-800 placeholder-gray-400" placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Catatan Tambahan</label>
                        <input type="text" id="input_catatan" class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-white focus:outline-none focus:border-[#CC9863] transition font-medium text-sm text-gray-800 placeholder-gray-400" placeholder="Packing extra, dsb...">
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= KOLOM KANAN ================= -->
        <div class="w-full xl:w-[450px] space-y-6 shrink-0 relative">

            <!-- AREA PENCARIAN PRODUK -->
            <div class="bg-[#1C1D21] p-1.5 rounded-2xl shadow-lg relative z-20">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="search-input" oninput="searchProducts()" class="w-full pl-12 pr-4 py-3.5 border-none rounded-xl text-sm font-bold focus:outline-none focus:ring-4 focus:ring-[#CC9863]/50 bg-white text-gray-900 transition-all placeholder-gray-400" placeholder="Ketik nama produk untuk keranjang...">

                    <button type="button" onclick="document.getElementById('search-input').value=''; searchProducts();" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-500 hidden" id="clear-search-btn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Hasil Search Dropdown -->
                <div id="search-results" class="hidden absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-2xl shadow-2xl overflow-hidden max-h-72 overflow-y-auto z-50">
                    <ul id="search-list" class="divide-y divide-gray-100">
                        <!-- Diisi via JS -->
                    </ul>
                </div>
            </div>

            <!-- Card 3: Keranjang -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col h-[350px]">
                <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-gray-50/50 rounded-t-3xl">
                    <h2 class="text-sm font-extrabold text-gray-900 uppercase tracking-wider">Keranjang Online</h2>
                    <span class="text-xs font-bold bg-[#CC9863] text-white px-2.5 py-1 rounded-lg shadow-sm" id="cart-counter">0 Item</span>
                </div>

                <!-- Daftar Keranjang -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3" id="cart-list">
                    <div class="h-full flex flex-col items-center justify-center text-gray-400">
                        <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <p class="text-xs font-semibold">Gunakan kolom pencarian di atas untuk memasukkan produk.</p>
                    </div>
                </div>
            </div>

            <!-- Card 4: Tagihan -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm sticky top-6">
                <h2 class="text-base font-extrabold text-gray-900 mb-4 border-b border-gray-100 pb-3">Ringkasan Pembayaran</h2>

                <div class="space-y-4 mb-6">
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Biaya Ongkir (Rp)</label>
                            <input type="number" id="input_ongkir" oninput="calculateTotal()" class="w-full px-3 py-2 border-2 border-gray-100 rounded-lg text-sm font-bold focus:outline-none focus:border-[#CC9863]" placeholder="0">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5 flex justify-between">
                                Diskon
                                <select id="diskon_type" onchange="calculateTotal()" class="bg-transparent border-none text-[9px] text-[#CC9863] p-0 font-bold focus:outline-none cursor-pointer">
                                    <option value="rupiah">Rp</option>
                                    <option value="persen">%</option>
                                </select>
                            </label>
                            <input type="number" id="input_diskon" oninput="calculateTotal()" class="w-full px-3 py-2 border-2 border-red-100 bg-red-50/30 rounded-lg text-sm font-bold text-red-600 focus:outline-none focus:border-red-400" placeholder="0">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-1.5">Metode Bayar</label>
                        <select id="input_metode" class="w-full px-3 py-2 border-2 border-gray-100 rounded-lg text-sm font-bold text-gray-800 focus:outline-none focus:border-[#CC9863]">
                            <option value="transfer">Transfer Bank</option>
                            <option value="ewallet">E-Wallet / Marketplace</option>
                            <option value="cod">COD (Bayar di Kurir)</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 mt-3">
                        <input type="checkbox" id="input_lunas" class="w-4 h-4 text-[#CC9863] rounded focus:ring-[#CC9863] cursor-pointer" checked>
                        <label for="input_lunas" class="text-xs font-bold text-gray-800 cursor-pointer select-none">Sudah Lunas (Uang diterima)</label>
                    </div>
                </div>

                <div class="border-t border-dashed border-gray-200 pt-4 mb-6">
                    <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1.5">
                        <span>Subtotal Produk</span>
                        <span id="label_subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1.5">
                        <span>Biaya Ongkir</span>
                        <span id="label_ongkir" class="font-bold text-gray-800">+ Rp 0</span>
                    </div>
                    <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1.5">
                        <span>Diskon Transaksi</span>
                        <span id="label_diskon" class="font-bold text-red-500">- Rp 0</span>
                    </div>
                    <div class="flex justify-between items-end mt-3 bg-[#FAFAFA] p-3 rounded-xl border border-gray-200">
                        <span class="font-extrabold text-gray-800 text-sm">Grand Total</span>
                        <span id="label_grandtotal" class="text-3xl font-black text-[#CC9863]">Rp 0</span>
                    </div>
                </div>

                <button type="button" id="btnSubmit" onclick="processOrder()" class="w-full bg-[#1C1D21] text-white py-4 rounded-2xl font-extrabold text-sm hover:bg-black transition-all shadow-xl shadow-black/10 flex justify-center items-center gap-2 transform active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Simpan & Proses Pesanan
                </button>
            </div>

        </div>
    </div>
</main>

<script>
    let cart = [];
    let searchTimeout = null;
    let selectedMemberId = null;

    const formatRupiah = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka || 0);

    function searchProducts() {
        const query = document.getElementById('search-input').value;
        const cabangId = document.getElementById('input_cabang').value;
        const resultBox = document.getElementById('search-results');
        const list = document.getElementById('search-list');
        const clearBtn = document.getElementById('clear-search-btn');

        if(query.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
            resultBox.classList.add('hidden');
            return;
        }

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetch(`{{ route('admin.transaction.search_product') }}?q=${query}&cabang_id=${cabangId}`)
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    if(data.length === 0) {
                        list.innerHTML = `
                            <li class="px-6 py-8 text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm font-bold text-gray-500">Tidak ada produk ditemukan.</p>
                            </li>`;
                    } else {
                        data.forEach(item => {
                            const isLowStock = item.stock < 5;
                            const stockColor = item.stock <= 0 ? 'text-red-500' : (isLowStock ? 'text-orange-500' : 'text-green-600');

                            const li = document.createElement('li');
                            li.className = 'px-5 py-3 hover:bg-orange-50 cursor-pointer flex justify-between items-center transition-colors group border-b border-gray-50 last:border-0';
                            li.innerHTML = `
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-xs font-black text-gray-500 group-hover:bg-[#CC9863] group-hover:text-white transition-colors">
                                        ${item.unit.toUpperCase()}
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-gray-900">${item.name}</span>
                                        <span class="text-[10px] font-extrabold uppercase tracking-wider ${stockColor}">
                                            Tersedia: ${item.stock} ${item.unit}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <b class="text-sm font-black text-[#CC9863] block">${formatRupiah(item.price)}</b>
                                </div>
                            `;
                            li.onclick = () => addToCart(item);
                            list.appendChild(li);
                        });
                    }
                    resultBox.classList.remove('hidden');
                });
        }, 300);
    }

    document.addEventListener('click', (e) => {
        if(!document.getElementById('search-results').contains(e.target) && e.target.id !== 'search-input') {
            document.getElementById('search-results').classList.add('hidden');
        }
    });

    function clearCart() {
        cart = [];
        renderCart();
    }

    function addToCart(product) {
        document.getElementById('search-results').classList.add('hidden');
        document.getElementById('search-input').value = '';
        document.getElementById('clear-search-btn').classList.add('hidden');

        if(product.stock <= 0) return AppFeedback.warning('Stok produk di cabang tersebut kosong!');

        let existing = cart.find(i => i.id === product.id);
        if(existing) {
            if(existing.qty + 1 > product.stock) return AppFeedback.warning('Stok gudang tidak cukup!');
            existing.qty += 1;
            if (existing.discountType === 'percent') updateItemDiscount(product.id, existing.discountInput);
        } else {
            cart.push({ ...product, qty: 1, itemDiscount: 0, discountType: 'rupiah', discountInput: '' });
        }
        renderCart();
    }

    function updateQty(id, change) {
        let item = cart.find(i => i.id === id);
        if(item) {
            if(change > 0 && item.qty + change > item.stock) return AppFeedback.warning('Stok gudang tidak cukup!');
            item.qty += change;
            if(item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            } else {
                if (item.discountType === 'percent') updateItemDiscount(id, item.discountInput);
            }
            renderCart();
        }
    }

    function removeCartItem(id) {
        cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    function toggleItemDiscountType(id) {
        const item = cart.find(i => i.id === id);
        if (!item) return;
        item.discountType = item.discountType === 'rupiah' ? 'percent' : 'rupiah';
        item.discountInput = 0;
        item.itemDiscount = 0;
        renderCart();
    }

    function updateItemDiscount(id, value) {
        const item = cart.find(i => i.id === id);
        if (!item) return;

        let discValue = parseFloat(value) || 0;
        if (discValue < 0) discValue = 0;

        let maxVal = item.price * item.qty;
        let discountNominal = 0;

        if (item.discountType === 'percent') {
            if (discValue > 100) { AppFeedback.warning('Diskon maksimal 100%'); discValue = 100; }
            item.discountInput = discValue;
            discountNominal = maxVal * (discValue / 100);
        } else {
            if (discValue > maxVal) { AppFeedback.warning('Diskon melebihi harga!'); discValue = maxVal; }
            item.discountInput = discValue;
            discountNominal = discValue;
        }

        item.itemDiscount = discountNominal;
        calculateTotal();
    }

    function renderCart() {
        const list = document.getElementById('cart-list');
        list.innerHTML = '';

        let subtotal = 0;
        let count = 0;

        if(cart.length === 0) {
            list.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-gray-400"><svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg><p class="text-xs font-semibold">Gunakan kolom pencarian di atas.</p></div>`;
        } else {
            cart.forEach(item => {
                let lineBase = item.price * item.qty;
                let lineDisc = item.itemDiscount || 0;
                let itemTotal = lineBase - lineDisc;
                subtotal += lineBase;
                count += item.qty;

                let discToggleText = item.discountType === 'percent' ? '%' : 'Rp';
                let discToggleClass = item.discountType === 'percent' ? 'bg-[#CC9863] text-white' : 'bg-orange-100 text-orange-700';

                list.innerHTML += `
                <div class="p-3 border border-gray-200 rounded-2xl bg-white shadow-sm flex flex-col gap-3 group relative hover:border-[#CC9863] transition-colors">
                    <div class="flex justify-between items-start gap-3">
                        <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-[#CC9863] font-black text-[10px] uppercase border border-orange-100 shrink-0">
                            ${item.unit}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 truncate">${item.name}</h4>
                            <p class="text-[10px] text-gray-500 font-bold mt-0.5">${formatRupiah(item.price)} x ${item.qty}</p>
                        </div>
                        <button type="button" onclick="removeCartItem(${item.id})" class="w-6 h-6 rounded bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors shrink-0" title="Hapus Produk">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="flex justify-between items-center bg-gray-50 p-2 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-lg p-0.5 shadow-sm">
                            <button type="button" onclick="updateQty(${item.id}, -1)" class="w-6 h-6 bg-gray-50 rounded text-gray-600 hover:text-gray-900 hover:bg-gray-200 text-xs font-bold transition">−</button>
                            <span class="w-6 text-center text-[11px] font-extrabold text-gray-800">${item.qty}</span>
                            <button type="button" onclick="updateQty(${item.id}, 1)" class="w-6 h-6 bg-gray-200 rounded text-gray-600 hover:text-gray-900 hover:bg-gray-300 text-xs font-bold transition">+</button>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-semibold line-through decoration-red-400">${item.itemDiscount > 0 ? formatRupiah(lineBase) : ''}</p>
                            <p class="text-sm font-black text-[#CC9863]">${formatRupiah(itemTotal)}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between bg-orange-50/50 p-2 rounded-lg border border-orange-100/50 mt-1">
                        <label class="text-[10px] font-bold text-orange-700 ml-1">Diskon Item</label>
                        <div class="flex items-center">
                            <button onclick="toggleItemDiscountType(${item.id})" class="px-2.5 py-1.5 text-[10px] font-black rounded-l border border-r-0 border-orange-200 transition-colors ${discToggleClass}">
                                ${discToggleText}
                            </button>
                            <input type="number" id="disc-input-${item.id}" value="${item.discountInput || ''}" placeholder="0" oninput="updateItemDiscount(${item.id}, this.value)" class="w-20 px-2 py-1 text-xs font-bold border border-orange-200 rounded-r text-right focus:outline-none focus:border-[#CC9863] bg-white text-orange-700 placeholder-orange-300 transition-colors">
                        </div>
                    </div>
                </div>`;
            });
        }

        document.getElementById('cart-counter').innerText = count + " Item";
        document.getElementById('label_subtotal').innerText = formatRupiah(subtotal);
        calculateTotal(subtotal);
    }

    function calculateTotal(passedSub = null) {
        let baseSubtotal = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
        let totalItemDiscount = cart.reduce((sum, i) => sum + (i.itemDiscount || 0), 0);
        let currentSubtotal = baseSubtotal - totalItemDiscount;

        let ongkir = parseFloat(document.getElementById('input_ongkir').value) || 0;
        let diskonInput = parseFloat(document.getElementById('input_diskon').value) || 0;
        let diskonType = document.getElementById('diskon_type').value;
        let finalDiskon = 0;

        if (diskonType === 'persen') {
            if(diskonInput > 100) diskonInput = 100;
            finalDiskon = currentSubtotal * (diskonInput / 100);
        } else {
            finalDiskon = diskonInput;
        }

        if(finalDiskon > (currentSubtotal + ongkir)) finalDiskon = (currentSubtotal + ongkir);

        let grandTotal = (currentSubtotal + ongkir) - finalDiskon;
        if(grandTotal < 0) grandTotal = 0;

        // Tampilkan hanya subtotal yang sudah dikurangi diskon item di label (biar tidak bingung)
        document.getElementById('label_subtotal').innerText = formatRupiah(currentSubtotal);
        document.getElementById('label_ongkir').innerText = `+ ${formatRupiah(ongkir)}`;
        document.getElementById('label_diskon').innerText = `- ${formatRupiah(finalDiskon)}`;
        document.getElementById('label_grandtotal').innerText = formatRupiah(grandTotal);
    }

    function checkMember() {
        const phone = document.getElementById('search-member-input').value;
        if (phone.length < 6) return AppFeedback.warning('Ketik nomor HP member terlebih dahulu.');

        fetch(`{{ url('kasir/pos/search-member') }}?phone=${phone}`, { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.found) {
                selectedMemberId = data.member.id;
                document.getElementById('input_nama').value = data.member.name;
                document.getElementById('input_telp').value = phone;
                AppFeedback.success('Member ditemukan: ' + data.member.name + '\n(Nama dan No HP otomatis terisi)');
            } else {
                AppFeedback.warning('Member tidak ditemukan dalam sistem.');
                selectedMemberId = null;
            }
        }).catch(() => AppFeedback.error('Gagal mencari member.'));
    }

    function processOrder() {
        if(cart.length === 0) return AppFeedback.warning('Keranjang tidak boleh kosong!');
        const nama = document.getElementById('input_nama').value;
        const telp = document.getElementById('input_telp').value;
        const alamat = document.getElementById('input_alamat').value;

        if(!nama || !telp || !alamat) return AppFeedback.warning('Kolom Nama, No. WhatsApp, dan Alamat Tujuan wajib diisi!');

        const btn = document.getElementById('btnSubmit');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Memproses...';
        btn.disabled = true;

        // Kalkulasi
        let baseSubtotal = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
        let totalItemDiscount = cart.reduce((sum, i) => sum + (i.itemDiscount || 0), 0);
        let currentSubtotal = baseSubtotal - totalItemDiscount;

        let diskonInput = parseFloat(document.getElementById('input_diskon').value) || 0;
        let diskonType = document.getElementById('diskon_type').value;
        let finalDiskon = diskonType === 'persen' ? (currentSubtotal * (diskonInput / 100)) : diskonInput;
        let ongkir = parseFloat(document.getElementById('input_ongkir').value) || 0;
        let grandTotal = (currentSubtotal + ongkir) - finalDiskon;

        const payload = {
            cabang_id: document.getElementById('input_cabang').value,
            member_id: selectedMemberId,
            nama_pelanggan: nama,
            no_telp: telp,
            sumber_pesanan: document.getElementById('input_sumber').value,
            kurir: document.getElementById('input_kurir').value,
            alamat: alamat,
            catatan: document.getElementById('input_catatan').value,
            ongkir: ongkir,
            diskon: finalDiskon,
            diskon_persen: diskonType === 'persen' ? diskonInput : 0,
            subtotal: currentSubtotal,
            total: grandTotal,
            metode_pembayaran: document.getElementById('input_metode').value,
            status_lunas: document.getElementById('input_lunas').checked,
            cart: cart,
            _token: '{{ csrf_token() }}'
        };

        if (payload.metode_pembayaran === 'cash_tempo' || payload.metode_pembayaran === 'tempo') {
            const besok = new Date();
            besok.setDate(besok.getDate() + 7);
            payload.cash_tempo = {
                tanggal_jatuh_tempo: besok.toISOString().split('T')[0],
                catatan_penagihan: 'Pembayaran tempo via Pesanan Online'
            };
        }

        fetch('{{ route("admin.transaction.store_online") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.location.href = data.redirect;
            } else {
                AppFeedback.error('Gagal: ' + data.message, { duration: 0 });
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        })
        .catch(err => {
            AppFeedback.error('Kesalahan server.', { duration: 0 });
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>
@endsection
