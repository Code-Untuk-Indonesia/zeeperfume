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
        <p class="text-gray-500 text-sm mt-1">Tambahkan informasi dasar produk beserta detail varian (ukuran/satuan) yang dimilikinya.</p>
    </div>

    <!-- NOTIFIKASI ERROR (Jika Gagal Simpan) -->
    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
            <div class="flex items-center gap-2 font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Gagal Menyimpan Data!
            </div>
            <p class="mt-1 text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
            <div class="flex items-center gap-2 font-bold mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Validasi Form Gagal:
            </div>
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- PENTING: Gunakan /store untuk Tambah Baru, bukan /update -->
    <form action="{{ url(auth()->user()->role->nama_role . '/stock/store') }}" method="POST"
        enctype="multipart/form-data" class="flex flex-col xl:flex-row gap-6">
        @csrf

        <!-- ================= KIRI: DETAIL VARIAN & STOK ================= -->
        <div class="flex-1 space-y-6">

            <div class="flex justify-between items-end mb-2 border-b border-gray-200 pb-4">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900">Detail Varian & Stok</h2>
                    <p class="text-[11px] font-medium text-gray-500 mt-1">Tentukan ukuran, harga, dan atur stok awal untuk produk ini.</p>
                </div>
            </div>

            <div id="variants-container">
                @php $oldVariants = old('variants', [0 => []]); @endphp
                @foreach($oldVariants as $index => $variant)
                    @include('components.stock-variant-card', ['index' => $index, 'variant' => $variant, 'branches' => $branches])
                @endforeach
            </div>

            <button type="button" id="btn-add-variant" class="w-full py-4 border-2 border-dashed border-gray-300 text-gray-500 font-bold rounded-2xl hover:bg-orange-50 hover:border-[#CC9863] hover:text-[#CC9863] transition-all flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Varian Lainnya
            </button>

            <!-- Template Hidden untuk Penambahan Varian JS -->
            <template id="variant-template">
                @include('components.stock-variant-card', ['index' => '__IDX__', 'variant' => [], 'branches' => $branches])
            </template>
        </div>

        <!-- ================= KANAN: PENGATURAN KATEGORI & INFO PRODUK ================= -->
        <div class="w-full xl:w-[360px] space-y-6 shrink-0" x-data="categoryManager()">

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative sticky top-6">
                <h2 class="text-base font-extrabold text-gray-900 mb-4 border-b border-gray-100 pb-3">Informasi Induk Produk</h2>
                <div class="space-y-5">

                    <!-- Kategori Setup -->
                    @include('components.stock-category-select', ['categories' => $categories])

                    <!-- Nama Induk -->
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">
                            Nama Induk Produk <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-900"
                            placeholder="Cth: Parfum Baccarat" required>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">
                            Deskripsi Singkat
                        </label>
                        <textarea name="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition text-sm font-medium"
                            placeholder="Wangi floral, vanilla...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">
                            Visibilitas Kasir
                        </label>
                        <select name="status"
                            class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800">
                            <option value="ada_stok" {{ old('status') == 'ada_stok' ? 'selected' : '' }}>Aktif (Tampil di POS)</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Sembunyikan)</option>
                        </select>
                    </div>

                    <!-- Submit Actions -->
                    <div class="pt-4 border-t border-gray-100 flex flex-col gap-3">
                        <button type="submit"
                            class="w-full bg-[#1C1D21] text-white py-4 rounded-xl font-extrabold text-sm hover:bg-black transition-all shadow-lg flex justify-center items-center gap-2 transform active:scale-95 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Produk Baru
                        </button>
                        <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                            class="w-full bg-gray-50 text-gray-600 text-center py-3.5 rounded-xl font-bold text-sm hover:bg-gray-100 transition-colors border border-gray-200">
                            Batal
                        </a>
                    </div>
                </div>

                <!-- Modal Alpine Kategori -->
                <div x-cloak x-show="openCategoryModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openCategoryModal = false" x-show="openCategoryModal" x-transition.opacity></div>

                    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden" x-show="openCategoryModal" x-transition.scale.90>
                        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="text-base font-bold text-gray-900">Kategori Baru</h3>
                            <button type="button" @click="openCategoryModal = false" class="text-gray-400 hover:text-red-500 font-bold text-xl leading-none">&times;</button>
                        </div>

                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-2">Nama Kategori</label>
                                <input type="text" x-model="newCategoryName" @keydown.enter.prevent="saveCategory()"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-semibold"
                                    placeholder="Cth: Parfum Pria">
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
    [x-cloak] { display: none !important; }
</style>

@include('components.stock-category-manager-script')
@include('components.stock-variant-manager-script')
@endsection
