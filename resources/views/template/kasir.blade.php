<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - @yield('title', 'Kasir')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
        }

        /* Cegah scroll body agar POS full app-like */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Sembunyikan scrollbar untuk kategori tapi tetap bisa di-scroll */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-[#F3F4F6] text-gray-800 antialiased flex flex-col h-screen w-full">

    <!-- ================= TOP NAVBAR KASIR ================= -->
    <header class="bg-[#1C1D21] text-white h-16 flex items-center justify-between px-4 lg:px-6 shrink-0 shadow-md z-30 relative">

        <!-- Logo & Waktu -->
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2 font-bold text-xl tracking-wide">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                ZeePerfume<span class="text-[#CC9863]">KASIR</span>
            </div>
            <!-- Jam Realtime -->
            <div class="hidden md:flex items-center gap-2 bg-gray-800/50 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ \Carbon\Carbon::now()->translatedFormat('d M Y • H:i') }} WIB
            </div>
        </div>

        <!-- Menu Tengah (Desktop) -->
        <nav class="hidden md:flex items-center gap-2 absolute left-1/2 transform -translate-x-1/2">
            <!-- Tombol Transaksi / POS -->
            <a href="{{ url('kasir/pos') }}"
                class="{{ request()->is('kasir/pos*') || request()->is('kasir/member*') ? 'bg-[#CC9863] text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800' }} px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Transaksi
            </a>

            <!-- Tombol Riwayat Shift Kasir -->
            <a href="{{ url('kasir/transaction') }}"
                class="{{ request()->is('kasir/transaction*') ? 'bg-[#CC9863] text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800' }} px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Riwayat Shift
            </a>
        </nav>

        <!-- Profile & Action Kanan -->
        <div class="flex items-center gap-4">
            <div class="hidden sm:block text-right">
                <!-- Data Dinamis User -->
                <p class="text-sm font-bold text-white leading-tight">{{ auth()->check() ? auth()->user()->nama_lengkap : 'Kasir' }}</p>
                <p class="text-[10px] font-semibold text-[#CC9863] uppercase tracking-wider">{{ auth()->check() && auth()->user()->branch ? auth()->user()->branch->nama_cabang : 'Akses Global' }}</p>
            </div>

            <!-- Inisial Profil Dinamis -->
            <div class="w-9 h-9 rounded-full border-2 border-[#CC9863] bg-gray-800 flex items-center justify-center text-sm font-bold text-white shadow-sm" aria-hidden="true">
                {{ auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->nama_lengkap, 0, 1)) : 'K' }}
            </div>

            <div class="w-px h-6 bg-gray-700 hidden md:block mx-1"></div>

            <!-- Tombol Keluar / Tutup Shift -->
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit" class="flex items-center justify-center p-2 rounded-xl text-gray-400 transition hover:bg-gray-800 hover:text-red-400 focus-visible:ring-2 focus-visible:ring-[#CC9863] focus-visible:outline-none" title="Tutup Kasir / Logout" aria-label="Keluar dari sistem">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    <!-- ================= MOBILE MENU BOTTOM ================= -->
    <div class="md:hidden bg-white border-t border-gray-200 flex justify-around items-center p-2 pb-safe fixed bottom-0 w-full z-50 shadow-[0_-5px_15px_rgba(0,0,0,0.05)]">

        <!-- Tab POS Mobile -->
        <a href="{{ url('kasir/pos') }}" class="flex-1 flex flex-col items-center justify-center gap-1 py-1 {{ request()->is('kasir/pos*') || request()->is('kasir/member*') ? 'text-[#CC9863]' : 'text-gray-400 hover:text-gray-700' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="text-[10px] font-bold">POS</span>
        </a>

        <!-- Tab Riwayat Mobile -->
        <a href="{{ url('kasir/transaction') }}" class="flex-1 flex flex-col items-center justify-center gap-1 py-1 {{ request()->is('kasir/transaction*') ? 'text-[#CC9863]' : 'text-gray-400 hover:text-gray-700' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="text-[10px] font-bold">Riwayat</span>
        </a>

        <!-- Tab Logout Mobile -->
        <form method="POST" action="{{ route('logout') }}" class="flex-1 m-0 p-0">
            @csrf
            <button type="submit" class="w-full flex flex-col items-center justify-center gap-1 py-1 text-gray-400 hover:text-red-500 transition" aria-label="Keluar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="text-[10px] font-bold">Keluar</span>
            </button>
        </form>
    </div>

    <!-- ================= DYNAMIC CONTENT ================= -->
    <main class="flex-1 overflow-hidden relative pb-16 md:pb-0">
        @yield('content')
    </main>

</body>

</html>
