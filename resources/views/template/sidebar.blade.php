<!DOCTYPE html>
<html lang="id">

@php
    $currentRole = strtolower(auth()->user()->role?->nama_role ?? '');
    $canToggleDesktopSidebar = in_array($currentRole, ['owner', 'admin'], true);
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Mengganti Logo (Favicon) Website dari Laravel ke ZeePerfume -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('asset/zeeperfume_logo.svg') }}">
    <link rel="shortcut icon" href="{{ asset('asset/zeeperfume_logo.svg') }}">

    <title>ZeePerfume - @yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .sidebar-shell {
            transition: transform 280ms cubic-bezier(0.22, 1, 0.36, 1), opacity 180ms ease;
            will-change: transform, opacity;
        }

        #sidebar-open-button { display: none; }

        /* Menghilangkan panah default dari tag details */
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }

        @media (min-width: 1024px) {
            body.sidebar-layout {
                display: grid;
                grid-template-columns: 270px minmax(0, 1fr);
                transition: grid-template-columns 280ms cubic-bezier(0.22, 1, 0.36, 1);
                will-change: grid-template-columns;
            }

            body.sidebar-layout #sidebar {
                width: 100% !important;
                min-width: 0 !important;
                flex-basis: auto !important;
            }

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

            html.sidebar-hidden #sidebar-open-button {
                display: flex !important;
                pointer-events: auto;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .sidebar-shell, body.sidebar-layout { transition: none; }
        }

        @media (max-width: 1023px) {
            #sidebar { width: min(280px, calc(100vw - 2rem)); }
        }

        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #4B5563; }
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

