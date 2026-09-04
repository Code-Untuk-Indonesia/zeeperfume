@extends('template.sidebar')
@section('title', 'Edit Produk')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ url('admin/stock') }}" class="hover:text-[#CC9863] transition">Kelola Stok</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-semibold">Edit Produk</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Produk: Midnight Amber</h1>
        <p class="text-gray-500 text-sm mt-1">Perbarui informasi induk parfum, sesuaikan harga, atau tambah varian ukuran baru.</p>
    </div>

    <!-- Form Container -->
    <!-- Ingat untuk menggunakan endpoint update nantinya, misal: action="{{ url('admin/stock/1') }}" -->
    <form action="#" method="POST" enctype="multipart/form-data" class="flex flex-col xl:flex-row gap-6">
        @csrf
        @method('PUT') <!-- Wajib untuk proses Update di Laravel -->

        <!-- ================= KOLOM KIRI: INFORMASI UTAMA & VARIAN ================= -->
        <div class="flex-1 space-y-6">

            <!-- Card Basic Info -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Induk Parfum</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Parfum <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="Midnight Amber" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi & Notes Aroma</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition">Top notes: Vanilla, Bergamot. Middle notes: Rose, Jasmine. Base notes: Musk, Amber. Wangi tahan lama hingga 12 jam.</textarea>
                    </div>
                </div>
            </div>

            <!-- Card Varian & Sub Produk -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Varian Ukuran / Sub-Produk</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">Atur harga dan alokasi stok untuk masing-masing ukuran.</p>
                    </div>
                    <button type="button" class="bg-orange-50 text-[#CC9863] px-4 py-2 rounded-xl text-xs font-bold hover:bg-orange-100 transition flex items-center gap-1.5 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Ukuran
                    </button>
                </div>

                <div class="space-y-4">

                    <!-- Item Varian 1 (Edit Data - 30ml) -->
                    <div class="border border-gray-200 rounded-2xl p-4 bg-gray-50/50 relative group">
                        <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100" title="Hapus Varian">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Ukuran / Nama Varian <span class="text-red-500">*</span></label>
                                <input type="text" name="variant_name[]" value="30ml" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" required>
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">SKU Varian</label>
                                <input type="text" name="variant_sku[]" value="PRFM-MA-30" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]">
                            </div>

                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Modal (Rp)</label>
                                <input type="number" name="variant_cost[]" value="80000" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="variant_price[]" value="150000" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" required>
                            </div>

                            <div class="md:col-span-12 border-t border-gray-200 pt-3 mt-1">
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Stok Tersedia Saat Ini</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-gray-700 w-12 shrink-0">Pusat:</span>
                                        <input type="number" name="stock_pusat[]" value="25" class="w-full px-2 py-1.5 border border-gray-200 rounded text-center focus:outline-none focus:border-[#CC9863]">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-gray-700 w-12 shrink-0">Cabang 1:</span>
                                        <input type="number" name="stock_cb1[]" value="12" class="w-full px-2 py-1.5 border border-gray-200 rounded text-center focus:outline-none focus:border-[#CC9863]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item Varian 2 (Edit Data - 50ml) -->
                    <div class="border border-gray-200 rounded-2xl p-4 bg-gray-50/50 relative group">
                        <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100" title="Hapus Varian">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Ukuran / Nama Varian <span class="text-red-500">*</span></label>
                                <input type="text" name="variant_name[]" value="50ml" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" required>
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">SKU Varian</label>
                                <input type="text" name="variant_sku[]" value="PRFM-MA-50" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]">
                            </div>

                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Modal (Rp)</label>
                                <input type="number" name="variant_cost[]" value="120000" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="variant_price[]" value="250000" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" required>
                            </div>

                            <div class="md:col-span-12 border-t border-gray-200 pt-3 mt-1">
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Stok Tersedia Saat Ini</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-gray-700 w-12 shrink-0">Pusat:</span>
                                        <input type="number" name="stock_pusat[]" value="15" class="w-full px-2 py-1.5 border border-gray-200 rounded text-center focus:outline-none focus:border-[#CC9863]">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-gray-700 w-12 shrink-0">Cabang 1:</span>
                                        <input type="number" name="stock_cb1[]" value="8" class="w-full px-2 py-1.5 border border-gray-200 rounded text-center focus:outline-none focus:border-[#CC9863]">
                                    </div>
                                </div>
                            </div>
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

                <!-- Tampilan jika foto sudah ada -->
                <div class="relative w-full h-48 bg-gray-100 rounded-2xl border-2 border-gray-200 mb-3 overflow-hidden group">
                    <!-- Dummy Image Preview -->
                    <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Produk" class="w-full h-full object-cover">

                    <!-- Overlay Ganti Foto -->
                    <label class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition cursor-pointer">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-sm font-bold">Ganti Foto</span>
                        <input type="file" name="image" class="hidden">
                    </label>
                </div>
                <p class="text-xs text-gray-400 text-center">Klik pada gambar untuk mengganti foto. (SVG, PNG, JPG Max. 2MB)</p>
            </div>

            <!-- Kategori & Status -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pengaturan Katalog</h2>

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-semibold text-gray-700">Kategori Parfum</label>
                            <button type="button" onclick="document.getElementById('categoryModal').classList.remove('hidden')" class="text-xs text-[#CC9863] hover:underline font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Kategori Baru
                            </button>
                        </div>
                        <select name="category" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition">
                            <option value="">Pilih Kategori</option>
                            <option value="edp" selected>Eau de Parfum (EDP)</option> <!-- Terpilih -->
                            <option value="edt">Eau de Toilette (EDT)</option>
                            <option value="bodymist">Body Mist</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Visibilitas</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-700">
                            <option value="active" selected>Aktif (Tampil di Kasir)</option> <!-- Terpilih -->
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
                    Update Produk
                </button>
            </div>
        </div>
    </form>

    <!-- ================= MODAL TAMBAH KATEGORI (Tetap Sama) ================= -->
    <div id="categoryModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-gray-900">Tambah Kategori Baru</h3>
                <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" id="newCategoryName" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Extrait de Parfum">
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition shadow-sm">Batal</button>
                <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" class="px-5 py-2.5 bg-[#CC9863] text-white rounded-xl font-bold hover:bg-[#b58555] transition shadow-sm">Simpan Kategori</button>
            </div>
        </div>
    </div>

</main>
@endsection
