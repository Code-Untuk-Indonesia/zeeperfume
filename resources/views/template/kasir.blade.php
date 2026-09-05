<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - @yield('title', 'Kasir')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F3F4F6;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Safe area & viewport */
        .pb-safe {
            padding-bottom: max(0.5rem, env(safe-area-inset-bottom));
        }

        .pt-safe {
            padding-top: env(safe-area-inset-top);
        }

        .app-height {
            min-height: 100vh;
            min-height: 100dvh;
        }
    </style>
</head>

<body class="app-height bg-[#F3F4F6] text-gray-800 antialiased overflow-x-hidden">
    <div class="app-height flex flex-col">

        <!-- ========================================================= -->
        <!-- TOP NAVBAR -->
        <!-- ========================================================= -->
        <header class="bg-[#1C1D21] text-white shrink-0 z-40 shadow-md pt-safe">
            <div class="min-h-[64px] px-3 sm:px-4 lg:px-6 flex items-center justify-between gap-3 relative">

                <!-- LEFT: LOGO + TIME -->
                <div class="flex items-center gap-3 lg:gap-6 min-w-0">
                    <!-- Logo -->
                    <a href="{{ url('kasir/pos') }}" class="flex items-center gap-2 min-w-0">
                        <div class="w-9 h-9 shrink-0 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="hidden xs:block min-w-0">
                            <div
                                class="flex items-center whitespace-nowrap text-base sm:text-lg lg:text-xl font-bold tracking-tight">
                                ZeePerfume <span class="text-[#CC9863] ml-1">KASIR</span>
                            </div>
                        </div>
                    </a>

                    <!-- Clock -->
                    <div
                        class="hidden xl:flex items-center gap-2 bg-gray-800/70 border border-white/5 px-3 py-2 rounded-xl text-xs font-medium text-gray-300 whitespace-nowrap">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span id="realtime-clock">
                            <!-- Waktu awal dimuat dari server, lalu ditimpa JS -->
                            {{ \Carbon\Carbon::now()->translatedFormat('d M Y • H:i') }} WIB
                        </span>
                    </div>
                </div>

                <!-- CENTER MENU (Desktop) -->
                <nav class="hidden md:flex items-center gap-1 lg:gap-2 absolute left-1/2 -translate-x-1/2">
                    <!-- POS -->
                    <a href="{{ url('kasir/pos') }}"
                        class="{{ request()->is('kasir/pos*') || request()->is('kasir/member*') ? 'bg-[#CC9863] text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} px-3 lg:px-4 py-2 rounded-xl text-xs lg:text-sm font-bold flex items-center gap-2 whitespace-nowrap transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Transaksi</span>
                    </a>

                    <!-- TRANSACTION HISTORY -->
                    <a href="{{ url('kasir/transaction') }}"
                        class="{{ request()->is('kasir/transaction*') ? 'bg-[#CC9863] text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} px-3 lg:px-4 py-2 rounded-xl text-xs lg:text-sm font-semibold flex items-center gap-2 whitespace-nowrap transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Riwayat Shift</span>
                    </a>
                </nav>

                <!-- RIGHT PROFILE -->
                <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 shrink-0">
                    <!-- User Detail -->
                    <div class="hidden lg:block text-right max-w-[180px]">
                        <p class="text-sm font-bold text-white leading-tight truncate">
                            {{ auth()->check() ? auth()->user()->nama_lengkap : 'Kasir' }}
                        </p>
                        <p class="mt-1 text-[10px] font-semibold text-[#CC9863] uppercase tracking-wider truncate">
                            {{ auth()->check() && auth()->user()->branch ? auth()->user()->branch->nama_cabang : 'Akses Global' }}
                        </p>
                    </div>

                    <!-- Avatar -->
                    <div
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-[#CC9863] bg-gray-800 flex items-center justify-center text-sm font-bold text-white shadow-sm shrink-0">
                        {{ auth()->check() ? mb_strtoupper(mb_substr(auth()->user()->nama_lengkap, 0, 1)) : 'K' }}
                    </div>

                    <div class="hidden lg:block w-px h-7 bg-gray-700"></div>

                    <!-- Logout Desktop -->
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:block m-0 p-0">
                        @csrf
                        <button type="submit"
                            class="flex items-center justify-center w-10 h-10 rounded-xl text-gray-400 transition hover:bg-gray-800 hover:text-red-400 focus-visible:ring-2 focus-visible:ring-[#CC9863] focus-visible:outline-none"
                            title="Tutup Kasir / Logout" aria-label="Keluar dari sistem">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- ========================================================= -->
        <!-- MAIN CONTENT -->
        <!-- ========================================================= -->
        <main class="flex-1 min-h-0 overflow-x-hidden overflow-y-auto pb-[74px] md:pb-0">
            @yield('content')
        </main>

        <!-- ========================================================= -->
        <!-- MOBILE BOTTOM NAVIGATION -->
        <!-- ========================================================= -->
        <nav
            class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-t border-gray-200 shadow-[0_-8px_30px_rgba(0,0,0,0.06)] pb-safe">
            <div class="max-w-lg mx-auto px-2 py-1.5 flex items-center">
                <!-- POS -->
                <a href="{{ url('kasir/pos') }}"
                    class="flex-1 min-w-0 flex flex-col items-center justify-center gap-1 py-2 rounded-xl transition {{ request()->is('kasir/pos*') || request()->is('kasir/member*') ? 'text-[#CC9863] bg-[#CC9863]/10' : 'text-gray-400 hover:text-gray-700' }}">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-[10px] sm:text-xs font-bold">Transaksi</span>
                </a>

                <!-- HISTORY -->
                <a href="{{ url('kasir/transaction') }}"
                    class="flex-1 min-w-0 flex flex-col items-center justify-center gap-1 py-2 rounded-xl transition {{ request()->is('kasir/transaction*') ? 'text-[#CC9863] bg-[#CC9863]/10' : 'text-gray-400 hover:text-gray-700' }}">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-[10px] sm:text-xs font-bold">Riwayat</span>
                </a>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}" class="flex-1 min-w-0 m-0 p-0">
                    @csrf
                    <button type="submit"
                        class="w-full flex flex-col items-center justify-center gap-1 py-2 rounded-xl text-gray-400 transition hover:bg-red-50 hover:text-red-500"
                        aria-label="Keluar">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-[10px] sm:text-xs font-bold">Keluar</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>

    <!-- Script Waktu Real-time -->
    <script>
        function updateClock() {
            const clockElement = document.getElementById('realtime-clock');
            if (!clockElement) return;

            const now = new Date();

            const day = String(now.getDate()).padStart(2, '0');
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            const month = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            clockElement.textContent = `${day} ${month} ${year} • ${hours}:${minutes} WIB`;
        }

        // Perbarui jam setiap detik
        setInterval(updateClock, 1000);
        updateClock(); // Panggil sekali di awal
    </script>
</body>

</html>
