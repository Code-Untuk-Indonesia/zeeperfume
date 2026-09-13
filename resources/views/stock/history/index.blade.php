@extends('template.sidebar')
@section('title', 'Riwayat Perpindahan Stok')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full min-h-screen">

        <!-- ================= HEADER ================= -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-widest text-gray-400 mb-2">
                    <a href="{{ route($rolePrefix . '.stock.index') }}" class="hover:text-[#CC9863] transition-colors">Stok Barang</a>
                    <span class="text-gray-300">/</span>
                    <span class="text-[#CC9863]">Riwayat Perpindahan</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Tracking & Log Stok</h1>
                <p class="mt-1 text-sm font-medium text-gray-500">Pantau detail alur keluar-masuk, distribusi pusat ke cabang, dan penyesuaian barang.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-white rounded-xl border border-gray-200 shadow-sm flex items-center gap-3">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span><span class="text-[10px] font-bold text-gray-600 uppercase">Masuk</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span><span class="text-[10px] font-bold text-gray-600 uppercase">Keluar</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span><span class="text-[10px] font-bold text-gray-600 uppercase">Sistem</span></div>
                </div>
            </div>
        </div>

        <!-- ================= FILTER SECTION ================= -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] mb-8 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                <h2 class="text-xs font-black text-gray-900 uppercase tracking-widest">Filter Pencarian Log</h2>
            </div>
            <form action="{{ route($rolePrefix . '.stock.history') }}" method="GET" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-5 items-end">

                    <!-- Search -->
                    <div class="lg:col-span-3">
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Pencarian</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, SKU, nota..."
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 placeholder-gray-400 transition-colors focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                        </div>
                    </div>

                    <!-- Outlet -->
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Lokasi / Outlet</label>
                        <select name="cabang_id" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 transition-colors focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 cursor-pointer">
                            <option value="">Semua Lokasi</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected((string) request('cabang_id') === (string) $branch->id)>{{ $branch->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Varian -->
                    <div class="lg:col-span-3">
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Produk & Varian</label>
                        <select name="varian_id" class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 transition-colors focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 cursor-pointer">
                            <option value="">Semua Varian</option>
                            @foreach ($variants as $variant)
                                <option value="{{ $variant->id }}" @selected((string) request('varian_id') === (string) $variant->id)>
                                    {{ $variant->nama_produk }} - {{ $variant->nama_varian }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 transition-colors focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 cursor-pointer">
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                            class="w-full px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-800 transition-colors focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 cursor-pointer">
                    </div>

                    <!-- Actions -->
                    <div class="lg:col-span-12 flex justify-end gap-3 mt-2 border-t border-gray-50 pt-5">
                        @if (request()->hasAny(['search', 'cabang_id', 'varian_id', 'jenis_riwayat', 'tanggal_mulai', 'tanggal_selesai']))
                            <a href="{{ route($rolePrefix . '.stock.history') }}" class="px-6 py-3 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-200 hover:text-gray-900 transition-colors">Reset Filter</a>
                        @endif
                        <button type="submit" class="px-8 py-3 bg-[#1C1D21] text-white rounded-xl text-xs font-extrabold uppercase tracking-wider hover:bg-[#CC9863] transition-colors shadow-md transform active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2l-7 7v6l-4 2v-8L3 6V4z"></path></svg>
                            Terapkan Filter
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- ================= TRACKING LOG TABLE ================= -->
        <section class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                <div>
                    <h2 class="text-base font-black text-gray-900">Catatan Aktivitas</h2>
                    <p class="text-[11px] font-medium text-gray-500 mt-1">Total <span class="font-bold text-gray-700">{{ $histories->total() }}</span> log ditemukan.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-left border-collapse">
                    <thead class="bg-white border-b border-gray-100 text-[10px] font-extrabold uppercase tracking-widest text-gray-400">
                        <tr>
                            <th class="px-6 py-4 w-32">Waktu</th>
                            <th class="px-6 py-4">Aktivitas & Produk</th>
                            <th class="px-6 py-4">Lokasi & Jalur (Tracking)</th>
                            <th class="px-6 py-4 text-right">Perubahan Qty</th>
                            <th class="px-6 py-4">Otorisasi / Nota</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse ($histories as $history)
                            @php
                                $type = strtolower($history->jenis_riwayat);

                                // Setup Warna dan Ikon berdasarkan jenis
                                if (in_array($type, ['masuk', 'tambah', 'restok'])) {
                                    $theme = 'emerald';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>';
                                    $prefix = '+';
                                } elseif (in_array($type, ['keluar', 'kurang', 'penjualan'])) {
                                    $theme = 'rose';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>';
                                    $prefix = ''; // Nilai di DB biasanya sudah minus, jika belum set '-'. Asumsi: qty sudah mencerminkan minus/plus.
                                } else {
                                    $theme = 'indigo';
                                    $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>';
                                    $prefix = $history->qty > 0 ? '+' : '';
                                }

                                // Deteksi Kata Kunci Transfer / Distribusi untuk UI
                                $isTransfer = str_contains(strtolower($history->keterangan), 'transfer') || str_contains(strtolower($history->keterangan), 'distribusi');
                            @endphp

                            <tr class="hover:bg-gray-50/60 transition-colors group">

                                <!-- 1. Kolom Waktu -->
                                <td class="px-6 py-4 align-top">
                                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($history->waktu)->format('d M Y') }}</p>
                                    <p class="text-[11px] font-semibold text-gray-400 mt-1">{{ \Carbon\Carbon::parse($history->waktu)->format('H:i') }} WIB</p>
                                </td>

                                <!-- 2. Kolom Aktivitas & Produk -->
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-start gap-3">
                                        <!-- Ikon Status -->
                                        <div class="w-8 h-8 shrink-0 rounded-xl bg-{{$theme}}-50 text-{{$theme}}-500 flex items-center justify-center border border-{{$theme}}-100 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $icon !!}</svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 group-hover:text-[#CC9863] transition-colors line-clamp-1">{{ $history->nama_produk }}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[10px] font-extrabold text-gray-500 uppercase tracking-widest bg-gray-100 px-2 py-0.5 rounded">{{ $history->nama_varian }}</span>
                                                @if($history->sku)
                                                    <span class="text-[10px] font-bold text-gray-400">{{ $history->sku }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- 3. Kolom Lokasi & Tracking (Flow) -->
                                <td class="px-6 py-4 align-top">
                                    <div class="flex flex-col gap-1.5">
                                        <!-- Posisi Saat Ini -->
                                        <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="font-bold text-gray-800">{{ $history->nama_cabang }}</span>
                                        </div>

                                        <!-- Keterangan / Flow Tracking -->
                                        @if ($history->keterangan)
                                            <div class="flex items-start gap-1.5 mt-0.5">
                                                @if($isTransfer)
                                                    <svg class="w-3.5 h-3.5 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                                    <span class="text-[11px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">{{ $history->keterangan }}</span>
                                                @else
                                                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <span class="text-[11px] font-medium text-gray-500 max-w-[200px] leading-relaxed">{{ $history->keterangan }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- 4. Kolom Qty (Tegas & Jelas) -->
                                <td class="px-6 py-4 align-top text-right">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border bg-{{$theme}}-50 border-{{$theme}}-100">
                                        <span class="text-base font-black text-{{$theme}}-600 tracking-tight">{{ $prefix }}{{ number_format($history->qty, 0, ',', '.') }}</span>
                                        <span class="text-[10px] font-extrabold uppercase text-{{$theme}}-400">{{ $history->satuan }}</span>
                                    </div>
                                </td>

                                <!-- 5. Kolom Referensi / Otorisasi -->
                                <td class="px-6 py-4 align-top">
                                    <p class="font-bold text-gray-800 text-xs flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center text-[8px] uppercase font-black text-gray-500 border border-gray-200">{{ substr($history->nama_lengkap ?? 'Sys', 0, 1) }}</span>
                                        {{ $history->nama_lengkap ?? 'Otomatis Sistem' }}
                                    </p>
                                    @if ($history->nomor_nota)
                                        <p class="mt-2 text-[10px] font-extrabold uppercase tracking-wider text-[#CC9863] bg-[#CC9863]/10 px-2 py-1 rounded inline-block border border-[#CC9863]/20">Nota: {{ $history->nomor_nota }}</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="w-16 h-16 mx-auto bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-center text-gray-400 mb-4 shadow-sm">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5h6m-7 4h8m-8 4h5m-7 6h12a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h3 class="font-black text-gray-900 text-base">Log Kosong / Tidak Ditemukan</h3>
                                    <p class="mt-1 text-xs font-medium text-gray-500">Coba sesuaikan filter pencarian atau rentang tanggal di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($histories->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Halaman <span class="text-gray-900">{{ $histories->currentPage() }}</span> dari {{ $histories->lastPage() }}</p>
                    <div>
                        {{ $histories->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        </section>

    </main>
@endsection
