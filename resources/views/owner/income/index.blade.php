@extends('template.sidebar')
@section('title', 'Laporan Pendapatan (Income)')

@section('content')
    <main class="flex-1 bg-[#F3F4F6] overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full relative">

        <!-- HEADER SECTION -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Income Report</h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Analisis mendalam sumber pendapatan dan omzet kotor.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <form action="{{ route('owner.income.index') }}" method="GET" class="flex gap-2">
                    <div class="flex items-center bg-white border border-gray-200 rounded-2xl px-3 shadow-sm">
                        <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <select name="month" onchange="this.form.submit()"
                            class="bg-transparent border-none text-gray-700 py-2.5 font-bold text-sm focus:outline-none cursor-pointer appearance-none">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                    {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex items-center bg-white border border-gray-200 rounded-2xl px-3 shadow-sm">
                        <select name="year" onchange="this.form.submit()"
                            class="bg-transparent border-none text-gray-700 py-2.5 font-bold text-sm focus:outline-none cursor-pointer appearance-none">
                            <option value="2026" {{ $year == '2026' ? 'selected' : '' }}>2026</option>
                            <option value="2025" {{ $year == '2025' ? 'selected' : '' }}>2025</option>
                        </select>
                    </div>
                </form>

                <button onclick="window.print()"
                    class="bg-indigo-500 text-white px-5 py-2.5 rounded-2xl font-bold shadow-sm hover:bg-indigo-600 flex items-center gap-2 text-sm transition-colors print:hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Export
                </button>
            </div>
        </div>

        <!-- ================= TOP METRICS CARDS ================= -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- 1. TOTAL INCOME -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full blur-2xl"></div>
                <div class="flex justify-between items-center mb-3 relative z-10">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Omzet</h3>
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-black text-gray-900 mb-2 relative z-10">Rp
                    {{ number_format($totalIncome, 0, ',', '.') }}</h2>
                <p class="text-[11px] font-semibold text-gray-400">Total pendapatan kotor bulan ini.</p>
            </div>

            <!-- 2. TOTAL TRANSAKSI -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full blur-2xl"></div>
                <div class="flex justify-between items-center mb-3 relative z-10">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Transaksi</h3>
                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-black text-gray-900 mb-2 relative z-10">{{ number_format($totalTrx, 0, ',', '.') }}
                    <span class="text-base text-gray-400">Trx</span></h2>
                <p class="text-[11px] font-semibold text-gray-400">Jumlah struk/nota yang diterbitkan.</p>
            </div>

            <!-- 3. RATA-RATA TRANSAKSI -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-50 rounded-full blur-2xl"></div>
                <div class="flex justify-between items-center mb-3 relative z-10">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Rata-Rata Order</h3>
                    <div class="w-8 h-8 rounded-full bg-orange-50 flex items-center justify-center text-[#CC9863]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-black text-gray-900 mb-2 relative z-10">Rp
                    {{ number_format($avgTransaction, 0, ',', '.') }}</h2>
                <p class="text-[11px] font-semibold text-gray-400">Nilai rata-rata keranjang per pelanggan.</p>
            </div>
        </div>

        <!-- ================= DAILY OUTLET REPORT ================= -->
        <section class="mb-8" aria-labelledby="daily-outlet-report-title">
            <div class="flex flex-col gap-4 mb-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 id="daily-outlet-report-title" class="text-xl font-black text-gray-900">Rekap Harian per Outlet</h2>
                    <p class="text-sm text-gray-500 mt-1 font-medium">
                        Penjualan dan kas diterima pada {{ $dailyReportDate->translatedFormat('d F Y') }}.
                    </p>
                </div>

                <form action="{{ route('owner.income.index') }}" method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-end">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="year" value="{{ $year }}">
                    <div>
                        <label for="report_date" class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Tanggal laporan</label>
                        <input id="report_date" type="date" name="report_date" value="{{ $dailyReportDate->format('Y-m-d') }}"
                            class="w-full sm:w-auto rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-bold text-gray-700 shadow-sm focus:border-[#CC9863] focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                    </div>
                    <div>
                        <label for="report_branch" class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Outlet</label>
                        <select id="report_branch" name="report_branch"
                            class="w-full sm:w-48 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-bold text-gray-700 shadow-sm focus:border-[#CC9863] focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                            <option value="">Semua outlet</option>
                            @foreach ($dailyReportBranches as $reportBranch)
                                <option value="{{ $reportBranch->id }}" @selected((string) request('report_branch') === (string) $reportBranch->id)>
                                    {{ $reportBranch->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="min-h-[44px] rounded-xl bg-[#1C1D21] px-4 py-2.5 text-sm font-extrabold text-white transition hover:bg-black focus:outline-none focus:ring-2 focus:ring-[#CC9863] focus:ring-offset-2">
                        Tampilkan
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 mb-4">
                <div class="rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Omzet tanggal ini</p>
                    <p class="mt-2 text-xl font-black text-gray-900">Rp {{ number_format($dailySummary['total_pendapatan'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Kas diterima</p>
                    <p class="mt-2 text-xl font-black text-green-700">Rp {{ number_format($dailySummary['total_diterima'], 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Sisa piutang tempo</p>
                    <p class="mt-2 text-xl font-black text-red-700">Rp {{ number_format($dailySummary['total_piutang'], 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-[24px] border border-gray-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[680px] text-left">
                        <thead class="border-b border-gray-100 bg-gray-50/70 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                            <tr>
                                <th class="px-5 py-4">Outlet</th>
                                <th class="px-5 py-4 text-right">Transaksi</th>
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
                                        @if ((int) $report->total_transaksi === 0)
                                            <p class="mt-1 text-[11px] font-semibold text-gray-400">Belum ada transaksi pada tanggal ini</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right font-bold text-gray-700">{{ number_format($report->total_transaksi, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-right font-black text-gray-900">Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-right font-black text-green-700">Rp {{ number_format($report->total_diterima, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-right font-black text-red-700">Rp {{ number_format($report->total_piutang, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center">
                                        <p class="font-bold text-gray-700">Outlet tidak ditemukan</p>
                                        <p class="mt-1 text-sm text-gray-500">Periksa filter outlet yang dipilih atau tampilkan semua outlet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($dailyOutletReports->isNotEmpty())
                            <tfoot class="border-t-2 border-gray-100 bg-gray-50/50 text-sm">
                                <tr>
                                    <th class="px-5 py-4 text-left font-extrabold text-gray-900">Total</th>
                                    <th class="px-5 py-4 text-right font-black text-gray-900">{{ number_format($dailySummary['total_transaksi'], 0, ',', '.') }}</th>
                                    <th class="px-5 py-4 text-right font-black text-gray-900">Rp {{ number_format($dailySummary['total_pendapatan'], 0, ',', '.') }}</th>
                                    <th class="px-5 py-4 text-right font-black text-green-700">Rp {{ number_format($dailySummary['total_diterima'], 0, ',', '.') }}</th>
                                    <th class="px-5 py-4 text-right font-black text-red-700">Rp {{ number_format($dailySummary['total_piutang'], 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </section>



        <!-- ================= BOTTOM SECTION: INCOME TRANSACTIONS ================= -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Rincian Transaksi Pendapatan</h2>
                    <p class="text-[11px] text-gray-500 font-semibold mt-1">Daftar semua struk/pemasukan di bulan
                        {{ $monthName }}.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead
                        class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-gray-50/50 border-b border-gray-100">
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
                                    <p class="font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('d M Y') }}</p>
                                    <p class="text-[10px] text-gray-500 font-semibold mt-0.5">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-black text-[#CC9863]">{{ $trx->nomor_nota }}</p>
                                    <p class="text-[10px] text-gray-500 font-semibold mt-0.5">Plg:
                                        {{ $trx->member->nama ?? 'Umum' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ $trx->branch->nama_cabang ?? 'Pusat' }}</p>
                                    <p class="text-[10px] text-gray-500 font-semibold mt-0.5">Oleh:
                                        {{ explode(' ', $trx->cashier->nama_lengkap ?? 'Kasir')[0] }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $methodColors = [
                                            'cash' => 'bg-green-50 text-green-600 border-green-100',
                                            'qris' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'transfer' => 'bg-purple-50 text-purple-600 border-purple-100',
                                            'tempo' => 'bg-red-50 text-red-600 border-red-100',
                                            'cash_tempo' => 'bg-red-50 text-red-600 border-red-100',
                                        ];
                                        $colorClass =
                                            $methodColors[strtolower($trx->metode_bayar)] ??
                                            'bg-gray-100 text-gray-600 border-gray-200';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold border uppercase tracking-wider {{ $colorClass }}">
                                        {{ str_replace('_', ' ', $trx->metode_bayar) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-gray-900 text-base">
                                    Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium italic">Tidak
                                    ada catatan pemasukan/transaksi di bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination Component -->
            @if ($incomeTransactions->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $incomeTransactions->appends(['month' => $month, 'year' => $year])->links('pagination::tailwind') }}
                </div>
            @endif
        </div>

    </main>
@endsection
