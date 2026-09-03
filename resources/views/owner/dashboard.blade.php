@extends('template.sidebar')
@section('title', 'Dashboard Owner')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 w-full">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Welcome back, Heldi!</h1>
            <p class="text-gray-500 text-sm mt-1">Saat yang tepat untuk memantau performa bisnis parfum Anda.</p>
        </div>

        <div class="flex items-center gap-4">
            <!-- Tabs Filter Waktu -->
            <div class="bg-white p-1 rounded-xl border border-gray-200 flex text-sm font-semibold shadow-sm">
                <button class="px-4 py-1.5 rounded-lg bg-[#CC9863] text-white shadow-sm transition">Hari Ini</button>
                <button class="px-4 py-1.5 rounded-lg text-gray-500 hover:text-gray-900 transition">Minggu Ini</button>
                <button class="px-4 py-1.5 rounded-lg text-gray-500 hover:text-gray-900 transition">Bulan Ini</button>
            </div>

            <button class="bg-white border border-gray-200 p-2.5 rounded-xl text-gray-600 hover:bg-gray-50 shadow-sm transition hidden sm:block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </button>
        </div>
    </div>

    <!-- Stats Grid (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-semibold text-gray-500">Total Omzet</h3>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19l16-16m0 0H9m11 0v11"></path></svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Rp 15.700.000</h2>
                <div class="flex items-center gap-2 text-xs">
                    <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded font-semibold flex items-center gap-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> 12.1%
                    </span>
                    <span class="text-gray-400">vs kemarin</span>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-semibold text-gray-500">Laba Kotor</h3>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19l16-16m0 0H9m11 0v11"></path></svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Rp 8.500.000</h2>
                <div class="flex items-center gap-2 text-xs">
                    <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded font-semibold flex items-center gap-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> 6.3%
                    </span>
                    <span class="text-gray-400">vs kemarin</span>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-semibold text-gray-500">Total Transaksi</h3>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19l16-16m0 0H9m11 0v11"></path></svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">142 Trx</h2>
                <div class="flex items-center gap-2 text-xs">
                    <span class="bg-red-50 text-red-600 px-2 py-0.5 rounded font-semibold flex items-center gap-0.5">
                        <svg class="w-3 h-3 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> 2.4%
                    </span>
                    <span class="text-gray-400">vs kemarin</span>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-semibold text-gray-500">Piutang (Tempo)</h3>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19l16-16m0 0H9m11 0v11"></path></svg>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Rp 2.913.000</h2>
                <div class="flex items-center gap-2 text-xs">
                    <span class="bg-green-50 text-green-600 px-2 py-0.5 rounded font-semibold flex items-center gap-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> 5.1%
                    </span>
                    <span class="text-gray-400">terbayar hari ini</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Row (Charts) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- Bar Chart Area (Money Flow / Omzet vs Modal) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-lg font-bold text-gray-900">Performa Penjualan</h2>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-4 text-xs font-semibold text-gray-500">
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span> Omzet</div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-200"></span> Modal (HPP)</div>
                    </div>
                    <select class="hidden sm:block text-xs border border-gray-200 rounded-lg px-2 py-1 bg-gray-50 focus:outline-none">
                        <option>Semua Outlet</option>
                        <option>Outlet Pusat</option>
                    </select>
                </div>
            </div>

            <!-- Mockup Bar Chart CSS -->
            <div class="flex items-end justify-between h-48 w-full gap-2 relative">
                <!-- Y-Axis Labels -->
                <div class="absolute left-0 top-0 h-full flex flex-col justify-between text-[10px] text-gray-400 pb-6 hidden sm:flex">
                    <span>15M</span>
                    <span>10M</span>
                    <span>5M</span>
                    <span>1M</span>
                </div>

                <div class="flex-1 flex justify-between items-end h-full pl-0 sm:pl-10">
                    <!-- Bar Group 1 (Senin) -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-40">
                            <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 60%"></div>
                            <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 35%"></div>
                        </div>
                        <span class="text-[10px] text-gray-400 font-semibold">Sen</span>
                    </div>
                    <!-- Bar Group 2 (Selasa) -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-40">
                            <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 75%"></div>
                            <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 45%"></div>
                        </div>
                        <span class="text-[10px] text-gray-400 font-semibold">Sel</span>
                    </div>
                    <!-- Bar Group 3 (Rabu - Active with Tooltip) -->
                    <div class="flex flex-col items-center gap-2 w-1/7 relative group">
                        <!-- Tooltip -->
                        <div class="absolute -top-8 bg-white border border-gray-200 shadow-lg px-2 py-1 rounded-lg text-[10px] font-bold text-gray-700 whitespace-nowrap opacity-0 group-hover:opacity-100 transition">
                            Rp 10.000.000
                        </div>
                        <div class="flex items-end gap-1 w-full justify-center h-40">
                            <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 50%"></div>
                            <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 30%"></div>
                        </div>
                        <span class="text-[10px] text-gray-400 font-semibold">Rab</span>
                    </div>
                    <!-- Bar Group 4 (Kamis) -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-40">
                            <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 90%"></div>
                            <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 55%"></div>
                        </div>
                        <span class="text-[10px] text-gray-400 font-semibold">Kam</span>
                    </div>
                    <!-- Bar Group 5 (Jumat) -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-40">
                            <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 85%"></div>
                            <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 50%"></div>
                        </div>
                        <span class="text-[10px] text-gray-400 font-semibold">Jum</span>
                    </div>
                    <!-- Bar Group 6 (Sabtu) -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-40">
                            <div class="w-3 sm:w-4 bg-[#CC9863] opacity-60 rounded-t-sm" style="height: 40%"></div>
                            <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 25%"></div>
                        </div>
                        <span class="text-[10px] text-gray-400 font-semibold">Sab</span>
                    </div>
                    <!-- Bar Group 7 (Minggu) -->
                    <div class="flex flex-col items-center gap-2 w-1/7">
                        <div class="flex items-end gap-1 w-full justify-center h-40">
                            <div class="w-3 sm:w-4 bg-[#CC9863] opacity-60 rounded-t-sm" style="height: 60%"></div>
                            <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 40%"></div>
                        </div>
                        <span class="text-[10px] text-gray-400 font-semibold">Min</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donut Chart Area (Kategori Produk) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col relative">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-900">Proporsi Kategori</h2>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19l16-16m0 0H9m11 0v11"></path></svg>
                </div>
            </div>

            <div class="flex-1 flex items-center justify-center relative">
                <!-- SVG Donut Chart -->
                <svg viewBox="0 0 36 36" class="w-40 h-40 transform -rotate-90">
                    <path class="text-gray-100" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>
                    <path class="text-[#CC9863]" stroke-dasharray="50, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>
                    <path class="text-blue-400" stroke-dasharray="25, 100" stroke-dashoffset="-50" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>
                    <path class="text-purple-400" stroke-dasharray="15, 100" stroke-dashoffset="-75" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>
                    <path class="text-gray-800" stroke-dasharray="10, 100" stroke-dashoffset="-90" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-[10px] text-gray-400 font-semibold">Total Terjual</span>
                    <span class="text-lg font-extrabold text-gray-900">420 Pcs</span>
                </div>
            </div>

            <div class="mt-6 space-y-2">
                <div class="flex items-center text-xs justify-between">
                    <div class="flex items-center gap-2 text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span> Eau de Parfum (EDP)</div>
                    <span class="font-bold">50%</span>
                </div>
                <div class="flex items-center text-xs justify-between">
                    <div class="flex items-center gap-2 text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span> Body Mist</div>
                    <span class="font-bold">25%</span>
                </div>
                <div class="flex items-center text-xs justify-between">
                    <div class="flex items-center gap-2 text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-purple-400"></span> Eau de Toilette (EDT)</div>
                    <span class="font-bold">15%</span>
                </div>
                <div class="flex items-center text-xs justify-between">
                    <div class="flex items-center gap-2 text-gray-600"><span class="w-2.5 h-2.5 rounded-full bg-gray-800"></span> Essential Oil</div>
                    <span class="font-bold">10%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row (Table & Goals) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Transactions Table -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-900">Transaksi Terakhir</h2>
                <div class="flex items-center gap-3 text-xs">
                    <select class="border border-gray-200 rounded-lg px-2 py-1 bg-gray-50 focus:outline-none">
                        <option>Semua Outlet</option>
                    </select>
                    <a href="#" class="text-[#CC9863] font-semibold hover:underline flex items-center gap-1">See all <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="pb-3 px-2">Waktu</th>
                            <th class="pb-3 px-2">Kasir / Cabang</th>
                            <th class="pb-3 px-2">Total Harga</th>
                            <th class="pb-3 px-2">Metode</th>
                            <th class="pb-3 px-2">Pelanggan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-2">02 Sep 14:30</td>
                            <td class="py-3 px-2 font-medium">Dina (Pusat)</td>
                            <td class="py-3 px-2 font-bold text-gray-900">Rp 325.000</td>
                            <td class="py-3 px-2"><span class="text-xs bg-green-50 text-green-600 px-2 py-1 rounded font-bold">QRIS</span></td>
                            <td class="py-3 px-2 text-gray-500">Umum</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-2">02 Sep 14:15</td>
                            <td class="py-3 px-2 font-medium">Anton (Cabang 1)</td>
                            <td class="py-3 px-2 font-bold text-gray-900">Rp 170.000</td>
                            <td class="py-3 px-2"><span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded font-bold">TUNAI</span></td>
                            <td class="py-3 px-2 text-gray-500">Rina (Reguler)</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-2">02 Sep 13:50</td>
                            <td class="py-3 px-2 font-medium">Dina (Pusat)</td>
                            <td class="py-3 px-2 font-bold text-gray-900">Rp 1.500.000</td>
                            <td class="py-3 px-2"><span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded font-bold">TEMPO</span></td>
                            <td class="py-3 px-2 text-gray-500 font-semibold">Budi (Reseller)</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-2">02 Sep 12:10</td>
                            <td class="py-3 px-2 font-medium">Siska (Cabang 1)</td>
                            <td class="py-3 px-2 font-bold text-gray-900">Rp 85.000</td>
                            <td class="py-3 px-2"><span class="text-xs bg-purple-50 text-purple-600 px-2 py-1 rounded font-bold">TRANSFER</span></td>
                            <td class="py-3 px-2 text-gray-500">Umum</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Selling Products (Progress Bars) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-900">Produk Terlaris</h2>
                <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19l16-16m0 0H9m11 0v11"></path></svg>
                </div>
            </div>

            <div class="space-y-5">
                <!-- Item 1 -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-gray-700">Midnight Amber 50ml</span>
                        <span class="text-gray-500">120 Terjual</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4 relative">
                        <div class="bg-[#CC9863] h-4 rounded-full" style="width: 75%"></div>
                        <span class="absolute left-2 top-0 text-[10px] font-bold text-white leading-4">75%</span>
                    </div>
                </div>
                <!-- Item 2 -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-gray-700">OXTA Poseidon</span>
                        <span class="text-gray-500">85 Terjual</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4 relative">
                        <div class="bg-blue-400 h-4 rounded-full" style="width: 42%"></div>
                        <span class="absolute left-2 top-0 text-[10px] font-bold text-white leading-4">42%</span>
                    </div>
                </div>
                <!-- Item 3 -->
                <div>
                    <div class="flex justify-between text-xs font-semibold mb-1">
                        <span class="text-gray-700">Vanilla Clouds 100ml</span>
                        <span class="text-gray-500">40 Terjual</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-4 relative">
                        <div class="bg-purple-400 h-4 rounded-full" style="width: 25%"></div>
                        <span class="absolute left-2 top-0 text-[10px] font-bold text-white leading-4">25%</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
