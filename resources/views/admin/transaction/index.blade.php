@extends('template.sidebar')
@section('title', 'Riwayat Transaksi')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau seluruh aktivitas penjualan dari semua cabang outlet secara real-time.</p>
        </div>
        <div class="flex gap-3 w-full sm:w-auto">
            <button class="bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Lanjut
            </button>
            <button class="bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </button>
        </div>
    </div>

    <!-- Filter Bar (Multi Outlet Concept) -->
    <div class="bg-white p-4 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] sm:text-sm transition" placeholder="Cari No. Invoice atau Pelanggan...">
        </div>

        <!-- Dropdown Filters -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 w-full md:w-2/3">
            <!-- Filter Outlet -->
            <select class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                <option>Semua Outlet</option>
                <option>Outlet Pusat</option>
                <option>Cabang 1</option>
                <option>Cabang 2</option>
            </select>
            <!-- Filter Tanggal -->
            <select class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                <option>Hari Ini</option>
                <option>Kemarin</option>
                <option>7 Hari Terakhir</option>
                <option>Bulan Ini</option>
                <option>Pilih Tanggal...</option>
            </select>
            <!-- Filter Pembayaran -->
            <select class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863] col-span-2 md:col-span-1">
                <option>Semua Pembayaran</option>
                <option>Tunai (Cash)</option>
                <option>Transfer (QRIS)</option>
                <option>Transfer (Bank)</option>
                <option>Cash Tempo (Hutang)</option>
            </select>
        </div>
    </div>

    <!-- Transaction Table -->
    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider text-left border-y border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Waktu & Invoice</th>
                        <th class="px-6 py-4">Outlet & Kasir</th>
                        <th class="px-6 py-4">Detail Produk</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4 text-right">Total Nominal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                    <!-- Row 1: Normal Transaction (QRIS) -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">INV-2609-001</p>
                            <p class="text-xs text-gray-400">02 Sep 2026, 14:30 WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-700">Outlet Pusat</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Kasir: Dina
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium">1x OXTA Poseidon EDP...</p>
                            <p class="text-xs text-gray-400">+1 item lainnya</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-green-50 text-green-600 border border-green-100 uppercase">
                                QRIS
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="font-bold text-gray-900">Rp 325.000</p>
                            <span class="text-[10px] text-green-500 font-semibold">Lunas</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-[#CC9863] bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition" title="Lihat Detail">Detail</button>
                                <button class="text-gray-500 hover:text-gray-800 bg-gray-50 hover:bg-gray-100 px-2 py-1.5 rounded-lg transition" title="Cetak Struk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2: Cash Transaction -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">INV-2609-002</p>
                            <p class="text-xs text-gray-400">02 Sep 2026, 13:15 WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-700">Cabang 1</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Kasir: Anton
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium">2x Vanilla Clouds Mist</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase">
                                Tunai
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="font-bold text-gray-900">Rp 170.000</p>
                            <span class="text-[10px] text-green-500 font-semibold">Lunas</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-[#CC9863] bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition" title="Lihat Detail">Detail</button>
                                <button class="text-gray-500 hover:text-gray-800 bg-gray-50 hover:bg-gray-100 px-2 py-1.5 rounded-lg transition" title="Cetak Struk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3: Tempo / Hutang (Needs Attention) -->
                    <tr class="hover:bg-red-50/30 transition bg-red-50/10">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">INV-2609-003</p>
                            <p class="text-xs text-gray-400">02 Sep 2026, 10:00 WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-700">Outlet Pusat</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Kasir: Dina
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium">10x Grosir Parfum Campur</p>
                            <p class="text-xs font-semibold text-red-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Plg: Reseller Budi
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase">
                                Tempo
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="font-bold text-gray-900">Rp 1.500.000</p>
                            <span class="text-[10px] text-red-500 font-bold bg-red-100 px-2 py-0.5 rounded-full inline-block mt-1">Belum Lunas</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-[#CC9863] bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition" title="Lihat Detail & Bayar">Proses</button>
                                <button class="text-gray-500 hover:text-gray-800 bg-gray-50 hover:bg-gray-100 px-2 py-1.5 rounded-lg transition" title="Bagikan Struk Digital (PDF)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-sm text-gray-500">Menampilkan 1 hingga 3 dari 1,245 transaksi</span>
            <div class="flex gap-1">
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-400 hover:bg-gray-50 disabled:opacity-50" disabled>Previous</button>
                <button class="px-3 py-1.5 text-sm bg-[#CC9863] text-white rounded-lg font-medium">1</button>
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">2</button>
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">3</button>
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>

</main>
@endsection
