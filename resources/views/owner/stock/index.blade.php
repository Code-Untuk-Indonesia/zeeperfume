@extends('template.sidebar')
@section('title', 'Kelola Stok Barang')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Kelola Stok Barang</h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Pantau dan kelola persediaan produk parfum di semua cabang outlet Anda.</p>
            </div>
            <a href="{{ route('owner.stock.create') }}"
                class="bg-[#CC9863] text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-[#CC9863]/20 hover:bg-[#b58555] transition-all transform active:scale-95 flex items-center justify-center gap-2 text-sm shrink-0 w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Produk
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <!-- Stat 1 -->
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                <div class="bg-blue-50 text-blue-500 p-3.5 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider">Total Produk Induk</p>
                    <h4 class="text-xl font-black text-gray-900 mt-0.5">{{ number_format($totalProducts ?? 0) }} Item</h4>
                </div>
            </div>
            <!-- Stat 2 -->
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                <div class="bg-red-50 text-red-500 p-3.5 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider">Varian Stok Menipis</p>
                    <h4 class="text-xl font-black text-red-600 mt-0.5">{{ $lowStockCount ?? 0 }} Item <span class="text-[11px] text-gray-400 font-medium ml-1">(&lt; 10 qty)</span></h4>
                </div>
            </div>
            <!-- Stat 3 -->
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                <div class="bg-green-50 text-green-500 p-3.5 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider">Status Database</p>
                    <h4 class="text-xl font-black text-green-600 mt-0.5">Real-time</h4>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <form action="{{ route('owner.stock.index') }}" method="GET" class="bg-white p-5 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col lg:flex-row gap-4 justify-between items-center">

            <!-- Search -->
            <div class="relative w-full lg:w-96">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl font-semibold bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 text-sm transition"
                    placeholder="Cari nama parfum atau SKU..." onblur="this.form.submit()">
            </div>

            <!-- Filters -->
            <div class="flex gap-3 w-full lg:w-auto">
                <select name="kategori" onchange="this.form.submit()" class="block w-full lg:w-56 px-4 py-3 text-sm font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863] transition cursor-pointer">
                    <option value="all">Semua Kategori</option>
                    @if(isset($categories))
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    @endif
                </select>

                {{-- Reset Button --}}
                @if (request('search') || request('kategori') && request('kategori') !== 'all')
                    <a href="{{ route('owner.stock.index') }}" class="px-5 py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl text-sm font-bold transition flex items-center justify-center shrink-0">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>

        <!-- Table Section -->
        <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-500 text-xs font-extrabold uppercase tracking-wider border-y border-gray-200">
                        <tr>
                            <th class="px-6 py-5 w-64">Produk Induk</th>
                            <th class="px-6 py-5 w-48">Kategori</th>
                            <th class="px-6 py-5 min-w-[350px]">Varian, Harga & Distribusi Stok Cabang</th>
                            <th class="px-6 py-5 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50/50 transition-colors group">

                                <!-- Kolom 1: Info Produk Induk -->
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-[#CC9863] text-lg font-black shrink-0 border border-orange-100 shadow-sm">
                                            {{ strtoupper(substr($product->nama_produk, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-extrabold text-gray-900 text-base leading-tight whitespace-normal line-clamp-2">{{ $product->nama_produk }}</p>
                                            <p class="text-xs font-semibold text-gray-400 mt-1">
                                                {{ $product->variants->count() ?? 0 }} Varian Ukuran
                                            </p>
                                            @if($product->tipe_stok === 'draft')
                                                <span class="inline-block mt-2 px-2 py-0.5 bg-gray-200 text-gray-600 text-[10px] font-bold rounded uppercase">Draft / Sembunyi</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom 2: Kategori -->
                                <td class="px-6 py-5 align-top pt-7">
                                    <span class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold border border-gray-200 shadow-sm">
                                        {{ $product->category->nama_kategori ?? 'Tanpa Kategori' }}
                                    </span>
                                </td>

                                <!-- Kolom 3: Varian & Stok (The Nested Cards) -->
                                <td class="px-6 py-5 align-top">
                                    @if ($product->variants->count() > 0)
                                        <div class="flex flex-col gap-3">
                                            @foreach ($product->variants as $variant)
                                                <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm hover:border-[#CC9863]/40 transition-colors">

                                                    {{-- Varian Header: Nama & Harga --}}
                                                    <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-100">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-gray-900 text-sm">{{ $variant->nama_varian }}</span>
                                                            @if($variant->sku)
                                                                <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded font-bold">{{ $variant->sku }}</span>
                                                            @endif
                                                        </div>
                                                        <span class="font-black text-[#CC9863]">
                                                            Rp {{ number_format($variant->harga_jual, 0, ',', '.') }}
                                                            <span class="text-[10px] text-gray-400 font-semibold">/ {{ $variant->satuan }}</span>
                                                        </span>
                                                    </div>

                                                    {{-- Alokasi Stok Badges --}}
                                                    <div class="flex flex-wrap gap-2">
                                                        @if ($variant->branchStocks && $variant->branchStocks->count() > 0)
                                                            @foreach ($variant->branchStocks as $stockInfo)
                                                                @php
                                                                    $isLow = $stockInfo->stok < 10;
                                                                    // Style dinamis berdasarkan jumlah stok
                                                                    $bgClass = $isLow ? 'bg-red-50 text-red-700 border-red-200' : 'bg-white text-gray-700 border-gray-200 shadow-sm';
                                                                    $dotClass = $isLow ? 'bg-red-500 animate-pulse' : 'bg-green-500';
                                                                    // Nama cabang yang lebih rapi
                                                                    $branchName = $stockInfo->cabang_id == 1 ? 'Pusat' : ($stockInfo->branch->nama_cabang ?? 'Cabang');
                                                                @endphp

                                                                <div class="inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg border text-xs font-semibold {{ $bgClass }}">
                                                                    <span class="w-2 h-2 rounded-full {{ $dotClass }} shadow-sm"></span>
                                                                    <span class="text-gray-500">{{ $branchName }} :</span>
                                                                    <span class="font-black text-gray-900">{{ $stockInfo->stok }} {{ $variant->satuan }}</span>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <span class="text-xs font-medium text-red-400 italic bg-red-50 px-2 py-1 rounded-md">Belum ada stok disebar.</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-3 bg-red-50 border border-red-100 rounded-xl text-red-500 text-xs font-bold inline-block">
                                            ⚠️ Produk belum memiliki varian & harga.
                                        </div>
                                    @endif
                                </td>

                                <!-- Kolom 4: Aksi -->
                                <td class="px-6 py-5 text-center align-top pt-6">
                                    <div class="flex justify-center gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('owner.stock.edit', $product->id) }}"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-gray-50 text-gray-500 hover:text-white hover:bg-[#CC9863] transition-colors border border-gray-200 hover:border-[#CC9863]"
                                            title="Edit Produk">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <!-- Delete Form & Button -->
                                        <form action="{{ route('owner.stock.destroy', $product->id) }}" method="POST"
                                            data-feedback-confirm
                                            data-feedback-confirm-title="Konfirmasi penghapusan"
                                            data-feedback-confirm-message="Produk ini beserta seluruh data varian dan stoknya akan dihapus."
                                            data-feedback-confirm-label="Hapus produk"
                                            data-feedback-confirm-tone="danger">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-gray-50 text-gray-500 hover:text-white hover:bg-red-500 transition-colors border border-gray-200 hover:border-red-500"
                                                title="Hapus Produk">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center bg-gray-50/30">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 text-gray-300 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                        <h3 class="text-gray-900 font-bold text-base mb-1">Belum Ada Produk</h3>
                                        <p class="text-gray-500 text-sm max-w-sm">Anda belum menambahkan produk ke dalam sistem atau data pencarian tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Pagination Data Asli -->
            @if(isset($products) && $products->hasPages())
                <div class="px-6 py-5 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white">
                    <span class="text-sm font-semibold text-gray-500">
                        Menampilkan <span class="text-gray-900">{{ $products->firstItem() }}</span> - <span class="text-gray-900">{{ $products->lastItem() }}</span> dari total <span class="text-gray-900">{{ $products->total() }}</span> produk
                    </span>
                    <div class="pagination-custom">
                        {{ $products->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection
