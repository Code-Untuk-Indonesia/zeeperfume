<!DOCTYPE html>
<html lang="id">

@php
    $currentRole = strtolower(auth()->user()->role?->nama_role ?? '');
    $canToggleDesktopSidebar = in_array($currentRole, ['owner', 'admin'], true);
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .sidebar-shell {
            transition: transform 280ms cubic-bezier(0.22, 1, 0.36, 1), opacity 180ms ease;
            will-change: transform, opacity;
        }

        #sidebar-open-button {
            display: none;
        }

        /* Konfigurasi Grid Sidebar Desktop */
        @media (min-width: 1024px) {
            body.sidebar-layout {
                display: grid;
                grid-template-columns: 260px minmax(0, 1fr);
                transition: grid-template-columns 280ms cubic-bezier(0.22, 1, 0.36, 1);
                will-change: grid-template-columns;
            }

            body.sidebar-layout #sidebar {
                width: 100% !important;
                min-width: 0 !important;
                flex-basis: auto !important;
            }

            /* Saat Disembunyikan (Hidden) */
            html.sidebar-hidden body.sidebar-layout {
                grid-template-columns: 0 minmax(0, 1fr);
            }

            html.sidebar-hidden #sidebar {
                padding-left: 0 !important;
                padding-right: 0 !important;
                opacity: 0;
                pointer-events: none;
                overflow: hidden;
            }

            /* Tampilkan Hamburger di Kiri Atas Konten saat Sidebar Disembunyikan */
            html.sidebar-hidden #sidebar-open-button {
                display: flex !important;
                pointer-events: auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .sidebar-shell,
            body.sidebar-layout {
                transition: none;
            }
        }

        @media (max-width: 1023px) {
            #sidebar {
                width: min(260px, calc(100vw - 2rem));
                padding-left: max(1.25rem, env(safe-area-inset-left));
                padding-right: max(1.25rem, env(safe-area-inset-right));
            }
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

        details>summary {
            list-style: none;
        }

        details>summary::-webkit-details-marker {
            display: none;
        }
    </style>
    @if ($canToggleDesktopSidebar)
        <script>
            (() => {
                try {
                    if (window.localStorage.getItem('zeeperfume.sidebar.{{ $currentRole }}') === 'hidden') {
                        document.documentElement.classList.add('sidebar-hidden');
                    }
                } catch (error) {}
            })();
        </script>
    @endif
</head>

