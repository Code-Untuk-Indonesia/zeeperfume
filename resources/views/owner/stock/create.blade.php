@extends('template.sidebar')
@section('title', 'Tambah Produk Baru')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                    class="hover:text-[#CC9863] transition font-semibold">Kelola Stok</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#CC9863] font-bold">Tambah Produk</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Katalog Produk Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Tambahkan informasi dasar produk beserta varian (ukuran/satuan) yang
                dimilikinya.</p>
        </div>

        <form action="{{ url(auth()->user()->role->nama_role . '/stock/store') }}" method="POST"
            enctype="multipart/form-data" class="flex flex-col xl:flex-row gap-6">
            @csrf

            <!-- ================= KIRI: VARIAN PRODUK (DYNAMIC) ================= -->
            <div class="flex-1 space-y-6" x-data="variantManager()">

                <div class="flex justify-between items-end mb-2 border-b border-gray-200 pb-4">
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900">Daftar Varian</h2>
                        <p class="text-[11px] font-medium text-gray-500 mt-1">Tentukan ukuran, harga, dan atur stok untuk
                            setiap varian dari produk ini.</p>
                    </div>
                    <button type="button" @click="addVariant()"
                        class="bg-[#1C1D21] text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-black transition-colors shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Varian
                    </button>
                </div>

                <!-- Loop Varian AlpineJS -->
                <template x-for="(v, index) in variants" :key="v.id">
                    <div
                        class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm relative transition-all duration-300">

                        <button type="button" x-show="variants.length > 1" @click="removeVariant(v.id)"
                            class="absolute top-4 right-4 text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 p-1.5 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                            <!-- Foto Varian -->
                            <div class="md:col-span-12">
                                <label class="block text-xs font-bold text-gray-700 mb-2">Foto Varian (Opsional)</label>
                                <div class="flex items-center gap-4">
                                    <label
                                        class="flex flex-col items-center justify-center w-20 h-20 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 cursor-pointer hover:bg-gray-100 hover:border-[#CC9863] transition-all relative overflow-hidden group">
                                        <template x-if="!v.imagePreview">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CC9863]" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                        </template>
                                        <template x-if="v.imagePreview">
                                            <img :src="v.imagePreview" class="w-full h-full object-cover">
                                        </template>
                                        <!-- Input File Dynamic Name -->
                                        <input type="file" :name="`variants[${v.id}][image]`"
                                            accept="image/png, image/jpeg, image/webp" class="hidden"
                                            @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => v.imagePreview = e.target.result; reader.readAsDataURL(file); }">
                                    </label>
                                    <div class="text-[10px] text-gray-500 font-medium leading-relaxed">
                                        <p>Format yang didukung: <span class="font-bold">JPG, PNG, WEBP</span> (Max 2MB).
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Varian -->
                            <div class="md:col-span-4">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Varian <span
                                        class="text-red-500">*</span></label>
                                <input type="text" :name="`variants[${v.id}][name]`"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                                    placeholder="Cth: 30ml / Refill Murni" required>
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">SKU (Kode)</label>
                                <input type="text" :name="`variants[${v.id}][sku]`"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                                    placeholder="PRFM-30">
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Satuan Produk <span
                                        class="text-red-500">*</span></label>
                                <select :name="`variants[${v.id}][unit]`"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white font-bold text-[#CC9863] focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                                    required>
                                    <option value="pcs">Pcs / Botol</option>
                                    <option value="ml">Mililiter (ml)</option>
                                    <option value="liter">Liter (L)</option>
                                    <option value="gram">Gram (g)</option>
                                </select>
                            </div>

                            <!-- Harga -->
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Modal (Rp)</label>
                                <input type="number" :name="`variants[${v.id}][cost]`"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]"
                                    placeholder="0">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-bold text-gray-900 mb-1">Harga Jual (Rp) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" :name="`variants[${v.id}][price]`"
                                    class="w-full px-3 py-2.5 border border-[#CC9863]/50 rounded-lg bg-orange-50 font-black text-[#CC9863] focus:outline-none focus:ring-2 focus:ring-[#CC9863]"
                                    placeholder="0" required>
                            </div>

                            <!-- Stok Area -->
                            <div class="md:col-span-12 mt-2 pt-4 border-t border-dashed border-gray-200">

                                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200 mb-4">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-gray-800 uppercase tracking-wide">Stok
                                            Awal Gudang Pusat</label>
                                        <p class="text-[9px] text-gray-500 font-medium">Stok master sebelum
                                            didistribusikan.</p>
                                    </div>
                                    <div class="relative w-28 shrink-0">
                                        <input type="number" :name="`variants[${v.id}][stock_pusat]`"
                                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-center font-black text-gray-900 focus:outline-none focus:border-[#CC9863]"
                                            placeholder="0" required>
                                    </div>
                                </div>

                                <!-- Toggle Distribusi -->
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer mb-3">
                                        <input type="checkbox" :name="`variants[${v.id}][distribute]`"
                                            x-model="v.showBranches"
                                            class="w-4 h-4 text-[#CC9863] rounded focus:ring-[#CC9863]">
                                        <span class="text-sm font-bold text-gray-700">Distribusikan langsung ke cabang
                                            lain?</span>
                                    </label>

                                    <div x-show="v.showBranches" x-transition
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-3 pl-6 border-l-2 border-gray-100">
                                        @foreach ($branches->where('id', '!=', 1) as $branch)
                                            <div class="flex flex-col gap-2 p-3 border border-gray-100 rounded-xl bg-white shadow-sm hover:border-[#CC9863]/30 transition-colors"
                                                x-data="{ isAssigned: false }">
                                                <div class="flex items-center justify-between">
                                                    <label class="flex items-center gap-2 cursor-pointer flex-1">
                                                        <input type="checkbox"
                                                            :name="`variants[${v.id}][branches][{{ $branch->id }}][assign]`"
                                                            x-model="isAssigned" value="1"
                                                            class="w-4 h-4 text-[#CC9863] rounded focus:ring-[#CC9863]">
                                                        <span class="text-xs font-bold text-gray-800 truncate"
                                                            title="{{ $branch->nama_cabang }}">{{ $branch->nama_cabang }}</span>
                                                    </label>
                                                    <div class="relative w-20 shrink-0">
                                                        <input type="number"
                                                            :name="`variants[${v.id}][branches][{{ $branch->id }}][stock]`"
                                                            class="w-full px-2 py-1.5 border border-gray-300 rounded text-center text-sm font-bold disabled:bg-gray-100 disabled:opacity-50"
                                                            placeholder="0" :disabled="!isAssigned">
                                                    </div>
                                                </div>
                                                <select :name="`variants[${v.id}][branches][{{ $branch->id }}][source]`"
                                                    class="w-full text-[10px] font-semibold bg-gray-50 border border-gray-200 rounded-md p-1.5 text-gray-600 focus:outline-none focus:border-[#CC9863] disabled:bg-gray-100 disabled:opacity-50"
                                                    :disabled="!isAssigned">
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
                </template>
            </div>

            <!-- ================= KANAN: PENGATURAN KATEGORI & INFO PRODUK ================= -->
            <div class="w-full xl:w-[360px] space-y-6 shrink-0" x-data="categoryManager()">

                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative sticky top-6">
                    <h2 class="text-base font-extrabold text-gray-900 mb-4 border-b border-gray-100 pb-3">Informasi Induk
                    </h2>
                    <div class="space-y-5">

                        <!-- Kategori Setup -->
                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide">Kategori
                                    <span class="text-red-500">*</span></label>
                                <button type="button" @click.prevent="openCategoryModal = true"
                                    class="text-[10px] bg-orange-50 text-[#CC9863] px-2 py-1 rounded font-bold hover:bg-orange-100 transition focus:outline-none">+
                                    Baru</button>
                            </div>
                            <select name="category_id" id="category_select"
                                class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama & Deskripsi Induk -->
                        <div>
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Nama
                                Induk Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="name"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-900"
                                placeholder="Cth: Baccarat Rouge" required>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Deskripsi
                                Singkat</label>
                            <textarea name="description" rows="2"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition text-sm font-medium"
                                placeholder="Wangi floral, vanilla..."></textarea>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Visibilitas
                                Kasir</label>
                            <select name="status"
                                class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800">
                                <option value="ada_stok">Aktif (Tampil di POS)</option>
                                <option value="draft">Draft (Sembunyikan)</option>
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
                                Simpan Produk
                            </button>
                            <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                                class="w-full bg-gray-50 text-gray-600 text-center py-3.5 rounded-xl font-bold text-sm hover:bg-gray-100 transition-colors border border-gray-200">
                                Batal
                            </a>
                        </div>
                    </div>

                    <!-- Modal Alpine untuk Kategori -->
                    <div x-cloak x-show="openCategoryModal"
                        class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openCategoryModal = false"
                            x-show="openCategoryModal" x-transition.opacity></div>
                        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden"
                            x-show="openCategoryModal" x-transition.scale.90>
                            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                <h3 class="text-base font-bold text-gray-900">Kategori Baru</h3>
                                <button type="button" @click="openCategoryModal = false"
                                    class="text-gray-400 hover:text-red-500 font-bold text-xl leading-none">&times;</button>
                            </div>
                            <div class="p-5 space-y-4">
                                <div>
                                    <label
                                        class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-2">Nama
                                        Kategori</label>
                                    <input type="text" x-model="newCategoryName"
                                        @keydown.enter.prevent="saveCategory()"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-semibold"
                                        placeholder="Cth: Parfum Pria">
                                    <p x-show="errorMessage" x-text="errorMessage"
                                        class="text-xs text-red-500 mt-2 font-medium"></p>
                                    <p x-show="successMessage" x-text="successMessage"
                                        class="text-xs text-green-600 mt-2 font-bold"></p>
                                </div>
                            </div>
                            <div class="p-5 border-t border-gray-100 bg-gray-50 flex gap-3">
                                <button type="button" @click="openCategoryModal = false"
                                    class="flex-1 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-sm">Batal</button>
                                <button type="button" @click.prevent="saveCategory()" :disabled="isLoading"
                                    class="flex-1 py-2.5 bg-[#1C1D21] text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2">
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
        document.addEventListener('alpine:init', () => {
            // Logika Dinamis Varian Produk
            Alpine.data('variantManager', () => ({
                variants: [{
                    id: Date.now(),
                    showBranches: false,
                    imagePreview: null
                }],
                addVariant() {
                    this.variants.push({
                        id: Date.now(),
                        showBranches: false,
                        imagePreview: null
                    });
                },
                removeVariant(id) {
                    this.variants = this.variants.filter(v => v.id !== id);
                }
            }));

            // Logika Tambah Kategori AJAX
            Alpine.data('categoryManager', () => ({
                openCategoryModal: false,
                newCategoryName: '',
                isLoading: false,
                errorMessage: '',
                successMessage: '',
                async saveCategory() {
                    if (!this.newCategoryName.trim()) {
                        this.errorMessage = 'Nama kategori tidak boleh kosong!';
                        return;
                    }
                    this.isLoading = true;
                    this.errorMessage = '';
                    this.successMessage = '';
                    try {
                        const response = await fetch(
                            "{{ url(auth()->user()->role->nama_role . '/category/ajax') }}", {
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
                            const selectEl = document.getElementById('category_select');
                            selectEl.add(new Option(data.category.nama_kategori, data.category.id,
                                true, true));
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
                        this.errorMessage = 'Koneksi terputus.';
                    }
                }
            }));
        });
    </script>
@endsection
