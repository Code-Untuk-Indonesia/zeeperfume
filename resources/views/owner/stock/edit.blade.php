@extends('template.sidebar')
@section('title', 'Edit Produk')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}" class="hover:text-[#CC9863] transition font-semibold">Kelola Stok</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-bold">Edit Produk</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Edit: {{ $product->nama_produk }}</h1>
        <p class="text-gray-500 text-sm mt-1">Perbarui informasi dasar produk dan sesuaikan harga, satuan, serta stok untuk setiap varian.</p>
    </div>

    <form action="{{ url(auth()->user()->role->nama_role . '/stock/update/' . $product->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col xl:flex-row gap-6">
        @csrf

        <!-- ================= KIRI: VARIAN PRODUK (EXISTING) ================= -->
        <div class="flex-1 space-y-6">
            <div class="flex justify-between items-end mb-2 border-b border-gray-200 pb-4">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900">Daftar Varian Terekam</h2>
                    <p class="text-[11px] font-medium text-gray-500 mt-1">Berikut adalah varian yang terhubung dengan produk ini.</p>
                </div>
            </div>

            @foreach($product->variants as $variant)
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm relative transition-all duration-300 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">

                    <!-- Foto Varian Edit -->
                    <div class="md:col-span-12" x-data="{ imagePreview: '{{ !empty(trim($variant->image)) ? asset(trim($variant->image)) : '' }}' }">
                        <label class="block text-xs font-bold text-gray-700 mb-2">Foto Varian (Opsional)</label>
                        <div class="flex items-center gap-4">
                            <label class="flex flex-col items-center justify-center w-20 h-20 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 cursor-pointer hover:bg-gray-100 hover:border-[#CC9863] transition-all relative overflow-hidden group">
                                <template x-if="!imagePreview">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </div>
                                </template>
                                <template x-if="imagePreview">
                                    <div class="relative w-full h-full">
                                        <img :src="imagePreview" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-white text-[9px] font-bold uppercase tracking-wider">Ubah Foto</span>
                                        </div>
                                    </div>
                                </template>
                                <input type="file" name="variants[{{ $variant->id }}][image]" accept="image/png, image/jpeg, image/webp" class="hidden"
                                    @change="const file = $event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = (e) => imagePreview = e.target.result; reader.readAsDataURL(file); }">
                            </label>
                            <div class="text-[10px] text-gray-500 font-medium leading-relaxed">
                                <p>Biarkan kosong jika tidak ingin mengubah foto lama.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Varian -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Varian <span class="text-red-500">*</span></label>
                        <input type="text" name="variants[{{ $variant->id }}][name]" value="{{ $variant->nama_varian }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" required>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">SKU (Kode)</label>
                        <input type="text" name="variants[{{ $variant->id }}][sku]" value="{{ $variant->sku }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]">
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Satuan Produk <span class="text-red-500">*</span></label>
                        <select name="variants[{{ $variant->id }}][unit]" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white font-bold text-[#CC9863] focus:outline-none focus:ring-1 focus:ring-[#CC9863]" required>
                            <option value="pcs" {{ strtolower($variant->satuan) == 'pcs' ? 'selected' : '' }}>Pcs / Botol</option>
                            <option value="ml" {{ strtolower($variant->satuan) == 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
                            <option value="liter" {{ strtolower($variant->satuan) == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                            <option value="gram" {{ strtolower($variant->satuan) == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                        </select>
                    </div>

                    <!-- Harga -->
                    <div class="md:col-span-6">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Modal (Rp)</label>
                        <input type="number" name="variants[{{ $variant->id }}][cost]" value="{{ $variant->harga_modal ?? $variant->harga_beli }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]">
                    </div>
                    <div class="md:col-span-6">
                        <label class="block text-xs font-bold text-gray-900 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="variants[{{ $variant->id }}][price]" value="{{ $variant->harga_jual }}" class="w-full px-3 py-2.5 border border-[#CC9863]/50 rounded-lg bg-orange-50 font-black text-[#CC9863] focus:outline-none focus:ring-2 focus:ring-[#CC9863]" required>
                    </div>

                    <!-- Stok Area Edit -->
                    <div class="md:col-span-12 mt-2 pt-4 border-t border-dashed border-gray-200">
                        <label class="block text-xs font-bold text-gray-500 mb-3 uppercase tracking-wider">Update Stok Tersedia</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

                            <!-- Stok Pusat -->
                            @php $stokPusat = $variant->branchStocks->where('cabang_id', 1)->first()->stok ?? 0; @endphp
                            <div class="flex flex-col gap-1.5 p-3 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-[#CC9863] w-12 shrink-0">Pusat:</span>
                                    <input type="number" name="variants[{{ $variant->id }}][stock_pusat]" value="{{ $stokPusat }}" class="w-20 px-2 py-1.5 border border-gray-300 rounded-lg text-center font-bold focus:outline-none focus:border-[#CC9863]">
                                </div>
                                <div class="text-[9px] font-semibold text-gray-500 uppercase tracking-widest text-center mt-1">Gudang Utama</div>
                            </div>

                            <!-- Stok Cabang -->
                            @foreach($branches->where('id', '!=', 1) as $branch)
                                @php $stokCabang = $variant->branchStocks->where('cabang_id', $branch->id)->first()->stok ?? 0; @endphp
                                <div class="flex flex-col gap-2 p-3 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-[#CC9863]/50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-gray-700 truncate w-16" title="{{ $branch->nama_cabang }}">{{ explode(' ', trim($branch->nama_cabang))[0] }}:</span>
                                        <input type="number" name="variants[{{ $variant->id }}][branches][{{ $branch->id }}][stock]" value="{{ $stokCabang }}" class="w-16 px-2 py-1.5 border border-gray-200 rounded text-center text-xs font-bold focus:outline-none focus:border-[#CC9863]">
                                    </div>
                                    <select name="variants[{{ $variant->id }}][branches][{{ $branch->id }}][source]" class="w-full text-[9px] font-semibold bg-gray-50 border border-gray-200 rounded-md p-1.5 text-gray-600 focus:outline-none focus:border-[#CC9863] cursor-pointer">
                                        <option value="direct">Dari Supplier (Langsung)</option>
                                        <option value="from_pusat">Transfer dari Pusat</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- ================= KANAN: PENGATURAN KATEGORI & INFO PRODUK ================= -->
        <div class="w-full xl:w-[360px] space-y-6 shrink-0">

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative sticky top-6">
                <h2 class="text-base font-extrabold text-gray-900 mb-4 border-b border-gray-100 pb-3">Informasi Induk</h2>
                <div class="space-y-5">

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->kategori_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Nama Induk Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->nama_produk) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-900" required>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Deskripsi Singkat</label>
                        <textarea name="description" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition text-sm font-medium">{{ old('description', $product->deskripsi) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Visibilitas Kasir</label>
                        <select name="status" class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800">
                            <option value="ada_stok" {{ $product->tipe_stok == 'ada_stok' ? 'selected' : '' }}>Aktif (Tampil di POS)</option>
                            <option value="draft" {{ $product->tipe_stok == 'draft' ? 'selected' : '' }}>Draft (Sembunyikan)</option>
                        </select>
                    </div>

                    <!-- Submit Actions -->
                    <div class="pt-4 border-t border-gray-100 flex flex-col gap-3">
                        <button type="submit" class="w-full bg-[#CC9863] text-white py-4 rounded-xl font-extrabold text-sm hover:bg-[#b58555] transition-all shadow-xl shadow-[#CC9863]/20 flex justify-center items-center gap-2 transform active:scale-95 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            Update Produk
                        </button>
                        <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}" class="w-full bg-gray-50 text-gray-600 text-center py-3.5 rounded-xl font-bold text-sm hover:bg-gray-100 transition-colors border border-gray-200">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
@endsection
