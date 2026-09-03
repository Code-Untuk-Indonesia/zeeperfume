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
        <div class="flex items-center gap-4 w-full sm:w-auto">
            <!-- Search Transaction (Sesuai referensi gambar) -->
            <div class="relative hidden md:block">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" class="block w-64 pl-9 pr-3 py-2 border border-gray-200 rounded-full text-sm bg-white focus:outline-none focus:ring-1 focus:ring-[#CC9863]" placeholder="Cari data keuangan...">
            </div>
            <button class="bg-white border border-gray-200 p-2 rounded-full text-gray-600 hover:bg-gray-50 shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </button>
        </div>
    </div>

    <!-- ================= TOP METRICS CARDS ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

        <!-- Left Block: Omzet & Modal (Sesuai gambar referensi) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
            <!-- Total Income / Omzet -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Omzet</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900">Rp 45.2M</h2>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-block bg-green-50 text-green-600 px-2 py-0.5 rounded text-xs font-bold mb-1">+8.5%</span>
                    <p class="text-[10px] text-gray-400">Bulan lalu</p>
                </div>
            </div>

            <div class="w-full h-px bg-gray-100 my-2"></div>

            <!-- Total Expenses / Modal -->
            <div class="flex justify-between items-start mt-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Modal & Biaya</p>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900">Rp 22.7M</h2>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-block bg-red-50 text-red-600 px-2 py-0.5 rounded text-xs font-bold mb-1">-4.2%</span>
                    <p class="text-[10px] text-gray-400">Bulan lalu</p>
                </div>
            </div>
        </div>

        <!-- Right Block: Laba Bersih & Margin Card -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex flex-col md:flex-row gap-6">

            <!-- Keuntungan Section -->
            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-gray-900 mb-1">Laba Bersih (Keuntungan)</p>
                            <p class="text-xs text-gray-500">Bulan Ini</p>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900">Rp 22.500.000</h2>
                    </div>
                </div>

                <div class="mt-8 flex gap-4 items-end bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">Rata-rata Laba Harian</p>
                        <h3 class="text-xl font-bold text-gray-900">Rp 750.000</h3>
                        <p class="text-[10px] text-gray-400 mt-1">Performa bisnis Anda sangat baik!</p>
                    </div>
                    <!-- Mini Sparkline Simulation -->
                    <div class="w-24 h-12 flex items-end justify-between gap-1">
                        <div class="w-full bg-gray-200 rounded-t-sm h-1/4"></div>
                        <div class="w-full bg-gray-200 rounded-t-sm h-2/4"></div>
                        <div class="w-full bg-gray-200 rounded-t-sm h-1/2"></div>
                        <div class="w-full bg-[#CC9863] rounded-t-sm h-full relative">
                            <span class="absolute -top-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white text-[8px] font-bold px-1 rounded">+12%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dark Highlight Card (Margin / Kas) -->
            <div class="w-full md:w-64 bg-[#1C1D21] rounded-2xl p-6 text-white flex flex-col justify-between relative overflow-hidden shadow-lg">
                <!-- Abstract Circle bg -->
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>

                <div>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-xs font-semibold text-gray-300">Margin Keuntungan</p>
                        <svg class="w-4 h-4 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <h2 class="text-4xl font-extrabold text-white">49.7%</h2>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-700">
                    <p class="text-xs text-gray-400 leading-snug">Rasio Laba Bersih terhadap Omzet. Margin Anda <span class="text-green-400 font-bold">sangat sehat</span> untuk industri retail.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= CHARTS SECTION ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Donut Chart: Alokasi Pengeluaran / Modal -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Proporsi Modal & Pengeluaran</h2>

            <div class="flex-1 flex flex-col items-center justify-center">
                <!-- Custom SVG Donut Chart -->
                <div class="relative w-48 h-48 mb-8">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <path class="text-gray-100" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none"/>
                        <path class="text-[#CC9863]" stroke-dasharray="45, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <path class="text-blue-500" stroke-dasharray="35, 100" stroke-dashoffset="-45" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <path class="text-gray-400" stroke-dasharray="20, 100" stroke-dashoffset="-80" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-[10px] font-semibold text-gray-400">Total Modal</span>
                        <span class="text-xl font-bold text-gray-900">100%</span>
                    </div>
                </div>

                <!-- Legend List -->
                <div class="w-full space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#CC9863]"></span>
                            <span class="font-semibold text-gray-700">Kulakan / HPP Produk</span>
                        </div>
                        <div class="flex gap-4">
                            <span class="font-bold text-gray-900">Rp 10.2M</span>
                            <span class="text-gray-400 w-8 text-right">45%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <span class="font-semibold text-gray-700">Gaji Karyawan & Kasir</span>
                        </div>
                        <div class="flex gap-4">
                            <span class="font-bold text-gray-900">Rp 7.9M</span>
                            <span class="text-gray-400 w-8 text-right">35%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                            <span class="font-semibold text-gray-700">Operasional (Listrik, dll)</span>
                        </div>
                        <div class="flex gap-4">
                            <span class="font-bold text-gray-900">Rp 4.6M</span>
                            <span class="text-gray-400 w-8 text-right">20%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bar Chart: Keuntungan Harian -->
        <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-lg font-bold text-gray-900">Grafik Laba Bersih Mingguan</h2>
                <select class="border border-gray-200 rounded-full px-4 py-1.5 text-xs font-semibold text-gray-600 bg-gray-50 focus:outline-none">
                    <option>Minggu Ini</option>
                    <option>Bulan Ini</option>
                </select>
            </div>

            <div class="flex-1 flex">
                <!-- Y-Axis Labels -->
                <div class="flex flex-col justify-between text-[10px] font-semibold text-gray-400 pb-6 pr-4 border-r border-gray-100">
                    <span>Rp 5M</span>
                    <span>Rp 3M</span>
                    <span>Rp 1M</span>
                    <span>Rp 0</span>
                </div>

                <!-- Bars Area -->
                <div class="flex-1 flex justify-between items-end pl-6 h-64">
                    <!-- Sun -->
                    <div class="flex flex-col items-center gap-3 w-1/7">
                        <div class="w-6 md:w-10 bg-[#1C1D21] rounded-t-xl hover:bg-[#CC9863] transition-colors cursor-pointer" style="height: 90%;"></div>
                        <span class="text-[11px] font-bold text-gray-500">Sen</span>
                    </div>
                    <!-- Mon -->
                    <div class="flex flex-col items-center gap-3 w-1/7">
                        <div class="w-6 md:w-10 bg-[#1C1D21] rounded-t-xl hover:bg-[#CC9863] transition-colors cursor-pointer" style="height: 40%;"></div>
                        <span class="text-[11px] font-bold text-gray-500">Sel</span>
                    </div>
                    <!-- Tue -->
                    <div class="flex flex-col items-center gap-3 w-1/7">
                        <div class="w-6 md:w-10 bg-[#1C1D21] rounded-t-xl hover:bg-[#CC9863] transition-colors cursor-pointer" style="height: 60%;"></div>
                        <span class="text-[11px] font-bold text-gray-500">Rab</span>
                    </div>
                    <!-- Wed -->
                    <div class="flex flex-col items-center gap-3 w-1/7 relative group">
                        <!-- Tooltip -->
                        <div class="absolute -top-10 bg-[#1C1D21] text-white px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition shadow-lg pointer-events-none">
                            Rp 3.500.000
                        </div>
                        <div class="w-6 md:w-10 bg-[#CC9863] rounded-t-xl cursor-pointer" style="height: 55%;"></div>
                        <span class="text-[11px] font-bold text-gray-900">Kam</span>
                    </div>
                    <!-- Thu -->
                    <div class="flex flex-col items-center gap-3 w-1/7">
                        <div class="w-6 md:w-10 bg-[#1C1D21] rounded-t-xl hover:bg-[#CC9863] transition-colors cursor-pointer" style="height: 45%;"></div>
                        <span class="text-[11px] font-bold text-gray-500">Jum</span>
                    </div>
                    <!-- Fri -->
                    <div class="flex flex-col items-center gap-3 w-1/7">
                        <div class="w-6 md:w-10 bg-[#1C1D21] rounded-t-xl hover:bg-[#CC9863] transition-colors cursor-pointer" style="height: 75%;"></div>
                        <span class="text-[11px] font-bold text-gray-500">Sab</span>
                    </div>
                    <!-- Sat -->
                    <div class="flex flex-col items-center gap-3 w-1/7">
                        <div class="w-6 md:w-10 bg-[#1C1D21] rounded-t-xl hover:bg-[#CC9863] transition-colors cursor-pointer" style="height: 85%;"></div>
                        <span class="text-[11px] font-bold text-gray-500">Min</span>
                    </div>
                </div>
            </div>
            <!-- X-Axis Line -->
            <div class="w-full h-px bg-gray-200 mt-2"></div>
        </div>

    </div>

</main>
@endsection
