<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - POS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</head>
<body class="bg-[#C89B6A] min-h-screen flex items-center justify-center p-4 antialiased text-gray-800">

    <!-- App Container -->
    <div class="w-full max-w-[1440px] h-[90vh] min-h-[760px] bg-white rounded-[2rem] shadow-2xl flex overflow-hidden">

        <!-- ================= SIDEBAR ================= -->
        <aside class="w-[260px] bg-[#1C1D21] text-gray-400 flex flex-col justify-between py-8 px-6 shrink-0">
            <div>
                <!-- Logo -->
                <div class="flex items-center gap-3 text-white font-bold text-2xl mb-12 px-2">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    POS<span class="text-[#CC9863]">Biz</span>
                </div>

                <!-- Nav Menu -->
                <nav class="space-y-2 text-sm">
                    <!-- Active Item -->
                    <a href="#" class="flex items-center gap-3 bg-[#CC9863] text-white px-4 py-3 rounded-xl font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                    <!-- Inactive Items -->
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Riwayat Transaksi
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Kelola Stok Barang
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Laporan & Omzet
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Kelola Outlet
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Kelola Pegawai
                    </a>
                </nav>
            </div>

            <!-- Bottom Sidebar Area -->
            <div class="space-y-4">
                <!-- User Profile -->
                <div class="flex items-center justify-between p-3 border border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-800 transition">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/150?img=11" alt="Owner" class="w-10 h-10 rounded-full border-2 border-green-500">
                        <div>
                            <p class="text-white text-sm font-medium">Budi Santoso</p>
                            <p class="text-[11px] text-green-500 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Owner Active</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
                <!-- Theme Toggle -->
                <div class="flex bg-[#2A2B30] rounded-full p-1">
                    <button class="flex-1 flex justify-center items-center gap-2 bg-[#CC9863] text-white text-xs py-2 rounded-full font-semibold">
                        Light
                    </button>
                    <button class="flex-1 flex justify-center items-center gap-2 text-gray-400 text-xs py-2 rounded-full font-semibold hover:text-white">
                        Dark
                    </button>
                </div>
            </div>
        </aside>

        <!-- ================= MAIN DASHBOARD CONTENT ================= -->
        <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-10 py-8">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Owner</h1>
                    <p class="text-gray-500 text-sm mt-1">Ringkasan performa bisnis dari semua outlet Anda hari ini.</p>
                </div>
                <button class="text-sm bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-xl font-semibold shadow-sm hover:bg-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Hari Ini, 2 Sep
                </button>
            </div>

            <!-- Welcome Banner -->
            <div class="bg-[#CC9863] rounded-[2rem] p-8 flex justify-between items-center text-white mb-8 relative overflow-hidden shadow-md">
                <div class="z-10 relative max-w-lg">
                    <h2 class="text-2xl font-bold mb-2">Performa Bisnis Luar Biasa! 🚀</h2>
                    <p class="text-white/90 mb-6 text-sm leading-relaxed">Omzet total hari ini mencapai target harian. Semua outlet beroperasi dengan lancar. Segera periksa laporan detail atau kelola pesanan stok yang mulai menipis.</p>
                    <div class="flex gap-4">
                        <button class="bg-white text-[#CC9863] px-6 py-2.5 rounded-full text-sm font-bold shadow-sm hover:bg-gray-50 transition">Export Laporan Excel</button>
                        <button class="bg-[#B58555] text-white border border-[#D5A777] px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-[#A6784A] transition">Cek Stok Global</button>
                    </div>
                </div>
                <!-- Avatar in Banner -->
                <div class="z-10 relative hidden md:block pr-4">
                    <div class="w-32 h-32 rounded-full bg-white/20 p-2 border border-white/30 backdrop-blur-sm shadow-xl">
                        <img src="https://i.pravatar.cc/150?img=11" alt="Owner Profile" class="w-full h-full rounded-full object-cover">
                    </div>
                </div>
                <!-- Decorative Circles -->
                <div class="absolute right-[-10%] top-[-30%] w-80 h-80 border-[40px] border-white/10 rounded-full"></div>
                <div class="absolute left-[-5%] bottom-[-50%] w-64 h-64 border-[20px] border-white/5 rounded-full"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Stat Card 1 -->
                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                    <div class="bg-green-50 p-3.5 rounded-2xl text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Total Omzet</p>
                        <h3 class="text-xl font-extrabold text-gray-900">Rp 24.5M</h3>
                    </div>
                </div>
                <!-- Stat Card 2 (Highlighted) -->
                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex items-center gap-4 relative z-10 transform scale-105">
                    <div class="bg-orange-50 p-3.5 rounded-2xl text-[#CC9863]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Transaksi</p>
                        <h3 class="text-xl font-extrabold text-gray-900">1,245</h3>
                    </div>
                </div>
                <!-- Stat Card 3 -->
                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                    <div class="bg-blue-50 p-3.5 rounded-2xl text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Outlet Aktif</p>
                        <h3 class="text-xl font-extrabold text-gray-900">5</h3>
                    </div>
                </div>
                <!-- Stat Card 4 -->
                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                    <div class="bg-purple-50 p-3.5 rounded-2xl text-purple-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Total Pegawai</p>
                        <h3 class="text-xl font-extrabold text-gray-900">18</h3>
                    </div>
                </div>
            </div>

            <!-- Chart Section: Tipe Transaksi Pembayaran -->
            <div class="bg-white rounded-3xl p-7 border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Metode Pembayaran (Global)</h3>
                        <p class="text-xs text-gray-400 mt-1">Distribusi tipe transaksi dari semua outlet</p>
                    </div>
                    <span class="font-bold text-gray-900 bg-gray-50 px-3 py-1.5 rounded-lg text-sm">1,245 Transaksi</span>
                </div>

                <div class="flex items-center gap-12">
                    <!-- Donut Chart -->
                    <div class="relative w-44 h-44 shrink-0">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90 drop-shadow-sm">
                            <!-- Background Circle -->
                            <path class="text-gray-50" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>

                            <!-- Tempo (10%) - Red -->
                            <path class="text-red-500" stroke-dasharray="10, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>

                            <!-- Transfer Bank (15%) - Yellow -->
                            <path class="text-yellow-400" stroke-dasharray="15, 100" stroke-dashoffset="-10" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>

                            <!-- Tunai/Cash (30%) - Blue -->
                            <path class="text-blue-500" stroke-dasharray="30, 100" stroke-dashoffset="-25" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>

                            <!-- QRIS (45%) - Green -->
                            <path class="text-green-500" stroke-dasharray="45, 100" stroke-dashoffset="-55" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke="currentColor" stroke-width="4.5" fill="none"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-extrabold text-gray-900">45%</span>
                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">QRIS</span>
                        </div>
                    </div>

                    <!-- Progress Bars Detail -->
                    <div class="flex-1 space-y-5">
                        <!-- Item 1 -->
                        <div>
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="flex items-center gap-2 font-medium text-gray-700">
                                    <span class="w-3 h-3 rounded-md bg-green-500 shadow-sm"></span> Transfer (QRIS)
                                </span>
                                <span class="font-bold text-gray-900">560 trx</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full relative" style="width: 45%"></div>
                            </div>
                        </div>
                        <!-- Item 2 -->
                        <div>
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="flex items-center gap-2 font-medium text-gray-700">
                                    <span class="w-3 h-3 rounded-md bg-blue-500 shadow-sm"></span> Uang Tunai (Cash)
                                </span>
                                <span class="font-bold text-gray-900">373 trx</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full relative" style="width: 30%"></div>
                            </div>
                        </div>
                        <!-- Item 3 -->
                        <div>
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="flex items-center gap-2 font-medium text-gray-700">
                                    <span class="w-3 h-3 rounded-md bg-yellow-400 shadow-sm"></span> Transfer (Bank)
                                </span>
                                <span class="font-bold text-gray-900">187 trx</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-yellow-400 h-2 rounded-full relative" style="width: 15%"></div>
                            </div>
                        </div>
                        <!-- Item 4 -->
                        <div>
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="flex items-center gap-2 font-medium text-gray-700">
                                    <span class="w-3 h-3 rounded-md bg-red-500 shadow-sm"></span> Cash Tempo (Hutang)
                                </span>
                                <span class="font-bold text-gray-900">125 trx</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full relative" style="width: 10%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- ================= RIGHT ACTIVITY PANEL ================= -->
        <aside class="w-[360px] bg-[#FDFBF9] overflow-y-auto px-8 py-8 border-l border-gray-100 shrink-0">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Ringkasan Aktivitas</h2>

            <!-- Stok & Admin Activity -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Aktivitas Sistem & Admin</h3>
                    <a href="#" class="text-xs text-[#CC9863] font-bold hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex gap-4 hover:shadow-md transition cursor-pointer">
                        <div class="bg-red-50 text-red-500 rounded-xl p-2 text-center w-12 h-12 flex flex-col justify-center shrink-0 items-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Stok Kopi Susu Menipis</h4>
                            <p class="text-xs text-gray-500 mt-1 leading-snug">Sisa 5 cup di Outlet Pusat. Segera lakukan restok oleh Admin.</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex gap-4 hover:shadow-md transition cursor-pointer">
                        <div class="bg-blue-50 text-blue-500 rounded-xl p-2 text-center w-12 h-12 flex flex-col justify-center shrink-0 items-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-gray-900">Member Kasir Baru</h4>
                            <p class="text-xs text-gray-500 mt-1 leading-snug">Admin menambahkan kasir baru (Anita) untuk Outlet Cabang 2.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Transaksi Terakhir (Live)</h3>
                    <a href="#" class="text-xs text-[#CC9863] font-bold hover:underline">Detail</a>
                </div>
                <div class="space-y-3">
                    <!-- Transaction 1 -->
                    <div class="bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition cursor-pointer">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?img=32" alt="Kasir" class="w-10 h-10 rounded-full border border-gray-200">
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">Rp 125.000</h4>
                                <p class="text-[11px] text-gray-500">Kasir: Dina (Outlet Pusat) • <span class="text-green-500 font-semibold">QRIS</span></p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-400">2 mnt</span>
                    </div>

                    <!-- Transaction 2 -->
                    <div class="bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition cursor-pointer">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?img=12" alt="Kasir" class="w-10 h-10 rounded-full border border-gray-200">
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">Rp 45.000</h4>
                                <p class="text-[11px] text-gray-500">Kasir: Anton (Cabang 1) • <span class="text-blue-500 font-semibold">Tunai</span></p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-400">15 mnt</span>
                    </div>

                    <!-- Transaction 3 -->
                    <div class="bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-sm">SP</div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">Cetak Resi Kurir</h4>
                                <p class="text-[11px] text-gray-500">Pembelian Online (ShopeeFood)</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-400">1 jam</span>
                    </div>

                    <!-- Transaction 4 -->
                    <div class="bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition cursor-pointer">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?img=5" alt="Kasir" class="w-10 h-10 rounded-full border border-gray-200">
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">Rp 850.000</h4>
                                <p class="text-[11px] text-gray-500">Kasir: Rina (Pusat) • <span class="text-red-500 font-semibold">Tempo</span></p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-400">2 jam</span>
                    </div>
                </div>
            </div>
        </aside>

    </div>
</body>
</html>
