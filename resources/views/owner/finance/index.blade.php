@extends('template.sidebar')
@section('title', 'Analytics Finance')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative min-h-screen">

        <!-- Loader Overlay (AJAX) -->
        <div id="ajax-loader"
            class="fixed inset-0 bg-white/70 backdrop-blur-sm z-[100] hidden items-center justify-center transition-all">
            <div class="flex flex-col items-center gap-4 bg-white p-8 rounded-[2rem] shadow-2xl border border-gray-100">
                <div class="w-12 h-12 border-4 border-[#CC9863]/30 border-t-[#CC9863] rounded-full animate-spin"></div>
                <p class="text-sm font-black text-gray-800 tracking-wide uppercase">Mengkalkulasi Data...</p>
            </div>
        </div>

        <!-- ================= HEADER SECTION ================= -->
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-5 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Analytics Finance</h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Laporan terperinci arus kas dan profitabilitas bisnis Anda.
                </p>
            </div>

            <!-- Filter Form (AJAX) & Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto" x-data="{ period: '{{ $period ?? 'this_month' }}' }">
                <div class="bg-white p-1.5 rounded-2xl border border-gray-200 shadow-sm flex-1 sm:flex-none">
                    <form id="financeFilter" onsubmit="event.preventDefault(); window.applyFinanceFilter();"
                        class="flex flex-col sm:flex-row items-center gap-2 w-full">

                        <div class="flex items-center w-full sm:w-auto">
                            <select name="period" x-model="period"
                                @change="if(period !== 'custom') window.applyFinanceFilter();"
                                class="w-full sm:w-[160px] h-[40px] px-4 bg-gray-50 border border-transparent rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/30 focus:border-[#CC9863] focus:bg-white transition-colors cursor-pointer appearance-none">
                                <option value="today">Hari Ini</option>
                                <option value="this_week">Minggu Ini</option>
                                <option value="this_month">Bulan Ini</option>
                                <option value="this_year">Tahun Ini</option>
                                <option value="all">Keseluruhan</option>
                                <option value="custom">Pilih Tanggal...</option>
                            </select>
                        </div>

                        <!-- Custom Date Inputs -->
                        <div x-show="period === 'custom'" x-transition class="flex w-full sm:w-auto items-center gap-2"
                            style="display: none;">
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="h-[40px] px-3 w-full sm:w-[130px] bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                            <span class="text-gray-400 font-bold">-</span>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="h-[40px] px-3 w-full sm:w-[130px] bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:border-[#CC9863]">

                            <button type="submit"
                                class="h-[40px] px-5 bg-[#CC9863] text-white rounded-xl font-bold hover:bg-[#b58555] transition text-sm flex items-center justify-center shadow-md shadow-[#CC9863]/20 shrink-0">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>

                <a href="{{ route('owner.transaction.index') }}"
                    class="h-[52px] bg-white border-2 border-gray-200 text-gray-700 px-5 rounded-2xl font-bold hover:border-[#CC9863] hover:text-[#CC9863] flex items-center justify-center gap-2 text-sm transition-all w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Semua Transaksi
                </a>


            </div>
        </div>

        <!-- Data Wrapper for AJAX -->
        <div id="finance-data-wrapper">

            <div class="mb-6 flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Periode Laporan:</span>
                <span
                    class="bg-[#CC9863]/10 text-[#CC9863] border border-[#CC9863]/20 font-black tracking-wide text-[11px] px-3 py-1 rounded-lg">{{ $periodLabel ?? 'Bulan Ini' }}</span>
            </div>

            <!-- ================= TOP METRICS CARDS ================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-8">

                <!-- 1. LABA BERSIH -->
                <div
                    class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition group border-b-4 {{ $labaBersih >= 0 ? 'border-b-emerald-500' : 'border-b-red-500' }}">
                    <div>
                        <h3
                            class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 group-hover:text-gray-900 transition-colors">
                            Total Balance (Net Profit)</h3>
                        <h2 class="text-2xl lg:text-3xl font-black text-gray-900 mb-4">Rp
                            {{ number_format($labaBersih, 0, ',', '.') }}</h2>
                    </div>
                    <div class="flex items-center justify-between">
                        <span
                            class="{{ $marginPercentage >= 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-600 border-red-100' }} border text-[11px] font-extrabold px-2.5 py-1 rounded-lg flex items-center gap-1.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if ($marginPercentage >= 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                @endif
                            </svg>
                            {{ $marginPercentage }}% Margin
                        </span>
                    </div>
                </div>

                <!-- 2. LABA KOTOR -->
                <div
                    class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition group">
                    <div>
                        <h3
                            class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 group-hover:text-gray-900 transition-colors">
                            Gross Profit (Laba Kotor)</h3>
                        <h2 class="text-2xl lg:text-3xl font-black text-gray-900 mb-4">Rp
                            {{ number_format($labaKotor, 0, ',', '.') }}</h2>
                    </div>
                    <div>
                        <span
                            class="text-[10px] font-bold text-gray-400 bg-gray-50 border border-gray-100 px-2.5 py-1.5 rounded-lg">Omzet
                            - Modal Barang</span>
                    </div>
                </div>

                <!-- 3. TOTAL EXPENSE (LINK TO EXPENSE) -->
                <a href="{{ route('owner.expense.index') }}"
                    class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between hover:shadow-md hover:border-[#CC9863]/50 transition group cursor-pointer relative overflow-hidden">
                    <div class="absolute right-4 top-4 text-gray-200 group-hover:text-[#CC9863] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </div>
                    <div>
                        <h3
                            class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 group-hover:text-gray-900 transition-colors">
                            Total Expenses</h3>
                        <h2 class="text-2xl lg:text-3xl font-black text-rose-600 mb-4">Rp
                            {{ number_format($totalBeban, 0, ',', '.') }}</h2>
                    </div>
                    <div>
                        <span
                            class="text-[10px] font-bold text-gray-400 bg-gray-50 border border-gray-100 px-2.5 py-1.5 rounded-lg group-hover:text-[#CC9863] group-hover:bg-[#CC9863]/10 transition-colors">HPP
                            + Biaya Ops. &rarr;</span>
                    </div>
                </a>

                <!-- 4. TOTAL ASET BARANG (LINK TO STOCK) -->
                <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                    class="bg-[#1C1D21] rounded-3xl border border-black shadow-lg p-6 flex flex-col justify-between hover:bg-[#CC9863] hover:border-[#CC9863] transition-all group cursor-pointer relative overflow-hidden">
                    <div class="absolute right-4 top-4 text-gray-500 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <h3
                                class="text-[11px] font-black text-gray-400 group-hover:text-white/80 uppercase tracking-widest transition-colors">
                                Total Aset Stok (Modal)</h3>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-black text-white mb-2">Rp
                            {{ number_format($totalAsetModal ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <div>
                        <span
                            class="text-[10px] font-bold text-gray-400 bg-white/10 border border-white/20 group-hover:text-white px-2.5 py-1.5 rounded-lg transition-colors">Cek
                            Gudang & Cabang &rarr;</span>
                    </div>
                </a>

            </div>

            <!-- ================= CHART SECTION ================= -->
            @php
                $hppPercentage = $totalBeban > 0 ? round(($totalHpp / $totalBeban) * 100) : 0;
                $opsPercentage = $totalBeban > 0 ? round(($totalPengeluaran / $totalBeban) * 100) : 0;
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- LINE CHART -->
                <div
                    class="lg:col-span-2 bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 flex flex-col relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6 relative z-10">
                        <div>
                            <h2 class="text-lg font-black text-gray-900">Income & Expense Overview</h2>
                            <p class="text-[11px] font-medium text-gray-400 mt-1">Pergerakan omzet dan biaya pada periode
                                terpilih.</p>
                        </div>
                        <div class="flex items-center gap-3 text-[10px] font-extrabold uppercase tracking-wider">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span><span
                                    class="text-gray-600">Omzet</span></div>
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100">
                                <span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span><span
                                    class="text-gray-600">Biaya</span></div>
                        </div>
                    </div>
                    <div class="relative w-full h-[280px] sm:h-[320px] z-10">
                        <canvas id="balanceChart"></canvas>
                    </div>
                    <div id="finance-chart-data" class="hidden" data-chart='@json($chartData)'
                        data-hpp="{{ (float) $totalHpp }}" data-ops="{{ (float) $totalPengeluaran }}"></div>
                </div>

                <!-- DONUT CHART -->
                <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 flex flex-col">
                    <div class="mb-5">
                        <h2 class="text-lg font-black text-gray-900">Expense Breakdown</h2>
                        <p class="text-[11px] text-gray-400 font-medium mt-1">Komposisi HPP dan biaya operasional.</p>
                    </div>
                    <div class="flex-1 flex items-center justify-center py-2">
                        <div class="relative w-[190px] h-[190px] sm:w-[210px] sm:h-[210px]">
                            <canvas id="expenseChart"></canvas>
                            <div
                                class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                                <span class="text-[9px] uppercase tracking-widest font-extrabold text-gray-400">Total
                                    Beban</span>
                                <span class="text-base font-black text-gray-900 mt-1">
                                    @if ($totalBeban >= 1000000)
                                        Rp {{ number_format($totalBeban / 1000000, 1, ',', '.') }} Jt
                                    @elseif($totalBeban >= 1000)
                                        Rp {{ number_format($totalBeban / 1000, 0, ',', '.') }} Rb
                                    @else
                                        Rp {{ number_format($totalBeban, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3 mt-4">
                        <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                            class="block p-3.5 bg-gray-50 rounded-2xl border border-gray-100 hover:border-[#CC9863] transition-colors group">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2"><span
                                        class="w-3 h-3 bg-[#1C1D21] rounded-md shrink-0"></span><span
                                        class="text-xs font-extrabold text-gray-700 group-hover:text-[#CC9863]">Modal /
                                        HPP</span></div>
                                <span class="text-xs font-black text-gray-900">{{ $hppPercentage }}%</span>
                            </div>
                            <div class="mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-[#1C1D21] rounded-full transition-all duration-700"
                                    style="width: {{ $hppPercentage }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-500 font-bold mt-2">Rp
                                {{ number_format($totalHpp, 0, ',', '.') }}</p>
                        </a>
                        <a href="{{ route('owner.expense.index') }}"
                            class="block p-3.5 bg-orange-50/50 rounded-2xl border border-orange-50 hover:border-[#CC9863] transition-colors group">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2"><span
                                        class="w-3 h-3 bg-[#CC9863] rounded-md shrink-0"></span><span
                                        class="text-xs font-extrabold text-gray-700 group-hover:text-[#CC9863]">Operasional</span>
                                </div>
                                <span class="text-xs font-black text-[#CC9863]">{{ $opsPercentage }}%</span>
                            </div>
                            <div class="mt-2 h-1.5 bg-orange-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#CC9863] rounded-full transition-all duration-700"
                                    style="width: {{ $opsPercentage }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-500 font-bold mt-2">Rp
                                {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ================= TABEL CABANG & HPP ================= -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- TABLE CABANG -->
                <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 sm:p-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                        <div>
                            <h2 class="text-base font-black text-gray-900">Rincian Keuangan per Outlet</h2>
                            <p class="text-[11px] font-medium text-gray-500 mt-1">Laba Bersih = Omzet - (HPP + Ops) cabang.
                            </p>
                        </div>
                        <a href="{{ route('owner.outlet.index') }}"
                            class="text-[10px] font-bold uppercase tracking-wider text-[#CC9863] hover:text-[#b58555] bg-orange-50 px-3 py-2 rounded-lg transition-colors">Kelola
                            Outlet</a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead
                                class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Nama Outlet</th>
                                    <th class="px-6 py-4 text-right">Laba Kotor</th>
                                    <th class="px-6 py-4 text-right">Ops / Pengeluaran</th>
                                    <th class="px-6 py-4 text-right">Laba Bersih</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50">
                                @foreach ($branchReports as $report)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-xl bg-[#1C1D21] flex items-center justify-center text-[#CC9863] font-black text-[10px] uppercase shadow-sm">
                                                    {{ substr($report->nama_cabang, 0, 2) }}
                                                </div>
                                                <span class="font-bold text-gray-900">{{ $report->nama_cabang }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-gray-900">Rp
                                            {{ number_format($report->laba_kotor, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right font-bold text-rose-500">Rp
                                            {{ number_format($report->pengeluaran, 0, ',', '.') }}</td>
                                        <td
                                            class="px-6 py-4 text-right font-black {{ $report->laba_bersih >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                            Rp {{ number_format($report->laba_bersih, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TABLE HPP DETAILS -->
                <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 sm:p-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                        <div>
                            <h2 class="text-base font-black text-gray-900">Rincian Modal Barang (HPP)</h2>
                            <p class="text-[11px] font-medium text-gray-500 mt-1">Barang penyumbang modal pengeluaran
                                terbesar.</p>
                        </div>
                        <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                            class="text-[10px] font-bold uppercase tracking-wider text-[#CC9863] hover:text-[#b58555] bg-orange-50 px-3 py-2 rounded-lg transition-colors">Kelola
                            Stok</a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead
                                class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Produk Terjual</th>
                                    <th class="px-6 py-4 text-center">Qty</th>
                                    <th class="px-6 py-4 text-right">Total HPP</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50">
                                @forelse($hppDetails->take(5) as $item)
                                    <tr onclick="window.location='{{ url(auth()->user()->role->nama_role . '/stock/edit/' . ($item->variant->product->id ?? '')) }}'"
                                        class="hover:bg-gray-50/50 transition cursor-pointer group">
                                        <td class="px-6 py-4">
                                            <p
                                                class="font-bold text-gray-900 group-hover:text-[#CC9863] transition-colors">
                                                {{ $item->variant->product->nama_produk ?? 'Unknown' }}</p>
                                            <span
                                                class="inline-block mt-0.5 text-[10px] font-bold text-gray-500">{{ $item->variant->nama_varian ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg text-[11px] font-black border border-gray-200 shadow-sm">{{ $item->total_qty }}
                                                <span
                                                    class="text-[9px] font-semibold uppercase">{{ $item->variant->satuan ?? 'pcs' }}</span></span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-gray-900">
                                            Rp {{ number_format($item->total_modal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            class="px-6 py-12 text-center text-gray-400 font-bold italic text-xs">Belum ada
                                            barang terjual pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($totalHpp > 0)
                                <tfoot class="bg-gray-50 text-gray-900 border-t border-gray-200">
                                    <tr>
                                        <td colspan="2"
                                            class="px-6 py-4 font-black tracking-widest text-[10px] uppercase text-right">
                                            Total Keseluruhan HPP</td>
                                        <td class="px-6 py-4 text-right font-black text-base">Rp
                                            {{ number_format($totalHpp, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- ================= TABEL PENGELUARAN ================= -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden mb-6">
                <div class="p-5 sm:p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <div>
                        <h2 class="text-base font-black text-gray-900">Daftar Pengeluaran Operasional</h2>
                        <p class="text-[11px] text-gray-500 font-medium mt-1">Catatan pengeluaran harian seluruh cabang.
                        </p>
                    </div>
                    <a href="{{ route('owner.expense.index') }}"
                        class="text-[10px] font-bold uppercase tracking-wider text-[#CC9863] hover:text-[#b58555] bg-orange-50 px-4 py-2.5 rounded-xl transition-colors shadow-sm flex items-center gap-2">
                        Kelola Beban <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-5">Tanggal</th>
                                <th class="px-6 py-5">Kategori & Nama</th>
                                <th class="px-6 py-5">Lokasi (Cabang)</th>
                                <th class="px-6 py-5 text-right">Nominal Pengeluaran</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                            @forelse($expenses->take(10) as $exp)
                                <tr onclick="window.location='{{ route('owner.expense.index') }}'"
                                    class="hover:bg-gray-50/50 transition cursor-pointer group">
                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($exp->tanggal_pengeluaran)->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900 group-hover:text-[#CC9863] transition-colors">
                                            {{ $exp->nama_pengeluaran }}</p>
                                        <span
                                            class="inline-block mt-1 text-[9px] font-extrabold uppercase tracking-wider bg-gray-100 text-gray-500 px-2.5 py-1 rounded-md border border-gray-200 shadow-sm">{{ $exp->kategori_pengeluaran }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-600">
                                        {{ $exp->branch->nama_cabang ?? 'Pusat' }}</td>
                                    <td class="px-6 py-4 text-right font-black text-rose-500 text-[15px]">Rp
                                        {{ number_format($exp->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-16 text-center text-gray-400 font-bold italic text-xs">Tidak ada
                                        catatan pengeluaran operasional pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- End Data Wrapper -->
    </main>

    <!-- ================= CHART.JS ================= -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        let lineChartInstance = null;
        let donutChartInstance = null;

        const rupiah = value => 'Rp ' + Number(value || 0).toLocaleString('id-ID');
        const compact = value => {
            value = Number(value || 0);
            if (Math.abs(value) >= 1000000000) return 'Rp ' + (value / 1000000000).toLocaleString('id-ID', {
                maximumFractionDigits: 1
            }) + ' M';
            if (Math.abs(value) >= 1000000) return 'Rp ' + (value / 1000000).toLocaleString('id-ID', {
                maximumFractionDigits: 1
            }) + ' jt';
            if (Math.abs(value) >= 1000) return 'Rp ' + (value / 1000).toLocaleString('id-ID', {
                maximumFractionDigits: 0
            }) + ' rb';
            return rupiah(value);
        };

        function initCharts(dataObj) {
            if (typeof Chart === 'undefined') return;

            // LINE CHART
            const balanceCanvas = document.getElementById('balanceChart');
            if (balanceCanvas) {
                if (lineChartInstance) lineChartInstance.destroy();
                const ctx = balanceCanvas.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 320);
                gradient.addColorStop(0, 'rgba(204, 152, 99, 0.3)'); // Coklat Emas
                gradient.addColorStop(1, 'rgba(204, 152, 99, 0)');

                lineChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dataObj.labels,
                        datasets: [{
                                label: 'Omzet',
                                data: dataObj.income,
                                borderColor: '#CC9863',
                                backgroundColor: gradient,
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                pointHoverBorderWidth: 3,
                                pointHoverBackgroundColor: '#ffffff',
                                pointHoverBorderColor: '#CC9863'
                            },
                            {
                                label: 'Total Biaya',
                                data: dataObj.expense,
                                borderColor: '#D1D5DB',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                borderDash: [6, 5],
                                fill: false,
                                tension: 0.35,
                                pointRadius: 0,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: '#ffffff',
                                pointHoverBorderColor: '#9CA3AF'
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
                        animation: {
                            duration: 0
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1C1D21',
                                padding: 12,
                                cornerRadius: 12,
                                titleFont: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                callbacks: {
                                    label: ctx => ` ${ctx.dataset.label}: ${rupiah(ctx.raw)}`
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
                                        weight: '800'
                                    },
                                    maxTicksLimit: 12
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
                                        weight: '800'
                                    },
                                    callback: val => compact(val)
                                }
                            }
                        }
                    }
                });
            }

            // DONUT CHART
            const expenseCanvas = document.getElementById('expenseChart');
            if (expenseCanvas) {
                if (donutChartInstance) donutChartInstance.destroy();
                const hasExpense = dataObj.totalHpp > 0 || dataObj.totalPengeluaran > 0;
                const expenseData = hasExpense ? [dataObj.totalHpp, dataObj.totalPengeluaran] : [1, 0];

                donutChartInstance = new Chart(expenseCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['Modal / HPP', 'Biaya Operasional'],
                        datasets: [{
                            data: expenseData,
                            backgroundColor: hasExpense ? ['#1C1D21', '#CC9863'] : ['#F3F4F6', '#F3F4F6'],
                            borderWidth: 0,
                            spacing: hasExpense ? 2 : 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '78%',
                        animation: {
                            duration: 0
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: hasExpense,
                                backgroundColor: '#1C1D21',
                                padding: 12,
                                cornerRadius: 12,
                                titleFont: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 11
                                },
                                callbacks: {
                                    label: ctx => {
                                        const value = Number(ctx.raw || 0);
                                        const total = dataObj.totalHpp + dataObj.totalPengeluaran;
                                        const pct = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return ` ${ctx.label}: ${rupiah(value)} (${pct}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Fungsi AJAX Filter
        window.applyFinanceFilter = function() {
            const form = document.getElementById('financeFilter');
            const params = new URLSearchParams(new FormData(form)).toString();
            const loader = document.getElementById('ajax-loader');

            loader.classList.remove('hidden');
            loader.classList.add('flex');

            fetch(`{{ route('owner.finance.index') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    window.history.pushState({}, '', `?${params}`);
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data.html, 'text/html');
                    document.getElementById('finance-data-wrapper').innerHTML = doc.getElementById(
                        'finance-data-wrapper').innerHTML;

                    const chartDataEl = document.getElementById('finance-chart-data');
                    if (chartDataEl) {
                        const newDataObj = JSON.parse(chartDataEl.dataset.chart);
                        newDataObj.totalHpp = parseFloat(chartDataEl.dataset.hpp);
                        newDataObj.totalPengeluaran = parseFloat(chartDataEl.dataset.ops);
                        initCharts(newDataObj);
                    }
                })
                .catch(err => console.error("Error loading data:", err))
                .finally(() => {
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const chartDataEl = document.getElementById('finance-chart-data');
            if (chartDataEl) {
                const initialData = JSON.parse(chartDataEl.dataset.chart);
                initialData.totalHpp = parseFloat(chartDataEl.dataset.hpp);
                initialData.totalPengeluaran = parseFloat(chartDataEl.dataset.ops);
                initCharts(initialData);
            }
        });
    </script>
@endsection
