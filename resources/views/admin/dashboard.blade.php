@extends('template.sidebar')
@section('title', 'Dashboard Admin')

@section('content')
    <main class="flex-1 bg-[#F3F4F6] overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full relative">

        <!-- HEADER & GREETING -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Welcome Back,
                    {{ explode(' ', trim(auth()->user()->nama_lengkap ?? 'Admin'))[0] }}</h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Kelola operasional dan pantau performa toko di sini.</p>
            </div>

            <!-- FILTER DROPDOWN DENGAN ALPINE JS -->
            <div x-data="{ filterType: '{{ request('filter', 'today') }}' }">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap sm:flex-nowrap gap-2">
                    <div class="relative flex-1 sm:flex-none">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <select name="filter" x-model="filterType" @change="if(filterType !== 'custom') $el.form.submit()"
                            class="w-full sm:w-auto pl-9 pr-8 py-2.5 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 appearance-none cursor-pointer">
                            <option value="today">Hari Ini</option>
                            <option value="this_week">Minggu Ini</option>
                            <option value="this_month">Bulan Ini</option>
                            <option value="this_year">Tahun Ini</option>
                            <option value="custom">Pilih Tanggal...</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- Input Kalender (Muncul Jika Pilih 'Pilih Tanggal...') -->
                    <template x-if="filterType === 'custom'">
                        <input type="date" name="tanggal_spesifik" value="{{ request('tanggal_spesifik') }}"
                            onchange="this.form.submit()"
                            class="py-2.5 px-4 bg-white border border-[#CC9863] rounded-2xl text-sm font-bold text-gray-800 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 cursor-pointer">
                    </template>
                </form>
            </div>
        </div>

        <!-- ================= 4 TOP STAT CARDS ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <!-- 1. Omzet -->
            <div
                class="bg-white p-5 rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm font-bold text-gray-500">Total Omzet</p>
                    <div class="w-8 h-8 rounded-full bg-green-50 text-green-500 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-3">Rp {{ number_format($omzet, 0, ',', '.') }}</h3>
                <div class="flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded-md text-[10px] font-bold flex items-center gap-1 {{ $trendOmzet >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="{{ $trendOmzet >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}">
                            </path>
                        </svg>
                        {{ number_format(abs($trendOmzet), 1) }}%
                    </span>
                    <span class="text-[10px] text-gray-400 font-semibold">vs periode lalu</span>
                </div>
            </div>

            <!-- 2. Transaksi -->
            <div
                class="bg-white p-5 rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm font-bold text-gray-500">Total Transaksi</p>
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-3">{{ number_format($totalTransaksi, 0, ',', '.') }} <span
                        class="text-base text-gray-400 font-bold">Nota</span></h3>
                <div class="flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded-md text-[10px] font-bold flex items-center gap-1 {{ $trendTransaksi >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="{{ $trendTransaksi >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}">
                            </path>
                        </svg>
                        {{ number_format(abs($trendTransaksi), 1) }}%
                    </span>
                    <span class="text-[10px] text-gray-400 font-semibold">vs periode lalu</span>
                </div>
            </div>

            <!-- 3. Pesanan Online -->
            <div
                class="bg-white p-5 rounded-[24px] border border-gray-100 shadow-sm hover:shadow-md transition duration-300">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm font-bold text-gray-500">Pesanan Online</p>
                    <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-3">{{ number_format($pesananOnline, 0, ',', '.') }} <span
                        class="text-base text-gray-400 font-bold">Paket</span></h3>
                <div class="flex items-center gap-2">
                    <span
                        class="px-2 py-0.5 rounded-md text-[10px] font-bold flex items-center gap-1 {{ $trendOnline >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="{{ $trendOnline >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}">
                            </path>
                        </svg>
                        {{ number_format(abs($trendOnline), 1) }}%
                    </span>
                    <span class="text-[10px] text-gray-400 font-semibold">vs periode lalu</span>
                </div>
            </div>

            <!-- 4. Peringatan Stok -->
            <a href="{{ route('admin.stock.index') }}"
                class="bg-white p-5 rounded-[24px] border border-red-100 shadow-sm hover:shadow-md transition duration-300 block cursor-pointer group">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-sm font-bold text-gray-500 group-hover:text-red-500 transition-colors">Peringatan Stok
                    </p>
                    <div class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black {{ $jumlahStokMenipis > 0 ? 'text-red-500' : 'text-gray-900' }} mb-3">
                    {{ $jumlahStokMenipis }} <span class="text-base text-gray-400 font-bold">Varian</span></h3>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-100 text-gray-600">Klik untuk kelola
                        &rarr;</span>
                </div>
            </a>
        </div>

        <!-- ================= MIDDLE SECTION (TOP PRODUCTS & CABANG) ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            <!-- TABLE SETORAN CABANG -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Setoran per Outlet</h2>
                        <p class="text-[11px] text-gray-500 mt-1">Total pendapatan yang dihasilkan ({{ $filterLabel }}).
                        </p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-extrabold text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Nama Outlet</th>
                                <th class="px-6 py-4 text-right">Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse ($laporanCabang as $cabang)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full {{ $cabang['setoran'] > 0 ? 'bg-[#CC9863]/10 text-[#CC9863]' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center font-black text-[10px] uppercase">
                                                {{ substr($cabang['nama'], 0, 2) }}
                                            </div>
                                            <span class="font-bold text-gray-900">{{ $cabang['nama'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900">Rp
                                        {{ number_format($cabang['setoran'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">Belum ada data
                                        cabang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TOP PRODUCTS -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Top 5 Produk Terlaris</h2>
                        <p class="text-[11px] text-gray-500 mt-1">Produk paling banyak terjual ({{ $filterLabel }}).</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-extrabold text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Produk & Varian</th>
                                <th class="px-6 py-4 text-center">Terjual</th>
                                <th class="px-6 py-4 text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse ($topProducts as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">
                                            {{ $item->variant->product->nama_produk ?? 'Unknown' }}</p>
                                        <p class="text-[10px] text-gray-500 font-semibold">
                                            {{ $item->variant->nama_varian ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="bg-[#CC9863]/10 text-[#CC9863] px-2 py-1 rounded font-black text-xs">{{ $item->total_qty }}
                                            pcs</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900">Rp
                                        {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">Belum ada
                                        penjualan produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= RECENT TRANSACTIONS TABLE ================= -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Transaksi Terakhir</h2>
                    <p class="text-[11px] text-gray-500 mt-1">Daftar transaksi terbaru di sistem.</p>
                </div>
                <a href="{{ route('admin.transaction.index') }}"
                    class="text-sm font-bold text-gray-400 hover:text-gray-900 bg-white border border-gray-200 px-3 py-1.5 rounded-lg transition">Lihat
                    Semua ></a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left whitespace-nowrap">
                    <thead class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4">Waktu & Invoice</th>
                            <th class="px-6 py-4">Total Nilai</th>
                            <th class="px-6 py-4 text-center">Origin</th>
                            <th class="px-6 py-4 text-center">Metode</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-gray-50/80 transition cursor-pointer"
                                onclick="window.location='{{ route('admin.transaction.show', $trx->id) }}'">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800 mb-0.5">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('d M H:i') }}</p>
                                    <p class="text-[11px] text-[#CC9863] font-black">{{ $trx->nomor_nota }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-black text-gray-900">Rp
                                        {{ number_format($trx->total_belanja, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($trx->shipment)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2 py-1 bg-blue-50 text-blue-600 rounded-md text-[10px] font-bold uppercase">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                                                </path>
                                            </svg>
                                            Online
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2 py-1 bg-orange-50 text-[#CC9863] rounded-md text-[10px] font-bold uppercase">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10.496 2.132a1 1 0 00-.992 0l-7 4A1 1 0 003 8v7a1 1 0 100 2h14a1 1 0 100-2V8a1 1 0 00.504-1.868l-7-4zM6 9a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1zm3 1a1 1 0 012 0v3a1 1 0 11-2 0v-3zm5-1a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Outlet
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="font-bold text-gray-600 uppercase text-[10px] bg-gray-100 px-2 py-1 rounded">{{ str_replace('_', ' ', $trx->metode_bayar) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $isTempo = in_array(strtolower($trx->metode_bayar), ['tempo', 'cash_tempo']);
                                        $belumLunas = $isTempo && $trx->cashTempo && $trx->cashTempo->sisa_piutang > 0;
                                    @endphp
                                    @if ($belumLunas)
                                        <span
                                            class="text-red-500 font-bold text-[10px] uppercase tracking-wider flex items-center justify-center gap-1"><span
                                                class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>Tempo</span>
                                    @else
                                        <span
                                            class="text-green-500 font-bold text-[10px] uppercase tracking-wider flex items-center justify-center gap-1"><span
                                                class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada transaksi
                                    pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
@endsection
