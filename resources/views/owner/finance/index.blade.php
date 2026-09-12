@extends('template.sidebar')
@section('title', 'Analytics Finance')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full relative min-h-screen">

        <!-- Loader Overlay -->
        <div id="ajax-loader"
            class="fixed inset-0 bg-white/60 backdrop-blur-sm z-[100] hidden items-center justify-center transition-all">
            <div class="flex flex-col items-center gap-3 bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <div class="w-10 h-10 border-4 border-indigo-600/30 border-t-indigo-600 rounded-full animate-spin"></div>
                <p class="text-sm font-bold text-gray-700">Mengkalkulasi Laporan...</p>
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
            <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto" x-data="{ period: '{{ $period }}' }">
                <div class="bg-white p-1.5 rounded-2xl border border-gray-200 shadow-sm flex-1 sm:flex-none">
                    <form id="financeFilter" onsubmit="event.preventDefault(); window.applyFinanceFilter();"
                        class="flex flex-col sm:flex-row items-center gap-2 w-full">

                        <div class="flex items-center w-full sm:w-auto">
                            <select name="period" x-model="period"
                                @change="if(period !== 'custom') window.applyFinanceFilter();"
                                class="w-full sm:w-[160px] h-[40px] px-3 bg-gray-50 border border-transparent rounded-xl text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-500 focus:bg-white transition-colors cursor-pointer">
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
                                class="h-[40px] px-3 w-full sm:w-[130px] bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:border-indigo-500">
                            <span class="text-gray-400 font-bold">-</span>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="h-[40px] px-3 w-full sm:w-[130px] bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 focus:outline-none focus:border-indigo-500">

                            <button type="submit"
                                class="h-[40px] px-4 bg-[#1C1D21] text-white rounded-xl font-bold hover:bg-black transition text-sm flex items-center justify-center shadow-sm shrink-0">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>

                <button onclick="window.print()"
                    class="h-[52px] bg-indigo-600 text-white px-5 rounded-2xl font-bold shadow-md shadow-indigo-600/20 hover:bg-indigo-700 hover:shadow-lg hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm transition-all print:hidden w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Export Report
                </button>
            </div>
        </div>

        <!-- Data Wrapper for AJAX -->
        <div id="finance-data-wrapper">

            <div class="mb-5 flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Periode Analisis:</span>
                <span
                    class="bg-indigo-50 text-indigo-600 border border-indigo-100 font-bold text-xs px-2.5 py-1 rounded-md">{{ $periodLabel }}</span>
            </div>

            <!-- ================= TOP METRICS CARDS ================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-8">

                <!-- 1. LABA BERSIH -->
                <div
                    class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition group border-b-4 {{ $labaBersih >= 0 ? 'border-b-green-500' : 'border-b-red-500' }}">
                    <div>
                        <h3
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 group-hover:text-gray-900 transition-colors">
                            Total Balance (Net Profit)</h3>
                        <h2 class="text-2xl lg:text-3xl font-black text-gray-900 mb-4">Rp
                            {{ number_format($labaBersih, 0, ',', '.') }}</h2>
                    </div>
                    <div class="flex items-center justify-between">
                        <span
                            class="{{ $marginPercentage >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-[11px] font-extrabold px-2.5 py-1 rounded-lg flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 group-hover:text-gray-900 transition-colors">
                            Gross Profit (Laba Kotor)</h3>
                        <h2 class="text-2xl lg:text-3xl font-black text-gray-900 mb-4">Rp
                            {{ number_format($labaKotor, 0, ',', '.') }}</h2>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-lg">Omzet - Modal
                            HPP Barang</span>
                    </div>
                </div>

                <!-- 3. TOTAL EXPENSE -->
                <div
                    class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between hover:shadow-md transition group">
                    <div>
                        <h3
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 group-hover:text-gray-900 transition-colors">
                            Total Expenses</h3>
                        <h2 class="text-2xl lg:text-3xl font-black text-gray-900 mb-4">Rp
                            {{ number_format($totalBeban, 0, ',', '.') }}</h2>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-lg">HPP Barang +
                            Biaya Ops.</span>
                    </div>
                </div>

                <!-- 4. TOTAL ASET BARANG (MODAL) -->
                <div
                    class="bg-indigo-50/50 rounded-3xl border border-indigo-100 shadow-sm p-6 flex flex-col justify-between hover:shadow-md hover:border-indigo-300 transition group">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xs font-bold text-indigo-900 uppercase tracking-wider">Total Aset Stok (Modal)
                            </h3>
                            <div
                                class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-black text-indigo-700 mb-2">Rp
                            {{ number_format($totalAsetModal ?? 0, 0, ',', '.') }}</h2>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-indigo-400 bg-indigo-100/50 px-2.5 py-1 rounded-lg">Nilai
                            aset riil Gudang & Cabang</span>
                    </div>
                </div>
            </div>

            <!-- ================= CHART SECTION ================= -->
            @php
                $hppPercentage = $totalBeban > 0 ? round(($totalHpp / $totalBeban) * 100) : 0;
                $opsPercentage = $totalBeban > 0 ? round(($totalPengeluaran / $totalBeban) * 100) : 0;
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- LINE CHART -->
                <div
                    class="lg:col-span-2 bg-white rounded-[24px] border border-gray-100 shadow-sm p-5 sm:p-6 flex flex-col">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-lg font-black text-gray-900">Income & Expense Overview</h2>
                            <p class="text-[11px] font-medium text-gray-400 mt-1">Pergerakan omzet dan biaya pada periode
                                terpilih.</p>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-bold">
                            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span><span
                                    class="text-gray-600">Omzet</span></div>
                            <div
                                class="flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100">
                                <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span><span
                                    class="text-gray-600">Total Biaya</span></div>
                        </div>
                    </div>
                    <div class="relative w-full h-[280px] sm:h-[320px]">
                        <canvas id="balanceChart"></canvas>
                    </div>
                    <!-- Data untuk re-render chart AJAX -->
                    <div id="finance-chart-data" class="hidden" data-chart='@json($chartData)'
                        data-hpp="{{ (float) $totalHpp }}" data-ops="{{ (float) $totalPengeluaran }}"></div>
                </div>

                <!-- DONUT CHART -->
                <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-5 sm:p-6 flex flex-col">
                    <div class="mb-5">
                        <h2 class="text-lg font-black text-gray-900">Expense Breakdown</h2>
                        <p class="text-[11px] text-gray-400 font-medium mt-1">Komposisi HPP dan biaya operasional.</p>
                    </div>
                    <div class="flex-1 flex items-center justify-center py-2">
                        <div class="relative w-[190px] h-[190px] sm:w-[210px] sm:h-[210px]">
                            <canvas id="expenseChart"></canvas>
                            <div
                                class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                                <span class="text-[9px] uppercase tracking-wider font-extrabold text-gray-400">Total
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
                        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2"><span
                                        class="w-3 h-3 bg-gray-800 rounded-md shrink-0"></span><span
                                        class="text-xs font-extrabold text-gray-700">Modal / HPP</span></div>
                                <span class="text-xs font-black text-gray-900">{{ $hppPercentage }}%</span>
                            </div>
                            <div class="mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gray-800 rounded-full transition-all duration-700"
                                    style="width: {{ $hppPercentage }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-500 font-bold mt-2">Rp
                                {{ number_format($totalHpp, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3.5 bg-indigo-50/60 rounded-2xl border border-indigo-50">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2"><span
                                        class="w-3 h-3 bg-indigo-500 rounded-md shrink-0"></span><span
                                        class="text-xs font-extrabold text-gray-700">Operasional</span></div>
                                <span class="text-xs font-black text-indigo-700">{{ $opsPercentage }}%</span>
                            </div>
                            <div class="mt-2 h-1.5 bg-indigo-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 rounded-full transition-all duration-700"
                                    style="width: {{ $opsPercentage }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-500 font-bold mt-2">Rp
                                {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= TABEL CABANG & HPP ================= -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                <!-- TABLE CABANG -->
                <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 sm:p-6 border-b border-gray-50 bg-gray-50/30">
                        <h2 class="text-base font-black text-gray-900">Rincian Keuangan per Outlet</h2>
                        <p class="text-[11px] font-medium text-gray-500 mt-1">Laba Bersih = Omzet - (HPP + Ops) cabang.</p>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead
                                class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Outlet</th>
                                    <th class="px-6 py-4 text-right">Laba Kotor</th>
                                    <th class="px-6 py-4 text-right">Pengeluaran</th>
                                    <th class="px-6 py-4 text-right">Laba Bersih</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50">
                                @foreach ($branchReports as $report)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-[10px] uppercase border border-indigo-100">
                                                    {{ substr($report->nama_cabang, 0, 2) }}</div>
                                                <span class="font-bold text-gray-900">{{ $report->nama_cabang }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-gray-900">Rp
                                            {{ number_format($report->laba_kotor, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right font-bold text-red-500">Rp
                                            {{ number_format($report->pengeluaran, 0, ',', '.') }}</td>
                                        <td
                                            class="px-6 py-4 text-right font-black {{ $report->laba_bersih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            Rp {{ number_format($report->laba_bersih, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TABLE HPP DETAILS -->
                <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                    <div class="p-5 sm:p-6 border-b border-gray-50 bg-gray-50/30">
                        <h2 class="text-base font-black text-gray-900">Rincian Modal Barang (HPP) Terjual</h2>
                        <p class="text-[11px] font-medium text-gray-500 mt-1">Barang penyumbang modal (pengeluaran)
                            terbesar.</p>
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
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-900">
                                                {{ $item->variant->product->nama_produk ?? 'Unknown' }}</p>
                                            <span
                                                class="inline-block mt-0.5 text-[10px] font-bold text-gray-500">{{ $item->variant->nama_varian ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="bg-gray-100 text-gray-600 px-2 py-1 rounded-md text-xs font-bold">{{ $item->total_qty }}
                                                <span
                                                    class="text-[10px] font-semibold">{{ $item->variant->satuan ?? 'pcs' }}</span></span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-gray-900">Rp
                                            {{ number_format($item->total_modal, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            class="px-6 py-10 text-center text-gray-400 font-bold italic text-xs">Belum ada
                                            barang terjual.</td>
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
                        <p class="text-[11px] text-gray-500 font-semibold mt-1">Catatan pengeluaran harian cabang.</p>
                    </div>
                    <a href="{{ route('owner.expense.index') }}"
                        class="text-[11px] font-bold text-indigo-600 hover:underline bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg">Kelola
                        Beban &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Kategori & Nama</th>
                                <th class="px-6 py-4">Lokasi (Cabang)</th>
                                <th class="px-6 py-4 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                            @forelse($expenses->take(10) as $exp)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($exp->tanggal_pengeluaran)->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">{{ $exp->nama_pengeluaran }}</p>
                                        <span
                                            class="inline-block mt-1 text-[9px] font-extrabold uppercase tracking-wider bg-gray-100 text-gray-500 px-2 py-0.5 rounded-md">{{ $exp->kategori_pengeluaran }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-600">
                                        {{ $exp->branch->nama_cabang ?? 'Pusat' }}</td>
                                    <td class="px-6 py-4 text-right font-black text-red-500">Rp
                                        {{ number_format($exp->nominal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-10 text-center text-gray-400 font-bold italic text-xs">Tidak ada
                                        catatan pengeluaran operasional.</td>
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
                gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)'); // Indigo 600
                gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

                lineChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dataObj.labels,
                        datasets: [{
                                label: 'Omzet',
                                data: dataObj.income,
                                borderColor: '#4F46E5', // Indigo 600
                                backgroundColor: gradient,
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 0,
                                pointHoverRadius: 5,
                                pointHoverBorderWidth: 3,
                                pointHoverBackgroundColor: '#ffffff',
                                pointHoverBorderColor: '#4F46E5'
                            },
                            {
                                label: 'Total Biaya',
                                data: dataObj.expense,
                                borderColor: '#9CA3AF',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                borderDash: [6, 5],
                                fill: false,
                                tension: 0.35,
                                pointRadius: 0,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: '#ffffff',
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
                        }, // Disable animation on AJAX reload for snappiness
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#111827',
                                padding: 12,
                                cornerRadius: 8,
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
                                        weight: '600'
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
                                        weight: '600'
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
                            backgroundColor: hasExpense ? ['#1F2937', '#4F46E5'] : ['#F3F4F6', '#F3F4F6'],
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
                                backgroundColor: '#111827',
                                padding: 10,
                                cornerRadius: 8,
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
                    // Update URL browser
                    window.history.pushState({}, '', `?${params}`);

                    // Replace HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data.html, 'text/html');
                    document.getElementById('finance-data-wrapper').innerHTML = doc.getElementById(
                        'finance-data-wrapper').innerHTML;

                    // Re-render Chart
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

        // Init Chart on First Load
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
