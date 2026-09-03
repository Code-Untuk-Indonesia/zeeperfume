@extends('template.sidebar')
@section('title', 'Tambah Produk Baru')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ url('admin/stock') }}" class="hover:text-[#CC9863] transition">Kelola Stok</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-semibold">Tambah Produk</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Produk Baru</h1>
        <p class="text-gray-500 text-sm mt-1">Masukkan detail informasi parfum, modal, harga jual, dan persediaan awal stok.</p>
    </div>

    <!-- Form Container -->
    <form action="{{ url('admin/stock') }}" method="POST" enctype="multipart/form-data" class="flex flex-col xl:flex-row gap-6">
        @csrf

        <!-- ================= KOLOM KIRI: INFORMASI UTAMA ================= -->
        <div class="flex-1 space-y-6">
            <!-- Card Basic Info -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Umum & Harga</h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Parfum <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Midnight Amber 50ml" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">SKU (Stock Keeping Unit)</label>
                            <input type="text" name="sku" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: PRF-001">
                        </div>

                        <!-- Bagian Harga Modal & Harga Jual -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Modal (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-semibold text-sm">Rp</span>
                                </div>
                                <input type="number" name="cost_price" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="0" required>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Hanya dilihat oleh Admin/Owner untuk hitung laba.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-semibold text-sm">Rp</span>
                                </div>
                                <input type="number" name="price" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="0" required>
                            </div>
                            <p class="text-[10px] text-[#CC9863] mt-1 font-medium">Harga yang akan tampil di Kasir (POS).</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Produk</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Tuliskan notes aroma (top, middle, base notes) atau deskripsi singkat parfum..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Card Varian & Stok (Multi Outlet support) -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Distribusi Stok Awal</h2>
                </div>

                <div class="bg-orange-50/50 p-4 rounded-2xl border border-orange-100 mb-4 flex gap-3">
                    <svg class="w-5 h-5 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-gray-600">Alokasikan stok awal ke masing-masing cabang. Biarkan kosong atau 0 jika tidak ada stok.</p>
                </div>

                <div class="space-y-3">
                    <!-- Outlet Pusat -->
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-xl bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-bold text-xs">PST</div>
                            <span class="font-semibold text-gray-700 text-sm">Outlet Pusat</span>
                        </div>
                        <div class="w-32 flex items-center gap-2">
                            <input type="number" name="stock_pusat" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-center focus:outline-none focus:border-[#CC9863]" placeholder="0" min="0">
                            <span class="text-xs text-gray-500 font-medium">Pcs</span>
                        </div>
                    </div>
                    <!-- Cabang 1 -->
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-xl bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center font-bold text-xs">CB1</div>
                            <span class="font-semibold text-gray-700 text-sm">Cabang 1</span>
                        </div>
                        <div class="w-32 flex items-center gap-2">
                            <input type="number" name="stock_cabang1" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-center focus:outline-none focus:border-[#CC9863]" placeholder="0" min="0">
                            <span class="text-xs text-gray-500 font-medium">Pcs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= KOLOM KANAN: MEDIA & PENGATURAN ================= -->
        <div class="w-full xl:w-[380px] space-y-6 shrink-0">

            <!-- Foto Produk -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Media Produk</h2>

                <div class="border-2 border-dashed border-gray-300 rounded-2xl bg-gray-50 p-6 flex flex-col items-center justify-center text-center hover:bg-gray-100 hover:border-[#CC9863] transition cursor-pointer group">
                    <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-8 h-8 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">Klik untuk unggah foto</p>
                    <p class="text-xs text-gray-400 mt-1">SVG, PNG, JPG (Max. 2MB)</p>
                    <input type="file" name="image" class="hidden"> <!-- Hidden input untuk JS trigger -->
                </div>
            </div>

            <!-- Kategori & Status -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pengaturan</h2>

                <div class="space-y-4">
                    <div>
                        <!-- Tombol trigger modal kategori -->
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-semibold text-gray-700">Kategori Parfum</label>
                            <button type="button" onclick="document.getElementById('categoryModal').classList.remove('hidden')" class="text-xs text-[#CC9863] hover:underline font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Kategori Baru
                            </button>
                        </div>
                        <select name="category" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition">
                            <option value="">Pilih Kategori</option>
                            <option value="edp">Eau de Parfum (EDP)</option>
                            <option value="edt">Eau de Toilette (EDT)</option>
                            <option value="bodymist">Body Mist</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Visibilitas</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-700">
                            <option value="active">Aktif (Tampil di Kasir & Web)</option>
                            <option value="draft">Draft (Disembunyikan)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ url('admin/stock') }}" class="flex-1 bg-white border border-gray-200 text-center text-gray-700 py-3.5 rounded-2xl font-bold hover:bg-gray-50 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="flex-1 bg-[#1C1D21] text-white py-3.5 rounded-2xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-900/20">
                    Simpan Produk
                </button>
            </div>
        </div>
    </form>

    <!-- ================= MODAL TAMBAH KATEGORI ================= -->
    <div id="categoryModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-900">Tambah Kategori Baru</h3>
                <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" id="newCategoryName" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Extrait de Parfum">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition shadow-sm">
                    Batal
                </button>
                <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" class="px-5 py-2.5 bg-[#CC9863] text-white rounded-xl font-bold hover:bg-[#b58555] transition shadow-sm">
                    Simpan Kategori
                </button>
            </div>
        </div>
    </div>

</main>
@endsection
