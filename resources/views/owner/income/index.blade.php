@extends('template.sidebar')
@section('title', 'Laporan Pendapatan (Income)')

@section('content')
<main class="flex-1 bg-gray-50/50 overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full relative min-h-screen">

    <!-- ================= HEADER SECTION ================= -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="bg-[#CC9863]/10 p-2 rounded-xl text-[#CC9863]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                    Income Report
                </h1>
            </div>
            <p class="text-gray-500 text-sm font-medium flex items-center gap-2">
                Analisis pendapatan, transaksi, dan performa outlet.
                <span class="inline-flex items-center bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider">
                    {{ $monthName }}
                </span>
            </p>
        </div>

        <div class="flex items-center gap-3 self-end lg:self-auto">
            <a href="{{ route('owner.income.print', request()->only(['month', 'year', 'start_date', 'end_date', 'branch_id', 'payment_method'])) }}" target="_blank" rel="noopener" class="bg-white border border-gray-200 text-gray-700 h-10 px-4 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-2 0v4H8v-4m-2-9h12"></path>
                </svg>
                <span class="hidden sm:inline">Cetak PDF</span>
            </a>
            <a href="{{ route('owner.income.export', request()->only(['month', 'year', 'start_date', 'end_date', 'branch_id', 'payment_method'])) }}" class="bg-[#CC9863] text-white h-10 px-4 rounded-xl font-bold shadow-sm hover:bg-[#b58555] hover:shadow-md transition flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="hidden sm:inline">Export Excel</span>
            </a>
        </div>
    </div>

    <!-- ================= FILTER SECTION ================= -->
    <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm p-5 mb-8 relative z-20">
        <form action="{{ route('owner.income.index') }}" method="GET" id="incomeFilter">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">

                <!-- Period Filter Group -->
                <div class="lg:col-span-2 grid grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Bulan</label>
                        <select name="month" id="monthFilter" class="w-full h-[40px] px-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863] transition-all">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ (int) request('month', $month) === $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Tahun</label>
                        <select name="year" id="yearFilter" class="w-full h-[40px] px-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863] transition-all">
                            @php $currentYear = now()->year; @endphp
                            @for ($y = $currentYear; $y >= $currentYear - 5; $y--)
                                <option value="{{ $y }}" {{ (int) request('year', $year) === $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Custom Date Range -->
                <div class="lg:col-span-2 grid grid-cols-2 gap-3 relative">
                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 hidden lg:flex h-full items-center">
                        <div class="w-px h-8 bg-gray-200"></div>
                        <span class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 bg-white px-1 text-[8px] font-black text-gray-300">OR</span>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Dari Tanggal</label>
                        <input type="date" name="start_date" id="startDate" value="{{ request('start_date') }}" class="w-full h-[40px] px-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863] transition-all">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="endDate" value="{{ request('end_date') }}" min="{{ request('start_date') }}" class="w-full h-[40px] px-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863] transition-all">
                    </div>
                </div>

                <!-- Entity Filters -->
                <div>
                    <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Outlet</label>
                    <select name="branch_id" class="w-full h-[40px] px-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863] transition-all">
                        <option value="">Semua Outlet</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ (string) request('branch_id') === (string) $branch->id ? 'selected' : '' }}>
                                {{ $branch->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-500">Metode</label>
                        <select name="payment_method" class="w-full h-[40px] px-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863] transition-all">
                            <option value="">Semua</option>
                            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="cash_tempo" {{ request('payment_method') === 'cash_tempo' ? 'selected' : '' }}>Cash Tempo</option>
                            <option value="tempo" {{ request('payment_method') === 'tempo' ? 'selected' : '' }}>Tempo</option>
                        </select>
                    </div>

                    <button type="submit" class="h-[40px] w-[40px] shrink-0 bg-gray-900 text-white rounded-xl flex items-center justify-center hover:bg-black transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </div>

            @if(request()->anyFilled(['start_date', 'end_date', 'branch_id', 'payment_method', 'month', 'year']))
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Filter Aktif:</span>
                        @if(request('branch_id'))
                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 px-2 py-1 rounded-md text-[10px] font-bold border border-blue-100">
                                Outlet: {{ $branches->firstWhere('id', request('branch_id'))?->nama_cabang }}
                            </span>
                        @endif
                        @if(request('payment_method'))
                            <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-600 px-2 py-1 rounded-md text-[10px] font-bold border border-purple-100 uppercase">
                                Pembayaran: {{ str_replace('_', ' ', request('payment_method')) }}
                            </span>
                        @endif
                        @if(request('start_date') && request('end_date'))
                            <span class="inline-flex items-center gap-1 bg-green-50 text-green-600 px-2 py-1 rounded-md text-[10px] font-bold border border-green-100">
                                {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m') }} - {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m') }}
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('owner.income.index') }}" class="text-[11px] font-bold text-red-500 hover:text-red-600 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Clear Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- ================= TOP METRICS CARDS ================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <!-- TOTAL INCOME -->
        <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full blur-2xl opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <h3 class="text-[11px] font-extrabold text-gray-500 uppercase tracking-wider mb-1">Total Omzet</h3>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center text-blue-600 shadow-inner border border-blue-100/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-[11px] font-medium text-gray-400 relative z-10">Total pendapatan kotor pada periode aktif.</p>
        </div>

        <!-- TOTAL TRANSACTIONS -->
        <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-purple-50 to-fuchsia-50 rounded-full blur-2xl opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <h3 class="text-[11px] font-extrabold text-gray-500 uppercase tracking-wider mb-1">Total Transaksi</h3>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                        {{ number_format($totalTrx, 0, ',', '.') }} <span class="text-base text-gray-400 font-bold">Trx</span>
                    </h2>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-50 to-fuchsia-50 flex items-center justify-center text-purple-600 shadow-inner border border-purple-100/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
            <p class="text-[11px] font-medium text-gray-400 relative z-10">Jumlah struk/nota yang diterbitkan.</p>
        </div>

        <!-- AVERAGE TRANSACTION -->
        <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm p-6 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-gradient-to-br from-orange-50 to-amber-50 rounded-full blur-2xl opacity-60 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div>
                    <h3 class="text-[11px] font-extrabold text-gray-500 uppercase tracking-wider mb-1">Rata-Rata Order</h3>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</h2>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-50 to-amber-50 flex items-center justify-center text-orange-500 shadow-inner border border-orange-100/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <p class="text-[11px] font-medium text-gray-400 relative z-10">Nilai rata-rata keranjang per pelanggan.</p>
        </div>
    </div>

    <!-- ================= TREND CHART ================= -->
    <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm p-6 mb-8 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-lg font-black text-gray-900">Grafik Omzet Pemasukan</h2>
                <p class="text-xs font-medium text-gray-500 mt-1">Performa harian untuk periode saat ini</p>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Pendapatan</span>
            </div>
        </div>
        <div class="relative w-full h-[280px]">
            <canvas id="incomeChart"></canvas>
        </div>
    </div>

    <!-- ================= DAILY OUTLET REPORT ================= -->
    <section class="mb-8" aria-labelledby="daily-outlet-report-title">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-5">
            <div>
                <h2 id="daily-outlet-report-title" class="text-lg font-black text-gray-900">Rekap Pendapatan per Outlet</h2>
                <p class="text-xs font-medium text-gray-500 mt-1">Berdasarkan filter periode saat ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <div class="rounded-xl border border-gray-100 bg-white px-5 py-4 shadow-sm flex flex-col justify-center relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#CC9863]"></div>
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Total Omzet</p>
                <p class="text-xl font-black text-gray-900">Rp {{ number_format($dailySummary['total_pendapatan'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white px-5 py-4 shadow-sm flex flex-col justify-center relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Kas Tunai Masuk</p>
                <p class="text-xl font-black text-emerald-600">Rp {{ number_format($dailySummary['total_diterima'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-white px-5 py-4 shadow-sm flex flex-col justify-center relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Total Piutang / Tempo</p>
                <p class="text-xl font-black text-rose-600">Rp {{ number_format($dailySummary['total_piutang'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-[20px] border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left">
                    <thead class="border-b border-gray-100 bg-gray-50 text-[10px] font-extrabold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-4">Outlet</th>
                            <th class="px-6 py-4 text-center">Transaksi</th>
                            <th class="px-6 py-4 text-right">Omzet</th>
                            <th class="px-6 py-4 text-right">Kas Diterima</th>
                            <th class="px-6 py-4 text-right">Piutang Tempo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                        @forelse ($dailyOutletReports as $report)
                            <tr class="transition hover:bg-gray-50/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 font-bold">
                                            {{ substr($report->nama_cabang, 0, 1) }}
                                        </div>
                                        <p class="font-bold text-gray-900">{{ $report->nama_cabang }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-gray-700 font-bold text-xs">
                                        {{ number_format($report->total_transaksi, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-gray-900">Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-black text-emerald-600">Rp {{ number_format($report->total_diterima, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-black text-rose-600">Rp {{ number_format($report->total_piutang, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <p class="font-bold text-gray-500">Belum ada transaksi</p>
                                        <p class="text-xs text-gray-400 mt-1">Tidak ada data untuk rentang waktu ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ================= BOTTOM SECTION: INCOME TRANSACTIONS ================= -->
    <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-black text-gray-900">Rincian Transaksi Pendapatan</h2>
                <p class="text-xs text-gray-500 font-medium mt-1">Daftar semua struk/pemasukan pada filter tanggal saat ini.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap min-w-[800px]">
                <thead class="text-[10px] font-extrabold text-gray-500 uppercase tracking-wider bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Invoice & Pelanggan</th>
                        <th class="px-6 py-4">Lokasi & Kasir</th>
                        <th class="px-6 py-4 text-center">Metode</th>
                        <th class="px-6 py-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                    @forelse($incomeTransactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('d M Y') }}</p>
                                <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-black text-[#CC9863] hover:text-[#b58555] transition-colors cursor-pointer">{{ $trx->nomor_nota }}</p>
                                <div class="flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <p class="text-[11px] text-gray-500 font-medium">{{ $trx->member->nama ?? 'Pelanggan Umum' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">{{ $trx->branch->nama_cabang ?? 'Pusat' }}</p>
                                <div class="flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <p class="text-[11px] text-gray-500 font-medium">{{ explode(' ', $trx->cashier->nama_lengkap ?? 'Kasir')[0] }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $methodColors = [
                                        'cash'       => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'qris'       => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'transfer'   => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        'tempo'      => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'cash_tempo' => 'bg-orange-50 text-orange-600 border-orange-100',
                                    ];
                                    $colorClass = $methodColors[strtolower($trx->metode_bayar)] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold border uppercase tracking-wider {{ $colorClass }}">
                                    {{ str_replace('_', ' ', $trx->metode_bayar) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-gray-900 text-base">
                                Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="font-bold text-gray-500">Tidak ada catatan transaksi</p>
                                    <p class="text-xs text-gray-400 mt-1">Belum ada pemasukan di rentang waktu ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($incomeTransactions->hasPages())
            <div class="p-4 border-t border-gray-100 bg-white">
                {{ $incomeTransactions->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

</main>

<!-- ================= CHART.JS ================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Chart === 'undefined') return;

    const ctx = document.getElementById('incomeChart');
    if (!ctx) return;

    const chartData = {
        labels: @json($chartData['labels'] ?? []),
        data: @json($chartData['income'] ?? [])
    };

    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
    gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Total Omzet (Rp)',
                data: chartData.data,
                borderColor: '#6366F1',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#ffffff',
                pointHoverBorderColor: '#6366F1',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + Number(context.raw).toLocaleString('id-ID');
                        },
                        title: function(context) {
                            return 'Tanggal: ' + context[0].label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { color: '#9CA3AF', font: { size: 11, weight: '600' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F3F4F6', drawBorder: false },
                    border: { display: false },
                    ticks: {
                        color: '#9CA3AF',
                        font: { size: 11, weight: '600' },
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000) + ' Jt';
                            if (value >= 1000) return (value / 1000) + ' K';
                            return value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
