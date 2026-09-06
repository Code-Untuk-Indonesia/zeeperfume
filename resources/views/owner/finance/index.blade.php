@extends('template.sidebar')
@section('title', 'Laporan Keuangan')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Laporan Keuangan</h1>
            <p class="text-gray-500 text-sm mt-1">Analisis mendalam terkait modal, omzet, dan keuntungan bersih bisnis.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            
            <!-- Date Filter Form -->
            <form action="{{ route('owner.finance.index') }}" method="GET" class="flex gap-2">
                <select name="month" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-xl font-semibold shadow-sm text-sm focus:outline-none">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 px-3 py-2 rounded-xl font-semibold shadow-sm text-sm focus:outline-none">
                    <option value="2026" {{ $year == '2026' ? 'selected' : '' }}>2026</option>
                    <option value="2025" {{ $year == '2025' ? 'selected' : '' }}>2025</option>
                </select>
            </form>

            <button onclick="window.print()" class="bg-[#CC9863] text-white px-4 py-2 rounded-xl font-bold shadow-sm hover:bg-[#b58555] flex items-center gap-2 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Cetak / PDF
            </button>
        </div>
    </div>

    <!-- ================= TOP METRICS CARDS ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

        <!-- Left Block: Omzet & Modal -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
            <!-- Total Income / Omzet -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Omzet (Kotor)</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-extrabold text-gray-900 truncate">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>

            <div class="w-full h-px bg-gray-100 my-2"></div>

            <!-- Total Expenses / Modal -->
            <div class="flex justify-between items-start mt-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Pengeluaran (HPP)</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-extrabold text-gray-900 truncate">Rp {{ number_format($totalHpp, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Block: Laba Bersih & Margin Card -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col md:flex-row gap-6 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute right-0 bottom-0 w-64 h-64 bg-orange-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

            <!-- Keuntungan Section -->
            <div class="flex-1 flex flex-col justify-between relative z-10">
                <div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-900 mb-1">Laba Bersih (Net Profit)</p>
                            <p class="text-xs text-gray-500">Akumulasi seluruh cabang di {{ $monthName }}</p>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Rp {{ number_format($labaBersih, 0, ',', '.') }}</h2>
                    </div>
                </div>

                <div class="mt-8 flex gap-4 items-end bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">Rasio Profit terhadap Omzet</p>
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $marginPercentage }}% Laba</h3>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-[#CC9863] h-1.5 rounded-full" style="width: {{ $marginPercentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dark Highlight Card (Margin) -->
            <div class="w-full md:w-64 bg-[#1C1D21] rounded-2xl p-6 text-white flex flex-col justify-between relative z-10 shadow-lg">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-semibold text-gray-300">Margin Keuntungan</p>
                        <svg class="w-4 h-4 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h2 class="text-4xl font-extrabold text-white">{{ $marginPercentage }}%</h2>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-700">
                    <p class="text-[11px] text-gray-400 leading-snug">Rasio laba bersih terhadap omzet. Semakin tinggi persentasenya, semakin baik performa bisnis.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= CHARTS SECTION ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

        <!-- Donut Chart: Alokasi Pengeluaran -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
            <h2 class="text-base font-bold text-gray-900 mb-6">Proporsi Bisnis</h2>
            
            @php
                $hppPercent = $totalOmzet > 0 ? round(($totalHpp / $totalOmzet) * 100) : 0;
                $labaPercent = $totalOmzet > 0 ? 100 - $hppPercent : 0;
            @endphp

            <div class="flex-1 flex flex-col items-center justify-center">
                <div class="relative w-40 h-40 mb-8">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <path class="text-gray-100" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none"/>
                        <!-- HPP -->
                        <path class="text-gray-800" stroke-dasharray="{{ $hppPercent }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <!-- Laba -->
                        <path class="text-[#CC9863]" stroke-dasharray="{{ $labaPercent }}, 100" stroke-dashoffset="-{{ $hppPercent }}" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-[10px] font-semibold text-gray-400 uppercase">Omzet</span>
                        <span class="text-sm font-bold text-gray-900 truncate w-24">Rp {{ number_format($totalOmzet/1000000, 1) }}M</span>
                    </div>
                </div>

                <div class="w-full space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span>
                            <span class="font-semibold text-gray-700">Laba Bersih</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $labaPercent }}%</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-800"></span>
                            <span class="font-semibold text-gray-700">HPP / Modal Barang</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ $hppPercent }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-center items-center text-gray-400">
            <!-- Ini area untuk Chart Harian nantinya, sementara disembunyikan/dummy dihapus agar fokus pada data riil -->
            <svg class="w-16 h-16 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <p class="font-semibold">Grafik Harian sedang dalam pengembangan.</p>
        </div>

    </div>

    <!-- ================= DETAILED BREAKDOWN TABLE ================= -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
            <div>
                <h2 class="text-base font-bold text-gray-900">Rincian Laba per Cabang</h2>
                <p class="text-xs text-gray-500 mt-1">Perbandingan performa antar outlet di {{ $monthName }}.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4">Nama Outlet</th>
                        <th class="px-6 py-4 text-right">Total Omzet</th>
                        <th class="px-6 py-4 text-right">Total HPP</th>
                        <th class="px-6 py-4 text-right">Laba Bersih</th>
                        <th class="px-6 py-4 text-center">Margin Laba</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                    
                    @foreach($branchReports as $report)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-[#CC9863] font-bold text-xs shadow-sm border border-orange-100">
                                    {{ strtoupper(substr($report->nama_cabang, 0, 2)) }}
                                </div>
                                <span class="font-bold text-gray-900">{{ $report->nama_cabang }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-900">Rp {{ number_format($report->omzet, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-gray-500">Rp {{ number_format($report->hpp, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">Rp {{ number_format($report->laba, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-md border border-gray-200">{{ $report->margin }}%</span>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
                <!-- Total Row -->
                <tfoot class="bg-[#1C1D21] text-white">
                    <tr>
                        <td class="px-6 py-4 font-bold tracking-widest text-xs uppercase">Total {{ $monthName }}</td>
                        <td class="px-6 py-4 text-right font-bold">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-400">Rp {{ number_format($totalHpp, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right font-bold text-[#CC9863]">Rp {{ number_format($labaBersih, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center font-bold">{{ $marginPercentage }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</main>
@endsection