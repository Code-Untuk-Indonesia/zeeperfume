@extends('template.sidebar')
@section('title', 'Laporan Keuangan')

@section('content')

    @php
        $activeFilter = request('filter_type', $filterType ?? 'month');
        $activeFilters = collect([
            $branchId ? $branches->firstWhere('id', $branchId)?->nama_cabang : null,
            $paymentMethod ? strtoupper(str_replace('_', ' ', $paymentMethod)) : null,
        ])->filter();
    @endphp

    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full min-h-screen relative">

        <!-- Loader Overlay -->
        <div id="ajax-loader"
            class="fixed inset-0 bg-white/70 backdrop-blur-sm z-[100] hidden items-center justify-center transition-all">
            <div class="flex flex-col items-center gap-4 bg-white p-8 rounded-[2rem] shadow-2xl border border-gray-100">
                <div class="w-12 h-12 border-4 border-[#CC9863]/30 border-t-[#CC9863] rounded-full animate-spin"></div>
                <p class="text-sm font-black text-gray-800 tracking-wide uppercase">Memperbarui Laporan...</p>
            </div>
        </div>

        <!-- ================= HEADER ================= -->
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5 mb-8">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 rounded-2xl bg-[#CC9863]/10 text-[#CC9863] flex items-center justify-center border border-[#CC9863]/20 shadow-sm shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Laporan Keuangan</h1>
                    <p class="text-sm text-gray-500 font-medium mt-1">
                        Analisis lengkap omzet, modal, dan laba bersih perusahaan.
                    </p>
                </div>
            </div>
        </div>

        <!-- ================= FILTER ================= -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm mb-7 overflow-hidden">
            <form action="{{ route('owner.income.index') }}" method="GET" id="incomeFilter">
                <input type="hidden" name="filter_type" id="filterType" value="{{ $activeFilter }}">

                <div
                    class="px-6 py-5 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/40">
                    <div>
                        <h2 class="text-sm font-black text-gray-900 uppercase tracking-widest">Filter Laporan</h2>
                    </div>
                    <div class="inline-flex bg-gray-200/50 rounded-xl p-1 w-full sm:w-auto shadow-inner">
                        <button type="button" id="btnMonthMode" onclick="changeFilterMode('month')"
                            class="filter-mode flex-1 sm:flex-none px-5 py-2 rounded-lg text-xs font-bold transition-all">Mode
                            Bulanan</button>
                        <button type="button" id="btnCustomMode" onclick="changeFilterMode('custom')"
                            class="filter-mode flex-1 sm:flex-none px-5 py-2 rounded-lg text-xs font-bold transition-all">Rentang
                            Tanggal</button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-5 items-end">
                        <!-- MONTH -->
                        <div id="monthFilterGroup" class="xl:col-span-5 grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">Bulan</label>
                                <select name="month" id="monthInput"
                                    class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863] transition-colors cursor-pointer">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" @selected((int) $month === $i)>
                                            {{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">Tahun</label>
                                <select name="year" id="yearInput"
                                    class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863] transition-colors cursor-pointer">
                                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" @selected((int) $year === $y)>{{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- CUSTOM DATE -->
                        <div id="customFilterGroup" class="hidden xl:col-span-5 grid grid-cols-2 gap-3">
                            <div>
                                <label
                                    class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">Dari
                                    Tanggal</label>
                                <input type="date" name="start_date" id="startDateInput"
                                    value="{{ request('start_date') }}"
                                    class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">Sampai
                                    Tanggal</label>
                                <input type="date" name="end_date" id="endDateInput" value="{{ request('end_date') }}"
                                    class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863]">
                            </div>
                        </div>

                        <!-- OUTLET -->
                        <div class="xl:col-span-3">
                            <label
                                class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">Outlet</label>
                            <select name="branch_id"
                                class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863] cursor-pointer">
                                <option value="">Semua Outlet</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected((string) $branchId === (string) $branch->id)>
                                        {{ $branch->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- PAYMENT -->
                        <div class="xl:col-span-2">
                            <label
                                class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">Metode
                                Bayar</label>
                            <select name="payment_method"
                                class="w-full h-12 bg-gray-50 border border-gray-200 rounded-xl px-4 text-sm font-bold text-gray-700 focus:outline-none focus:border-[#CC9863] cursor-pointer">
                                <option value="">Semua Metode</option>
                                <option value="cash" @selected($paymentMethod === 'cash')>Cash</option>
                                <option value="qris" @selected($paymentMethod === 'qris')>QRIS</option>
                                <option value="transfer" @selected($paymentMethod === 'transfer')>Transfer</option>
                                <option value="cash_tempo" @selected($paymentMethod === 'cash_tempo')>Cash & Tempo</option>
                                <option value="tempo" @selected($paymentMethod === 'tempo')>Tempo</option>
                            </select>
                        </div>

                        <!-- ACTION -->
                        <div class="xl:col-span-2 flex items-center gap-2 mt-4 xl:mt-0">
                            <button type="submit"
                                class="flex-1 h-12 rounded-xl bg-[#CC9863] text-white text-xs font-black uppercase tracking-wider flex items-center justify-center shadow-md hover:bg-[#b58555] transition transform active:scale-95">
                                Terapkan
                            </button>
                            @if (request()->anyFilled(['branch_id', 'payment_method']) || request('filter_type') === 'custom')
                                <a href="{{ route('owner.income.index') }}" title="Reset Filter"
                                    class="w-12 h-12 shrink-0 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="flex items-center gap-2 mb-6">
            <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Periode Aktif:</span>
            <span
                class="px-3 py-1.5 rounded-lg bg-[#1C1D21] text-white text-[11px] font-bold shadow-sm">{{ $monthName }}</span>
            @foreach ($activeFilters as $filter)
                <span
                    class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-gray-700 text-[11px] font-bold">{{ $filter }}</span>
            @endforeach
        </div>

        <!-- ================= CALCULATOR CARDS (ALUR LABA BERSIH) ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8 relative">

            <!-- 1. OMZET -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-blue-100 rounded-full blur-3xl opacity-50"></div>
                <div class="relative z-10">
                    <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">1. Total Omzet</p>
                    <p class="text-3xl font-black text-gray-900">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 font-semibold mt-4">Total nilai dari <span
                            class="text-[#CC9863] font-bold">{{ $totalTrx }}</span> transaksi berhasil.</p>
                </div>
            </div>

            <!-- 2. LABA KOTOR -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 relative overflow-hidden group">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-orange-100 rounded-full blur-3xl opacity-50"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest">2. Laba Kotor</p>
                        <span
                            class="text-[10px] font-bold text-gray-400 bg-gray-50 border border-gray-100 px-2 py-1 rounded-md">Omzet
                            - Modal (HPP)</span>
                    </div>
                    <p class="text-3xl font-black text-gray-900">Rp {{ number_format($labaKotor, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 font-semibold mt-4 border-t border-gray-100 pt-3 flex justify-between">
                        <span>Modal Barang (HPP)</span>
                        <span class="font-bold text-red-500">- Rp {{ number_format($totalHpp, 0, ',', '.') }}</span>
                    </p>
                </div>
            </div>

            <!-- 3. LABA BERSIH -->
            <div class="bg-[#1C1D21] rounded-[24px] shadow-lg p-6 relative overflow-hidden group border border-gray-800">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-[#CC9863]/30 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest">3. Laba Bersih</p>
                        <span
                            class="text-[10px] font-bold text-[#CC9863] bg-[#CC9863]/10 border border-[#CC9863]/20 px-2 py-1 rounded-md">Laba
                            Kotor - Ops</span>
                    </div>
                    <p class="text-3xl font-black text-white">Rp {{ number_format($labaBersih, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 font-semibold mt-4 border-t border-gray-800 pt-3 flex justify-between">
                        <span>Biaya Operasional</span>
                        <span class="font-bold text-red-400">- Rp
                            {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                    </p>
                </div>
            </div>

        </div>

        <!-- ================= CHART ================= -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 lg:p-8 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-lg font-black text-gray-900">Tren Pendapatan Laporan</h2>
                    <p class="text-[11px] text-gray-500 font-medium mt-1">Grafik pergerakan omzet harian selama
                        {{ $monthName }}.</p>
                </div>
                <div class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-widest text-gray-500">
                    <span class="w-3 h-3 bg-[#CC9863] rounded-full"></span> Total Omzet
                </div>
            </div>
            <div class="relative h-[300px] w-full">
                <canvas id="incomeChart"></canvas>
            </div>
        </div>

        <!-- ================= OUTLET SUMMARY ================= -->
        <section class="mb-8">
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-xl font-black text-gray-900">Rekap Pendapatan Outlet</h2>
                    <p class="text-[11px] text-gray-500 font-medium mt-1">Rincian Laba Bersih untuk setiap cabang pada
                        periode ini.</p>
                </div>
            </div>

            <!-- Table Rekap Outlet (Satu Tabel Komprehensif) -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left">
                        <thead
                            class="bg-gray-50/60 border-b border-gray-100 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-5">Nama Outlet</th>
                                <th class="px-6 py-5 text-right">Omzet</th>
                                <th class="px-6 py-5 text-right">HPP (Modal)</th>
                                <th class="px-6 py-5 text-right text-[#CC9863]">Laba Kotor</th>
                                <th class="px-6 py-5 text-right">Pengeluaran</th>
                                <th class="px-6 py-5 text-right text-emerald-600 bg-emerald-50/30">Laba Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @forelse($branchReports as $report)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-xl bg-[#1C1D21] flex items-center justify-center text-[#CC9863] font-black text-xs shadow-sm">
                                                {{ strtoupper(substr($report->nama_cabang, 0, 1)) }}
                                            </div>
                                            <span class="font-bold text-gray-900">{{ $report->nama_cabang }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900">
                                        Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-500">
                                        Rp
                                        {{ number_format($report->total_pendapatan - $report->laba_kotor, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-[#CC9863]">
                                        Rp {{ number_format($report->laba_kotor, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-rose-500">
                                        - Rp {{ number_format($report->pengeluaran, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-emerald-600 bg-emerald-50/30">
                                        Rp {{ number_format($report->laba_bersih, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        class="px-6 py-12 text-center text-gray-400 font-bold italic text-xs">
                                        Belum ada data rekap outlet pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ================= KAS & PIUTANG SUMMARY ================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
            <!-- Summary Kas -->
            <div
                class="bg-emerald-50/50 rounded-[24px] border border-emerald-100 shadow-sm p-6 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-widest mb-1">Total Kas Tunai
                        Diterima</p>
                    <p class="text-2xl font-black text-emerald-700">Rp
                        {{ number_format($dailySummary['total_diterima'], 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>
            </div>
            <!-- Summary Piutang -->
            <div
                class="bg-rose-50/50 rounded-[24px] border border-rose-100 shadow-sm p-6 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-extrabold text-rose-600 uppercase tracking-widest mb-1">Total Piutang Belum
                        Lunas</p>
                    <p class="text-2xl font-black text-rose-700">Rp
                        {{ number_format($dailySummary['total_piutang'], 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

    </main>

    <!-- ================= SCRIPT AREA ================= -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            const chartData = @json($chartData);

            function compactNumber(value) {
                value = Number(value || 0);
                if (value >= 1000000000) return 'Rp ' + (value / 1000000000).toLocaleString('id-ID', {
                    maximumFractionDigits: 1
                }) + ' M';
                if (value >= 1000000) return 'Rp ' + (value / 1000000).toLocaleString('id-ID', {
                    maximumFractionDigits: 1
                }) + ' jt';
                if (value >= 1000) return 'Rp ' + (value / 1000).toLocaleString('id-ID', {
                    maximumFractionDigits: 0
                }) + ' rb';
                return 'Rp ' + value.toLocaleString('id-ID');
            }

            function renderChart() {
                if (typeof Chart === 'undefined') return;
                const canvas = document.getElementById('incomeChart');
                if (!canvas) return;

                const oldChart = Chart.getChart(canvas);
                if (oldChart) oldChart.destroy();

                const ctx = canvas.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(204, 152, 99, 0.4)'); // Coklat
                gradient.addColorStop(1, 'rgba(204, 152, 99, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Omzet',
                            data: chartData.income,
                            borderColor: '#CC9863',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#ffffff',
                            pointHoverBorderColor: '#1C1D21',
                            pointHoverBorderWidth: 3,
                        }]
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
                                titleFont: {
                                    size: 13,
                                    family: "'Plus Jakarta Sans', sans-serif"
                                },
                                bodyFont: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                padding: 12,
                                cornerRadius: 10,
                                callbacks: {
                                    label: ctx => ` Omzet: Rp ${Number(ctx.raw).toLocaleString('id-ID')}`
                                }
                            }
                        },
                        scales: {
                            x: {
                                border: {
                                    display: false
                                },
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#9CA3AF',
                                    font: {
                                        size: 10,
                                        weight: '800'
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
                                        weight: '800'
                                    },
                                    callback: val => compactNumber(val)
                                }
                            }
                        }
                    }
                });
            }

            window.changeFilterMode = function(mode) {
                const filterType = document.getElementById('filterType');
                const monthGroup = document.getElementById('monthFilterGroup');
                const customGroup = document.getElementById('customFilterGroup');
                const monthInput = document.getElementById('monthInput');
                const yearInput = document.getElementById('yearInput');
                const startDateInput = document.getElementById('startDateInput');
                const endDateInput = document.getElementById('endDateInput');
                const monthButton = document.getElementById('btnMonthMode');
                const customButton = document.getElementById('btnCustomMode');

                filterType.value = mode;

                const activeClass = ['bg-white', 'text-gray-900', 'shadow-sm', 'border', 'border-gray-200'];
                const inactiveClass = ['text-gray-500', 'border-transparent', 'hover:text-gray-700'];

                monthButton.classList.remove(...activeClass, ...inactiveClass);
                customButton.classList.remove(...activeClass, ...inactiveClass);

                if (mode === 'custom') {
                    monthGroup.classList.add('hidden');
                    customGroup.classList.remove('hidden');
                    monthInput.disabled = true;
                    yearInput.disabled = true;
                    startDateInput.disabled = false;
                    endDateInput.disabled = false;
                    customButton.classList.add(...activeClass);
                    monthButton.classList.add(...inactiveClass);
                } else {
                    monthGroup.classList.remove('hidden');
                    customGroup.classList.add('hidden');
                    monthInput.disabled = false;
                    yearInput.disabled = false;
                    startDateInput.disabled = true;
                    endDateInput.disabled = true;
                    monthButton.classList.add(...activeClass);
                    customButton.classList.add(...inactiveClass);
                }
            };

            function init() {
                changeFilterMode(document.getElementById('filterType').value || 'month');
                renderChart();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
@endsection
