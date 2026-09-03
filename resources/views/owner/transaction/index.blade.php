@extends('template.sidebar')
@section('title', 'Riwayat Transaksi')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau seluruh aktivitas penjualan dan kelola pengajuan perubahan data.</p>
        </div>
        <div class="flex gap-3 w-full sm:w-auto">
            <button class="bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Lanjut
            </button>
            <button class="bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF/Excel
            </button>
        </div>
    </div>

    <!-- ================= ALERT NOTIFIKASI APPROVAL ================= -->
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5 mb-6 shadow-sm relative overflow-hidden">
        <!-- Dekorasi bg -->
        <div class="absolute -right-4 -top-4 text-orange-200/50">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
        </div>

        <div class="flex items-start gap-4 relative z-10">
            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div class="flex-1">
                <h3 class="text-base font-bold text-gray-900">Menunggu Persetujuan Anda (2)</h3>
                <p class="text-sm text-gray-600 mt-1">Admin mengajukan perubahan data pada transaksi yang sudah selesai. Hal ini memerlukan otorisasi Owner.</p>

                <div class="mt-4 space-y-3">
                    <!-- Request 1 -->
                    <div class="bg-white p-3 rounded-xl border border-orange-100 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-sm">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Pengajuan <span class="text-red-500">Hapus Transaksi</span> (INV-2609-005)</p>
                            <p class="text-xs text-gray-500 mt-0.5">Oleh: Admin Farhan • Alasan: <i>Kasir salah input nominal uang pelanggan.</i></p>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-4 py-1.5 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition shadow-sm">Izinkan Hapus</button>
                            <button class="px-4 py-1.5 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition shadow-sm">Tolak</button>
                        </div>
                    </div>
                    <!-- Request 2 -->
                    <div class="bg-white p-3 rounded-xl border border-orange-100 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-sm">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Pengajuan <span class="text-blue-500">Edit Transaksi</span> (INV-2609-002)</p>
                            <p class="text-xs text-gray-500 mt-0.5">Oleh: Admin Farhan • Alasan: <i>Merubah metode dari Kasbon ke Lunas.</i></p>
                        </div>
                        <div class="flex gap-2">
                            <button class="px-4 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-lg hover:bg-blue-600 transition shadow-sm">Izinkan Edit</button>
                            <button class="px-4 py-1.5 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 transition shadow-sm">Tolak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] sm:text-sm transition" placeholder="Cari No. Invoice, Kasir, atau Pelanggan...">
        </div>

        <!-- Dropdown Filters -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 w-full md:w-2/3">
            <select class="block w-full pl-3 pr-8 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863]">
                <option>Semua Outlet</option>
                <option>Outlet Pusat</option>
                <option>Cabang 1</option>
            </select>
            <select class="block w-full pl-3 pr-8 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863]">
                <option>Semua Tanggal</option>
                <option>Hari Ini</option>
                <option>Bulan Ini</option>
            </select>
            <select class="block w-full pl-3 pr-8 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863]">
                <option>Metode Pembayaran</option>
                <option>Tunai</option>
                <option>QRIS</option>
                <option>Tempo / Hutang</option>
            </select>
            <select class="block w-full pl-3 pr-8 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863]">
                <option>Status Transaksi</option>
                <option>Selesai</option>
                <option>Dihapus</option>
                <option>Pending Edit</option>
            </select>
        </div>
    </div>

    <!-- Transaction Table -->
    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider text-left border-y border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Invoice & Waktu</th>
                        <th class="px-6 py-4">Kasir & Cabang</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4 text-right">Total Transaksi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                    <!-- Row 1: Normal Transaction (QRIS) -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-[#CC9863]">INV-2609-001</p>
                            <p class="text-xs text-gray-400 mt-0.5">03 Sep 2026, 14:30 WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">Dina S.</p>
                            <p class="text-xs text-gray-500">Outlet Pusat</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">Pelanggan Umum</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-600 border border-green-100 uppercase">
                                QRIS
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="font-bold text-gray-900">Rp 325.000</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Laba: Rp 80.000</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Selesai
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-gray-500 hover:text-blue-500 bg-gray-50 hover:bg-blue-50 p-2 rounded-lg transition" title="Lihat Detail Produk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button class="text-gray-500 hover:text-[#CC9863] bg-gray-50 hover:bg-orange-50 p-2 rounded-lg transition" title="Cetak / Download Struk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2: Pending Approval Hapus (Highlighted) -->
                    <tr class="bg-red-50/30 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-[#CC9863]">INV-2609-005</p>
                            <p class="text-xs text-gray-400 mt-0.5">03 Sep 2026, 11:15 WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">Anton M.</p>
                            <p class="text-xs text-gray-500">Cabang 1</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">Rina Melati</p>
                            <p class="text-[10px] text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full inline-block mt-1">Member Reguler</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase">
                                Tunai
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="font-bold text-gray-900 line-through text-gray-400">Rp 170.000</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Req: Hapus
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="bg-red-500 text-white hover:bg-red-600 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">Tinjau</button>
                        </td>
                    </tr>

                    <!-- Row 3: Hutang / Tempo -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-bold text-[#CC9863]">INV-2609-006</p>
                            <p class="text-xs text-gray-400 mt-0.5">02 Sep 2026, 09:00 WIB</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">Dina S.</p>
                            <p class="text-xs text-gray-500">Outlet Pusat</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">Budi Santoso</p>
                            <p class="text-[10px] text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-full inline-block mt-1 font-bold">Reseller VIP</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase">
                                Tempo
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="font-bold text-gray-900">Rp 1.500.000</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Belum Lunas</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tertunda
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-gray-500 hover:text-blue-500 bg-gray-50 hover:bg-blue-50 p-2 rounded-lg transition" title="Lihat Detail Produk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button class="text-gray-500 hover:text-[#CC9863] bg-gray-50 hover:bg-orange-50 p-2 rounded-lg transition" title="Cetak / Download Struk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
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
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>
</main>
@endsection
