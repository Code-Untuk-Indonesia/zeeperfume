@extends('template.sidebar')
@section('title', 'Dashboard Owner')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 w-full">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Selamat datang,
                    {{ auth()->user()->nama_lengkap ?? 'Owner' }}!</h1>
                <p class="text-gray-500 text-sm mt-1">Saat yang tepat untuk memantau performa bisnis parfum Anda.</p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Tabs Filter Waktu -->
                <div class="bg-white p-1 rounded-xl border border-gray-200 flex text-sm font-semibold shadow-sm">
                    <button class="px-4 py-1.5 rounded-lg bg-[#CC9863] text-white shadow-sm transition">Keseluruhan</button>
                    <button class="px-4 py-1.5 rounded-lg text-gray-500 hover:text-gray-900 transition">Bulan Ini</button>
                </div>

                <button
                    class="bg-white border border-gray-200 p-2.5 rounded-xl text-gray-600 hover:bg-gray-50 shadow-sm transition hidden sm:block">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Stats Grid (4 Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Card 1: Total Omzet -->
            <div
                class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-500">Total Omzet</h3>
                    <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Rp
                        {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h2>
                    <div class="flex items-center gap-2 text-xs">
                        <span
                            class="bg-green-50 text-green-600 px-2 py-0.5 rounded font-semibold flex items-center gap-0.5">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg> Stabil
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Transaksi -->
            <div
                class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-500">Total Transaksi</h3>
                    <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-2">
                        {{ number_format($totalTransactions ?? 0, 0, ',', '.') }} Trx</h2>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-gray-400">Keseluruhan order</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Member -->
            <div
                class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-500">Total Member</h3>
                    <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 mb-2">
                        {{ number_format($totalMembers ?? 0, 0, ',', '.') }} Orang</h2>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-gray-400">Terdaftar di sistem</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Peringatan Stok -->
            <div
                class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-red-500">Stok Kritis / Menipis</h3>
                    <div
                        class="w-7 h-7 rounded-full border border-red-100 bg-red-50 flex items-center justify-center text-red-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-red-600 mb-2">{{ isset($lowStocks) ? $lowStocks->count() : 0 }}
                        Item</h2>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-gray-400">Perlu restok segera</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Row (Charts) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            <!-- Bar Chart Area (Static Visual for now) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-lg font-bold text-gray-900">Performa Penjualan</h2>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-4 text-xs font-semibold text-gray-500">
                            <div class="flex items-center gap-1.5"><span
                                    class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span> Omzet</div>
                            <div class="flex items-center gap-1.5"><span
                                    class="w-2.5 h-2.5 rounded-full bg-blue-200"></span> Modal (HPP)</div>
                        </div>
                    </div>
                </div>

                <!-- Mockup Bar Chart CSS -->
                <div class="flex items-end justify-between h-48 w-full gap-2 relative opacity-70">
                    <!-- Y-Axis Labels -->
                    <div
                        class="absolute left-0 top-0 h-full flex flex-col justify-between text-[10px] text-gray-400 pb-6 hidden sm:flex">
                        <span>15M</span>
                        <span>10M</span>
                        <span>5M</span>
                        <span>1M</span>
                    </div>

                    <div class="flex-1 flex justify-between items-end h-full pl-0 sm:pl-10">
                        <div class="flex flex-col items-center gap-2 w-1/7">
                            <div class="flex items-end gap-1 w-full justify-center h-40">
                                <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 60%"></div>
                                <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 35%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 font-semibold">Sen</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-1/7">
                            <div class="flex items-end gap-1 w-full justify-center h-40">
                                <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 75%"></div>
                                <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 45%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 font-semibold">Sel</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-1/7">
                            <div class="flex items-end gap-1 w-full justify-center h-40">
                                <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 50%"></div>
                                <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 30%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 font-semibold">Rab</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-1/7">
                            <div class="flex items-end gap-1 w-full justify-center h-40">
                                <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 90%"></div>
                                <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 55%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 font-semibold">Kam</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-1/7">
                            <div class="flex items-end gap-1 w-full justify-center h-40">
                                <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 85%"></div>
                                <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 50%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 font-semibold">Jum</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-1/7">
                            <div class="flex items-end gap-1 w-full justify-center h-40">
                                <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 60%"></div>
                                <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 40%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 font-semibold">Sab</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-1/7">
                            <div class="flex items-end gap-1 w-full justify-center h-40">
                                <div class="w-3 sm:w-4 bg-[#CC9863] rounded-t-sm" style="height: 60%"></div>
                                <div class="w-3 sm:w-4 bg-blue-200 rounded-t-sm" style="height: 40%"></div>
                            </div>
                            <span class="text-[10px] text-gray-400 font-semibold">Min</span>
                        </div>
                    </div>
                </div>
                <p class="text-[10px] text-center text-gray-400 mt-2">*Visual grafik adalah ilustrasi statis.</p>
            </div>

            <!-- Donut Chart Area (Metode Pembayaran Dinamis) -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Metode Pembayaran</h2>
                </div>

                <div class="flex-1 flex items-center justify-center relative">
                    <!-- SVG Donut Chart (Static visual shape) -->
                    <svg viewBox="0 0 36 36" class="w-40 h-40 transform -rotate-90">
                        <path class="text-gray-100" stroke-dasharray="100, 100"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            stroke="currentColor" stroke-width="4.5" fill="none" />
                        <path class="text-[#CC9863]" stroke-dasharray="50, 100"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            stroke="currentColor" stroke-width="4.5" fill="none" />
                        <path class="text-blue-400" stroke-dasharray="25, 100" stroke-dashoffset="-50"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            stroke="currentColor" stroke-width="4.5" fill="none" />
                        <path class="text-gray-800" stroke-dasharray="25, 100" stroke-dashoffset="-75"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            stroke="currentColor" stroke-width="4.5" fill="none" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-[10px] text-gray-400 font-semibold">Total Order</span>
                        <span class="text-lg font-extrabold text-gray-900">{{ $totalTransactions ?? 0 }}</span>
                    </div>
                </div>

                <!-- Dynamic List Data Metode Pembayaran -->
                <div class="mt-6 space-y-2">
                    @if (isset($paymentMethods) && $paymentMethods->count() > 0)
                        @foreach ($paymentMethods as $pm)
                            <div class="flex items-center text-xs justify-between">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span>
                                    {{ strtoupper($pm->metode_bayar) }}
                                </div>
                                <span class="font-bold">{{ $pm->total_transaksi }} Trx</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-xs text-center text-gray-400">Belum ada data pembayaran</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bottom Row (Table & Stock Alert) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Recent Transactions Table (Data Dinamis) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Transaksi Terakhir</h2>
                    <a href="{{ route('owner.transaction.index') }}"
                        class="text-[#CC9863] text-xs font-semibold hover:underline">Lihat Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="pb-3 px-2">Waktu</th>
                                <th class="pb-3 px-2">Kasir / Cabang</th>
                                <th class="pb-3 px-2">Total Harga</th>
                                <th class="pb-3 px-2">Metode</th>
                                <th class="pb-3 px-2">Pelanggan</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                            @if (isset($recentTransactions) && $recentTransactions->count() > 0)
                                @foreach ($recentTransactions as $trx)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-2 text-gray-500 text-xs">
                                            {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->translatedFormat('d M H:i') }}
                                        </td>
                                        <td class="py-3 px-2 font-medium">
                                            {{ $trx->kasir->nama_lengkap ?? 'Kasir' }}
                                            <span
                                                class="text-[10px] text-gray-400">({{ $trx->cabang->nama_cabang ?? 'Pusat' }})</span>
                                        </td>
                                        <td class="py-3 px-2 font-bold text-gray-900">Rp
                                            {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                                        <td class="py-3 px-2">
                                            <span
                                                class="text-[10px] bg-gray-100 text-gray-600 px-2 py-1 rounded font-bold uppercase">
                                                {{ $trx->metode_bayar }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2 text-gray-500 text-xs">{{ $trx->member->nama ?? 'Umum' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-sm text-gray-500">Belum ada transaksi
                                        tercatat.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Warning Stock List (Data Dinamis) -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Peringatan Stok</h2>
                        <p class="text-[10px] text-gray-500">Stok produk &lt; 10</p>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="space-y-4 overflow-y-auto flex-1 pr-2">
                    @if (isset($lowStocks) && $lowStocks->count() > 0)
                        @foreach ($lowStocks as $stok)
                            <div
                                class="flex justify-between items-center bg-red-50/30 border border-red-50 p-3 rounded-xl">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800 leading-tight">
                                        {{ $stok->varian->nama_varian ?? 'Produk Tidak Dikenal' }}</h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Outlet:
                                        {{ $stok->cabang->nama_cabang ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-lg font-bold {{ $stok->stok == 0 ? 'text-red-600' : 'text-orange-500' }}">{{ $stok->stok }}</span>
                                    <p class="text-[10px] text-gray-400 uppercase">Sisa</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-center opacity-60 mt-4">
                            <svg class="w-10 h-10 text-green-500 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs font-semibold text-gray-600">Semua stok terpantau aman.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
@endsection
