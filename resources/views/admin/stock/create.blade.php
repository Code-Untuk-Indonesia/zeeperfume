@extends('template.sidebar')
@section('title', 'Tambah Produk Baru')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.stock.index') }}" class="hover:text-[#CC9863] transition font-semibold">Kelola Stok</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-bold">Tambah Produk</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Produk / Biang Baru</h1>
        <p class="text-gray-500 text-sm mt-1">Stok yang ditambahkan akan masuk ke Gudang Pusat terlebih dahulu secara default.</p>
    </div>

    <!-- Error Alert -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl font-semibold text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.stock.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col xl:flex-row gap-6">
        @csrf

        <!-- ================= KOLOM KIRI: INFORMASI UTAMA & STOK ================= -->
        <div class="flex-1 space-y-6">

            <!-- Card: Tipe Produk & Basic Info -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <!-- Toggle Tipe Produk -->
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <label class="block text-sm font-extrabold text-gray-900 mb-3">Tipe Barang yang Dijual <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="product_type" value="kemasan" class="peer sr-only" checked onchange="toggleProductType()">
                            <div class="p-4 border-2 border-gray-100 rounded-2xl peer-checked:border-[#CC9863] peer-checked:bg-orange-50 hover:bg-gray-50 transition flex items-start gap-3">
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-[#CC9863] peer-checked:bg-[#CC9863] flex shrink-0 mt-0.5 shadow-inner transition-colors"></div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">Parfum Kemasan (Botol)</h4>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">Dijual per botol/pcs (misal 30ml, 50ml).</p>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="product_type" value="refill" class="peer sr-only" onchange="toggleProductType()">
                            <div class="p-4 border-2 border-gray-100 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition flex items-start gap-3">
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex shrink-0 mt-0.5 shadow-inner transition-colors"></div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">Biang / Refill (ml)</h4>
                                    <p class="text-xs text-gray-500 mt-1 font-medium">Dijual eceran per mililiter (ml) ke pelanggan.</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Parfum / Biang <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition font-semibold text-gray-900" placeholder="Contoh: Baccarat Rouge 540" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi & Notes Aroma</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition font-medium text-gray-700" placeholder="Top notes, middle notes, base notes..."></textarea>
                    </div>
                </div>
            </div>

            <!-- ================= MODE 1: PARFUM KEMASAN (VARIAN & PCS) ================= -->
            <div id="section-kemasan" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Varian & Alokasi Stok</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5 font-medium">Stok akan masuk ke <span class="font-bold text-gray-800">Gudang Pusat</span>. Anda bisa mendistribusikan langsung ke cabang lain di bawah ini.</p>
                    </div>
                </div>

                <!-- Template 1 Varian -->
                <div class="border-2 border-gray-100 rounded-2xl p-5 bg-white relative">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Ukuran Botol <span class="text-red-500">*</span></label>
                            <input type="text" name="variant_name[]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 font-bold focus:outline-none focus:ring-2 focus:ring-[#CC9863]/30" placeholder="Contoh: 30ml">
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-gray-700 mb-1">SKU Varian</label>
                            <input type="text" name="variant_sku[]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 font-bold focus:outline-none focus:ring-2 focus:ring-[#CC9863]/30" placeholder="PRFM-30">
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Harga Modal (Rp)</label>
                            <input type="number" name="variant_cost[]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 font-bold focus:outline-none focus:ring-2 focus:ring-[#CC9863]/30" placeholder="0">
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="variant_price[]" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 font-bold focus:outline-none focus:ring-2 focus:ring-[#CC9863]/30" placeholder="0">
                        </div>

                        <!-- STOK AREA -->
                        <div class="md:col-span-12 mt-2 pt-4 border-t border-dashed border-gray-200">

                            <!-- Stok Pusat (Wajib) -->
                            <div class="flex items-center gap-4 bg-orange-50 p-4 rounded-xl border border-[#CC9863]/20 mb-4">
                                <div class="w-10 h-10 rounded-full bg-[#CC9863] text-white flex items-center justify-center shrink-0 shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-gray-800 uppercase tracking-wide">Stok Awal Gudang Pusat</label>
                                    <p class="text-[10px] text-gray-500 font-medium">Stok utama sebelum didistribusikan.</p>
                                </div>
                                <div class="relative w-32 shrink-0">
                                    <input type="number" name="stock_pusat_pcs[0]" class="w-full pl-3 pr-10 py-2.5 border-2 border-[#CC9863]/30 rounded-lg text-center font-black text-gray-900 focus:outline-none focus:border-[#CC9863]" placeholder="0" required>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">Pcs</span>
                                </div>
                            </div>

                            <!-- Distribusi ke Cabang (Toggle) -->
                            <div>
                                <label class="flex items-center gap-2 cursor-pointer mb-3">
                                    <input type="checkbox" onchange="toggleBranchPcs(0, this.checked)" class="w-4 h-4 text-[#CC9863] rounded focus:ring-[#CC9863]">
                                    <span class="text-sm font-bold text-gray-700">Distribusikan langsung ke cabang lain?</span>
                                </label>

                                <div id="branch-dist-pcs-0" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3 pl-6 border-l-2 border-gray-100">
                                    @foreach($branches->where('id', '!=', 1) as $branch)
                                        <div class="flex flex-col gap-2 p-3 border border-gray-100 rounded-xl bg-gray-50 hover:bg-white transition-colors">
                                            <div class="flex items-center justify-between">
                                                <label class="flex items-center gap-2 cursor-pointer flex-1">
                                                    <input type="checkbox" name="assign_branch_pcs[]" value="{{ $branch->id }}" class="w-4 h-4 text-[#CC9863] rounded focus:ring-[#CC9863]" onchange="document.getElementById('pcs_b{{$branch->id}}_0').disabled = !this.checked; document.getElementById('pcs_s{{$branch->id}}_0').disabled = !this.checked; document.getElementById('pcs_b{{$branch->id}}_0').focus();">
                                                    <span class="text-xs font-bold text-gray-800">{{ $branch->nama_cabang }}</span>
                                                </label>
                                                <div class="relative w-24 shrink-0">
                                                    <input type="number" name="stock_branch_pcs[0][{{ $branch->id }}]" id="pcs_b{{$branch->id}}_0" class="w-full px-2 py-1.5 border border-gray-300 rounded text-center text-sm font-bold disabled:bg-gray-200 disabled:opacity-50" placeholder="0" disabled>
                                                </div>
                                            </div>
                                            <!-- Pilihan Sumber Distribusi -->
                                            <select name="stock_source[0][{{ $branch->id }}]" id="pcs_s{{$branch->id}}_0" class="w-full text-[10px] font-semibold bg-white border border-gray-200 rounded-md p-1.5 text-gray-600 focus:outline-none focus:border-[#CC9863] disabled:bg-gray-200 disabled:opacity-50" disabled>
                                                <option value="direct">Dari Supplier Luar</option>
                                                <option value="from_pusat">Transfer dari Pusat</option>
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODE 2: BIANG / REFILL (ML/LITER) ================= -->
            <div id="section-refill" class="bg-white p-6 rounded-3xl border-2 border-blue-100 bg-blue-50/10 shadow-sm hidden transition-all duration-300">
                <div class="mb-6 border-b border-blue-100 pb-4 flex items-start gap-3">
                    <div class="bg-blue-100 text-blue-600 p-2.5 rounded-xl mt-1 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Manajemen Biang Murni (Refill)</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5 font-medium">Tetapkan harga per 1 ml. Stok masuk dalam satuan Liter akan otomatis dikonversi menjadi ml.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-700 mb-1">SKU Biang</label>
                            <input type="text" name="refill_sku" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white font-bold focus:outline-none focus:ring-2 focus:ring-blue-500/30" placeholder="BGN-001">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Harga Modal <span class="text-blue-600">per 1 ML</span></label>
                            <input type="number" name="refill_cost_per_ml" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white font-bold focus:outline-none focus:ring-2 focus:ring-blue-500/30" placeholder="0">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Harga Jual <span class="text-blue-600">per 1 ML</span> <span class="text-red-500">*</span></label>
                            <input type="number" name="refill_price_per_ml" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white font-bold text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30" placeholder="0">
                        </div>
                    </div>

                    <!-- STOK AREA -->
                    <div class="mt-2 pt-4 border-t border-dashed border-gray-200">

                        <!-- Stok Pusat (Wajib) -->
                        <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-xl border border-blue-200 mb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center shrink-0 shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-extrabold text-blue-900 uppercase tracking-wide">Stok Biang Pusat</label>
                            </div>
                            <div class="flex w-44 shrink-0">
                                <input type="number" name="refill_stock_pusat" class="w-full px-3 py-2 border-2 border-r-0 border-blue-200 rounded-l-lg bg-white text-center font-black text-gray-900 focus:outline-none focus:border-blue-500" placeholder="0">
                                <select name="refill_unit_pusat" class="bg-blue-100 border-2 border-l-0 border-blue-200 rounded-r-lg px-2 text-xs font-bold text-blue-800 focus:outline-none">
                                    <option value="ml">ml</option>
                                    <option value="liter">Liter</option>
                                </select>
                            </div>
                        </div>

                        <!-- Distribusi ke Cabang (Toggle) -->
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer mb-3">
                                <input type="checkbox" onchange="toggleBranchRefill(this.checked)" class="w-4 h-4 text-blue-500 rounded focus:ring-blue-500">
                                <span class="text-sm font-bold text-gray-700">Distribusikan langsung ke cabang lain?</span>
                            </label>

                            <div id="branch-dist-refill" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4 pl-6 border-l-2 border-gray-100">
                                @foreach($branches->where('id', '!=', 1) as $branch)
                                    <div class="flex flex-col gap-2 p-3 border border-gray-100 rounded-xl bg-white shadow-sm hover:border-blue-100 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="assign_branch_refill[]" value="{{ $branch->id }}" class="w-4 h-4 text-blue-500 rounded focus:ring-blue-500" onchange="document.getElementById('rfl_b{{$branch->id}}').disabled = !this.checked; document.getElementById('rfl_u{{$branch->id}}').disabled = !this.checked; document.getElementById('rfl_s{{$branch->id}}').disabled = !this.checked; document.getElementById('rfl_b{{$branch->id}}').focus();">
                                                <span class="text-xs font-bold text-gray-800">{{ $branch->nama_cabang }}</span>
                                            </label>
                                            <div class="flex h-9">
                                                <input type="number" name="refill_stock_branch[{{ $branch->id }}]" id="rfl_b{{$branch->id}}" class="w-full px-2 border border-r-0 border-gray-300 rounded-l-md text-center text-sm font-bold disabled:bg-gray-100 disabled:opacity-60" placeholder="0" disabled>
                                                <select name="refill_unit_branch[{{ $branch->id }}]" id="rfl_u{{$branch->id}}" class="bg-gray-50 border border-l-0 border-gray-300 rounded-r-md px-1.5 text-xs font-bold text-gray-600 disabled:bg-gray-100 disabled:opacity-60 focus:outline-none" disabled>
                                                    <option value="ml">ml</option>
                                                    <option value="liter">L</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Pilihan Sumber Distribusi -->
                                        <select name="refill_stock_source[{{ $branch->id }}]" id="rfl_s{{$branch->id}}" class="w-full text-[10px] font-bold bg-gray-50 border border-gray-200 rounded-lg p-2 text-gray-600 focus:outline-none focus:border-blue-500 cursor-pointer disabled:bg-gray-100 disabled:opacity-60" disabled>
                                            <option value="direct">Supplier Luar (Langsung)</option>
                                            <option value="from_pusat">Transfer dari Pusat</option>
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- ================= KOLOM KANAN: PENGATURAN KATEGORI (X-DATA) ================= -->
        <div class="w-full xl:w-[360px] space-y-6 shrink-0" x-data="categoryManager()">

            <!-- Kategori & Status -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative">
                <h2 class="text-base font-extrabold text-gray-900 mb-4 border-b border-gray-100 pb-3">Pengaturan Katalog</h2>
                <div class="space-y-5">

                    <!-- Dropdown Kategori -->
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide">Kategori <span class="text-red-500">*</span></label>

                            <!-- Tombol Trigger Modal Tambah Kategori -->
                            <button type="button" @click.prevent="openCategoryModal = true" class="text-[10px] bg-orange-50 text-[#CC9863] px-2 py-1 rounded font-bold hover:bg-orange-100 transition flex items-center gap-1 focus:outline-none">
                                + Baru
                            </button>
                        </div>

                        <select name="category_id" id="category_select" class="w-full px-4 py-3.5 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Status Visibilitas</label>
                        <select name="status" class="w-full px-4 py-3.5 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800">
                            <option value="ada_stok">Aktif (Tampil di POS Kasir)</option>
                            <option value="draft">Draft (Sembunyikan)</option>
                        </select>
                    </div>
                </div>

                <!-- ================= MODAL TAMBAH KATEGORI (ALPINE) ================= -->
                <!-- x-cloak untuk mencegah modal berkedip saat halaman dimuat -->
                <div x-cloak x-show="openCategoryModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="openCategoryModal = false" x-show="openCategoryModal" x-transition.opacity></div>

                    <!-- Modal Content -->
                    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform transition-all" x-show="openCategoryModal" x-transition.scale.90>
                        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="text-base font-bold text-gray-900">Tambah Kategori Baru</h3>
                            <button type="button" @click="openCategoryModal = false" class="text-gray-400 hover:text-red-500 font-bold text-xl leading-none focus:outline-none">&times;</button>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                                <input type="text" x-model="newCategoryName" @keydown.enter.prevent="saveCategory()" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 text-sm font-semibold transition" placeholder="Contoh: Parfum Pria">

                                <p x-show="errorMessage" x-text="errorMessage" x-transition class="text-xs text-red-500 mt-2 font-medium"></p>
                                <p x-show="successMessage" x-text="successMessage" x-transition class="text-xs text-green-600 mt-2 font-bold"></p>
                            </div>
                        </div>
                        <div class="p-5 border-t border-gray-100 bg-gray-50 flex gap-3">
                            <button type="button" @click="openCategoryModal = false" class="flex-1 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-100 transition focus:outline-none">Batal</button>
                            <button type="button" @click.prevent="saveCategory()" :disabled="isLoading" class="flex-1 py-2.5 bg-[#1C1D21] text-white rounded-xl font-bold text-sm hover:bg-black transition flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed focus:outline-none">
                                <span x-show="!isLoading">Simpan</span>
                                <span x-show="isLoading" class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
            </div>

            <!-- Action Buttons -->
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-3 sticky top-6">
                <button type="submit" class="w-full bg-[#1C1D21] text-white py-4 rounded-2xl font-extrabold text-sm hover:bg-black transition-all shadow-xl shadow-black/10 flex justify-center items-center gap-2 transform active:scale-95 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Produk
                </button>
                <a href="{{ route('admin.stock.index') }}" class="w-full bg-gray-50 text-gray-600 text-center py-3.5 rounded-2xl font-bold text-sm hover:bg-gray-100 transition-colors border border-gray-200">
                    Batal
                </a>
            </div>
        </div>
    </form>
</main>

<style>
    /* Menyembunyikan elemen AlpineJS sebelum dimuat */
    [x-cloak] { display: none !important; }
</style>

<script>
    // ==========================================
    // LOGIKA PLAIN JS UNTUK TOGGLE FORM
    // ==========================================
    function toggleProductType() {
        const type = document.querySelector('input[name="product_type"]:checked').value;
        const secKemasan = document.getElementById('section-kemasan');
        const secRefill = document.getElementById('section-refill');

        if (type === 'kemasan') {
            secKemasan.classList.remove('hidden');
            secRefill.classList.add('hidden');
            document.querySelectorAll('#section-kemasan input, #section-kemasan select').forEach(el => el.disabled = false);
            document.querySelectorAll('#section-refill input, #section-refill select').forEach(el => el.disabled = true);
            // Reset disabled pada cabang
            document.querySelectorAll('input[name="assign_branch_pcs[]"]').forEach(el => {
                if(!el.checked) {
                    const bInput = document.getElementById(`pcs_b${el.value}_0`);
                    const sInput = document.getElementById(`pcs_s${el.value}_0`);
                    if(bInput) bInput.disabled = true;
                    if(sInput) sInput.disabled = true;
                }
            });
        } else {
            secKemasan.classList.add('hidden');
            secRefill.classList.remove('hidden');
            document.querySelectorAll('#section-kemasan input, #section-kemasan select').forEach(el => el.disabled = true);
            document.querySelectorAll('#section-refill input, #section-refill select').forEach(el => el.disabled = false);
            // Reset disabled pada cabang refill
            document.querySelectorAll('input[name="assign_branch_refill[]"]').forEach(el => {
                if(!el.checked) {
                    const bInput = document.getElementById(`rfl_b${el.value}`);
                    const uInput = document.getElementById(`rfl_u${el.value}`);
                    const sInput = document.getElementById(`rfl_s${el.value}`);
                    if(bInput) bInput.disabled = true;
                    if(uInput) uInput.disabled = true;
                    if(sInput) sInput.disabled = true;
                }
            });
        }
    }

    function toggleBranchPcs(index, isChecked) {
        const container = document.getElementById(`branch-dist-pcs-${index}`);
        if(isChecked) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                const bInput = document.getElementById(`pcs_b${cb.value}_${index}`);
                const sInput = document.getElementById(`pcs_s${cb.value}_${index}`);
                if(bInput) { bInput.disabled = true; bInput.value = ''; }
                if(sInput) sInput.disabled = true;
            });
        }
    }

    function toggleBranchRefill(isChecked) {
        const container = document.getElementById(`branch-dist-refill`);
        if(isChecked) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                const bInput = document.getElementById(`rfl_b${cb.value}`);
                const uInput = document.getElementById(`rfl_u${cb.value}`);
                const sInput = document.getElementById(`rfl_s${cb.value}`);
                if(bInput) { bInput.disabled = true; bInput.value = ''; }
                if(uInput) uInput.disabled = true;
                if(sInput) sInput.disabled = true;
            });
        }
    }

    document.addEventListener("DOMContentLoaded", toggleProductType);

    // ==========================================
    // LOGIKA ALPINE JS UNTUK MODAL KATEGORI
    // ==========================================
    document.addEventListener('alpine:init', () => {
        Alpine.data('categoryManager', () => ({
            openCategoryModal: false,
            newCategoryName: '',
            isLoading: false,
            errorMessage: '',
            successMessage: '',

            async saveCategory() {
                if (!this.newCategoryName.trim()) {
                    this.errorMessage = 'Nama kategori tidak boleh kosong!';
                    this.successMessage = '';
                    return;
                }

                this.isLoading = true;
                this.errorMessage = '';
                this.successMessage = '';

                try {
                    const response = await fetch("{{ route('admin.category.storeAjax') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            nama_kategori: this.newCategoryName
                        })
                    });

                    const data = await response.json();
                    this.isLoading = false;

                    if (response.ok && data.success) {
                        this.successMessage = data.message;

                        // Tambahkan option baru ke select HTML & pilih otomatis
                        const selectEl = document.getElementById('category_select');
                        const newOption = new Option(data.category.nama_kategori, data.category.id, true, true);
                        selectEl.add(newOption);

                        // Tutup modal otomatis setelah 1 detik
                        setTimeout(() => {
                            this.openCategoryModal = false;
                            this.newCategoryName = '';
                            this.successMessage = '';
                        }, 1000);

                    } else {
                        this.errorMessage = data.message || 'Terjadi kesalahan.';
                    }
                } catch (error) {
                    this.isLoading = false;
                    this.errorMessage = 'Koneksi terputus. Pastikan controller & route sudah benar.';
                    console.error('Error:', error);
                }
            }
        }));
    });
</script>
@endsection
