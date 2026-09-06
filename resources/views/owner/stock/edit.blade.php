@extends('template.sidebar')
@section('title', 'Edit Produk')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.stock.index') }}" class="hover:text-[#CC9863] transition font-semibold">Kelola Stok</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-bold">Edit Produk</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Edit: {{ $product->nama_produk }}</h1>
        <p class="text-gray-500 text-sm mt-1">Perbarui informasi induk parfum, sesuaikan harga, atau perbarui stok manual.</p>
    </div>

    <!-- Error Alert -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl font-semibold text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.stock.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col xl:flex-row gap-6">
        @csrf
        <!-- Karena tidak boleh ganti tipe produk setelah dibuat, kita kunci valuenya -->
        <input type="hidden" name="product_type" value="{{ $productType }}">

        <!-- ================= KOLOM KIRI: INFORMASI UTAMA & VARIAN ================= -->
        <div class="flex-1 space-y-6">

            <!-- Card Basic Info -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Informasi Induk Parfum</h2>
                    <span class="px-3 py-1 text-[10px] font-extrabold rounded-lg uppercase tracking-wider {{ $productType === 'kemasan' ? 'bg-orange-50 text-[#CC9863]' : 'bg-blue-50 text-blue-600' }}">
                        Mode: {{ $productType === 'kemasan' ? 'Kemasan (Botol)' : 'Biang (Refill)' }}
                    </span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Parfum / Biang <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->nama_produk) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition font-bold" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi & Notes Aroma</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition">{{ old('description', $product->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ================= KONDISI 1: JIKA PRODUK KEMASAN ================= -->
            @if($productType === 'kemasan')
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Varian Ukuran & Stok</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5">Edit harga dan update penyesuaian stok untuk varian ini.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($product->variants as $index => $variant)
                    <div class="border border-gray-200 rounded-2xl p-4 bg-gray-50/50 relative">
                        <input type="hidden" name="variant_id[{{ $index }}]" value="{{ $variant->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Ukuran / Nama Varian <span class="text-red-500">*</span></label>
                                <input type="text" name="variant_name[{{ $index }}]" value="{{ $variant->nama_varian }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white font-bold focus:outline-none focus:ring-1 focus:ring-[#CC9863]" required>
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">SKU Varian</label>
                                <input type="text" name="variant_sku[{{ $index }}]" value="{{ $variant->sku }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white font-bold focus:outline-none focus:ring-1 focus:ring-[#CC9863]">
                            </div>

                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Modal (Rp)</label>
                                <input type="number" name="variant_cost[{{ $index }}]" value="{{ $variant->harga_modal }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white font-bold focus:outline-none focus:ring-1 focus:ring-[#CC9863]">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="variant_price[{{ $index }}]" value="{{ $variant->harga_jual }}" class="w-full px-3 py-2.5 border border-gray-200 rounded-lg bg-white font-bold focus:outline-none focus:ring-1 focus:ring-[#CC9863]" required>
                            </div>

                            <div class="md:col-span-12 border-t border-gray-200 pt-3 mt-1">
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Update Stok Tersedia (Pcs)</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    
                                    <!-- Ambil stok pusat -->
                                    @php $stokPusat = $variant->branchStocks->where('cabang_id', 1)->first()->stok ?? 0; @endphp
                                    <div class="flex flex-col gap-1.5 p-2.5 bg-orange-50 rounded-xl border border-orange-200">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-extrabold text-[#CC9863] w-12 shrink-0">Pusat:</span>
                                            <input type="number" name="stock_pusat[{{ $index }}]" value="{{ $stokPusat }}" class="w-20 px-2 py-1.5 border border-orange-200 rounded-lg text-center font-bold focus:outline-none focus:border-[#CC9863]">
                                        </div>
                                        <div class="text-[9px] font-semibold text-orange-600/60 uppercase tracking-widest text-center mt-1">Stok Utama</div>
                                    </div>

                                    <!-- Ambil stok cabang lainnya -->
                                    @foreach($branches->where('id', '!=', 1) as $branch)
                                        @php $stokCabang = $variant->branchStocks->where('cabang_id', $branch->id)->first()->stok ?? 0; @endphp
                                        <div class="flex flex-col gap-1.5 p-2.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-[#CC9863]/50 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[10px] font-bold text-gray-700 truncate w-16" title="{{ $branch->nama_cabang }}">{{ explode(' ', trim($branch->nama_cabang))[0] }}:</span>
                                                <input type="number" name="stock_branch[{{ $index }}][{{ $branch->id }}]" value="{{ $stokCabang }}" class="w-16 px-2 py-1 border border-gray-200 rounded text-center text-xs font-bold focus:outline-none focus:border-[#CC9863]">
                                            </div>
                                            <!-- Pilihan Sumber Distribusi Edit -->
                                            <select name="stock_source[{{ $index }}][{{ $branch->id }}]" class="w-full text-[9px] font-semibold bg-gray-50 border border-gray-200 rounded-md p-1.5 text-gray-600 focus:outline-none focus:border-[#CC9863] cursor-pointer">
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
            </div>
            @endif

            <!-- ================= KONDISI 2: JIKA PRODUK REFILL ================= -->
            @if($productType === 'refill')
            @php $refillVariant = $product->variants->first(); @endphp
            <div class="bg-white p-6 rounded-3xl border-2 border-blue-100 bg-blue-50/10 shadow-sm transition-all duration-300">
                <input type="hidden" name="refill_variant_id" value="{{ $refillVariant->id }}">
                
                <div class="mb-6 border-b border-blue-100 pb-4 flex items-start gap-3">
                    <div class="bg-blue-100 text-blue-600 p-2.5 rounded-xl mt-1 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Penyesuaian Biang Murni</h2>
                        <p class="text-[11px] text-gray-500 mt-0.5 font-medium">Jika stok cabang ditambah melalui transfer pusat, otomatis stok pusat akan berkurang.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-700 mb-1">SKU Biang</label>
                            <input type="text" name="refill_sku" value="{{ $refillVariant->sku }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white font-bold focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Harga Modal <span class="text-blue-600">per ML</span></label>
                            <input type="number" name="refill_cost_per_ml" value="{{ $refillVariant->harga_modal }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white font-bold focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Harga Jual <span class="text-blue-600">per ML</span> <span class="text-red-500">*</span></label>
                            <input type="number" name="refill_price_per_ml" value="{{ $refillVariant->harga_jual }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-white font-bold text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500/30" required>
                        </div>
                    </div>

                    <!-- STOK AREA REFILL -->
                    <div class="mt-2 pt-4 border-t border-dashed border-gray-200">
                        @php $refillPusat = $refillVariant->branchStocks->where('cabang_id', 1)->first()->stok ?? 0; @endphp
                        
                        <div class="flex items-center gap-4 bg-blue-50 p-4 rounded-xl border border-blue-200 mb-4">
                            <div class="flex-1">
                                <label class="block text-xs font-extrabold text-blue-900 uppercase tracking-wide">Stok Biang Pusat (ml)</label>
                            </div>
                            <div class="flex w-32 shrink-0 relative">
                                <input type="number" name="refill_stock_pusat" value="{{ $refillPusat }}" class="w-full px-3 py-2 border-2 border-blue-200 rounded-lg bg-white text-center font-black text-gray-900 focus:outline-none focus:border-blue-500" placeholder="0">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-2">
                            @foreach($branches->where('id', '!=', 1) as $branch)
                                @php $refillCabang = $refillVariant->branchStocks->where('cabang_id', $branch->id)->first()->stok ?? 0; @endphp
                                <div class="flex flex-col gap-2 p-3 border border-gray-200 rounded-xl bg-white shadow-sm hover:border-blue-300 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-800">{{ $branch->nama_cabang }}</span>
                                        <div class="flex items-center gap-2">
                                            <input type="number" name="refill_stock_branch[{{ $branch->id }}]" value="{{ $refillCabang }}" class="w-24 px-2 py-1 border border-gray-300 rounded text-center text-sm font-bold focus:outline-none focus:border-blue-500" placeholder="0">
                                            <span class="text-xs font-bold text-gray-400">ml</span>
                                        </div>
                                    </div>
                                    <!-- Pilihan Sumber Distribusi Edit -->
                                    <select name="refill_stock_source[{{ $branch->id }}]" class="w-full text-[10px] font-bold bg-gray-50 border border-gray-200 rounded-lg p-2 text-gray-600 focus:outline-none focus:border-blue-500 cursor-pointer">
                                        <option value="direct">Supplier Luar (Langsung)</option>
                                        <option value="from_pusat">Transfer dari Pusat</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>

        <!-- ================= KOLOM KANAN: PENGATURAN KATEGORI & SUBMIT ================= -->
        <div class="w-full xl:w-[360px] space-y-6 shrink-0">

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-base font-extrabold text-gray-900 mb-4 border-b border-gray-100 pb-3">Pengaturan Katalog</h2>
                <div class="space-y-5">
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide">Kategori <span class="text-red-500">*</span></label>
                        </div>
                        <select name="category_id" class="w-full px-4 py-3.5 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->kategori_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Status Visibilitas</label>
                        <select name="status" class="w-full px-4 py-3.5 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800">
                            <option value="ada_stok" {{ $product->tipe_stok == 'ada_stok' ? 'selected' : '' }}>Aktif (Tampil di POS Kasir)</option>
                            <option value="draft" {{ $product->tipe_stok == 'draft' ? 'selected' : '' }}>Draft (Sembunyikan)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-3 sticky top-6">
                <button type="submit" class="w-full bg-[#CC9863] text-white py-4 rounded-2xl font-extrabold text-sm hover:bg-[#b58555] transition-all shadow-xl shadow-[#CC9863]/20 flex justify-center items-center gap-2 transform active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Update Produk
                </button>
                <a href="{{ route('admin.stock.index') }}" class="w-full bg-gray-50 text-gray-600 text-center py-3.5 rounded-2xl font-bold text-sm hover:bg-gray-100 transition-colors border border-gray-200">
                    Batal
                </a>
            </div>
        </div>
    </form>
</main>
@endsection