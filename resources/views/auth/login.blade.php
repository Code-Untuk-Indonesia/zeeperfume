<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Masuk - ZEEPERFUME POS</title>

    <!-- Google Fonts: DM Serif Display & Manrope disamakan dengan Landing Page -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --font-heading: 'DM Serif Display', serif;
            --font-body: 'Manrope', sans-serif;
        }

        body {
            font-family: var(--font-body);
        }

        .font-luxury {
            font-family: var(--font-heading);
        }
    </style>
</head>

<body class="min-h-dvh bg-[#FFFAF5] text-[#39291F] antialiased selection:bg-[#A66C2D] selection:text-white">
    <!--
    THESIS: Login adalah gerbang kerja outlet, diselaraskan dengan kemewahan visual Zeeperfume.
    OWN-WORLD: Cokelat gelap eksklusif (Espresso), emas perunggu, dan krem hangat.
    STORY: Pengguna mengenali ZEEPERFUME secara utuh, memahami akun dikelola internal.
    -->
    <div class="grid min-h-dvh lg:grid-cols-[minmax(0,0.92fr)_minmax(32rem,1.08fr)]">

        <!-- PANEL KIRI (Desktop Khusus) dengan Background Sunset Gelap -->
        <section class="relative hidden overflow-hidden bg-[#241A13] px-10 py-10 text-white lg:flex lg:flex-col lg:justify-between xl:px-16 xl:py-14 bg-cover bg-center"
                 aria-label="Tentang ZeePerfume POS"
                 style="background-image: url('{{ asset('asset/Golden Coastal Sunset Lookout.png') }}');">

            <!-- Overlay gelap untuk meredam gambar sunset agar teks tetap terbaca -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#39291F]/95 via-[#39291F]/90 to-[#A66C2D]/60 pointer-events-none"></div>

            <!-- Aksen Garis Emas Atas -->
            <div class="absolute left-0 top-0 h-1.5 w-40 bg-[#A66C2D]"></div>

            <div class="relative z-10 flex items-center gap-3">
                <span class="font-luxury text-2xl tracking-widest text-[#FFF8EB] uppercase">ZEEPERFUME</span>
                <span class="border-l border-white/20 pl-3 text-xs font-semibold text-[#D9C19B] uppercase tracking-widest">Point of Sale</span>
            </div>

            <div class="relative z-10 max-w-xl py-16">
                <h1 class="font-luxury max-w-[10ch] text-[clamp(3.5rem,5vw,5rem)] leading-[1.05] tracking-tight text-white drop-shadow-md">
                    Kerja outlet, <br><span class="text-[#D9C19B] italic">lebih tertata.</span>
                </h1>
                <p class="mt-7 max-w-lg text-[15px] font-medium leading-relaxed text-[#FFF8EB]/80">
                    Transaksi, stok, dan laporan ZEEPERFUME berada dalam satu ruang kerja eksklusif untuk tim internal.
                </p>
            </div>

            <div class="relative z-10">
                <div class="grid grid-cols-3 border-y border-[#D9C19B]/20 py-5 text-sm font-semibold text-white tracking-wide uppercase text-[11px]">
                    <span>Transaksi</span>
                    <span class="border-l border-[#D9C19B]/20 pl-5">Stok</span>
                    <span class="border-l border-[#D9C19B]/20 pl-5">Laporan</span>
                </div>
                <p class="mt-5 text-xs text-[#D9C19B]/70 font-medium">Akses internal untuk tim ZEEPERFUME.</p>
            </div>
        </section>

        <!-- PANEL KANAN (Form Login) -->
        <main class="relative flex min-h-dvh items-center px-5 py-8 sm:px-10 lg:px-16 xl:px-24 overflow-hidden">

            <!-- Dekorasi Bias Cahaya Lembut (Warm Glow) -->
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-[#A66C2D] opacity-[0.08] blur-[80px] rounded-full pointer-events-none -z-10"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-[#D8B985] opacity-[0.12] blur-[80px] rounded-full pointer-events-none -z-10"></div>

            <div class="mx-auto w-full max-w-[29rem] relative z-10">
                <!-- Header Mobile -->
                <div class="mb-12 flex items-center justify-between lg:hidden border-b border-[#D9C19B]/30 pb-4">
                    <span class="font-luxury text-2xl tracking-widest text-[#39291F] uppercase">ZEEPERFUME</span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#A66C2D] bg-[#FFF8EB] px-3 py-1.5 rounded-full border border-[#D9C19B]/40">POS System</span>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="font-luxury text-4xl sm:text-[2.5rem] tracking-tight text-[#39291F]">Masuk ke ruang kerja</h2>
                    <p class="mt-3 max-w-md mx-auto lg:mx-0 text-sm font-medium leading-relaxed text-[#554333]">
                        Gunakan username dan password yang diberikan oleh pengelola akun.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-xl bg-[#FFF8EB] border border-[#D9C19B]/50 px-4 py-3 text-sm font-medium text-[#845323] flex items-center gap-3 shadow-sm" role="status">
                        <svg class="w-5 h-5 text-[#A66C2D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-[#FDF5F5] border border-[#E5B5B5] px-4 py-3 text-sm font-medium leading-6 text-[#9B2C2C] flex items-start gap-3 shadow-sm" role="alert">
                        <svg class="w-5 h-5 mt-0.5 text-[#9B2C2C] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form id="login-form" method="POST" action="{{ route('login.store') }}" class="space-y-6 bg-white p-6 sm:p-8 rounded-[2rem] shadow-xl shadow-[#39291F]/5 border border-[#D9C19B]/40">
                    @csrf

                    <div>
                        <label for="username" class="mb-2.5 block text-[11px] font-bold uppercase tracking-widest text-[#39291F]">Username</label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username') }}"
                            required
                            maxlength="100"
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan username"
                            class="min-h-13 md:h-14 w-full rounded-xl border border-[#D9C19B]/60 bg-[#FFFAF5] px-4 sm:px-5 text-sm font-medium text-[#39291F] placeholder:text-[#554333]/50 transition-all focus:border-[#A66C2D] focus:ring-1 focus:ring-[#A66C2D] focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-2.5 block text-[11px] font-bold uppercase tracking-widest text-[#39291F]">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                maxlength="255"
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="min-h-13 md:h-14 w-full rounded-xl border border-[#D9C19B]/60 bg-[#FFFAF5] py-3 pr-14 pl-4 sm:pl-5 text-sm font-medium text-[#39291F] placeholder:text-[#554333]/50 transition-all focus:border-[#A66C2D] focus:ring-1 focus:ring-[#A66C2D] focus:outline-none"
                            >
                            <button
                                id="password-toggle"
                                type="button"
                                class="absolute inset-y-0 right-1.5 my-auto h-11 w-11 flex items-center justify-center rounded-lg text-[#8A5B2D] transition-colors hover:bg-[#FFF8EB] hover:text-[#A66C2D] focus-visible:ring-2 focus-visible:ring-[#A66C2D]/50 focus-visible:outline-none"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                            >
                                <svg id="eye-open" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.46 12C3.73 7.94 7.52 5 12 5c4.48 0 8.27 2.94 9.54 7-1.27 4.06-5.06 7-9.54 7-4.48 0-8.27-2.94-9.54-7z"></path>
                                </svg>
                                <svg id="eye-closed" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M10.58 10.59a2 2 0 002.83 2.83M9.88 4.24A9.8 9.8 0 0112 4c4.48 0 8.27 2.94 9.54 7a10.15 10.15 0 01-2.03 3.59M6.61 6.61A10.35 10.35 0 002.46 12c1.27 4.06 5.06 7 9.54 7a9.8 9.8 0 004.12-.9"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <label class="flex min-h-11 cursor-pointer items-center gap-3 text-[13px] font-semibold text-[#554333]">
                        <input
                            name="remember"
                            type="checkbox"
                            value="1"
                            @checked(old('remember'))
                            class="h-5 w-5 rounded border-[#D9C19B] text-[#A66C2D] focus:ring-[#A66C2D] focus:ring-offset-0 bg-[#FFFAF5] transition-colors cursor-pointer"
                        >
                        Ingat sesi saya di perangkat ini
                    </label>

                    <button
                        id="login-button"
                        type="submit"
                        class="group flex min-h-13 md:h-14 w-full items-center justify-center gap-3 rounded-xl bg-[#A66C2D] px-5 py-3.5 text-[12px] font-bold uppercase tracking-widest text-white shadow-lg shadow-[#68431F]/20 transition-all hover:bg-[#845323] focus-visible:ring-2 focus-visible:ring-[#A66C2D]/50 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-wait disabled:bg-[#D9C19B] transform active:scale-[0.98]"
                    >
                        <span id="login-button-text">Masuk ke sistem</span>
                        <svg id="login-spinner" class="hidden w-4 h-4 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>

                <div class="mt-8 text-center border-t border-[#D9C19B]/40 pt-6">
                    <p class="text-[12px] font-medium leading-relaxed text-[#554333]">
                        Belum memiliki akun? <br class="sm:hidden">Hubungi owner atau admin pengelola.
                    </p>
                </div>
            </div>
        </main>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');
        const loginForm = document.getElementById('login-form');
        const loginButton = document.getElementById('login-button');
        const loginButtonText = document.getElementById('login-button-text');
        const loginSpinner = document.getElementById('login-spinner');

        passwordToggle.addEventListener('click', () => {
            const passwordIsVisible = passwordInput.type === 'text';

            passwordInput.type = passwordIsVisible ? 'password' : 'text';
            passwordToggle.setAttribute('aria-pressed', String(!passwordIsVisible));
            passwordToggle.setAttribute('aria-label', passwordIsVisible ? 'Tampilkan password' : 'Sembunyikan password');
            eyeOpen.classList.toggle('hidden', !passwordIsVisible);
            eyeClosed.classList.toggle('hidden', passwordIsVisible);

            // Mengembalikan fokus ke input password setelah toggle diklik
            passwordInput.focus();
        });

        loginForm.addEventListener('submit', () => {
            // Mencegah double submit (meningkatkan keamanan dari spam click / brute force ringan)
            loginButton.disabled = true;
            loginButtonText.textContent = 'Memeriksa akun...';
            loginSpinner.classList.remove('hidden');
        });
    </script>
</body>

</html>
