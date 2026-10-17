@extends('template.sidebar')
@section('title', 'Edit Produk')

@php
    $existingProducts = \App\Models\Product::all();
    $categories = \App\Models\Category::all();
@endphp

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                    class="hover:text-[#CC9863] transition font-semibold">Kelola Stok</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#CC9863] font-bold">Edit Produk</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Edit: {{ $product->nama_produk }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi dasar, sesuaikan stok, atau pindahkan varian ke produk
                induk lain.</p>
        </div>

        <!-- NOTIFIKASI ERROR -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
                <div class="flex items-center gap-2 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    Gagal Menyimpan Data!
                </div>
                <p class="mt-1 text-sm font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    Validasi Form Gagal:
                </div>
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url(auth()->user()->role->nama_role . '/stock/update/' . $product->id) }}" method="POST"
            enctype="multipart/form-data" class="flex flex-col xl:flex-row gap-6">
            @csrf

            <!-- ================= KIRI: VARIAN PRODUK ================= -->
            <div class="flex-1 space-y-6">
                <div class="flex justify-between items-end mb-2 border-b border-gray-200 pb-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900">Daftar Varian Produk</h2>
                        <p class="text-[11px] font-medium text-gray-500 mt-1">Berikut adalah varian yang terhubung dengan
                            produk ini.</p>
                    </div>
                </div>

                <div id="variants-container">
                    @foreach($product->variants as $variant)
                        <div
                            class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm relative transition-all duration-300 mb-6 hover:border-[#CC9863]/30">

                            <div
                                class="absolute -top-3 -left-3 w-8 h-8 rounded-xl bg-[#CC9863] text-white flex items-center justify-center font-black text-sm shadow-md variant-number border-2 border-white">
                                {{ $loop->iteration }}
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 pt-2">
                                <!-- Foto Varian Edit -->
                                <div class="md:col-span-12" x-data="{ imagePreview: '{{ !empty(trim($variant->image)) ? asset(trim($variant->image)) : '' }}' }">
                                    <label class="block text-xs font-bold text-gray-700 mb-2">Foto Varian (Opsional)</label>
                                    <div class="flex items-center gap-4">
                                        <label
                                            class="flex flex-col items-center justify-center w-20 h-20 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 cursor-pointer hover:bg-gray-100 hover:border-[#CC9863] transition-all relative overflow-hidden group">
                                            <template x-if="!imagePreview">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CC9863]"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </div>
                                            </template>
                                            <template x-if="imagePreview">
                                                <div class="relative w-full h-full">
                                                    <img :src="imagePreview" class="w-full h-full object-cover">
                                                    <div
                                                        class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <span
                                                            class="text-white text-[9px] font-bold uppercase tracking-wider">Ubah</span>
                                                    </div>
                                                </div>
                                            </template>
                                            <input type="file" name="variants[{{ $variant->id }}][image]"
                                                accept="image/png, image/jpeg, image/webp" class="hidden"
                                                @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }">
                                        </label>
                                        <div class="text-[10px] text-gray-500 font-medium leading-relaxed">
                                            <p>Abaikan jika tidak ingin mengubah foto.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Info Varian -->
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Varian <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="variants[{{ $variant->id }}][name]"
                                        value="{{ old('variants.' . $variant->id . '.name', $variant->nama_varian) }}"
                                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                                        required>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">SKU (Kode)</label>
                                    <input type="text" name="variants[{{ $variant->id }}][sku]"
                                        value="{{ old('variants.' . $variant->id . '.sku', $variant->sku) }}"
                                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]">
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Satuan Produk <span
                                            class="text-red-500">*</span></label>
                                    <select name="variants[{{ $variant->id }}][unit]"
                                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white font-bold text-[#CC9863] focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                                        required>
                                        @php $satuan = old('variants.'.$variant->id.'.unit', strtolower($variant->satuan)); @endphp
                                        <option value="pcs" {{ $satuan == 'pcs' ? 'selected' : '' }}>Pcs / Botol
                                        </option>
                                        <option value="ml" {{ $satuan == 'ml' ? 'selected' : '' }}>Mililiter (ml)
                                        </option>
                                        <option value="liter" {{ $satuan == 'liter' ? 'selected' : '' }}>Liter (L)
                                        </option>
                                        <option value="gram" {{ $satuan == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                    </select>
                                </div>

                                <!-- ==================== HARGA RUPIAH FORMAT ==================== -->
                                <div class="md:col-span-6" x-data="currencyFormat('{{ old('variants.' . $variant->id . '.cost', $variant->harga_beli) }}')">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Modal (Rp)</label>
                                    <input type="text" x-model="formatted" @input="updateValue"
                                        class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                                        placeholder="0">
                                    <input type="hidden" name="variants[{{ $variant->id }}][cost]" x-model="raw">
                                </div>
                                <div class="md:col-span-6" x-data="currencyFormat('{{ old('variants.' . $variant->id . '.price', $variant->harga_jual) }}')">
                                    <label class="block text-xs font-bold text-gray-900 mb-1">Harga Jual (Rp) <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" x-model="formatted" @input="updateValue"
                                        class="w-full px-3 py-2.5 border border-[#CC9863]/50 rounded-lg bg-orange-50 font-black text-[#CC9863] focus:outline-none focus:ring-2 focus:ring-[#CC9863]"
                                        placeholder="0" required>
                                    <input type="hidden" name="variants[{{ $variant->id }}][price]" x-model="raw">
                                </div>

                                <!-- Stok Area Edit -->
                                <div class="md:col-span-12 mt-2 pt-4 border-t border-dashed border-gray-200">
                                    <label
                                        class="block text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">Update
                                        Stok Tersedia</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

                                        <!-- Stok Pusat -->
                                        @php $stokPusat = $variant->branchStocks->where('cabang_id', 1)->first()->stok ?? 0; @endphp
                                        <div
                                            class="flex flex-col gap-1.5 p-3 bg-gray-50 rounded-xl border border-gray-200 hover:border-[#CC9863]/40 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-xs font-extrabold text-[#CC9863] w-12 shrink-0">Pusat:</span>
                                                <input type="number" name="variants[{{ $variant->id }}][stock_pusat]"
                                                    value="{{ old('variants.' . $variant->id . '.stock_pusat', $stokPusat) }}"
                                                    class="w-20 px-2 py-1.5 border border-gray-300 rounded-lg text-center font-bold focus:outline-none focus:border-[#CC9863]"
                                                    placeholder="0">
                                            </div>
                                            <div
                                                class="text-[9px] font-semibold text-gray-500 uppercase tracking-widest text-center mt-1">
                                                Gudang Utama</div>
                                        </div>

                                        <!-- Stok Cabang -->
                                        @foreach($branches->where('id', '!=', 1) as $branch)
                                            @php $stokCabang = $variant->branchStocks->where('cabang_id', $branch->id)->first()->stok ?? 0; @endphp
                                            <div
                                                class="flex flex-col gap-2 p-3 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-[#CC9863]/50 transition-colors">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-[10px] font-bold text-gray-700 truncate w-16"
                                                        title="{{ $branch->nama_cabang }}">{{ explode(' ', trim($branch->nama_cabang))[0] }}:</span>
                                                    <input type="number"
                                                        name="variants[{{ $variant->id }}][branches][{{ $branch->id }}][stock]"
                                                        value="{{ old('variants.' . $variant->id . '.branches.' . $branch->id . '.stock', $stokCabang) }}"
                                                        class="w-16 px-2 py-1.5 border border-gray-200 rounded text-center text-xs font-bold focus:outline-none focus:border-[#CC9863]"
                                                        placeholder="0">
                                                </div>
                                                <select
                                                    name="variants[{{ $variant->id }}][branches][{{ $branch->id }}][source]"
                                                    class="w-full text-[9px] font-semibold bg-gray-50 border border-gray-200 rounded-md p-1.5 text-gray-600 focus:outline-none focus:border-[#CC9863] cursor-pointer">
                                                    @php $sumber = old('variants.'.$variant->id.'.branches.'.$branch->id.'.source'); @endphp
                                                    <option value="direct" {{ $sumber == 'direct' ? 'selected' : '' }}>
                                                        Dari Supplier (Langsung)</option>
                                                    <option value="from_pusat"
                                                        {{ $sumber == 'from_pusat' ? 'selected' : '' }}>Transfer dari Pusat
                                                    </option>
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="btn-add-variant"
                    class="w-full py-4 border-2 border-dashed border-gray-300 text-[#CC9863] font-bold rounded-2xl bg-orange-50/50 hover:bg-orange-50 hover:border-[#CC9863] transition-all flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Varian Baru ke Produk Ini
                </button>

                <!-- Template Hidden untuk Penambahan Varian JS -->
                <template id="variant-template">
                    @include('components.stock-variant-card', [
                        'index' => '__IDX__',
                        'variant' => [],
                        'branches' => $branches,
                    ])
                </template>
            </div>

            <!-- ================= KANAN: PENGATURAN KATEGORI & INFO PRODUK ================= -->
            <div class="w-full xl:w-[360px] space-y-6 shrink-0"
                x-data="productManager({{ json_encode($existingProducts ?? []) }}, {{ json_encode($categories ?? []) }})">

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative sticky top-6">
                    <h2 class="text-base font-extrabold text-gray-900 mb-4 border-b border-gray-100 pb-3">Informasi Induk
                        Produk</h2>
                    <div class="space-y-5">

                        <!-- KATEGORI DROPDOWN PENCARIAN -->
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" x-show="selectedCategoryId" @click.prevent="openEditCategory()"
                                        class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded font-bold hover:bg-blue-100 transition focus:outline-none">
                                        Edit
                                    </button>
                                    <button type="button" @click.prevent="openCreateCategory()"
                                        class="text-[10px] bg-orange-50 text-[#CC9863] px-2 py-1 rounded font-bold hover:bg-orange-100 transition focus:outline-none">
                                        + Baru
                                    </button>
                                </div>
                            </div>

                            <div @click.away="openCat = false" class="relative w-full">
                                <div @click="openCat = !openCat"
                                    class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 cursor-pointer flex justify-between items-center focus:bg-white focus:border-[#CC9863] transition font-bold text-gray-800">
                                    <span
                                        x-text="selectedCategoryId ? (categories.find(c => c.id == selectedCategoryId)?.nama_kategori || 'Pilih Kategori') : 'Pilih Kategori'"
                                        class="truncate pr-2"></span>
                                    <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform"
                                        :class="{ 'rotate-180': openCat }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>

                                <div x-cloak x-show="openCat"
                                    class="absolute z-20 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100">
                                    <div class="p-2 border-b border-gray-100 bg-white sticky top-0 z-10">
                                        <div class="relative">
                                            <svg class="absolute left-2.5 top-2 w-4 h-4 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <input type="text" x-model="searchCat"
                                                class="w-full pl-8 pr-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#CC9863] text-sm bg-gray-50 focus:bg-white transition-colors"
                                                placeholder="Cari Kategori..." @click.stop>
                                        </div>
                                    </div>
                                    <ul class="max-h-48 overflow-y-auto p-1.5 custom-scrollbar">
                                        <li @click="selectedCategoryId = ''; openCat = false; searchCat = ''; fetchProductsByCategory()"
                                            class="px-3 py-2 hover:bg-orange-50 cursor-pointer rounded-lg text-sm font-semibold text-gray-500">
                                            -- Kosongkan --</li>
                                        <template
                                            x-for="cat in categories.filter(c => c.nama_kategori.toLowerCase().includes(searchCat.toLowerCase()))"
                                            :key="cat.id">
                                            <li @click="selectedCategoryId = cat.id; openCat = false; searchCat = ''; fetchProductsByCategory()"
                                                class="px-3 py-2 hover:bg-orange-50 cursor-pointer rounded-lg text-sm font-bold text-gray-800"
                                                x-text="cat.nama_kategori"></li>
                                        </template>
                                        <li x-show="categories.filter(c => c.nama_kategori.toLowerCase().includes(searchCat.toLowerCase())).length === 0"
                                            class="px-3 py-3 text-center text-xs text-gray-400">Tidak ditemukan.</li>
                                    </ul>
                                </div>
                                <input type="hidden" name="category_id" :value="selectedCategoryId" required>
                            </div>
                        </div>

                        <!-- PRODUK INDUK DROPDOWN PENCARIAN -->
                        <div>
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">
                                Pilih Produk Induk <span class="text-red-500">*</span>
                            </label>

                            <div @click.away="openProd = false" class="relative w-full mb-2">
                                <div @click="openProd = !openProd"
                                    class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 cursor-pointer flex justify-between items-center focus:bg-white focus:border-[#CC9863] transition font-bold text-gray-800">
                                    <span
                                        x-text="selectedProductId ? (allProducts.find(p => p.id == selectedProductId)?.nama_produk || '-- Pisahkan Jadi Produk Baru --') : '-- Pisahkan Jadi Produk Baru --'"
                                        class="truncate pr-2"></span>
                                    <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform"
                                        :class="{ 'rotate-180': openProd }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>

                                <div x-cloak x-show="openProd"
                                    class="absolute z-20 w-full mt-1 bg-white border border-gray-100 rounded-xl shadow-xl overflow-hidden"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100">
                                    <div class="p-2 border-b border-gray-100 bg-white sticky top-0 z-10">
                                        <div class="relative">
                                            <svg class="absolute left-2.5 top-2 w-4 h-4 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <input type="text" x-model="searchProd"
                                                class="w-full pl-8 pr-3 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#CC9863] text-sm bg-gray-50 focus:bg-white transition-colors"
                                                placeholder="Cari Produk..." @click.stop>
                                        </div>
                                    </div>
                                    <ul class="max-h-48 overflow-y-auto p-1.5 custom-scrollbar">
                                        <li @click="selectedProductId = ''; openProd = false; searchProd = ''; handleProductSelection()"
                                            class="px-3 py-2 hover:bg-orange-50 cursor-pointer rounded-lg text-sm font-bold text-[#CC9863]">
                                            -- Pisahkan Jadi Produk Baru --</li>
                                        <template
                                            x-for="prod in filteredProducts.filter(p => p.nama_produk.toLowerCase().includes(searchProd.toLowerCase()))"
                                            :key="prod.id">
                                            <li @click="selectedProductId = prod.id; openProd = false; searchProd = ''; handleProductSelection()"
                                                class="px-3 py-2 hover:bg-orange-50 cursor-pointer rounded-lg text-sm font-bold text-gray-800 truncate"
                                                x-text="prod.nama_produk"></li>
                                        </template>
                                        <li x-show="filteredProducts.filter(p => p.nama_produk.toLowerCase().includes(searchProd.toLowerCase())).length === 0"
                                            class="px-3 py-3 text-center text-xs text-gray-400">Produk tidak ditemukan.
                                        </li>
                                    </ul>
                                </div>
                                <input type="hidden" name="product_id" :value="selectedProductId">
                            </div>
                        </div>

                        <!-- Nama Induk (Tampil jika Buat Baru / Tetap di Induk yg sama) -->
                        <div x-show="!selectedProductId || selectedProductId == currentProductId">
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Nama
                                Induk Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="productName"
                                :required="!selectedProductId || selectedProductId == currentProductId"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-900">
                        </div>

                        <!-- Deskripsi (Tampil jika Buat Baru / Tetap di Induk yg sama) -->
                        <div x-show="!selectedProductId || selectedProductId == currentProductId">
                            <label
                                class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Deskripsi
                                Singkat</label>
                            <textarea name="description" rows="3" x-model="productDescription"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition text-sm font-medium"></textarea>
                        </div>

                        <!-- Info Perpindahan Varian -->
                        <div x-cloak x-show="selectedProductId && selectedProductId != currentProductId"
                            class="p-3 bg-blue-50 border border-blue-100 rounded-xl">
                            <p class="text-xs text-blue-800 font-medium">Semua varian di atas akan <strong>dipindahkan</strong>
                                ke dalam produk induk <strong x-text="productName"></strong>.</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Visibilitas
                                Kasir</label>
                            <select name="status"
                                class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800">
                                <option value="ada_stok"
                                    {{ old('status', $product->tipe_stok) == 'ada_stok' ? 'selected' : '' }}>Aktif (Tampil di
                                    POS)</option>
                                <option value="draft" {{ old('status', $product->tipe_stok) == 'draft' ? 'selected' : '' }}>
                                    Draft (Sembunyikan)</option>
                            </select>
                        </div>

                        <!-- Submit Actions -->
                        <div class="pt-4 border-t border-gray-100 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full bg-[#1C1D21] text-white py-4 rounded-xl font-extrabold text-sm hover:bg-black transition-all shadow-lg flex justify-center items-center gap-2 transform active:scale-95 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update & Simpan
                            </button>
                            <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                                class="w-full bg-gray-50 text-gray-600 text-center py-3.5 rounded-xl font-bold text-sm hover:bg-gray-100 transition-colors border border-gray-200">
                                Batal
                            </a>
                        </div>
                    </div>

                    <!-- Modal Alpine Kategori -->
                    <div x-cloak x-show="openCategoryModal"
                        class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openCategoryModal = false"
                            x-show="openCategoryModal" x-transition.opacity></div>

                        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden"
                            x-show="openCategoryModal" x-transition.scale.90>
                            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <h3 class="text-base font-bold text-gray-900" x-text="isEditCategoryMode ? 'Edit Kategori' : 'Kategori Baru'"></h3>
                                <button type="button" @click="openCategoryModal = false"
                                    class="text-gray-400 hover:text-red-500 font-bold text-xl leading-none">&times;</button>
                            </div>

                            <div class="p-5 space-y-4">
                                <div>
                                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="newCategoryName"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-semibold"
                                        placeholder="Cth: Parfum Pria">
                                </div>
                                <div>
                                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-2">Nilai Poin (Per Item)</label>
                                    <input type="number" step="0.1" min="0" x-model="newCategoryPoint" @keydown.enter.prevent="saveCategory()"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-semibold"
                                        placeholder="Cth: 1 atau 0.5">
                                    <p class="text-[10px] text-gray-500 mt-1 font-medium">Isi 0 jika kategori ini tidak menghasilkan poin (misal: aksesoris).</p>

                                    <p x-show="errorMessage" x-text="errorMessage" class="text-xs text-red-500 mt-2 font-medium"></p>
                                    <p x-show="successMessage" x-text="successMessage" class="text-xs text-green-600 mt-2 font-bold"></p>
                                </div>
                            </div>

                            <div class="p-5 border-t border-gray-100 bg-gray-50 flex gap-3">
                                <button type="button" @click="openCategoryModal = false"
                                    class="flex-1 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-sm">Batal</button>
                                <button type="button" @click.prevent="saveCategory()" :disabled="isLoading"
                                    class="flex-1 py-2.5 bg-[#1C1D21] text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 disabled:opacity-50">
                                    <span x-show="!isLoading">Simpan</span>
                                    <span x-show="isLoading">Loading...</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </main>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        document.getElementById('btn-add-variant')?.addEventListener('click', function() {
            const container = document.getElementById('variants-container');
            const template = document.getElementById('variant-template').innerHTML;

            const newIndex = 'new_' + Date.now();
            const newHtml = template.replace(/__IDX__/g, newIndex);
            container.insertAdjacentHTML('beforeend', newHtml);

            updateVariantNumbers();
        });

        function updateVariantNumbers() {
            const numbers = document.querySelectorAll('#variants-container .variant-number');
            numbers.forEach((el, i) => {
                el.innerText = i + 1;
            });
        }
    </script>
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('currencyFormat', (initialValue) => ({
                raw: initialValue || '',
                formatted: '',

                init() {
                    if (this.raw) {
                        this.formatted = this.formatNumber(this.raw);
                    }
                },

                updateValue(e) {
                    let val = e.target.value.replace(/\D/g, '');
                    this.raw = val;
                    this.formatted = this.formatNumber(val);
                },

                formatNumber(number) {
                    if (!number) return '';
                    return new Intl.NumberFormat('id-ID').format(number);
                }
            }));

            Alpine.data('productManager', (allProducts, allCategories) => ({
                openCategoryModal: false,
                isEditCategoryMode: false,
                newCategoryName: '',
                newCategoryPoint: 0,
                isLoading: false,
                errorMessage: '',
                successMessage: '',

                openCat: false,
                searchCat: '',
                openProd: false,
                searchProd: '',

                allProducts: allProducts || [],
                categories: allCategories || [],
                filteredProducts: [],

                selectedCategoryId: '{{ old("category_id", $product->kategori_id ?? "") }}',
                selectedProductId: '{{ old("product_id", $product->id ?? "") }}',
                currentProductId: '{{ $product->id ?? "" }}',
                productName: {!! json_encode(old('name', $product->nama_produk ?? '')) !!},
                productDescription: {!! json_encode(old('description', $product->deskripsi ?? '')) !!},

                init() {
                    if (this.selectedCategoryId) {
                        this.filterProducts();
                    }
                    if (this.selectedProductId) {
                        this.handleProductSelection();
                    }
                },

                fetchProductsByCategory() {
                    this.filterProducts();
                    this.selectedProductId = ''; // Reset
                    this.productName = '';
                    this.productDescription = '';
                },

                filterProducts() {
                    if (this.selectedCategoryId) {
                        this.filteredProducts = this.allProducts.filter(p => p.kategori_id == this
                            .selectedCategoryId);
                    } else {
                        this.filteredProducts = [];
                    }
                },

                handleProductSelection() {
                    if (this.selectedProductId) {
                        const product = this.allProducts.find(p => p.id == this.selectedProductId);
                        if (product) {
                            this.productName = product.nama_produk;
                            this.productDescription = product.deskripsi;
                        }
                    } else {
                        this.productName = '';
                        this.productDescription = '';
                    }
                },

                openCreateCategory() {
                    this.isEditCategoryMode = false;
                    this.newCategoryName = '';
                    this.newCategoryPoint = 0;
                    this.errorMessage = '';
                    this.successMessage = '';
                    this.openCategoryModal = true;
                },

                openEditCategory() {
                    if (!this.selectedCategoryId) return;
                    const cat = this.categories.find(c => c.id == this.selectedCategoryId);
                    if (cat) {
                        this.isEditCategoryMode = true;
                        this.newCategoryName = cat.nama_kategori;
                        this.newCategoryPoint = cat.nilai_poin || 0;
                        this.errorMessage = '';
                        this.successMessage = '';
                        this.openCategoryModal = true;
                    }
                },

                async saveCategory() {
                    if (!this.newCategoryName.trim()) {
                        this.errorMessage = 'Nama kategori tidak boleh kosong!';
                        return;
                    }

                    this.isLoading = true;
                    this.errorMessage = '';
                    this.successMessage = '';

                    const isEdit = this.isEditCategoryMode;
                    const baseUrl = "{{ url(auth()->user()->role->nama_role . '/category/ajax') }}";
                    const url = isEdit ? `${baseUrl}/${this.selectedCategoryId}` : baseUrl;
                    const method = isEdit ? 'PUT' : 'POST';

                    try {
                        const response = await fetch(url, {
                                method: method,
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    nama_kategori: this.newCategoryName,
                                    nilai_poin: this.newCategoryPoint || 0
                                })
                            });

                        const data = await response.json();
                        this.isLoading = false;

                        if (response.ok && data.success) {
                            this.successMessage = data.message;

                            if (isEdit) {
                                const index = this.categories.findIndex(c => c.id == data.category.id);
                                if (index !== -1) {
                                    this.categories[index] = data.category;
                                }
                            } else {
                                this.categories.push(data.category);
                                this.selectedCategoryId = data.category.id;
                                this.filterProducts();
                            }

                            setTimeout(() => {
                                this.openCategoryModal = false;
                                this.newCategoryName = '';
                                this.newCategoryPoint = '';
                                this.successMessage = '';
                            }, 1000);
                        } else {
                            this.errorMessage = data.message || 'Terjadi kesalahan.';
                        }
                    } catch (error) {
                        this.isLoading = false;
                        this.errorMessage = 'Koneksi terputus. Pastikan internet stabil.';
                    }
                }
            }));
        });
    </script>
@endsection