<body
    class="flex {{ $canToggleDesktopSidebar ? 'sidebar-layout' : '' }} bg-gray-50 h-screen w-screen text-gray-800 antialiased overflow-hidden relative">

    <!-- ==============================================
         OVERLAY BACKDROP (MOBILE ONLY)
         ============================================== -->
    <div id="sidebar-backdrop"
        class="fixed inset-0 bg-gray-900/60 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"
        onclick="toggleSidebar()"></div>

    <!-- ==============================================
         TOMBOL TAMPILKAN SIDEBAR (Desktop - Saat Hidden)
         Menggunakan Ikon Hamburger di kiri atas
         ============================================== -->
    @if ($canToggleDesktopSidebar)
        <button id="sidebar-open-button" type="button" onclick="toggleDesktopSidebar()"
            class="fixed left-4 top-4 z-[60] h-11 w-11 items-center justify-center rounded-xl bg-white text-gray-800 shadow-sm border border-gray-200 transition hover:bg-gray-100 hover:text-[#CC9863] focus:outline-none hidden lg:hidden"
            aria-label="Tampilkan menu samping" title="Tampilkan Menu">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    @endif

    <!-- ==============================================
         SIDEBAR (MAIN CONTAINER)
         ============================================== -->
    <aside id="sidebar"
        class="sidebar-shell fixed inset-y-0 left-0 z-50 w-[260px] bg-[#1C1D21] text-gray-400 flex flex-col justify-between py-6 lg:py-8 px-5 h-full overflow-y-auto transform -translate-x-full lg:relative lg:translate-x-0 shrink-0 shadow-2xl lg:shadow-none"
        aria-label="Menu utama">

        <div class="flex-1 flex flex-col">

            <!-- HEADER SIDEBAR: Logo, Text, & Tombol Hamburger -->
            <div class="relative flex flex-col items-center justify-center mb-8 pb-6 border-b border-gray-800/80 w-full pt-2">

                <!-- Tombol Sembunyikan Desktop (Ikon Hamburger) -->
                @if ($canToggleDesktopSidebar)
                    <button id="desktop-sidebar-toggle" type="button" onclick="toggleDesktopSidebar()"
                        class="absolute right-0 top-0 hidden lg:flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition focus:outline-none"
                        title="Sembunyikan Menu">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                @endif

                <!-- Tombol Tutup Mobile (X) -->
                <button id="mobile-sidebar-close" type="button" onclick="toggleSidebar()"
                    class="absolute right-0 top-0 lg:hidden h-9 w-9 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <!-- Logo & Brand Name (Center Vertikal) -->
                <div class="flex flex-col items-center gap-3 mt-4">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center p-2 shadow-inner border border-white/5">
                        <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-xl font-black text-white tracking-wide">
                        Zee<span class="text-[#CC9863]">Perfume</span>
                    </span>
                </div>

            </div>

            <!-- MENU NAVIGASI -->
            <nav class="space-y-1.5 flex-1">

                <!-- ================= MENU OWNER ================= -->
                @if ($currentRole === 'owner')
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-3">Menu Utama</p>

                    <a href="{{ url('owner/dashboard') }}"
                        class="{{ request()->is('owner/dashboard') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        Dashboard
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

                    <!-- GRUP KEUANGAN (OWNER) -->
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-5 mb-3">Keuangan</p>

                    <a href="{{ route('owner.income.index') }}"
                        class="{{ request()->routeIs('owner.income.*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Laporan Pendapatan
                    </a>

                    <a href="{{ route('owner.expense.index') }}"
                        class="{{ request()->routeIs('owner.expense.*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z">
                            </path>
                        </svg>
                        Beban Pengeluaran
                    </a>

                    <a href="{{ url('owner/finance') }}"
                        class="{{ request()->routeIs('owner.finance.*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        Analytics Finance
                    </a>

                    <!-- GRUP DATA MASTER (OWNER) -->
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-5 mb-2">Data Master</p>
                    <details class="group"
                        {{ request()->is('owner/employee*') || request()->is('owner/member*') || request()->is('owner/outlet*') || request()->is('owner/stock*') ? 'open' : '' }}>
                        <summary
                            class="flex items-center justify-between px-3 py-3 rounded-xl cursor-pointer transition-colors {{ request()->is('owner/employee*') || request()->is('owner/member*') || request()->is('owner/outlet*') || request()->is('owner/stock*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800 text-gray-300' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <span class="font-medium text-sm">Data Master</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="mt-2 space-y-1 pl-11 pr-2 pb-2">
                            <a href="{{ url('owner/stock') }}"
                                class="block py-2 text-sm transition-colors {{ request()->routeIs('owner.stock.index', 'owner.stock.create', 'owner.stock.edit') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Stok
                                Barang</a>
                            <a href="{{ route('owner.stock.history') }}"
                                class="block py-2 text-sm transition-colors {{ request()->routeIs('owner.stock.history') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Riwayat
                                Stok</a>
                            <a href="{{ url('owner/employee') }}"
                                class="block py-2 text-sm transition-colors {{ request()->is('owner/employee*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Manajemen
                                Pegawai</a>
                            <a href="{{ url('owner/member') }}"
                                class="block py-2 text-sm transition-colors {{ request()->is('owner/member*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola
                                Member</a>
                            <a href="{{ url('owner/outlet') }}"
                                class="block py-2 text-sm transition-colors {{ request()->is('owner/outlet*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola
                                Outlet</a>
                        </div>
                    </details>
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
                    <details class="group mt-2" {{ request()->is('admin/transaction*') ? 'open' : '' }}>
                        <summary
                            class="flex items-center justify-between px-3 py-3 rounded-xl cursor-pointer transition-colors {{ request()->is('admin/transaction*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800 text-gray-300' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="font-medium text-sm">Transaksi</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="mt-2 space-y-1 pl-11 pr-2 pb-2">
                            <a href="{{ route('admin.transaction.online') }}"
                                class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.transaction.online') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Pesanan
                                Online</a>
                            <a href="{{ route('admin.transaction.index') }}"
                                class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.transaction.index') || request()->routeIs('admin.transaction.show') || request()->routeIs('admin.transaction.edit') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Riwayat
                                Transaksi</a>
                        </div>
                    </details>

                    <!-- GRUP KEUANGAN (ADMIN) -->
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-5 mb-3">Keuangan</p>
                    <a href="{{ route('admin.expense.index') }}"
                        class="{{ request()->routeIs('admin.expense.*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-colors text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        Beban Pengeluaran
                    </a>

                    <!-- GRUP DATA MASTER (ADMIN) -->
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mt-5 mb-2">Data Master
                    </p>
                    <details class="group"
                        {{ request()->is('admin/stock*') || request()->is('admin/member*') ? 'open' : '' }}>
                        <summary
                            class="flex items-center justify-between px-3 py-3 rounded-xl cursor-pointer transition-colors {{ request()->is('admin/stock*') || request()->is('admin/member*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800 text-gray-300' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <span class="font-medium text-sm">Data Master</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>
                        <div class="mt-2 space-y-1 pl-11 pr-2 pb-2">
                            <a href="{{ url('admin/stock') }}"
                                class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.stock.index', 'admin.stock.create', 'admin.stock.edit') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola
                                Stok</a>
                            <a href="{{ route('admin.stock.history') }}"
                                class="block py-2 text-sm transition-colors {{ request()->routeIs('admin.stock.history') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Riwayat
                                Stok</a>
                            <a href="{{ url('admin/member') }}"
                                class="block py-2 text-sm transition-colors {{ request()->is('admin/member*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola
                                Member</a>
                        </div>
                    </details>
                @endif

                <!-- ================= MENU KASIR ================= -->
                @if ($currentRole === 'kasir')
                    <p class="px-3 text-[10px] font-bold tracking-wider text-gray-500 uppercase mb-3 mt-4">Menu Kasir</p>

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

            <!-- FOOTER SIDEBAR (Profil & Logout) -->
            <div class="mt-8 border-t border-gray-800/80 pt-6">
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-[#CC9863] text-white' : 'hover:bg-gray-800 text-gray-300' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profil Pengguna
                </a>

                <div
                    class="flex items-center justify-between gap-3 p-3 mt-3 bg-gray-800/30 rounded-2xl border border-gray-700/50">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div
                            class="w-10 h-10 rounded-full border-2 border-[#CC9863] bg-[#1C1D21] flex items-center justify-center text-sm font-bold text-white shadow-inner shrink-0">
                            {{ auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->nama_lengkap, 0, 1)) : 'U' }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="truncate text-sm font-bold text-white">
                                {{ auth()->check() ? auth()->user()->nama_lengkap : 'Guest' }}</p>
                            <p class="text-[10px] uppercase font-extrabold tracking-wider text-[#CC9863]">
                                {{ $currentRole }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                        @csrf
                        <button type="submit"
                            class="flex w-10 h-10 items-center justify-center rounded-xl text-red-400/80 transition hover:bg-red-500/10 hover:text-red-400 focus:outline-none"
                            title="Keluar dari Akun">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </aside>

    <!-- ==============================================
         MAIN CONTENT WRAPPER
         ============================================== -->
    <div class="min-w-0 flex-1 flex flex-col h-full overflow-hidden w-full relative">

        <!-- Mobile Header (Hamburger Menu - Mobile Only) -->
        <header
            class="lg:hidden flex items-center justify-between p-4 bg-[#1C1D21] text-white shrink-0 shadow-sm relative z-20">
            <div class="flex items-center gap-3">
                <button id="mobile-sidebar-open" type="button" onclick="toggleSidebar()"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gray-800 text-white transition hover:text-[#CC9863] focus:outline-none focus:ring-2 focus:ring-[#CC9863]"
                    aria-label="Buka menu samping">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="flex items-center gap-2 font-bold text-xl">
                    <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="Logo" class="w-6 h-6 object-contain">
                    <span class="text-[#CC9863]">ZeePerfume</span>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Section -->
        @yield('content')

    </div>

    <!-- Script navigasi sidebar desktop dan mobile -->
    <script>
        const sidebarStorageKey = 'zeeperfume.sidebar.{{ $currentRole }}';

        function isDesktopSidebarViewport() {
            return window.innerWidth >= 1024;
        }

        function syncSidebarState() {
            const sidebar = document.getElementById('sidebar');
            if (!sidebar) return;

            const root = document.documentElement;
            const desktopHidden = root.classList.contains('sidebar-hidden');
            const mobileClosed = sidebar.classList.contains('-translate-x-full');
            const desktopToggle = document.getElementById('desktop-sidebar-toggle');
            const desktopOpen = document.getElementById('sidebar-open-button');
            const mobileOpen = document.getElementById('mobile-sidebar-open');
            const mobileClose = document.getElementById('mobile-sidebar-close');
            const isDesktop = isDesktopSidebarViewport();

            sidebar.setAttribute('aria-hidden', String(isDesktop ? desktopHidden : mobileClosed));
            if (desktopToggle) desktopToggle.setAttribute('aria-expanded', String(!desktopHidden));
            if (desktopOpen) {
                desktopOpen.setAttribute('aria-expanded', String(!desktopHidden));
                // Tombol muncul HANYA saat desktop dan sidebar di-hide
                if (isDesktop && desktopHidden) {
                    desktopOpen.classList.remove('hidden');
                    desktopOpen.classList.add('flex');
                    desktopOpen.style.pointerEvents = 'auto';
                } else {
                    desktopOpen.classList.add('hidden');
                    desktopOpen.classList.remove('flex');
                    desktopOpen.style.pointerEvents = 'none';
                }
            }
            if (mobileOpen) mobileOpen.setAttribute('aria-expanded', String(!mobileClosed));
            if (mobileClose) mobileClose.setAttribute('aria-expanded', String(!mobileClosed));
        }

        window.toggleDesktopSidebar = function() {
            const root = document.documentElement;
            const hidden = !root.classList.contains('sidebar-hidden');
            const desktopToggle = document.getElementById('desktop-sidebar-toggle');
            const desktopOpen = document.getElementById('sidebar-open-button');

            root.classList.toggle('sidebar-hidden', hidden);
            try {
                window.localStorage.setItem(sidebarStorageKey, hidden ? 'hidden' : 'visible');
            } catch (error) {}

            syncSidebarState();
            const focusTarget = hidden ? desktopOpen : desktopToggle;
            if (focusTarget) focusTarget.focus();
        };

        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (!sidebar || !backdrop) return;

            const isClosed = sidebar.classList.contains('-translate-x-full');
            const mobileOpen = document.getElementById('mobile-sidebar-open');
            const mobileClose = document.getElementById('mobile-sidebar-close');

            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                window.requestAnimationFrame(() => backdrop.classList.remove('opacity-0'));
                window.requestAnimationFrame(() => mobileClose && mobileClose.focus());
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0');
                window.setTimeout(() => {
                    if (sidebar.classList.contains('-translate-x-full')) backdrop.classList.add('hidden');
                }, 300);
                window.requestAnimationFrame(() => mobileOpen && mobileOpen.focus());
            }

            syncSidebarState();
        };

        window.addEventListener('resize', syncSidebarState);
        document.addEventListener('keydown', (event) => {
            const sidebar = document.getElementById('sidebar');
            if (event.key === 'Escape' && !isDesktopSidebarViewport() && sidebar && !sidebar.classList.contains(
                    '-translate-x-full')) {
                window.toggleSidebar();
            }
        });

        syncSidebarState();
    </script>
</body>

</html>
