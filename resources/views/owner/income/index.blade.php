@extends('template.sidebar')
@section('title', 'Laporan Pendapatan (Income)')

@section('content')
<main class="flex-1 bg-[#F3F4F6] overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full relative">

    <!-- ================= HEADER SECTION & FILTER GLOBAL ================= -->
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-end gap-5 mb-6">

        {{-- TITLE --}}
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                Income Report
            </h1>
            <p class="text-gray-500 text-sm mt-1 font-medium">
                Analisis pendapatan, transaksi, dan performa outlet.
            </p>
        </div>

        {{-- FILTER --}}
        <form action="{{ route('owner.income.index') }}" method="GET" id="incomeFilter" class="w-full xl:w-auto">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                <div class="flex flex-col lg:flex-row lg:items-end gap-3">

                    {{-- MONTH --}}
                    <div class="min-w-[135px]">
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                            Bulan
                        </label>
                        <select name="month" id="monthFilter" class="w-full h-[42px] px-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ (int) request('month', $month) === $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- YEAR --}}
                    <div class="min-w-[105px]">
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                            Tahun
                        </label>
                        <select name="year" id="yearFilter" class="w-full h-[42px] px-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                            @php $currentYear = now()->year; @endphp
                            @for ($y = $currentYear; $y >= $currentYear - 5; $y--)
                                <option value="{{ $y }}" {{ (int) request('year', $year) === $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- DIVIDER --}}
                    <div class="hidden lg:flex h-[42px] items-center px-1">
                        <span class="text-[10px] font-black text-gray-300">ATAU</span>
                    </div>

                    {{-- START DATE --}}
                    <div>
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Dari</label>
                        <input type="date" name="start_date" id="startDate" value="{{ request('start_date') }}" class="w-full lg:w-[145px] h-[42px] px-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                    </div>

                    {{-- END DATE --}}
                    <div>
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Sampai</label>
                        <input type="date" name="end_date" id="endDate" value="{{ request('end_date') }}" min="{{ request('start_date') }}" class="w-full lg:w-[145px] h-[42px] px-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                    </div>

                    {{-- BRANCH --}}
                    <div class="min-w-[160px]">
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Outlet</label>
                        <select name="branch_id" class="w-full h-[42px] px-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                            <option value="">Semua Outlet</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (string) request('branch_id') === (string) $branch->id ? 'selected' : '' }}>
                                    {{ $branch->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PAYMENT --}}
                    <div class="min-w-[150px]">
                        <label class="block mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Pembayaran</label>
                        <select name="payment_method" class="w-full h-[42px] px-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                            <option value="">Semua Metode</option>
                            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="transfer" {{ request('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="cash_tempo" {{ request('payment_method') === 'cash_tempo' ? 'selected' : '' }}>Cash Tempo</option>
                            <option value="tempo" {{ request('payment_method') === 'tempo' ? 'selected' : '' }}>Tempo</option>
                        </select>
                    </div>

                    {{-- APPLY --}}
                    <button type="submit" class="h-[42px] px-5 bg-[#1C1D21] text-white rounded-xl font-bold hover:bg-black transition text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13v5a1 1 0 01-.553.894l-4 2A1 1 0 018 20v-7L3.2 4.6A1 1 0 013 4z"/></svg>
                        Terapkan
                    </button>
                </div>

                {{-- BOTTOM ACTION --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-4 pt-4 border-t border-gray-100">

                    {{-- ACTIVE PERIOD & FILTERS BADGES --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Periode Aktif</span>
                        <span class="inline-flex items-center bg-[#CC9863]/10 text-[#9C7046] px-3 py-1.5 rounded-lg text-xs font-black">
                            {{ $monthName }}
                        </span>

                        @if(request('branch_id'))
                            <span class="inline-flex items-center bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold border border-blue-100">
                                {{ $branches->firstWhere('id', request('branch_id'))?->nama_cabang }}
                            </span>
                        @endif

                        @if(request('payment_method'))
                            <span class="inline-flex items-center bg-purple-50 text-purple-600 px-3 py-1.5 rounded-lg text-xs font-bold uppercase border border-purple-100">
                                {{ str_replace('_', ' ', request('payment_method')) }}
                            </span>
                        @endif
                    </div>

                    {{-- RESET & EXPORT --}}
                    <div class="flex flex-wrap items-center gap-2">
                        @if(request()->anyFilled(['start_date', 'end_date', 'branch_id', 'payment_method', 'month', 'year']))
                            <a href="{{ route('owner.income.index') }}" class="h-[42px] px-4 rounded-xl text-xs font-bold bg-gray-100 text-gray-600 hover:bg-gray-200 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Reset
                            </a>
                        @endif

                        <a href="{{ route('owner.income.print', request()->only(['month', 'year', 'start_date', 'end_date', 'branch_id', 'payment_method'])) }}" target="_blank" rel="noopener" class="bg-gray-900 text-white h-[42px] px-5 rounded-xl font-bold shadow-sm hover:bg-black transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-2 0v4H8v-4m-2-9h12"></path>
                            </svg>
                            Cetak / PDF
                        </a>

                        <a href="{{ route('owner.income.export', request()->only(['month', 'year', 'start_date', 'end_date', 'branch_id', 'payment_method'])) }}" class="bg-[#CC9863] text-white h-[42px] px-5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Excel (.xlsx)
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Info Periode Aktif -->
    <div class="mb-4">
        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Data Periode: <span class="text-gray-900">{{ $monthName }}</span></h2>
    </div>

    <!-- ================= TOP METRICS CARDS ================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- 1. TOTAL INCOME -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full blur-2xl"></div>
            <div class="flex justify-between items-center mb-3 relative z-10">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Omzet</h3>
                <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-2 relative z-10">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
            <p class="text-[11px] font-semibold text-gray-400">Total pendapatan kotor pada periode filter.</p>
        </div>

        <!-- 2. TOTAL TRANSAKSI -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full blur-2xl"></div>
            <div class="flex justify-between items-center mb-3 relative z-10">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Transaksi</h3>
                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-2 relative z-10">
                {{ number_format($totalTrx, 0, ',', '.') }} <span class="text-base text-gray-400">Trx</span>
            </h2>
            <p class="text-[11px] font-semibold text-gray-400">Jumlah struk/nota yang diterbitkan.</p>
        </div>

        <!-- 3. RATA-RATA TRANSAKSI -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-50 rounded-full blur-2xl"></div>
            <div class="flex justify-between items-center mb-3 relative z-10">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Rata-Rata Order</h3>
                <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-[#CC9863]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-2 relative z-10">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</h2>
            <p class="text-[11px] font-semibold text-gray-400">Nilai rata-rata keranjang per pelanggan.</p>
        </div>
    </div>

    <!-- ================= TREND CHART ================= -->
    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-5 sm:p-6 mb-8 flex flex-col">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
            <div>
                <p class="text-[10px] font-extrabold text-indigo-500 uppercase tracking-wider mb-1">Financial Performance</p>
                <h2 class="text-base sm:text-lg font-extrabold text-gray-900">Grafik Omzet Pemasukan</h2>
            </div>
        </div>
        <div class="relative w-full h-[250px] sm:h-[300px]">
            <canvas id="incomeChart"></canvas>
        </div>
    </div>

    <!-- ================= DAILY OUTLET REPORT (MENGIKUTI FILTER GLOBAL) ================= -->
    <section class="mb-8" aria-labelledby="daily-outlet-report-title">
        <div class="flex flex-col gap-4 mb-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 id="daily-outlet-report-title" class="text-xl font-black text-gray-900">Rekap Pendapatan per Outlet</h2>
                <p class="text-sm text-gray-500 mt-1 font-medium">Berdasarkan filter periode saat ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 mb-4">
            <div class="rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm border-l-4 border-l-[#CC9863]">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Total Omzet</p>
                <p class="mt-2 text-xl font-black text-gray-900">Rp {{ number_format($dailySummary['total_pendapatan'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm border-l-4 border-l-green-500">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Kas Tunai Masuk</p>
                <p class="mt-2 text-xl font-black text-green-600">Rp {{ number_format($dailySummary['total_diterima'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm border-l-4 border-l-red-500">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Total Piutang / Tempo</p>
                <p class="mt-2 text-xl font-black text-red-600">Rp {{ number_format($dailySummary['total_piutang'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-[24px] border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left">
                    <thead class="border-b border-gray-100 bg-gray-50/70 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="px-5 py-4">Outlet</th>
                            <th class="px-5 py-4 text-center">Trx</th>
                            <th class="px-5 py-4 text-right">Omzet</th>
                            <th class="px-5 py-4 text-right">Kas diterima</th>
                            <th class="px-5 py-4 text-right">Piutang tempo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                        @forelse ($dailyOutletReports as $report)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <p class="font-extrabold text-gray-900">{{ $report->nama_cabang }}</p>
                                </td>
                                <td class="px-5 py-4 text-center font-bold text-gray-700">{{ number_format($report->total_transaksi, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-right font-black text-gray-900">Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-right font-black text-green-600">Rp {{ number_format($report->total_diterima, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-right font-black text-red-600">Rp {{ number_format($report->total_piutang, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center">
                                    <p class="font-bold text-gray-500 italic">Belum ada transaksi di rentang tanggal ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ================= BOTTOM SECTION: INCOME TRANSACTIONS ================= -->
    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-base font-bold text-gray-900">Rincian Transaksi Pendapatan</h2>
                <p class="text-[11px] text-gray-500 font-semibold mt-1">Daftar semua struk/pemasukan pada filter tanggal saat ini.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                        <th class="px-6 py-4">Invoice / Nota</th>
                        <th class="px-6 py-4">Lokasi & Kasir</th>
                        <th class="px-6 py-4 text-center">Metode</th>
                        <th class="px-6 py-4 text-right">Nominal Omzet</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                    @forelse($incomeTransactions as $trx)
                        <tr class="hover:bg-gray-50 transition cursor-pointer">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('d M Y') }}</p>
                                <p class="text-[10px] text-gray-500 font-semibold mt-0.5">{{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-black text-[#CC9863]">{{ $trx->nomor_nota }}</p>
                                <p class="text-[10px] text-gray-500 font-semibold mt-0.5">Plg: {{ $trx->member->nama ?? 'Umum' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">{{ $trx->branch->nama_cabang ?? 'Pusat' }}</p>
                                <p class="text-[10px] text-gray-500 font-semibold mt-0.5">Oleh: {{ explode(' ', $trx->cashier->nama_lengkap ?? 'Kasir')[0] }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $methodColors = [
                                        'cash'       => 'bg-green-50 text-green-600 border-green-100',
                                        'qris'       => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'transfer'   => 'bg-purple-50 text-purple-600 border-purple-100',
                                        'tempo'      => 'bg-red-50 text-red-600 border-red-100',
                                        'cash_tempo' => 'bg-red-50 text-red-600 border-red-100',
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
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium italic">Tidak ada catatan pemasukan/transaksi di rentang waktu ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($incomeTransactions->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
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
    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
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
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return ' Omzet: Rp ' + Number(context.raw).toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { color: '#9CA3AF', font: { size: 10, weight: '600' } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F3F4F6' },
                    border: { display: false },
                    ticks: {
                        color: '#9CA3AF',
                        font: { size: 10, weight: '600' },
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000) + 'jt';
                            if (value >= 1000) return (value / 1000) + 'k';
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