<body class="flex flex-col lg:flex-row {{ $canToggleDesktopSidebar ? 'sidebar-layout' : '' }} bg-[#FAFAFA] h-screen w-screen text-gray-800 antialiased overflow-hidden relative selection:bg-[#CC9863] selection:text-white">

    <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

    @if ($canToggleDesktopSidebar)
        <button id="sidebar-open-button" type="button" onclick="toggleDesktopSidebar()"
            class="fixed left-5 top-5 z-[60] h-11 w-11 items-center justify-center rounded-xl bg-white text-gray-800 shadow-md border border-gray-100 transition hover:bg-gray-50 hover:text-[#CC9863] focus:outline-none hidden lg:hidden group"
            aria-label="Tampilkan menu samping" title="Tampilkan Menu">
            <svg class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    @endif

    <aside id="sidebar"
        class="sidebar-shell fixed inset-y-0 left-0 z-50 w-[270px] bg-[#141518] text-gray-400 flex flex-col h-full transform -translate-x-full lg:relative lg:translate-x-0 shrink-0 shadow-2xl lg:shadow-none"
        aria-label="Menu utama">

        <div class="shrink-0 relative flex flex-col items-center justify-center pt-6 lg:pt-8 mb-6 pb-6 border-b border-white/10 px-4">
            @if ($canToggleDesktopSidebar)
                <button id="desktop-sidebar-toggle" type="button" onclick="toggleDesktopSidebar()"
                    class="absolute right-3 top-4 hidden lg:flex h-8 w-8 items-center justify-center rounded-lg text-gray-500 hover:bg-white/10 hover:text-white transition focus:outline-none"
                    title="Sembunyikan Menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            @endif

            <button id="mobile-sidebar-close" type="button" onclick="toggleSidebar()"
                class="absolute right-3 top-4 lg:hidden h-8 w-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-white/10 hover:text-white transition focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Logo -->
            <div class="flex flex-col items-center gap-3 mt-2">
                <div class="w-14 h-14 bg-gradient-to-b from-white/10 to-white/5 rounded-2xl flex items-center justify-center p-2.5 shadow-inner border border-white/10">
                    <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="ZeePerfume" class="w-full h-full object-contain">
                </div>
                <span class="text-xl font-black text-white tracking-wide">
                    Zee<span class="text-[#CC9863]">Perfume</span>
                </span>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scroll px-4 pb-4 space-y-1.5">

            <!-- ================= MENU OWNER ================= -->
            @if ($currentRole === 'owner')
                <p class="px-3 text-[9px] font-black tracking-[0.2em] text-gray-500 uppercase mb-2 mt-2">Tinjauan</p>
                <a href="{{ route('owner.dashboard') }}"
                    class="{{ request()->routeIs('owner.dashboard') ? 'bg-[#CC9863] text-white shadow-md shadow-[#CC9863]/20' : 'hover:bg-white/5 text-gray-300' }} flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-[13px] font-semibold">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('owner.finance.index') }}"
                    class="{{ request()->routeIs('owner.finance.*') ? 'bg-[#CC9863] text-white shadow-md shadow-[#CC9863]/20' : 'hover:bg-white/5 text-gray-300' }} flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-[13px] font-semibold mb-2">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Analytics Finance
                </a>

                @php $isTrxOwner = request()->routeIs('owner.transaction.*', 'owner.income.*', 'owner.expense.*'); @endphp
                <details class="group" {{ $isTrxOwner ? 'open' : '' }}>
                    <summary class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer transition-colors text-[13px] font-semibold {{ $isTrxOwner ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-gray-300' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Transaksi & Keuangan</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="mt-1 space-y-1 pl-11 pr-2 pb-2 pt-1">
                        <a href="{{ route('owner.transaction.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('owner.transaction.*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Riwayat Transaksi</a>
                        <a href="{{ route('owner.income.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('owner.income.*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Laporan Pendapatan</a>
                        <a href="{{ route('owner.expense.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('owner.expense.*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola Pengeluaran</a>
                    </div>
                </details>

                @php $isInvOwner = request()->routeIs('owner.stock.*'); @endphp
                <details class="group" {{ $isInvOwner ? 'open' : '' }}>
                    <summary class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer transition-colors text-[13px] font-semibold {{ $isInvOwner ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-gray-300' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span>Inventori Stok</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="mt-1 space-y-1 pl-11 pr-2 pb-2 pt-1">
                        <a href="{{ route('owner.stock.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('owner.stock.index', 'owner.stock.create', 'owner.stock.edit') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola Stok</a>
                        <a href="{{ route('owner.stock.history') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('owner.stock.history') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Riwayat Stok</a>
                    </div>
                </details>

                @php $isMasterOwner = request()->routeIs('owner.employee.*', 'owner.outlet.*', 'owner.member.*'); @endphp
                <details class="group" {{ $isMasterOwner ? 'open' : '' }}>
                    <summary class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer transition-colors text-[13px] font-semibold {{ $isMasterOwner ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-gray-300' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            <span>Data Master</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="mt-1 space-y-1 pl-11 pr-2 pb-2 pt-1">
                        <a href="{{ route('owner.employee.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('owner.employee.*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola Pegawai</a>
                        <a href="{{ route('owner.outlet.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('owner.outlet.*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola Outlet</a>
                        <a href="{{ route('owner.member.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('owner.member.*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola Member</a>
                    </div>
                </details>
            @endif


            <!-- ================= MENU ADMIN ================= -->
            @if ($currentRole === 'admin')
                <p class="px-3 text-[9px] font-black tracking-[0.2em] text-gray-500 uppercase mb-2 mt-2">Tinjauan</p>
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'bg-[#CC9863] text-white shadow-md shadow-[#CC9863]/20' : 'hover:bg-white/5 text-gray-300' }} flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-[13px] font-semibold mb-2">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                @php $isTrxAdmin = request()->routeIs('admin.transaction.*', 'admin.expense.*'); @endphp
                <details class="group" {{ $isTrxAdmin ? 'open' : '' }}>
                    <summary class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer transition-colors text-[13px] font-semibold {{ $isTrxAdmin ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-gray-300' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Transaksi & Keuangan</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="mt-1 space-y-1 pl-11 pr-2 pb-2 pt-1">
                        <a href="{{ route('admin.transaction.online') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('admin.transaction.online') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Pesanan Online</a>
                        <a href="{{ route('admin.transaction.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('admin.transaction.index', 'admin.transaction.show', 'admin.transaction.edit') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Riwayat Transaksi</a>
                        <a href="{{ route('admin.expense.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('admin.expense.*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola Pengeluaran</a>
                    </div>
                </details>

                @php $isInvAdmin = request()->routeIs('admin.stock.*'); @endphp
                <details class="group" {{ $isInvAdmin ? 'open' : '' }}>
                    <summary class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer transition-colors text-[13px] font-semibold {{ $isInvAdmin ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-gray-300' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span>Inventori Stok</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="mt-1 space-y-1 pl-11 pr-2 pb-2 pt-1">
                        <a href="{{ route('admin.stock.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('admin.stock.index', 'admin.stock.create', 'admin.stock.edit') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola Stok</a>
                        <a href="{{ route('admin.stock.history') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('admin.stock.history') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Riwayat Stok</a>
                    </div>
                </details>

                @php $isMasterAdmin = request()->routeIs('admin.member.*'); @endphp
                <details class="group" {{ $isMasterAdmin ? 'open' : '' }}>
                    <summary class="flex items-center justify-between px-3 py-2.5 rounded-xl cursor-pointer transition-colors text-[13px] font-semibold {{ $isMasterAdmin ? 'bg-white/10 text-white' : 'hover:bg-white/5 text-gray-300' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            <span>Data Master</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="mt-1 space-y-1 pl-11 pr-2 pb-2 pt-1">
                        <a href="{{ route('admin.member.index') }}" class="block py-2 text-[12px] transition-colors {{ request()->routeIs('admin.member.*') ? 'text-[#CC9863] font-bold' : 'text-gray-500 hover:text-gray-300' }}">Kelola Member</a>
                    </div>
                </details>
            @endif


            <!-- ================= MENU KASIR ================= -->
            @if ($currentRole === 'kasir')
                <p class="px-3 text-[10px] font-extrabold tracking-[0.2em] text-gray-500 uppercase mb-2 mt-2">Menu Kasir</p>
                <a href="{{ route('kasir.pos') }}"
                    class="{{ request()->routeIs('kasir.pos') ? 'bg-[#CC9863] text-white shadow-md shadow-[#CC9863]/20' : 'text-[#CC9863] hover:bg-white/5 border border-dashed border-[#CC9863]/40' }} flex items-center gap-3 px-3 py-3 rounded-xl transition-all text-[13px] font-bold">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Buka Layar Kasir
                </a>
                <a href="{{ route('kasir.transaction.index') }}"
                    class="{{ request()->routeIs('kasir.transaction.*') ? 'bg-white/10 text-white mt-3' : 'hover:bg-white/5 text-gray-300 mt-3' }} flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all text-[13px] font-semibold">
                    <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Riwayat Transaksi
                </a>
            @endif
        </nav>

        <!-- FOOTER SIDEBAR: Profil & Logout (Sticky Bottom) -->
        <div class="shrink-0 mt-2 border-t border-white/10 px-4 py-5 bg-[#141518]">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-[13px] font-semibold {{ request()->routeIs('profile.*') ? 'bg-[#CC9863] text-white shadow-md shadow-[#CC9863]/20' : 'hover:bg-white/5 text-gray-300' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Pengaturan Akun
            </a>

            <div class="flex items-center justify-between gap-3 p-3 mt-3 bg-black/20 rounded-xl border border-white/5">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-full border border-[#CC9863] bg-[#1C1D21] flex items-center justify-center text-sm font-bold text-[#CC9863] shadow-inner shrink-0">
                        {{ auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->nama_lengkap, 0, 1)) : 'U' }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="truncate text-[13px] font-bold text-white leading-tight">
                            {{ auth()->check() ? auth()->user()->nama_lengkap : 'Guest' }}
                        </p>
                        <p class="text-[9px] uppercase font-extrabold tracking-widest text-gray-500 mt-0.5">
                            {{ $currentRole }}
                        </p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit"
                        class="flex w-9 h-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-red-500/10 hover:text-red-400 focus:outline-none"
                        title="Keluar dari Akun">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    <div class="min-w-0 flex-1 flex flex-col h-full overflow-hidden w-full relative">

        <header class="lg:hidden flex items-center justify-between p-4 bg-[#141518] text-white shrink-0 shadow-sm relative z-20">
            <div class="flex items-center gap-3">
                <button id="mobile-sidebar-open" type="button" onclick="toggleSidebar()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-gray-300 transition hover:text-[#CC9863] hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-[#CC9863]"
                    aria-label="Buka menu samping">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div class="flex items-center gap-2 font-bold text-lg tracking-wide">
                    <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="Logo" class="w-6 h-6 object-contain">
                    <span class="text-white">Zee<span class="text-[#CC9863]">Perfume</span></span>
                </div>
            </div>
        </header>

        @yield('content')

    </div>

    <script>
        const sidebarStorageKey = 'zeeperfume.sidebar.{{ $currentRole }}';

        function isDesktopSidebarViewport() { return window.innerWidth >= 1024; }

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
            try { window.localStorage.setItem(sidebarStorageKey, hidden ? 'hidden' : 'visible'); } catch (error) {}

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
            if (event.key === 'Escape' && !isDesktopSidebarViewport() && sidebar && !sidebar.classList.contains('-translate-x-full')) {
                window.toggleSidebar();
            }
        });

        syncSidebarState();
    </script>
</body>
</html>
