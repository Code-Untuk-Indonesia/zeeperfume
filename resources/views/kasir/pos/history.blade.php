@extends('template.kasir')
@section('title', 'Riwayat Transaksi')

@section('content')
<!-- Container utama mengambil sisa tinggi layar di bawah top navbar -->
<div class="flex-1 overflow-y-auto p-4 lg:p-8 bg-[#FAFAFA] w-full">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-500 text-sm mt-1">Daftar transaksi yang Anda proses pada shift hari ini.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="bg-white border border-gray-200 px-4 py-2.5 rounded-xl font-bold text-gray-700 shadow-sm flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                03 Sep 2026
            </div>
            <!-- Tombol kembali ke POS -->
            <a href="{{ url('kasir/pos') }}" class="bg-[#1C1D21] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-800 transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Kembali ke POS
            </a>
        </div>
    </div>

    <!-- Quick Stats Khusus Kasir -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <!-- NEW CARD: Total Pendapatan Keseluruhan (Highlighted) -->
        <div class="col-span-2 lg:col-span-1 bg-[#1C1D21] p-4 rounded-2xl shadow-sm text-white flex flex-col justify-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pendapatan</p>
            <h3 class="text-xl font-extrabold text-[#CC9863]">Rp 4.100.000</h3>
        </div>

        <!-- Rincian Stats Lainnya -->
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Transaksi</p>
            <h3 class="text-xl font-extrabold text-gray-900">42 Trx</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Penerimaan Tunai</p>
            <h3 class="text-xl font-extrabold text-gray-900">Rp 2.150.000</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Penerimaan QRIS</p>
            <h3 class="text-xl font-extrabold text-gray-900">Rp 1.400.000</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-red-100 bg-red-50/30 shadow-sm flex flex-col justify-center">
            <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-1">Kasbon / Tempo</p>
            <h3 class="text-xl font-extrabold text-red-600">Rp 550.000</h3>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4 justify-between items-center">
        <!-- Search -->
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] sm:text-sm transition" placeholder="Cari No. Invoice pelanggan...">
        </div>

        <!-- Filters -->
        <div class="flex gap-3 w-full md:w-auto">
            <select class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                <option>Semua Metode</option>
                <option>Tunai</option>
                <option>QRIS / Transfer</option>
                <option>Tempo</option>
            </select>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider text-left border-y border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">No. Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4 text-right">Total Transaksi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                    <!-- Row 1: QRIS -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-500">14:30 WIB</td>
                        <td class="px-6 py-4 font-bold text-gray-900">INV-2609-042</td>
                        <td class="px-6 py-4 font-medium text-gray-900">Pelanggan Umum</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-600 border border-green-100 uppercase">
                                QRIS
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">Rp 325.000</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-[#CC9863] bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition" title="Lihat Detail Produk">Detail</button>
                                <button class="text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1" title="Cetak Struk Lagi">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2: Tunai -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-500">14:15 WIB</td>
                        <td class="px-6 py-4 font-bold text-gray-900">INV-2609-041</td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            Siti Aisyah
                            <span class="text-[10px] text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded ml-1">Reguler</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase">
                                Tunai
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">Rp 170.000</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-[#CC9863] bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition" title="Lihat Detail Produk">Detail</button>
                                <button class="text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1" title="Cetak Struk Lagi">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3: Tempo -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-500">13:50 WIB</td>
                        <td class="px-6 py-4 font-bold text-gray-900">INV-2609-040</td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            Budi Santoso
                            <span class="text-[10px] text-yellow-700 bg-yellow-100 px-1.5 py-0.5 rounded ml-1 font-bold">VIP</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase">
                                Tempo
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">Rp 550.000</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-[#CC9863] bg-orange-50 hover:bg-orange-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition" title="Lihat Detail Produk">Detail</button>
                                <button class="text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1" title="Cetak Struk Lagi">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-sm text-gray-500">Menampilkan 1 hingga 3 dari 42 transaksi hari ini</span>
            <div class="flex gap-1">
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-400 hover:bg-gray-50 disabled:opacity-50" disabled>Previous</button>
                <button class="px-3 py-1.5 text-sm bg-[#CC9863] text-white rounded-lg font-medium">1</button>
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">2</button>
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection
