@extends('template.sidebar')
@section('title', 'Dashboard Admin')

@section('content')
<div class="flex flex-col xl:flex-row flex-1 overflow-y-auto w-full bg-[#FAFAFA]">

    <main class="flex-1 px-4 lg:px-10 py-6 lg:py-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau operasional kasir, pendapatan harian, dan peringatan stok.</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button class="text-sm bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-gray-50 flex items-center justify-center gap-2 flex-1 sm:flex-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    03 Sep 2026
                </button>
                <button class="text-sm bg-[#1C1D21] text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-gray-800 flex items-center justify-center gap-2 flex-1 sm:flex-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Rekap Harian
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-3xl border border-[#CC9863]/30 shadow-[0_4px_20px_rgb(0,0,0,0.05)] flex items-center gap-4 relative z-10">
                <div class="bg-orange-50 p-3.5 rounded-2xl text-[#CC9863]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Transaksi Hari Ini</p>
                    <h3 class="text-xl font-extrabold text-gray-900">45 Trx</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                <div class="bg-green-50 p-3.5 rounded-2xl text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Omzet Hari Ini</p>
                    <h3 class="text-xl font-extrabold text-gray-900">Rp 4.5M</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                <div class="bg-blue-50 p-3.5 rounded-2xl text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Pesanan Online</p>
                    <div class="flex items-center gap-2">
                        <h3 class="text-xl font-extrabold text-gray-900">12</h3>
                        <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded font-bold">5 Perlu Cetak Resi</span>
                    </div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition cursor-pointer">
                <div class="bg-red-50 p-3.5 rounded-2xl text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Stok Menipis</p>
                    <h3 class="text-xl font-extrabold text-red-500">8 Item</h3>
                </div>
            </div>
        </div>

        <h2 class="text-lg font-bold text-gray-900 mb-4">Laporan Pendapatan Hari Ini</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-[#CC9863] font-bold">PST</div>
                        <div>
                            <h3 class="font-bold text-gray-900">Outlet Pusat</h3>
                            <p class="text-xs text-gray-500">Kasir Aktif: Dina S.</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold bg-green-50 text-green-600 px-2 py-1 rounded-md">Buka</span>
                </div>
                <div class="flex justify-between items-end mb-2">
                    <span class="text-xs text-gray-500">Total Setoran</span>
                    <span class="text-xl font-extrabold text-gray-900">Rp 2.850.000</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-1">
                    <div class="bg-[#CC9863] h-1.5 rounded-full" style="width: 65%"></div>
                </div>
                <p class="text-[10px] text-gray-400 text-right">65% dari target harian</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 font-bold">CB1</div>
                        <div>
                            <h3 class="font-bold text-gray-900">Cabang 1 (Mall)</h3>
                            <p class="text-xs text-gray-500">Kasir Aktif: Anton M.</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold bg-green-50 text-green-600 px-2 py-1 rounded-md">Buka</span>
                </div>
                <div class="flex justify-between items-end mb-2">
                    <span class="text-xs text-gray-500">Total Setoran</span>
                    <span class="text-xl font-extrabold text-gray-900">Rp 1.650.000</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5 mb-1">
                    <div class="bg-[#CC9863] h-1.5 rounded-full" style="width: 40%"></div>
                </div>
                <p class="text-[10px] text-gray-400 text-right">40% dari target harian</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg text-gray-900">Perhatian Stok & Operasional</h3>
                    <p class="text-xs text-gray-500 mt-1">Item yang membutuhkan tindakan segera.</p>
                </div>
                <a href="{{ url('admin/stock') }}" class="text-sm text-[#CC9863] font-semibold hover:underline">Kelola Stok</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Peringatan</th>
                            <th class="px-6 py-3">Lokasi / Keterangan</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                                    <div>
                                        <p class="font-bold text-gray-900">Stok Habis: Ocean Breeze 30ml</p>
                                        <p class="text-xs text-gray-500">SKU: PRF-008</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">Cabang 1 (Mall)</td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-xs bg-[#1C1D21] text-white px-3 py-1.5 rounded-lg shadow-sm hover:bg-gray-800 transition">Update Stok</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                                    <div>
                                        <p class="font-bold text-gray-900">Stok Menipis: Vanilla Clouds 100ml</p>
                                        <p class="text-xs text-gray-500">Sisa 5 Botol</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">Outlet Pusat</td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-xs bg-[#1C1D21] text-white px-3 py-1.5 rounded-lg shadow-sm hover:bg-gray-800 transition">Update Stok</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <aside class="w-full xl:w-[360px] bg-white xl:bg-[#FDFBF9] px-4 lg:px-8 py-6 lg:py-8 border-t xl:border-t-0 xl:border-l border-gray-100 shrink-0">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6">Aktivitas Sistem</h2>

        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pemberitahuan</h3>
            </div>
            <div class="space-y-3">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex gap-4 hover:shadow-md transition cursor-pointer">
                    <div class="bg-blue-50 text-blue-500 rounded-xl p-2 text-center w-12 h-12 flex flex-col justify-center shrink-0 items-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-gray-900">Member Reseller Baru</h4>
                        <p class="text-xs text-gray-500 mt-1 leading-snug">Kasir (Pusat) mendaftarkan Budi Santoso sebagai VIP.</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Transaksi Terakhir (Live)</h3>
                <a href="{{ url('admin/transaction') }}" class="text-xs text-[#CC9863] font-bold hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                <div class="bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition cursor-pointer">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/150?img=32" alt="Kasir" class="w-10 h-10 rounded-full border border-gray-200">
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Rp 325.000</h4>
                            <p class="text-[11px] text-gray-500">Kasir: Dina (Pusat) • <span class="text-green-500 font-semibold">QRIS</span></p>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-400">Baru saja</span>
                </div>

                <div class="bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition cursor-pointer">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-sm">SP</div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Cetak Resi Kurir</h4>
                            <p class="text-[11px] text-gray-500">Online (ShopeeFood)</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-400">10 mnt</span>
                </div>

                <div class="bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition cursor-pointer">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/150?img=12" alt="Kasir" class="w-10 h-10 rounded-full border border-gray-200">
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Rp 170.000</h4>
                            <p class="text-[11px] text-gray-500">Kasir: Anton (Cabang 1)</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-400">1 jam</span>
                </div>
            </div>
        </div>
    </aside>

</div>
@endsection
