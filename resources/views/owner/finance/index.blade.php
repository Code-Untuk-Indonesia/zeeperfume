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
            <!-- Search Transaction -->
            <div class="relative hidden lg:block">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" class="block w-64 pl-9 pr-3 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" placeholder="Cari data keuangan...">
            </div>

            <!-- Date Filter -->
            <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-gray-50 flex items-center gap-2 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                September 2026
            </button>

            <!-- Export Button -->
            <button class="bg-[#CC9863] text-white px-4 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] flex items-center gap-2 text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export PDF
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
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900">Rp 45.2M</h2>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-block bg-green-50 text-green-600 px-2 py-0.5 rounded text-xs font-bold mb-1">+8.5%</span>
                    <p class="text-[10px] text-gray-400">vs bln lalu</p>
                </div>
            </div>

            <div class="w-full h-px bg-gray-100 my-2"></div>

            <!-- Total Expenses / Modal -->
            <div class="flex justify-between items-start mt-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Pengeluaran (HPP + Ops)</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900">Rp 22.7M</h2>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-block bg-red-50 text-red-600 px-2 py-0.5 rounded text-xs font-bold mb-1">-4.2%</span>
                    <p class="text-[10px] text-gray-400">vs bln lalu</p>
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
                            <p class="text-xs text-gray-500">Akumulasi seluruh cabang bulan ini</p>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Rp 22.500.000</h2>
                    </div>
                </div>

                <div class="mt-8 flex gap-4 items-end bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">Target Bulanan</p>
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-xl font-bold text-gray-900">Rp 30.000.000</h3>
                            <span class="text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-0.5 rounded">75% Tercapai</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-[#CC9863] h-1.5 rounded-full" style="width: 75%"></div>
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
                    <h2 class="text-4xl font-extrabold text-white">49.7%</h2>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-700">
                    <p class="text-[11px] text-gray-400 leading-snug">Rasio laba bersih terhadap omzet. Status keuangan bisnis Anda saat ini <span class="text-green-400 font-bold">sangat sehat</span>.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= CHARTS SECTION ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

        <!-- Donut Chart: Alokasi Pengeluaran -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
            <h2 class="text-base font-bold text-gray-900 mb-6">Proporsi Pengeluaran</h2>

            <div class="flex-1 flex flex-col items-center justify-center">
                <div class="relative w-40 h-40 mb-8">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <!-- Background Circle -->
                        <path class="text-gray-100" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none"/>
                        <!-- HPP -->
                        <path class="text-[#CC9863]" stroke-dasharray="45, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <!-- Gaji -->
                        <path class="text-gray-800" stroke-dasharray="35, 100" stroke-dashoffset="-45" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <!-- Operasional -->
                        <path class="text-gray-400" stroke-dasharray="20, 100" stroke-dashoffset="-80" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-xl font-bold text-gray-900">22.7M</span>
                        <span class="text-[9px] font-semibold text-gray-400 uppercase">Total Keluar</span>
                    </div>
                </div>

                <!-- Legend List -->
                <div class="w-full space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span>
                            <span class="font-semibold text-gray-700">HPP / Kulakan</span>
                        </div>
                        <span class="font-bold text-gray-900">45%</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-800"></span>
                            <span class="font-semibold text-gray-700">Gaji Karyawan</span>
                        </div>
                        <span class="font-bold text-gray-900">35%</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                            <span class="font-semibold text-gray-700">Operasional Outlet</span>
                        </div>
                        <span class="font-bold text-gray-900">20%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- DUAL Bar Chart: Cash Flow -->
        <div class="lg:col-span-8 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-base font-bold text-gray-900">Arus Kas Harian</h2>
                    <div class="flex gap-4 mt-2 text-[11px] font-semibold text-gray-500">
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-[#1C1D21]"></span> Pemasukan (Omzet)</div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-gray-300"></span> Pengeluaran</div>
                    </div>
                </div>
                <select class="border border-gray-200 rounded-full px-4 py-1.5 text-xs font-semibold text-gray-600 bg-gray-50 focus:outline-none">
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                </select>
            </div>

            <div class="flex-1 flex">
                <!-- Y-Axis Labels -->
                <div class="flex flex-col justify-between text-[10px] font-semibold text-gray-400 pb-6 pr-4 border-r border-gray-100">
                    <span>10M</span>
                    <span>7.5M</span>
                    <span>5M</span>
                    <span>2.5M</span>
                    <span>0</span>
                </div>

                <!-- Dual Bars Area -->
                <div class="flex-1 flex justify-around items-end pl-2 sm:pl-6 h-56 relative">
                    <!-- Day 1 -->
                    <div class="flex flex-col items-center gap-2 w-1/7 group relative">
                        <div class="flex items-end gap-1 w-full justify-center h-48">
                            <div class="w-2.5 sm:w-4 bg-[#1C1D21] rounded-t-md hover:bg-[#CC9863] transition-colors" style="height: 60%;" title="Omzet: 6M"></div>
                            <div class="w-2.5 sm:w-4 bg-gray-300 rounded-t-md" style="height: 30%;" title="Pengeluaran: 3M"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500">Sen</span>
                    </div>
                    <!-- Day 2 -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-48">
                            <div class="w-2.5 sm:w-4 bg-[#1C1D21] rounded-t-md hover:bg-[#CC9863] transition-colors" style="height: 75%;"></div>
                            <div class="w-2.5 sm:w-4 bg-gray-300 rounded-t-md" style="height: 25%;"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500">Sel</span>
                    </div>
                    <!-- Day 3 -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-48">
                            <div class="w-2.5 sm:w-4 bg-[#1C1D21] rounded-t-md hover:bg-[#CC9863] transition-colors" style="height: 50%;"></div>
                            <div class="w-2.5 sm:w-4 bg-gray-300 rounded-t-md" style="height: 40%;"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500">Rab</span>
                    </div>
                    <!-- Day 4 (Highlight/Today) -->
                    <div class="flex flex-col items-center gap-2 w-1/7 relative group">
                        <div class="absolute -top-10 bg-white border border-gray-200 text-gray-800 px-3 py-1.5 rounded-lg text-[10px] font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition shadow-lg pointer-events-none z-10">
                            Laba: Rp +3.5M
                        </div>
                        <div class="flex items-end gap-1 w-full justify-center h-48">
                            <div class="w-2.5 sm:w-4 bg-[#CC9863] rounded-t-md" style="height: 85%;"></div>
                            <div class="w-2.5 sm:w-4 bg-gray-300 rounded-t-md" style="height: 50%;"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-900 bg-gray-100 px-2 rounded-full">Kam</span>
                    </div>
                    <!-- Day 5 -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-48">
                            <div class="w-2.5 sm:w-4 bg-[#1C1D21] rounded-t-md hover:bg-[#CC9863] transition-colors" style="height: 40%;"></div>
                            <div class="w-2.5 sm:w-4 bg-gray-300 rounded-t-md" style="height: 20%;"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500">Jum</span>
                    </div>
                    <!-- Day 6 -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-48">
                            <div class="w-2.5 sm:w-4 bg-[#1C1D21] rounded-t-md hover:bg-[#CC9863] transition-colors" style="height: 95%;"></div>
                            <div class="w-2.5 sm:w-4 bg-gray-300 rounded-t-md" style="height: 45%;"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500">Sab</span>
                    </div>
                    <!-- Day 7 -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-48">
                            <div class="w-2.5 sm:w-4 bg-[#1C1D21] rounded-t-md hover:bg-[#CC9863] transition-colors" style="height: 80%;"></div>
                            <div class="w-2.5 sm:w-4 bg-gray-300 rounded-t-md" style="height: 35%;"></div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500">Min</span>
                    </div>
                </div>
            </div>
            <!-- X-Axis Line -->
            <div class="w-full h-px bg-gray-200 mt-2"></div>
        </div>

    </div>

    <!-- ================= DETAILED BREAKDOWN TABLE ================= -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
            <div>
                <h2 class="text-base font-bold text-gray-900">Rincian Laba per Cabang</h2>
                <p class="text-xs text-gray-500 mt-1">Perbandingan performa antar outlet di bulan September 2026.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4">Nama Outlet</th>
                        <th class="px-6 py-4 text-right">Total Omzet</th>
                        <th class="px-6 py-4 text-right">Total HPP</th>
                        <th class="px-6 py-4 text-right">Biaya Operasional</th>
                        <th class="px-6 py-4 text-right">Laba Bersih</th>
                        <th class="px-6 py-4 text-center">Margin</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                    <!-- Data 1 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-[#CC9863] font-bold text-xs">PST</div>
                                <span class="font-bold text-gray-900">Outlet Pusat</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-900">Rp 25.000.000</td>
                        <td class="px-6 py-4 text-right text-gray-500">Rp 8.500.000</td>
                        <td class="px-6 py-4 text-right text-gray-500">Rp 4.000.000</td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">Rp 12.500.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-md">50.0%</span>
                        </td>
                    </tr>
                    <!-- Data 2 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 font-bold text-xs">CB1</div>
                                <span class="font-bold text-gray-900">Cabang 1 (Mall)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-900">Rp 15.200.000</td>
                        <td class="px-6 py-4 text-right text-gray-500">Rp 5.200.000</td>
                        <td class="px-6 py-4 text-right text-gray-500">Rp 3.500.000</td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">Rp 6.500.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-md">42.7%</span>
                        </td>
                    </tr>
                    <!-- Data 3 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500 font-bold text-xs">CB2</div>
                                <span class="font-bold text-gray-900">Cabang 2 (Gajah Mada)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-900">Rp 5.000.000</td>
                        <td class="px-6 py-4 text-right text-gray-500">Rp 2.000.000</td>
                        <td class="px-6 py-4 text-right text-gray-500">Rp 1.500.000</td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">Rp 1.500.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-md">30.0%</span>
                        </td>
                    </tr>
                </tbody>
                <!-- Total Row -->
                <tfoot class="bg-[#1C1D21] text-white">
                    <tr>
                        <td class="px-6 py-4 font-bold">TOTAL KESELURUHAN</td>
                        <td class="px-6 py-4 text-right font-bold">Rp 45.200.000</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-400">Rp 15.700.000</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-400">Rp 7.000.000</td>
                        <td class="px-6 py-4 text-right font-bold text-[#CC9863]">Rp 22.500.000</td>
                        <td class="px-6 py-4 text-center font-bold">49.7%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</main>
@endsection
