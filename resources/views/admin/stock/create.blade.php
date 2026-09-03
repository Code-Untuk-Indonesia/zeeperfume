@extends('template.sidebar')
@section('title', 'Tambah Produk / Biang Baru')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ url('admin/stock') }}" class="hover:text-[#CC9863] transition">Kelola Stok</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-semibold">Tambah Produk</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Produk / Biang Baru</h1>
        <p class="text-gray-500 text-sm mt-1">Pilih jenis produk untuk menyesuaikan satuan stok (Pcs atau Mililiter/Liter).</p>
    </div>

    <!-- Form Container -->
    <form action="{{ url('admin/stock') }}" method="POST" enctype="multipart/form-data" class="flex flex-col xl:flex-row gap-6">
        @csrf

        <!-- ================= KOLOM KIRI: INFORMASI UTAMA & STOK ================= -->
        <div class="flex-1 space-y-6">

            <!-- Card: Tipe Produk & Basic Info -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <!-- Toggle Tipe Produk -->
                <div class="mb-6 border-b border-gray-100 pb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-3">Tipe Barang yang Dijual <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="product_type" value="kemasan" class="peer sr-only" checked onchange="toggleProductType()">
                            <div class="p-4 border-2 border-gray-100 rounded-2xl peer-checked:border-[#CC9863] peer-checked:bg-orange-50 hover:bg-gray-50 transition flex items-start gap-3">
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-[#CC9863] peer-checked:bg-[#CC9863] flex shrink-0 mt-0.5 shadow-inner transition-colors"></div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">Parfum Kemasan (Botol)</h4>
                                    <p class="text-xs text-gray-500 mt-1">Dijual per botol/pcs dengan berbagai varian ukuran (30ml, 50ml, dll).</p>
                                </div>
                            </div>
                        </label>
                        <label class="cursor-pointer relative">
                            <input type="radio" name="product_type" value="refill" class="peer sr-only" onchange="toggleProductType()">
                            <div class="p-4 border-2 border-gray-100 rounded-2xl peer-checked:border-[#CC9863] peer-checked:bg-orange-50 hover:bg-gray-50 transition flex items-start gap-3">
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-[#CC9863] peer-checked:bg-[#CC9863] flex shrink-0 mt-0.5 shadow-inner transition-colors"></div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">Biang / Refill (ml)</h4>
                                    <p class="text-xs text-gray-500 mt-1">Stok dihitung dalam Liter/ml dan dijual per mililiter (ml) ke pelanggan.</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Parfum / Biang <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Baccarat Rouge 540" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi & Notes Aroma</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Top notes, middle notes, base notes..."></textarea>
                    </div>
                </div>
            </div>

            <!-- ================= MODE 1: PARFUM KEMASAN (VARIAN & PCS) ================= -->
            <div id="section-kemasan" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm transition-all duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Varian Ukuran Botol</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">Atur harga dan alokasi stok (dalam <span class="font-bold text-gray-700">Pcs</span>) untuk masing-masing ukuran.</p>
                    </div>
                    <button type="button" class="bg-orange-50 text-[#CC9863] px-4 py-2 rounded-xl text-xs font-bold hover:bg-orange-100 transition flex items-center gap-1.5 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Varian
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Item Varian -->
                    <div class="border border-gray-200 rounded-2xl p-4 bg-gray-50/50 relative group">
                        <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100" title="Hapus Varian">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Ukuran Botol <span class="text-red-500">*</span></label>
                                <input type="text" name="variant_name[]" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" placeholder="Contoh: 30ml">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">SKU Varian</label>
                                <input type="text" name="variant_sku[]" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" placeholder="PRFM-30">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Modal (Rp)</label>
                                <input type="number" name="variant_cost[]" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" placeholder="0">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="variant_price[]" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" placeholder="0">
                            </div>
                            <div class="md:col-span-12 border-t border-gray-200 pt-3 mt-1">
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Alokasi Stok (Pcs)</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-gray-700 w-12 shrink-0">Pusat:</span>
                                        <input type="number" name="stock_pusat_pcs[]" class="w-full px-2 py-1.5 border border-gray-200 rounded text-center focus:outline-none focus:border-[#CC9863]" placeholder="0 Pcs">
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-gray-700 w-12 shrink-0">Cabang 1:</span>
                                        <input type="number" name="stock_cb1_pcs[]" class="w-full px-2 py-1.5 border border-gray-200 rounded text-center focus:outline-none focus:border-[#CC9863]" placeholder="0 Pcs">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODE 2: BIANG / REFILL (ML/LITER) ================= -->
            <div id="section-refill" class="bg-white p-6 rounded-3xl border border-blue-100 bg-blue-50/10 shadow-sm hidden transition-all duration-300">
                <div class="mb-6 border-b border-blue-100 pb-4 flex items-start gap-3">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-xl mt-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Manajemen Biang (Refill)</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">Atur harga per Mililiter (ml) dan stok masuk dalam hitungan Mililiter (ml) atau Liter.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Harga Biang -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Modal <span class="text-blue-600 font-bold">per 1 ML</span> (Rp)</label>
                            <input type="number" name="refill_cost_per_ml" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Jual <span class="text-blue-600 font-bold">per 1 ML</span> (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="refill_price_per_ml" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="0">
                        </div>
                    </div>

                    <!-- Input Stok Biang -->
                    <div class="border border-blue-100 bg-white p-5 rounded-2xl">
                        <label class="block text-sm font-bold text-gray-700 mb-3 border-b border-gray-100 pb-2">Distribusi Stok Biang Saat Ini</label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Stok Pusat -->
                            <div>
                                <span class="text-xs font-semibold text-gray-500 block mb-1">Outlet Pusat:</span>
                                <div class="flex">
                                    <input type="number" name="refill_stock_pusat" class="w-full px-3 py-2 border border-gray-200 rounded-l-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-right" placeholder="0">
                                    <select name="refill_unit_pusat" class="bg-gray-100 border border-l-0 border-gray-200 rounded-r-lg px-2 text-sm font-semibold text-gray-700 focus:outline-none">
                                        <option value="ml">Mililiter (ml)</option>
                                        <option value="liter">Liter (L)</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Stok Cabang 1 -->
                            <div>
                                <span class="text-xs font-semibold text-gray-500 block mb-1">Cabang 1 (Mall):</span>
                                <div class="flex">
                                    <input type="number" name="refill_stock_cb1" class="w-full px-3 py-2 border border-gray-200 rounded-l-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 text-right" placeholder="0">
                                    <select name="refill_unit_cb1" class="bg-gray-100 border border-l-0 border-gray-200 rounded-r-lg px-2 text-sm font-semibold text-gray-700 focus:outline-none">
                                        <option value="ml">Mililiter (ml)</option>
                                        <option value="liter">Liter (L)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-3">*Sistem akan otomatis mengonversi Liter ke Mililiter (1 Liter = 1000 ml) di database.</p>
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
                    <input type="file" name="image" class="hidden">
                </div>
            </div>

            <!-- Kategori & Status -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pengaturan Katalog</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-semibold text-gray-700">Kategori</label>
                            <button type="button" onclick="document.getElementById('categoryModal').classList.remove('hidden')" class="text-xs text-[#CC9863] hover:underline font-semibold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Baru
                            </button>
                        </div>
                        <select name="category" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition">
                            <option value="">Pilih Kategori</option>
                            <option value="edp">Eau de Parfum (EDP)</option>
                            <option value="edt">Eau de Toilette (EDT)</option>
                            <option value="biang">Biang Murni (Khusus Refill)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Visibilitas</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-700">
                            <option value="active">Aktif (Tampil di Kasir)</option>
                            <option value="draft">Draft (Disembunyikan)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ url('admin/stock') }}" class="flex-1 bg-white border border-gray-200 text-center text-gray-700 py-3.5 rounded-2xl font-bold hover:bg-gray-50 transition shadow-sm">Batal</a>
                <button type="submit" class="flex-1 bg-[#1C1D21] text-white py-3.5 rounded-2xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-900/20">Simpan Data</button>
            </div>
        </div>
    </form>

    <!-- ================= MODAL TAMBAH KATEGORI ================= -->
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
                    <input type="text" id="newCategoryName" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Biang Premium">
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-100 transition shadow-sm">Batal</button>
                <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" class="px-5 py-2.5 bg-[#CC9863] text-white rounded-xl font-bold hover:bg-[#b58555] transition shadow-sm">Simpan</button>
            </div>
        </div>
    </div>

</main>

<!-- SCRIPT UNTUK TOGGLE KEMASAN vs REFILL -->
<script>
    function toggleProductType() {
        const type = document.querySelector('input[name="product_type"]:checked').value;
        const secKemasan = document.getElementById('section-kemasan');
        const secRefill = document.getElementById('section-refill');

        if (type === 'kemasan') {
            secKemasan.classList.remove('hidden');
            secRefill.classList.add('hidden');
            // Menambahkan/menghapus "required" attribute agar form bisa disubmit
            document.querySelectorAll('#section-kemasan input').forEach(el => el.disabled = false);
            document.querySelectorAll('#section-refill input').forEach(el => el.disabled = true);
        } else {
            secKemasan.classList.add('hidden');
            secRefill.classList.remove('hidden');
            document.querySelectorAll('#section-kemasan input').forEach(el => el.disabled = true);
            document.querySelectorAll('#section-refill input').forEach(el => el.disabled = false);
        }
    }

    // Inisialisasi saat halaman pertama kali dimuat
    document.addEventListener("DOMContentLoaded", function() {
        toggleProductType();
    });
</script>
@endsection
