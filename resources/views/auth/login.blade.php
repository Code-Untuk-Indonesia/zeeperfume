<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Masuk - ZeePerfume POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-dvh bg-[#F6F5F2] font-sans text-[#1C1D21] antialiased">
    <!--
    THESIS: Login adalah gerbang kerja outlet, bukan halaman promosi atau kartu generik di tengah layar.
    OWN-WORLD: Arang, putih hangat, dan amber dari dashboard dengan bidang tegas serta sudut 16px.
    STORY: Pengguna mengenali ZeePerfume, memahami akun dikelola internal, lalu masuk dengan username dan password.
    FIRST VIEWPORT: Konteks operasional berada di panel arang kiri dan form menjadi fokus tunggal pada bidang terang kanan.
    FORM: Komposisi split-screen yang menyatu dengan shell dashboard; seed key ZEE-AUTH-01.
    FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
    -->
    <div class="grid min-h-dvh lg:grid-cols-[minmax(0,0.92fr)_minmax(32rem,1.08fr)]">
        <section class="relative hidden overflow-hidden bg-[#1C1D21] px-10 py-10 text-white lg:flex lg:flex-col lg:justify-between xl:px-16 xl:py-14" aria-label="Tentang ZeePerfume POS">
            <div class="absolute left-0 top-0 h-1.5 w-40 bg-[#CC9863]"></div>

            <div class="flex items-center gap-3">
                <span class="text-2xl font-extrabold tracking-[-0.03em] text-[#D9AA78]">ZeePerfume</span>
                <span class="border-l border-white/20 pl-3 text-xs font-semibold text-[#D7D7D9]">Point of Sale</span>
            </div>

            <div class="max-w-xl py-16">
                <h1 class="max-w-[10ch] text-[clamp(3rem,5vw,5rem)] font-extrabold leading-[1.02] tracking-[-0.04em]">
                    Kerja outlet, <span class="text-[#D9AA78]">lebih tertata.</span>
                </h1>
                <p class="mt-7 max-w-lg text-base leading-7 text-[#D7D7D9]">
                    Transaksi, stok, dan laporan Zee Perfume berada dalam satu ruang kerja untuk tim outlet.
                </p>
            </div>

            <div>
                <div class="grid grid-cols-3 border-y border-white/15 py-5 text-sm font-semibold text-[#EEEEEF]">
                    <span>Transaksi</span>
                    <span class="border-l border-white/15 pl-5">Stok</span>
                    <span class="border-l border-white/15 pl-5">Laporan</span>
                </div>
                <p class="mt-5 text-xs text-[#B9B9BD]">Akses internal untuk tim Zee Perfume.</p>
            </div>
        </section>

        <main class="flex min-h-dvh items-center px-5 py-8 sm:px-10 lg:px-16 xl:px-24">
            <div class="mx-auto w-full max-w-[29rem]">
                <div class="mb-12 flex items-center justify-between lg:hidden">
                    <span class="text-xl font-extrabold tracking-[-0.03em] text-[#8A5B2E]">ZeePerfume</span>
                    <span class="text-xs font-semibold text-[#5F6065]">Point of Sale</span>
                </div>

                <div class="mb-9">
                    <h2 class="text-3xl font-extrabold tracking-[-0.035em] text-[#1C1D21] sm:text-4xl">Masuk ke ruang kerja</h2>
                    <p class="mt-3 max-w-md text-sm leading-6 text-[#5F6065]">
                        Gunakan username dan password yang diberikan oleh pengelola akun.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-xl bg-[#E8F3EC] px-4 py-3 text-sm font-medium text-[#245B39]" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-xl bg-[#FBEAEA] px-4 py-3 text-sm font-medium leading-6 text-[#8A2525]" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form id="login-form" method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="username" class="mb-2 block text-sm font-bold text-[#2D2E32]">Username</label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Masukkan username"
                            class="min-h-13 w-full rounded-2xl border border-[#8B8D91] bg-white px-4 py-3 text-base text-[#1C1D21] placeholder:text-[#686A70] transition focus:border-[#8A5B2E] focus:ring-3 focus:ring-[#CC9863]/30 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-bold text-[#2D2E32]">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="min-h-13 w-full rounded-2xl border border-[#8B8D91] bg-white py-3 pr-14 pl-4 text-base text-[#1C1D21] placeholder:text-[#686A70] transition focus:border-[#8A5B2E] focus:ring-3 focus:ring-[#CC9863]/30 focus:outline-none"
                            >
                            <button
                                id="password-toggle"
                                type="button"
                                class="absolute inset-y-0 right-1 flex min-h-11 min-w-11 items-center justify-center rounded-xl text-[#55575C] transition hover:bg-[#F0EEE9] hover:text-[#1C1D21] focus-visible:ring-3 focus-visible:ring-[#8A5B2E]/35 focus-visible:outline-none"
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

                    <label class="flex min-h-11 cursor-pointer items-center gap-3 text-sm font-medium text-[#45464B]">
                        <input
                            name="remember"
                            type="checkbox"
                            value="1"
                            @checked(old('remember'))
                            class="h-5 w-5 rounded border-[#6C6E73] text-[#8A5B2E] focus:ring-[#8A5B2E]"
                        >
                        Ingat sesi saya di perangkat ini
                    </label>

                    <button
                        id="login-button"
                        type="submit"
                        class="flex min-h-13 w-full items-center justify-center rounded-2xl bg-[#1C1D21] px-5 py-3.5 text-sm font-bold text-white shadow-[0_10px_24px_rgba(28,29,33,0.18)] transition hover:bg-[#323338] focus-visible:ring-3 focus-visible:ring-[#8A5B2E]/45 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-wait disabled:bg-[#595A5F]"
                    >
                        <span id="login-button-text">Masuk ke sistem</span>
                    </button>
                </form>

                <p class="mt-8 border-t border-[#D8D6D0] pt-5 text-xs leading-5 text-[#5F6065]">
                    Belum memiliki akun? Hubungi owner atau admin yang mengelola pengguna.
                </p>
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

        passwordToggle.addEventListener('click', () => {
            const passwordIsVisible = passwordInput.type === 'text';

            passwordInput.type = passwordIsVisible ? 'password' : 'text';
            passwordToggle.setAttribute('aria-pressed', String(!passwordIsVisible));
            passwordToggle.setAttribute('aria-label', passwordIsVisible ? 'Tampilkan password' : 'Sembunyikan password');
            eyeOpen.classList.toggle('hidden', !passwordIsVisible);
            eyeClosed.classList.toggle('hidden', passwordIsVisible);
            passwordInput.focus();
        });

        loginForm.addEventListener('submit', () => {
            loginButton.disabled = true;
            loginButtonText.textContent = 'Memeriksa akun...';
        });
    </script>
</body>

</html>
