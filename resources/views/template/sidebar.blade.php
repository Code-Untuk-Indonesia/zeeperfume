<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex text-gray-800 antialiased overflow-hidden">

    <!-- Sidebar (Hidden on Mobile, Visible on LG screens) -->
    <aside class="hidden lg:flex w-[260px] bg-[#1C1D21] text-gray-400 flex-col justify-between py-8 px-6 shrink-0 h-screen overflow-y-auto">
        <div>
            <!-- Logo -->
            <div class="flex items-center gap-3 text-white font-bold text-2xl mb-12 px-2">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="text-[#CC9863]">ZeePerfume</span>
            </div>

            <!-- Nav Menu -->
            <nav class="space-y-2 text-sm">
                <!-- Dashboard Owner -->
                <a href="{{ url('owner/dashboard') }}" class="{{ request()->is('owner/dashboard') || request()->is('admin/dashboard') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800' }} flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                <!-- Transaksi POS (Akses Cepat) -->
                <a href="{{ url('kasir/pos') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Buka Kasir (POS)
                </a>

                <!-- Riwayat Transaksi -->
                <a href="{{ url('admin/transaction') }}" class="{{ request()->is('admin/transaction*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Riwayat Transaksi
                </a>

                <!-- Kelola Stok Barang -->
                <a href="{{ url('admin/stock') }}" class="{{ request()->is('admin/stock*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Kelola Stok Barang
                </a>

                <!-- Kelola Member -->
                <a href="{{ url('admin/member') }}" class="{{ request()->is('admin/member*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Kelola Member
                </a>

                <!-- Manajemen Pegawai -->
                <a href="{{ url('owner/employee') }}" class="{{ request()->is('owner/employee*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Manajemen Pegawai
                </a>

                <!-- Kelola Outlet (Placeholder untuk nanti) -->
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Kelola Outlet
                </a>
            </nav>
        </div>

        <div class="space-y-4 mt-8">
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
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <!-- Mobile Header -->
        <header class="lg:hidden flex items-center justify-between p-4 bg-[#1C1D21] text-white shrink-0">
            <div class="flex items-center gap-2 font-bold text-xl">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="text-[#CC9863]">ZeePerfume</span>
            </div>
            <button class="p-2 bg-gray-800 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </header>

        <!-- Dynamic Content Section -->
        @yield('content')

    </div>

</body>
</html>
