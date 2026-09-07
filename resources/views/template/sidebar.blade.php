<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }

        /* Smooth Accordion transition */
        details>summary {
            list-style: none;
        }

        details>summary::-webkit-details-marker {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex text-gray-800 antialiased overflow-hidden">

    @php($currentRole = strtolower(auth()->user()->role?->nama_role ?? ''))

    <!-- ==============================================
         SIDEBAR (DESKTOP)
         ============================================== -->
    <aside
        class="hidden lg:flex w-[260px] bg-[#1C1D21] text-gray-400 flex-col justify-between py-8 px-5 shrink-0 h-screen overflow-y-auto">
        <div>
            <!-- Logo -->
            <div class="flex items-center gap-3 text-white font-bold text-2xl mb-10 px-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="text-[#CC9863]">ZeePerfume</span>
            </div>

            <!-- Nav Menu -->
            <nav class="space-y-2">

                <!-- ================= MENU OWNER ================= -->
                @if ($currentRole === 'owner')
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-3 mt-4">Menu Owner
                    </p>

                    <a href="{{ url('owner/dashboard') }}"
                        class="{{ request()->is('owner/dashboard') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ url('owner/finance') }}"
                        class="{{ request()->is('owner/finance*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        Laporan Keuangan
                    </a>
                    <a href="{{ url('owner/transaction') }}"
                        class="{{ request()->is('owner/transaction*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        Riwayat & Approval
                    </a>

                    <!-- GRUP DATA MASTER (OWNER) -->
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-4 mb-2">Data Master
                    </p>
                    <a href="{{ url('owner/employee') }}"
                        class="{{ request()->is('owner/employee*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        Manajemen Pegawai
                    </a>
                    <a href="{{ url('owner/outlet') }}"
                        class="{{ request()->is('owner/outlet*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        Kelola Outlet
                    </a>
                    <a href="{{ url('owner/member') }}"
                        class="{{ request()->is('owner/member*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Kelola Member
                    </a>
                @endif

                <!-- ================= MENU ADMIN ================= -->
                @if ($currentRole === 'admin')
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-3 mt-4">Menu Utama
                    </p>

                    <a href="{{ url('admin/dashboard') }}"
                        class="{{ request()->is('admin/dashboard') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- GRUP TRANSAKSI (ADMIN) -->
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-4 mb-2">Transaksi</p>
                    <a href="{{ route('admin.transaction.online') }}"
                        class="{{ request()->routeIs('admin.transaction.online') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Buat Pesanan Online
                    </a>
                    <a href="{{ route('admin.transaction.index') }}"
                        class="{{ request()->routeIs('admin.transaction.index') || request()->routeIs('admin.transaction.show') || request()->routeIs('admin.transaction.edit') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        Riwayat Transaksi
                    </a>

                    <!-- GRUP DATA MASTER (ADMIN) -->
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-4 mb-2">Data Master
                    </p>
                    <a href="{{ url('admin/stock') }}"
                        class="{{ request()->is('admin/stock*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Kelola Stok Barang
                    </a>
                    <a href="{{ url('admin/member') }}"
                        class="{{ request()->is('admin/member*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Kelola Member
                    </a>
                @endif

                <!-- ================= MENU KASIR ================= -->
                @if ($currentRole === 'kasir')
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-3 mt-4">Menu Kasir
                    </p>

                    <a href="{{ url('kasir/pos') }}"
                        class="{{ request()->is('kasir/pos') ? 'bg-[#CC9863] text-white shadow-md' : 'text-[#CC9863] hover:bg-gray-800 border border-dashed border-[#CC9863]/50' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Buka Layar Kasir (POS)
                    </a>

                    <a href="{{ url('kasir/transaction') }}"
                        class="{{ request()->is('kasir/transaction*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors mt-2 text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Riwayat Transaksi
                    </a>
                @endif
            </nav>
        </div>

        <div class="space-y-4 mt-8 border-t border-gray-700 pt-4">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profil Saya
            </a>
            <div class="flex items-center justify-between gap-3 p-3 bg-gray-800/50 rounded-2xl border border-gray-700">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full border-2 border-[#CC9863] bg-[#1C1D21] flex items-center justify-center text-sm font-bold text-white shadow-inner">
                        {{ auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->nama_lengkap, 0, 1)) : 'U' }}
                    </div>
                    <div>
                        <p class="max-w-[100px] truncate text-sm font-bold text-white">
                            {{ auth()->check() ? auth()->user()->nama_lengkap : 'Guest' }}</p>
                        <p class="text-[10px] uppercase font-extrabold tracking-wider text-[#CC9863]">
                            {{ $currentRole }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-10 h-10 items-center justify-center rounded-xl text-red-400/80 transition hover:bg-red-500/10 hover:text-red-400 focus:outline-none"
                        title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ==============================================
         MAIN WRAPPER & MOBILE HEADER
         ============================================== -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">

        <header
            class="lg:hidden flex items-center justify-between p-4 bg-[#1C1D21] text-white shrink-0 shadow-sm relative z-20">
            <div class="flex items-center gap-2 font-bold text-xl">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="text-[#CC9863]">ZeePerfume</span>
            </div>

            <details class="relative group">
                <summary
                    class="flex min-h-11 cursor-pointer list-none items-center gap-2 rounded-xl bg-gray-800 px-3 text-sm font-semibold focus:outline-none">
                    Menu
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </summary>

                <div
                    class="absolute right-0 top-14 z-50 w-64 rounded-3xl border border-gray-700 bg-[#1C1D21] p-3 shadow-2xl hidden group-open:block">
                    <!-- User Header -->
                    <div
                        class="px-3 py-3 border-b border-gray-700 mb-3 bg-gray-800/50 rounded-2xl flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full border border-[#CC9863] bg-[#1C1D21] flex items-center justify-center text-sm font-bold text-white">
                            {{ auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->nama_lengkap, 0, 1)) : 'U' }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white truncate max-w-[140px]">
                                {{ auth()->check() ? auth()->user()->nama_lengkap : 'Guest' }}</p>
                            <p class="text-[10px] uppercase text-[#CC9863] font-bold">{{ $currentRole }}</p>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="block rounded-xl px-3 py-2.5 text-sm font-semibold mb-2 transition-colors {{ request()->routeIs('profile.*') ? 'bg-[#CC9863] text-white' : 'text-gray-300 hover:bg-gray-800' }}">Profil
                        Saya</a>

                    @if ($currentRole === 'owner')
                        <a href="{{ route('owner.dashboard') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Dashboard</a>
                        <a href="{{ route('owner.finance.index') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Laporan
                            Keuangan</a>
                        <a href="{{ route('owner.transaction.index') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Riwayat &
                            Approval</a>

                        <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-3 mb-1">Data
                            Master</p>
                        <a href="{{ route('owner.employee.index') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Manajemen
                            Pegawai</a>
                        <a href="{{ route('owner.outlet.index') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Kelola
                            Outlet</a>
                        <a href="{{ route('owner.member.index') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Kelola
                            Member</a>
                    @elseif ($currentRole === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Dashboard
                            Admin</a>

                        <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-3 mb-1">
                            Transaksi</p>
                        <a href="{{ route('admin.transaction.online') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm font-bold {{ request()->routeIs('admin.transaction.online') ? 'text-white bg-[#CC9863]' : 'text-[#CC9863] hover:bg-gray-800' }}">Buat
                            Pesanan Online</a>
                        <a href="{{ route('admin.transaction.index') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Riwayat
                            Transaksi</a>

                        <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-3 mb-1">Data
                            Master</p>
                        <a href="{{ route('admin.stock.index') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Kelola Stok
                            Barang</a>
                        <a href="{{ route('admin.member.index') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300">Kelola
                            Member</a>
                    @elseif ($currentRole === 'kasir')
                        <a href="{{ route('kasir.pos') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm font-bold {{ request()->is('kasir/pos') ? 'bg-[#CC9863] text-white' : 'text-[#CC9863] bg-gray-800/50' }}">Buka
                            POS Kasir</a>
                        <a href="{{ route('kasir.transaction.index') }}"
                            class="block rounded-xl px-3 py-2.5 text-sm hover:bg-gray-800 text-gray-300 mt-1">Riwayat
                            Transaksi</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t border-gray-700 pt-2">
                        @csrf
                        <button type="submit"
                            class="w-full text-left rounded-xl px-3 py-2.5 text-sm text-red-400 font-bold hover:bg-red-500/10 transition-colors">Keluar
                            Sistem</button>
                    </form>
                </div>
            </details>
        </header>

        <!-- Dynamic Content Section -->
        @yield('content')

    </div>

    <!-- Script menutup Dropdown Mobile saat klik area lain -->
    <script>
        document.addEventListener('click', function(event) {
            const details = document.querySelector('details');
            if (details && !details.contains(event.target)) {
                details.removeAttribute('open');
            }
        });
    </script>
</body>

</html>
