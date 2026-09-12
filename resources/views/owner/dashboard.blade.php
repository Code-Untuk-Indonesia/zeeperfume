@extends('template.sidebar')
@section('title', 'Dashboard Owner')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 w-full relative min-h-screen">

        <!-- Loader Overlay -->
        <div id="ajax-loader"
            class="fixed inset-0 bg-white/60 backdrop-blur-sm z-[100] hidden items-center justify-center transition-all">
            <div class="flex flex-col items-center gap-3 bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <div class="w-10 h-10 border-4 border-[#CC9863]/30 border-t-[#CC9863] rounded-full animate-spin"></div>
                <p class="text-sm font-bold text-gray-700">Memuat Data...</p>
            </div>
        </div>

        <!-- Header Section & Smart Filter -->
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-5 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">
                    Selamat datang, {{ explode(' ', auth()->user()->nama_lengkap ?? 'Owner')[0] }}!
                </h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Saat yang tepat untuk memantau performa bisnis parfum Anda.
                </p>
            </div>

            <!-- Filter Form (AJAX) -->
            <div class="w-full xl:w-auto bg-white p-2.5 rounded-2xl border border-gray-200 shadow-sm"
                x-data="{ period: '{{ $period }}' }">
                <form id="dashboardFilter" onsubmit="event.preventDefault(); window.applyDashboardFilter();"
                    class="flex flex-col md:flex-row items-center gap-2 w-full">

                    <div class="flex items-center w-full md:w-auto">
                        <select name="period" x-model="period"
                            @change="if(period !== 'custom') window.applyDashboardFilter();"
                            class="w-full md:w-[180px] h-[42px] px-3 bg-gray-50 border border-transparent rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863] focus:bg-white transition-colors cursor-pointer">
                            <option value="today">Hari Ini</option>
                            <option value="this_week">Minggu Ini</option>
                            <option value="this_month">Bulan Ini</option>
                            <option value="this_year">Tahun Ini</option>
                            <option value="all">Keseluruhan</option>
                            <option value="custom">Pilih Tanggal...</option>
                        </select>
                    </div>

                    <!-- Custom Date Inputs (Hanya muncul jika Pilih Tanggal) -->
                    <div x-show="period === 'custom'" x-transition class="flex w-full md:w-auto items-center gap-2"
                        style="display: none;">
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            class="h-[42px] px-3 w-full md:w-[130px] bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                        <span class="text-gray-400 font-bold">-</span>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            class="h-[42px] px-3 w-full md:w-[130px] bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:border-[#CC9863]">

                        <!-- Tombol Terapkan Custom -->
                        <button type="submit"
                            class="h-[42px] px-4 bg-[#1C1D21] text-white rounded-xl font-bold hover:bg-black transition text-sm flex items-center justify-center shadow-sm shrink-0">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Wrapper for AJAX -->
        <div id="dashboard-data-wrapper">

            <!-- Active Period Info -->
            <div class="mb-5 flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Periode Data:</span>
                <span
                    class="bg-[#CC9863]/10 text-[#CC9863] font-bold text-xs px-2.5 py-1 rounded-md">{{ $periodLabel ?? 'Bulan Ini' }}</span>
            </div>

            <!-- ================= STATS CARDS ================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">

                <!-- Kas Diterima -->
                <div
                    class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-semibold text-gray-500">Kas Masuk (In Hand)</h3>
                        <div
                            class="w-7 h-7 rounded-full border border-green-100 bg-green-50 flex items-center justify-center text-green-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-black text-gray-900 mb-1">Rp
                            {{ number_format($kasDiterima ?? 0, 0, ',', '.') }}</h2>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Di luar tagihan
                            tempo</span>
                    </div>
                </div>

                <!-- Piutang Tempo -->
                <div
                    class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm flex flex-col justify-between hover:shadow-md transition border-b-4 border-b-orange-400">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-semibold text-orange-600">Piutang (Tempo)</h3>
                        <div
                            class="w-7 h-7 rounded-full border border-orange-100 bg-orange-50 flex items-center justify-center text-orange-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-black text-orange-600 mb-1">Rp
                            {{ number_format($totalPiutang ?? 0, 0, ',', '.') }}</h2>
                        <span class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded-md font-bold text-[10px]">Uang
                            Tertahan</span>
                    </div>
                </div>

                <!-- Total Aset Barang -->
                <div
                    class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-semibold text-blue-600">Aset Stok (Modal)</h3>
                        <div
                            class="w-7 h-7 rounded-full border border-blue-100 bg-blue-50 flex items-center justify-center text-blue-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-black text-blue-600 mb-1">Rp
                            {{ number_format($totalAsetModal ?? 0, 0, ',', '.') }}</h2>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Realtime Gudang</span>
                    </div>
                </div>

                <!-- Total Transaksi -->
                <div
                    class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-semibold text-gray-500">Total Transaksi</h3>
                        <div
                            class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-black text-gray-900 mb-1">
                            {{ number_format($totalTransactions ?? 0, 0, ',', '.') }} Trx</h2>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Keseluruhan order</span>
                    </div>
                </div>

                <!-- Peringatan Stok -->
                <a href="{{ route('owner.stock.index') }}"
                    class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm flex flex-col justify-between hover:shadow-md hover:border-red-300 transition group border-l-4 border-l-red-500">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-semibold text-red-500 group-hover:text-red-600">Stok Kritis</h3>
                        <div
                            class="w-7 h-7 rounded-full border border-red-100 bg-red-50 flex items-center justify-center text-red-500 group-hover:bg-red-500 group-hover:text-white transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-black text-red-600 mb-1">
                            {{ isset($lowStocks) ? $lowStocks->count() : 0 }} Item</h2>
                        <span
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider group-hover:text-red-400">Perlu
                            restok segera &rarr;</span>
                    </div>
                </a>
            </div>

            <!-- ================= MIDDLE SECTION (Charts) ================= -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Chart Area -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Performa Penjualan</h2>
                            <p class="text-[11px] text-gray-400 mt-1 font-semibold">{{ $periodLabel }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-bold text-gray-500">
                            <div class="flex items-center gap-1.5"><span
                                    class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span> Omzet</div>
                            <div class="flex items-center gap-1.5"><span
                                    class="w-2.5 h-2.5 rounded-full bg-blue-200"></span> Modal (HPP)</div>
                        </div>
                    </div>
                    <div class="relative w-full flex-1 min-h-[250px]">
                        <canvas id="salesChart"></canvas>
                    </div>
                    <!-- Div hidden for chart JS -->
                    <div id="chart-data-source" class="hidden" data-chart='@json($chartData)'></div>
                </div>

                <!-- Donut Chart Area -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col relative">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-gray-900">Metode Bayar</h2>
                    </div>
                    <div class="flex-1 flex flex-col items-center justify-center">
                        @if (isset($paymentMethods) && $paymentMethods->count() > 0)
                            <div class="w-full space-y-4">
                                @foreach ($paymentMethods as $pm)
                                    @php $pct = $totalTransactions > 0 ? round(($pm->total_transaksi / $totalTransactions) * 100) : 0; @endphp
                                    <div>
                                        <div class="flex justify-between text-xs font-bold mb-1.5">
                                            <span
                                                class="text-gray-700 uppercase tracking-wide">{{ str_replace('_', ' ', $pm->metode_bayar) }}</span>
                                            <span class="text-gray-900">{{ $pm->total_transaksi }} Trx <span
                                                    class="text-gray-400">({{ $pct }}%)</span></span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="bg-[#CC9863] h-2 rounded-full transition-all duration-500"
                                                style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center opacity-50">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-xs font-bold text-gray-400">Belum ada pembayaran</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ================= TOP PRODUCTS & CABANG ================= -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- TABLE CABANG -->
                <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 sm:p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div>
                            <h2 class="text-base font-black text-gray-900">Pendapatan per Outlet</h2>
                            <p class="text-[11px] font-medium text-gray-500 mt-1">Berdasarkan filter periode aktif.</p>
                        </div>
                        <a href="{{ route('owner.income.index') }}"
                            class="text-[11px] font-bold text-[#CC9863] hover:underline bg-[#CC9863]/10 px-3 py-1.5 rounded-lg">Rincian
                            &rarr;</a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead
                                class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Nama Outlet</th>
                                    <th class="px-6 py-4 text-center">Trx</th>
                                    <th class="px-6 py-4 text-right">Total Omzet</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50">
                                @foreach ($branchIncomes as $branch)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 font-black text-[10px] uppercase border border-blue-100">
                                                    {{ substr($branch->nama_cabang, 0, 2) }}</div>
                                                <span class="font-bold text-gray-900">{{ $branch->nama_cabang }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-gray-600">
                                            <span
                                                class="bg-gray-100 px-2.5 py-1 rounded-md text-xs">{{ $branch->total_trx }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-gray-900">Rp
                                            {{ number_format($branch->total_income, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TOP PRODUCTS -->
                <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 sm:p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div>
                            <h2 class="text-base font-black text-gray-900">Top 5 Produk Terlaris</h2>
                            <p class="text-[11px] font-medium text-gray-500 mt-1">Produk penyumbang omzet terbanyak.</p>
                        </div>
                        <a href="{{ route('owner.stock.index') }}"
                            class="text-[11px] font-bold text-[#CC9863] hover:underline bg-[#CC9863]/10 px-3 py-1.5 rounded-lg">Cek
                            Stok &rarr;</a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead
                                class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Produk & Varian</th>
                                    <th class="px-6 py-4 text-center">Terjual</th>
                                    <th class="px-6 py-4 text-right">Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50">
                                @forelse ($topProducts as $item)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-900">
                                                {{ $item->variant->product->nama_produk ?? 'Unknown' }}</p>
                                            <p class="text-[10px] text-gray-500 font-semibold mt-0.5">
                                                {{ $item->variant->nama_varian ?? '-' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="bg-[#CC9863]/10 text-[#CC9863] px-2.5 py-1 rounded-md font-black text-xs">{{ $item->total_qty }}
                                                pcs</span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-gray-900">Rp
                                            {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center">
                                            <p class="font-bold text-gray-400 italic text-xs">Belum ada penjualan di
                                                periode ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================= BOTTOM SECTION ================= -->
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-white pb-2 rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-5 sm:p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div>
                            <h2 class="text-base font-black text-gray-900">Transaksi Terakhir</h2>
                            <p class="text-[11px] font-medium text-gray-500 mt-1">Daftar 5 transaksi terbaru pada periode
                                ini.</p>
                        </div>
                        <a href="{{ route('owner.transaction.index') }}"
                            class="text-[11px] font-bold text-gray-600 hover:text-gray-900 bg-white shadow-sm border border-gray-200 px-3 py-1.5 rounded-lg transition-colors">Lihat
                            Riwayat</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Waktu & Nota</th>
                                    <th class="px-6 py-4">Kasir / Cabang</th>
                                    <th class="px-6 py-4 text-center">Metode</th>
                                    <th class="px-6 py-4">Pelanggan</th>
                                    <th class="px-6 py-4 text-right">Total Harga</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                                @forelse($recentTransactions as $trx)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-900">
                                                {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->translatedFormat('d M Y') }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 font-semibold mt-0.5">
                                                {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->translatedFormat('H:i') }} •
                                                <span class="text-[#CC9863] font-black">{{ $trx->nomor_nota }}</span></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-900">{{ $trx->kasir->nama_lengkap ?? 'Kasir' }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 font-semibold mt-0.5">
                                                {{ $trx->cabang->nama_cabang ?? 'Pusat' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $methodColors = [
                                                    'cash' =>
                                                        'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                                    'qris' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                                    'transfer' =>
                                                        'bg-indigo-50 text-indigo-600 border border-indigo-100',
                                                    'tempo' => 'bg-rose-50 text-rose-600 border border-rose-100',
                                                    'cash_tempo' =>
                                                        'bg-orange-50 text-orange-600 border border-orange-100',
                                                ];
                                                $colorClass =
                                                    $methodColors[strtolower($trx->metode_bayar)] ??
                                                    'bg-gray-100 text-gray-600';
                                            @endphp
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-md text-[9px] font-extrabold uppercase tracking-wider {{ $colorClass }}">
                                                {{ str_replace('_', ' ', $trx->metode_bayar) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-bold text-gray-500">
                                            {{ $trx->member->nama ?? 'Umum' }}</td>
                                        <td class="px-6 py-4 text-right font-black text-gray-900 text-base">Rp
                                            {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="py-10 text-center text-sm text-gray-400 font-medium italic">Belum ada
                                            transaksi tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> <!-- End Dashboard Wrapper -->
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        let salesChartInstance = null;

        function initChart(chartData) {
            const canvas = document.getElementById('salesChart');
            if (!canvas || typeof Chart === 'undefined') return;

            if (salesChartInstance) {
                salesChartInstance.destroy();
            }

            const ctx = canvas.getContext('2d');
            salesChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                            label: 'Omzet',
                            data: chartData.omzet,
                            backgroundColor: '#CC9863',
                            borderRadius: 4,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Modal (HPP)',
                            data: chartData.hpp,
                            backgroundColor: '#BFDBFE',
                            borderRadius: 4,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#111827',
                            padding: 12,
                            cornerRadius: 8,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(ctx) {
                                    return ` ${ctx.dataset.label}: Rp ${Number(ctx.raw).toLocaleString('id-ID')}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#9CA3AF',
                                font: {
                                    size: 10,
                                    weight: '600'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            border: {
                                display: false
                            },
                            grid: {
                                color: '#F3F4F6'
                            },
                            ticks: {
                                color: '#9CA3AF',
                                font: {
                                    size: 10,
                                    weight: '600'
                                },
                                callback: function(val) {
                                    if (val >= 1000000) return (val / 1000000) + ' Jt';
                                    if (val >= 1000) return (val / 1000) + ' K';
                                    return val;
                                }
                            }
                        }
                    }
                }
            });
        }

        window.applyDashboardFilter = function() {
            const form = document.getElementById('dashboardFilter');
            const params = new URLSearchParams(new FormData(form)).toString();
            const loader = document.getElementById('ajax-loader');

            loader.classList.remove('hidden');
            loader.classList.add('flex');

            fetch(`{{ route('owner.dashboard') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    // Update URL browser tanpa refresh
                    window.history.pushState({}, '', `?${params}`);

                    // Render HTML Baru
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data.html, 'text/html');
                    document.getElementById('dashboard-data-wrapper').innerHTML = doc.getElementById(
                        'dashboard-data-wrapper').innerHTML;

                    // Re-render Chart
                    initChart(data.chart);
                })
                .catch(err => console.error("Gagal memuat data", err))
                .finally(() => {
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                });
        }

        // Initialize pada saat halaman pertama dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const initialChartData = JSON.parse(document.getElementById('chart-data-source').dataset.chart);
            initChart(initialChartData);
        });
    </script>
@endsection
