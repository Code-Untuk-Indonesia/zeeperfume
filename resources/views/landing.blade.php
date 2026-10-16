<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description"
        content="ZEEPERFUME: pilihan parfum pria, wanita, retail, grosir, dan laundry. Temukan aroma favorit dan kunjungi cabang Pontianak atau Jungkat.">
    <title>ZEEPERFUME | Your Scent, Your Story</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('asset/zeeperfume_logo.svg') }}">
    <link rel="shortcut icon" href="{{ asset('asset/zeeperfume_logo.svg') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Mengkonfigurasi Palet Warna Eksklusif ZEEPERFUME -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'z-white': '#FFFDFF',
                        'z-soft': '#B3D6F2',
                        'z-muted': '#526B82',
                        'z-blue': '#3B75FD',
                        'z-navy': '#020331',
                        'z-black': '#000004',
                        'z-surface': '#F4F8FC',
                        'z-tint': '#E8F2FB',
                        'z-rating': '#B77905',
                    }
                }
            }
        }
    </script>

    <!-- Ikon yang digunakan di seluruh halaman -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts: DM Serif Display & Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Manrope:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --font-heading: 'DM Serif Display', serif;
            --font-body: 'Manrope', sans-serif;
            --font-logo: 'DM Serif Display', serif;

            /* ZEEPERFUME - Warm Luxury Coastal */
            --color-cream: #FFF6E4;
            --color-champagne: #F6E3BF;
            --color-espresso: #39291F;
            --color-brown: #554333;
            --color-gold: #A66C2D;
            --color-gold-hover: #845323;
            --color-coastal: #34515B;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-body);
            font-weight: 400;
            background-color: #FFFCF7;
            color: var(--color-espresso);
        }

        /* Typography */
        h1,
        h2,
        h3,
        .font-luxury {
            font-family: var(--font-heading);
        }

        .hero-title {
            font-family: var(--font-heading);
            font-size: clamp(2.7rem, 6vw, 5rem);
            font-weight: 400;
            line-height: 1.04;
            letter-spacing: -0.035em;
        }

        .section-title {
            font-family: var(--font-heading);
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 400;
            line-height: 1.08;
            letter-spacing: -0.02em;
        }

        .brand-logo {
            font-family: var(--font-logo);
            font-size: 2rem;
            font-weight: 400;
            line-height: 1;
        }

        nav,
        .nav-link {
            font-family: var(--font-body);
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        p,
        .body-text {
            font-family: var(--font-body);
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.8;
        }

        .category-label,
        .eyebrow {
            font-family: var(--font-body);
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Hero backgrounds */
        .hero-mobile,
        .hero-desktop {
            background-repeat: no-repeat;
            background-size: cover;
        }

        .hero-mobile {
            background-position: 53% center;
        }

        .hero-desktop {
            background-position: center center;
        }

        /* Hero animation */
        .hero-fade {
            opacity: 0;
            transform: translateY(18px);
            animation: heroFade 900ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }

        @keyframes heroFade {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile adjustments */
        @media (max-width: 767px) {
            .hero-mobile {
                background-position: center center;
            }
        }

        /* Tablet adjustments */
        @media (min-width: 768px) and (max-width: 1023px) {
            .hero-desktop {
                background-position: 57% center;
            }

            .hero-desktop h1 {
                font-size: clamp(2.8rem, 5.2vw, 3.5rem);
            }
        }

        /* Accessibility: respect reduced motion */
        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto !important;
            }

            .hero-fade {
                animation: none;
                opacity: 1;
                transform: none;
            }
        }

        [x-cloak] {
            display: none !important;
        }

        :focus-visible {
            outline: 2px solid var(--color-gold);
            outline-offset: 4px;
        }

        /* Anchor offset for fixed navigation */
        [id] {
            scroll-margin-top: 100px;
        }
    </style>
</head>

<body class="text-z-black antialiased overflow-x-hidden selection:bg-z-blue selection:text-z-white">

    <!-- ================= SMART NAVBAR ================= -->
    <!-- Tambahkan state 'mobileMenuOpen: false' pada x-data -->
    <header x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
        class="fixed top-0 inset-x-0 z-50 transition-all duration-300 ease-in-out"
        :class="scrolled ? 'pt-0 px-0' : 'pt-4 sm:pt-6 px-4 sm:px-6 lg:px-8'">

        <nav class="mx-auto bg-z-white/90 backdrop-blur-xl transition-all duration-300 ease-in-out border-b border-z-soft/30 relative"
            :class="scrolled ? 'max-w-full rounded-none shadow-md py-1' :
                'max-w-[1380px] rounded-2xl shadow-lg shadow-z-navy/5 border border-z-soft/50'">

            <!-- FLEX CONTAINER UTAMA -->
            <div class="h-[60px] md:h-[64px] px-5 md:px-8 flex items-center justify-between">

                <!-- LOGO -->
                <a href="#home" class="flex items-center gap-3 shrink-0">
                    <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="ZEEPERFUME Logo"
                        class="h-7 md:h-8 w-auto object-contain">
                    <span
                        class="text-[12px] md:text-[13px] font-extrabold uppercase tracking-[0.15em] text-z-navy mt-0.5">
                        ZEEPERFUME
                    </span>
                </a>

                <!-- DESKTOP NAV -->
                <div class="hidden lg:flex items-center gap-8">
                    <a href="#home"
                        class="text-[10px] font-bold uppercase tracking-[0.2em] text-z-navy hover:text-z-blue transition-colors">Beranda</a>
                    <a href="#collections"
                        class="text-[10px] font-bold uppercase tracking-[0.2em] text-z-muted hover:text-z-blue transition-colors">Produk</a>
                    <a href="#about"
                        class="text-[10px] font-bold uppercase tracking-[0.2em] text-z-muted hover:text-z-blue transition-colors">Tentang
                        Kami</a>
                    <a href="#contact"
                        class="text-[10px] font-bold uppercase tracking-[0.2em] text-z-muted hover:text-z-blue transition-colors">Lokasi</a>
                </div>

                <!-- BUTTON TOGGLE MOBILE (Alpine.js @click) -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" aria-label="Toggle menu"
                    class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl text-z-navy border border-z-soft/50 hover:bg-z-soft/30 focus:outline-none transition-colors">

                    <!-- Icon Hamburger (Tampil saat menu tertutup) -->
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <!-- Icon Close 'X' (Tampil saat menu terbuka) -->
                    <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- MOBILE NAV CONTENT (Dropdown List) -->
            <!-- Dikeluarkan dari flex div utama agar posisinya absolut memanjang ke bawah sempurna -->
            <div x-show="mobileMenuOpen" x-transition.opacity.duration.300ms x-cloak
                @click.away="mobileMenuOpen = false"
                class="lg:hidden absolute top-full left-0 w-full bg-z-white border-t border-z-soft/30 shadow-xl rounded-b-2xl overflow-hidden">
                <div class="flex flex-col gap-2 p-4">
                    <!-- Gunakan @click="mobileMenuOpen = false" agar menu tertutup otomatis setelah link diklik -->
                    <a @click="mobileMenuOpen = false" href="#home"
                        class="px-4 py-3 rounded-xl bg-z-soft/20 text-xs font-bold text-z-blue">Beranda</a>
                    <a @click="mobileMenuOpen = false" href="#collections"
                        class="px-4 py-3 rounded-xl text-xs font-bold text-z-muted hover:bg-z-soft/20 hover:text-z-blue transition-colors">Produk</a>
                    <a @click="mobileMenuOpen = false" href="#about"
                        class="px-4 py-3 rounded-xl text-xs font-bold text-z-muted hover:bg-z-soft/20 hover:text-z-blue transition-colors">Tentang
                        Kami</a>
                    <a @click="mobileMenuOpen = false" href="#contact"
                        class="px-4 py-3 rounded-xl text-xs font-bold text-z-muted hover:bg-z-soft/20 hover:text-z-blue transition-colors">Lokasi
                        & Kontak</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- ================= HERO SECTION ================= -->
    <main id="home" class="pt-[80px] md:pt-[100px] px-3 sm:px-6 lg:px-8 pb-8 relative z-10">

        <!-- MOBILE HERO -->
        <section
            class="hero-mobile relative isolate md:hidden mx-auto w-full max-w-[480px]
               min-h-[760px] rounded-[2rem] overflow-hidden
               border border-[#D8B985]/60 shadow-xl"
            style="background-image: url('{{ asset('asset/Golden Coastal Sunset Lookout.png') }}');">

            <!-- Warm overlay -->
            <div class="absolute inset-0 z-0 pointer-events-none"
                style="background: linear-gradient(
                180deg,
                rgba(255,247,231,.96) 0%,
                rgba(255,241,211,.87) 38%,
                rgba(248,220,176,.48) 67%,
                rgba(65,46,30,.12) 100%
             );">
            </div>

            <div class="relative z-10 flex min-h-[760px] flex-col px-5 pt-9 pb-5">

                <!-- Copywriting -->
                <div class="hero-fade text-center">
                    <p
                        class="mb-3 text-[10px] font-extrabold uppercase
                          tracking-[.28em] text-[#8A5B2D]">
                        Satu Aroma, Satu Cerita
                    </p>

                    <h1
                        class="font-luxury mb-4 text-[2.65rem] leading-[1.06]
                           tracking-[-.035em] text-[#39291F]">
                        <span class="block">Aroma yang</span>
                        <span class="block italic text-[#A66C2D]">Menceritakan</span>
                        <span class="block">Karaktermu.</span>
                    </h1>

                    <p
                        class="mx-auto max-w-[340px] text-[13px] font-medium
                          leading-[1.8] text-[#554333]">
                        100+ pilihan wewangian untuk personal dan laundry.
                        Nikmati aroma yang elegan dan tahan lama dengan
                        harga yang tetap terjangkau.
                    </p>
                </div>

                <!-- CTA -->
                <div class="hero-fade delay-200 mt-6 flex flex-col gap-3">

                    <a href="#collections"
                        class="group flex min-h-[48px] w-full items-center
                          justify-center gap-3 rounded-full
                          border border-[#A66C2D] bg-[#A66C2D]
                          px-5 py-3 text-[10px] font-extrabold
                          uppercase tracking-[.16em] text-white
                          shadow-lg shadow-[#68431F]/20
                          transition duration-300
                          hover:bg-[#845323] active:scale-[.98]">

                        Temukan Aromamu

                        <svg class="h-4 w-4 transition-transform
                                group-hover:translate-x-1"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>

                    <a href="#contact-form"
                        class="flex min-h-[46px] w-full items-center
                          justify-center rounded-full
                          border border-[#A66C2D]/50
                          bg-[#FFF8EB]/75 px-5 py-3
                          text-[10px] font-extrabold uppercase
                          tracking-[.16em] text-[#493322]
                          shadow-sm backdrop-blur-md
                          transition hover:bg-[#FFF8EB]">

                        Hubungi Kami
                    </a>
                </div>

                <!-- Product image -->
                <div
                    class="hero-fade delay-300 relative mt-auto
                        flex min-h-[285px] flex-1 items-end
                        justify-center pt-1 pb-1">
                    <!-- Tambahkan pb-4 atau pb-8 di sini untuk memberi jarak dari bawah -->

                    <!-- Tambahkan mb-4 (atau mb-8, mb-10) di sini untuk mendorong botol ke atas secara presisi -->
                    <img src="{{ asset('asset/btllelabo.svg') }}" alt="ZEEPERFUME - parfum premium"
                        fetchpriority="high"
                        class="relative z-10 max-h-[330px] w-[68%]
                           max-w-[250px] object-contain mb-4
                           drop-shadow-[0_20px_24px_rgba(57,41,31,.24)]
                           transition-transform duration-500
                           hover:scale-[1.04]">
                </div>

            </div>
        </section>


        <!-- TABLET & DESKTOP HERO -->
        <section
            class="hero-desktop relative isolate mx-auto hidden
               min-h-[650px] w-full max-w-[1380px]
               overflow-hidden rounded-[2.5rem]
               border border-[#D9C19B]/50
               shadow-2xl shadow-[#39291F]/15
               md:flex lg:min-h-[710px]"
            style="background-image: url('{{ asset('asset/Golden Coastal Sunset Lookout.png') }}');">

            <!-- Overlay: strong behind text, fades into the landscape -->
            <div class="absolute inset-0 z-0 pointer-events-none"
                style="background: linear-gradient(
                90deg,
                rgba(255,246,228,.97) 0%,
                rgba(255,241,215,.91) 31%,
                rgba(255,231,193,.72) 47%,
                rgba(255,226,184,.18) 67%,
                rgba(57,41,31,.03) 100%
             );">
            </div>

            <!-- Subtle bottom tint -->
            <div class="absolute inset-0 z-0 pointer-events-none"
                style="background: linear-gradient(
                0deg,
                rgba(57,41,31,.13) 0%,
                transparent 28%
             );">
            </div>

            <div
                class="relative z-10 grid min-h-[650px] w-full
                    grid-cols-2 items-center gap-3 px-8 py-12 md:px-10 lg:min-h-[710px] lg:px-16 xl:px-20">
                <!-- LEFT: COPYWRITING -->
                <div
                    class="relative z-20 flex max-w-[590px]
                        flex-col items-start py-5 text-left">

                    <div class="hero-fade mb-5 flex items-center gap-3">
                        <span class="h-px w-9 bg-[#A66C2D]"></span>

                        <p
                            class="text-[10px] font-extrabold uppercase
                              tracking-[.3em] text-[#80552D]">
                            Satu Aroma, Satu Cerita
                        </p>
                    </div>

                    <h1
                        class="hero-fade delay-100 font-luxury
                           mb-6 text-[3.3rem] leading-[1.04]
                           tracking-[-.035em] text-[#39291F]
                           lg:text-[4.2rem] xl:text-[4.7rem]">
                        <span class="block">Aroma yang</span>
                        <span class="block italic text-[#A66C2D]">
                            Menceritakan
                        </span>
                        <span class="block">Karaktermu.</span>
                    </h1>

                    <p
                        class="hero-fade delay-200 max-w-[430px]
                          text-[13px] font-medium leading-[1.9]
                          text-[#554333] lg:text-[14px]">
                        100+ pilihan wewangian untuk personal dan laundry.
                        Nikmati aroma yang elegan dan tahan lama dengan
                        harga yang tetap terjangkau.
                    </p>

                    <!-- CTA BUTTONS -->
                    <div
                        class="hero-fade delay-300 mt-8 flex
                            flex-wrap items-center gap-3 lg:mt-10">

                        <a href="#collections"
                            class="group inline-flex min-h-[50px]
                              items-center justify-center gap-3
                              rounded-full border border-[#A66C2D]
                              bg-[#A66C2D] px-7 py-3
                              text-[10px] font-extrabold uppercase
                              tracking-[.15em] text-white
                              shadow-lg shadow-[#68431F]/20 transition duration-300
                              hover:-translate-y-0.5 hover:bg-[#845323] active:scale-[.98]">

                            Temukan Aromamu

                            <svg class="h-4 w-4 transition-transform
                                    group-hover:translate-x-1"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>

                        <a href="#contact-form"
                            class="inline-flex min-h-[50px]
                              items-center justify-center
                              rounded-full border
                              border-[#A66C2D]/45
                              bg-[#FFF8EB]/65 px-7 py-3
                              text-[10px] font-extrabold uppercase
                              tracking-[.15em] text-[#493322]
                              shadow-sm backdrop-blur-md
                              transition duration-300
                              hover:-translate-y-0.5
                              hover:bg-[#FFF8EB]/90">

                            Hubungi Kami
                        </a>

                    </div>

                    <!-- Trust / product positioning -->
                    <div
                        class="hero-fade delay-300 mt-8
                            flex items-center gap-3
                            text-[10px] font-semibold
                            tracking-wide text-[#62472E]/85">

                        <span
                            class="flex h-7 w-7 items-center
                                 justify-center rounded-full
                                 border border-[#A66C2D]/35
                                 bg-[#FFF8EB]/60">

                            <svg class="h-3.5 w-3.5 text-[#A66C2D]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 3l2.7 5.5 6.1.9-4.4 4.3
                                     1 6.1-5.4-2.9-5.4 2.9 1-6.1
                                     -4.4-4.3 6.1-.9L12 3z" />
                            </svg>
                        </span>

                        Temukan aroma yang sesuai dengan karaktermu
                    </div>

                </div>

                <!-- RIGHT: SEPARATE PRODUCT IMAGE -->
                <div
                    class="relative flex h-full min-h-[500px]
                        items-center justify-center
                        lg:justify-end lg:pr-5 xl:pr-10">

                    <!-- Soft light behind the bottle (Posisikan di atas agar otomatis di belakang gambar) -->
                    <div
                        class="pointer-events-none absolute z-0
                            right-[8%] top-1/2 h-[340px] w-[340px]
                            -translate-y-1/2 rounded-full
                            bg-[#FFE6AC]/25 blur-[65px]
                            lg:right-[10%] lg:h-[410px]
                            lg:w-[410px]">
                    </div>

                    <!-- Gambar Botol -->
                    <!-- Atur ukuran pada bagian max-w-[...] di bawah ini -->
                    <img src="{{ asset('asset/btllelabo.svg') }}" alt="Zeeperfume Botol"
                        class="relative z-10 w-full object-contain
                               max-w-[260px] lg:max-w-[350px] xl:max-w-[380px]
                               drop-shadow-[0_20px_25px_rgba(30,20,15,.4)]
                               transition-transform duration-700 hover:scale-[1.03]">

                </div>

            </div>
        </section>

    </main>


    <!-- ================= QUICK STATS BANNER ================= -->
    <section class="relative z-20 -mt-10 sm:-mt-16 md:-mt-24 px-4 sm:px-6 lg:px-8 mb-16">
        <div
            class="max-w-[1050px] mx-auto bg-white/85 backdrop-blur-xl
                   border border-[#D9C19B]/50 rounded-3xl md:rounded-[2.5rem]
                   shadow-xl shadow-[#39291F]/5 py-8 md:py-10 px-4 sm:px-8 lg:px-12
                   relative overflow-hidden">

            <!-- Dekorasi Bias Cahaya Lembut (Warm Tones) -->
            <div
                class="absolute -top-20 -right-20 w-64 h-64 bg-[#A66C2D] opacity-[0.08]
                       blur-[70px] rounded-full pointer-events-none">
            </div>
            <div
                class="absolute -bottom-20 -left-20 w-64 h-64 bg-[#D8B985] opacity-[0.15]
                       blur-[70px] rounded-full pointer-events-none">
            </div>

            <!-- Grid: 2 kolom di mobile, 3 kolom di desktop -->
            <div
                class="grid grid-cols-2 md:grid-cols-3 text-center relative z-10
                       md:divide-x divide-[#D9C19B]/40 gap-y-6 md:gap-y-0">

                <!-- Stat 1 -->
                <div class="flex flex-col items-center justify-center px-2 group">
                    <p
                        class="font-luxury text-4xl sm:text-5xl md:text-6xl font-medium
                               text-[#39291F] mb-1 md:mb-2 group-hover:-translate-y-1
                               transition-transform duration-500 ease-out">
                        02
                    </p>
                    <p
                        class="text-[9px] sm:text-[10px] md:text-[11px] font-bold uppercase
                              tracking-[0.2em] md:tracking-[0.3em] text-[#8A5B2D] mb-1.5">
                        Cabang Resmi
                    </p>
                    <p
                        class="text-[11px] md:text-[13px] text-[#554333] italic
                              font-luxury tracking-wide hidden sm:block">
                        Hadir lebih dekat dengan Anda
                    </p>
                </div>

                <!-- Stat 2 -->
                <!-- Border kiri tipis khusus mobile untuk pemisah antar 2 kolom -->
                <div
                    class="flex flex-col items-center justify-center px-2 group
                            border-l border-[#D9C19B]/40 md:border-none">
                    <p
                        class="font-luxury text-4xl sm:text-5xl md:text-6xl font-medium
                               text-[#39291F] mb-1 md:mb-2 group-hover:-translate-y-1
                               transition-transform duration-500 ease-out flex items-start justify-center">
                        500<span class="text-2xl sm:text-3xl md:text-4xl text-[#A66C2D] ml-0.5 font-light">+</span>
                    </p>
                    <p
                        class="text-[9px] sm:text-[10px] md:text-[11px] font-bold uppercase
                              tracking-[0.2em] md:tracking-[0.3em] text-[#8A5B2D] mb-1.5">
                        Varian Aroma
                    </p>
                    <p
                        class="text-[11px] md:text-[13px] text-[#554333] italic
                              font-luxury tracking-wide hidden sm:block">
                        Eksplorasi tanpa batas
                    </p>
                </div>

                <!-- Stat 3 -->
                <!-- Di mobile: mengambil 2 kolom penuh (col-span-2) dan diberi garis atas -->
                <div
                    class="flex flex-col items-center justify-center px-2 pt-6 md:pt-0 group
                            col-span-2 md:col-span-1 border-t border-[#D9C19B]/40 md:border-none">
                    <p
                        class="font-luxury text-4xl sm:text-5xl md:text-6xl font-medium
                               text-[#39291F] mb-1 md:mb-2 group-hover:-translate-y-1
                               transition-transform duration-500 ease-out flex items-start justify-center">
                        100<span class="text-2xl sm:text-3xl md:text-4xl text-[#A66C2D] font-light">RB+</span>
                    </p>
                    <p
                        class="text-[9px] sm:text-[10px] md:text-[11px] font-bold uppercase
                              tracking-[0.2em] md:tracking-[0.3em] text-[#8A5B2D] mb-1.5">
                        Produk Terjual
                    </p>
                    <p
                        class="text-[11px] md:text-[13px] text-[#554333] italic
                              font-luxury tracking-wide">
                        Simbol kepercayaan pelanggan
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= EXCLUSIVE SERIES SLIDER SECTION (SHOWCASE ONLY) ================= -->
    <section id="exclusive-series"
        class="relative py-16 lg:py-24 overflow-hidden border-y border-[#D9C19B]/30 bg-[#39291F]">

        <!-- Background Image Utama untuk Seluruh Section -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('asset/Mist Valley.svg') }}" alt="Background"
                class="w-full h-full object-cover opacity-50">
            <!-- Gradient overlay agar teks dan kartu tetap mudah dibaca -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#39291F] via-[#39291F]/70 to-[#39291F]/40"></div>
        </div>

        <div class="relative z-10 max-w-[1380px] mx-auto px-0 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center mb-10 md:mb-16 px-4">
                <h2 class="font-luxury text-4xl sm:text-5xl lg:text-6xl text-white mb-4 drop-shadow-lg">
                    Zeeperfume Collections
                </h2>
                <!-- Garis dekorasi -->
                <div class="h-1 w-20 bg-[#A66C2D] mx-auto rounded-full shadow-[0_0_10px_rgba(166,108,45,0.8)]"></div>
            </div>

            <!-- Slider Container (Tanpa Tombol Navigasi Desktop) -->
            <div class="relative w-full">

                <!-- Track Slider (Scrollbar disembunyikan menggunakan class 'hide-scrollbar') -->
                <div
                    class="flex overflow-x-auto snap-x snap-mandatory gap-4 md:gap-5 hide-scrollbar pb-10 pt-4 px-4 sm:px-0">

                    <!-- ================= CARD 1: Redber Rouge ================= -->
                    <div
                        class="shrink-0 w-[260px] md:w-[280px] snap-center bg-[#39291F]/60 backdrop-blur-xl border border-[#D9C19B]/30 rounded-[2rem] p-5 flex flex-col relative group transition-transform duration-300 hover:-translate-y-2 shadow-2xl">

                        <!-- Badges Header -->
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[10px] text-white/80 font-medium tracking-wide">Terjual 34,894
                                kali!</span>
                            <div class="flex flex-col items-end gap-1.5">
                                <span
                                    class="bg-[#39291F]/80 text-[#D9C19B] text-[9px] font-bold px-2 py-0.5 rounded border border-[#D9C19B]/40">Best
                                    Seller</span>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="h-[180px] w-full flex items-center justify-center mb-8">
                            <div class="block w-full h-full relative">
                                <!-- Efek cahaya di belakang botol saat di-hover -->
                                <div
                                    class="absolute inset-0 bg-[#A66C2D] rounded-full blur-[40px] opacity-0 group-hover:opacity-30 transition-opacity duration-500">
                                </div>
                                <img src="{{ asset('asset/btllelabo.svg') }}" alt="Redber Rouge"
                                    class="max-w-full max-h-full object-contain mx-auto relative z-10 drop-shadow-[0_15px_15px_rgba(0,0,0,0.6)] group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="mt-auto pb-2">
                            <h3 class="font-luxury text-3xl text-white mb-2 drop-shadow-md">
                                Redber Rouge
                            </h3>
                            <p class="text-[13px] text-[#FFF8EB]/80 line-clamp-3 leading-relaxed">
                                Sensasi kemewahan dalam setiap semprotan. Aroma modern berkarakter kuat yang memikat.
                            </p>
                        </div>
                    </div>

                    <!-- ================= CARD 2: Romantic Wishy ================= -->
                    <div
                        class="shrink-0 w-[260px] md:w-[280px] snap-center bg-[#39291F]/60 backdrop-blur-xl border border-[#D9C19B]/30 rounded-[2rem] p-5 flex flex-col relative group transition-transform duration-300 hover:-translate-y-2 shadow-2xl">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[10px] text-white/80 font-medium tracking-wide">Terjual 36,661
                                kali!</span>
                        </div>
                        <div class="h-[180px] w-full flex items-center justify-center mb-8">
                            <div class="block w-full h-full relative">
                                <div
                                    class="absolute inset-0 bg-[#A66C2D] rounded-full blur-[40px] opacity-0 group-hover:opacity-30 transition-opacity duration-500">
                                </div>
                                <img src="{{ asset('asset/03_Romantic_wishy_HD.png') }}" alt="Romantic Wishy"
                                    class="max-w-full max-h-full object-contain mx-auto relative z-10 drop-shadow-[0_15px_15px_rgba(0,0,0,0.6)] group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>
                        <div class="mt-auto pb-2">
                            <h3 class="font-luxury text-3xl text-white mb-2 drop-shadow-md">
                                Romantic Wishy
                            </h3>
                            <p class="text-[13px] text-[#FFF8EB]/80 line-clamp-3 leading-relaxed">
                                Pesona floral dan sentuhan manis yang membangkitkan nuansa romantis yang elegan.
                            </p>
                        </div>
                    </div>

                    <!-- ================= CARD 3: Valency Noir ================= -->
                    <div
                        class="shrink-0 w-[260px] md:w-[280px] snap-center bg-[#39291F]/60 backdrop-blur-xl border border-[#D9C19B]/30 rounded-[2rem] p-5 flex flex-col relative group transition-transform duration-300 hover:-translate-y-2 shadow-2xl">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[10px] text-white/80 font-medium tracking-wide">Terjual 36,598
                                kali!</span>
                            <div class="flex flex-col items-end gap-1.5">
                                <span
                                    class="bg-[#39291F]/80 text-[#D9C19B] text-[9px] font-bold px-2 py-0.5 rounded border border-[#D9C19B]/40">Exclusive</span>
                            </div>
                        </div>
                        <div class="h-[180px] w-full flex items-center justify-center mb-8">
                            <div class="block w-full h-full relative">
                                <div
                                    class="absolute inset-0 bg-[#A66C2D] rounded-full blur-[40px] opacity-0 group-hover:opacity-30 transition-opacity duration-500">
                                </div>
                                <img src="{{ asset('asset/02_Valency_Noir_HD.png') }}" alt="Valency Noir"
                                    class="max-w-full max-h-full object-contain mx-auto relative z-10 drop-shadow-[0_15px_15px_rgba(0,0,0,0.6)] group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>
                        <div class="mt-auto pb-2">
                            <h3 class="font-luxury text-3xl text-white mb-2 drop-shadow-md">
                                Valency Noir
                            </h3>
                            <p class="text-[13px] text-[#FFF8EB]/80 line-clamp-3 leading-relaxed">
                                Aroma malam yang memikat. Perpaduan sempurna untuk menciptakan impresi klasik misterius.
                            </p>
                        </div>
                    </div>

                    <!-- ================= CARD 4: Swett Nagita ================= -->
                    <div
                        class="shrink-0 w-[260px] md:w-[280px] snap-center bg-[#39291F]/60 backdrop-blur-xl border border-[#D9C19B]/30 rounded-[2rem] p-5 flex flex-col relative group transition-transform duration-300 hover:-translate-y-2 shadow-2xl">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[10px] text-white/80 font-medium tracking-wide">Terjual 33,745
                                kali!</span>
                        </div>
                        <div class="h-[180px] w-full flex items-center justify-center mb-8">
                            <div class="block w-full h-full relative">
                                <div
                                    class="absolute inset-0 bg-[#A66C2D] rounded-full blur-[40px] opacity-0 group-hover:opacity-30 transition-opacity duration-500">
                                </div>
                                <img src="{{ asset('asset/04_Swett_Nagita_HD.png') }}" alt="Swett Nagita"
                                    class="max-w-full max-h-full object-contain mx-auto relative z-10 drop-shadow-[0_15px_15px_rgba(0,0,0,0.6)] group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>
                        <div class="mt-auto pb-2">
                            <h3 class="font-luxury text-3xl text-white mb-2 drop-shadow-md">
                                Swett Nagita
                            </h3>
                            <p class="text-[13px] text-[#FFF8EB]/80 line-clamp-3 leading-relaxed">
                                Pancarkan energi ceria lewat aroma manis yang segar dan tahan lama.
                            </p>
                        </div>
                    </div>

                    <!-- ================= CARD 5: Scandaleousy ================= -->
                    <div
                        class="shrink-0 w-[260px] md:w-[280px] snap-center bg-[#39291F]/60 backdrop-blur-xl border border-[#D9C19B]/30 rounded-[2rem] p-5 flex flex-col relative group transition-transform duration-300 hover:-translate-y-2 shadow-2xl">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-[10px] text-white/80 font-medium tracking-wide">Terjual 24,263
                                kali!</span>
                            <div class="flex flex-col items-end gap-1.5">
                                <span
                                    class="bg-[#39291F]/80 text-[#D9C19B] text-[9px] font-bold px-2 py-0.5 rounded border border-[#D9C19B]/40">Limited</span>
                            </div>
                        </div>
                        <div class="h-[180px] w-full flex items-center justify-center mb-8">
                            <div class="block w-full h-full relative">
                                <div
                                    class="absolute inset-0 bg-[#A66C2D] rounded-full blur-[40px] opacity-0 group-hover:opacity-30 transition-opacity duration-500">
                                </div>
                                <img src="{{ asset('asset/05_Scandaleousy_HD.png') }}" alt="Scandaleousy"
                                    class="max-w-full max-h-full object-contain mx-auto relative z-10 drop-shadow-[0_15px_15px_rgba(0,0,0,0.6)] group-hover:scale-110 transition-transform duration-500">
                            </div>
                        </div>
                        <div class="mt-auto pb-2">
                            <h3 class="font-luxury text-3xl text-white mb-2 drop-shadow-md">
                                Scandaleousy
                            </h3>
                            <p class="text-[13px] text-[#FFF8EB]/80 line-clamp-3 leading-relaxed">
                                Dirancang untuk Anda yang berani mengekspresikan diri dan meninggalkan memori kuat.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Bagian "Discover All" Button -->
            <div class="mt-4 px-4 sm:px-0">
                <div class="flex flex-row items-center gap-4 sm:gap-8 max-w-lg mx-auto">
                    <span class="h-px bg-[#D9C19B]/40 flex-1 block"></span>
                    <a href="#contact-form"
                        class="inline-flex items-center justify-center h-12 px-6 sm:px-8 bg-white/10 hover:bg-[#FFF8EB] text-white hover:text-[#39291F] border border-[#D9C19B]/50 rounded-full font-bold text-[10px] sm:text-[11px] uppercase tracking-widest transition-colors duration-300">
                        Pesan Aroma Favoritmu
                    </a>
                    <span class="h-px bg-[#D9C19B]/40 flex-1 block"></span>
                </div>
            </div>

        </div>
    </section>
    <!-- ================= FAVORITES COLLECTIONS (SQUARE LINKS GRID DENGAN MODAL) ================= -->
    <section id="favorites-collections"
        class="py-16 lg:py-24 bg-[#FFFAF5] border-y border-[#D9C19B]/30 overflow-hidden" x-data="{
            modalOpen: false,
            activeImage: '',
            activeProduct: null,
            products: {
                'redber': {
                    title: 'Redber Rouge',
                    subtitle: 'Modern & Berkelas',
                    desc: 'Sensasi kemewahan dalam setiap semprotan. Aroma modern berkarakter kuat yang siap menjadikan Anda pusat perhatian di berbagai momen istimewa. Cocok untuk penggunaan formal maupun kasual yang elegan.',
                    img: '{{ asset('asset/lp-Twilly Series 30 ML.svg') }}',
                    gallery: [
                        '{{ asset('asset/01_Redber_Rouge_HD.png') }}',
                        '{{ asset('asset/botolbg_2.jpg') }}'
                    ]
                },
                'valency': {
                    title: 'Valency Noir',
                    subtitle: 'Misterius & Elegan',
                    desc: 'Aroma malam yang memikat. Perpaduan sempurna untuk menciptakan impresi klasik misterius yang sulit dilupakan. Memancarkan aura percaya diri yang tenang namun menghanyutkan.',
                    img: '{{ asset('asset/02_Valency_Noir_HD.png') }}',
                    gallery: [
                        '{{ asset('asset/02_Valency_Noir_HD.png') }}',
                        '{{ asset('asset/botolbg.svg') }}'
                    ]
                },
                'marva': {
                    title: 'Marva',
                    subtitle: 'Eksklusif & Karismatik',
                    desc: 'Hadirkan pesona karismatik dan wibawa dalam rutinitas Anda. Marva memadukan nuansa elegan yang mendalam dan berkelas, dirancang untuk individu yang berani mengekspresikan diri.',
                    img: '{{ asset('asset/08_Marva_HD.png') }}',
                    gallery: [
                        '{{ asset('asset/08_Marva_HD.png') }}',
                        '{{ asset('asset/botolbg_2.jpg') }}'
                    ]
                }
            },
            openModal(id) {
                this.activeProduct = this.products[id];
                this.activeImage = this.activeProduct.gallery[0];
                this.modalOpen = true;
            }
        }">

        <div class="max-w-[1380px] mx-auto px-0 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12 md:mb-16 relative px-4">
                <div class="flex items-center justify-center gap-4 mb-2">
                    <svg class="w-4 h-4 text-[#A66C2D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    <h2
                        class="font-luxury text-4xl sm:text-5xl lg:text-[3.5rem] text-[#39291F] tracking-tight drop-shadow-sm">
                        Favorites
                    </h2>
                    <svg class="w-4 h-4 text-[#A66C2D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
                <p class="text-[14px] sm:text-base text-[#554333] font-medium px-2">
                    Pilihan eksklusif pelanggan setia kami
                </p>
            </div>

            <!-- Cards Container (Slider di Mobile, Grid di Desktop) -->
            <!-- Penambahan flex, overflow-x-auto, snap-x untuk mobile -->
            <div
                class="flex md:grid md:grid-cols-3 overflow-x-auto md:overflow-visible snap-x snap-mandatory hide-scrollbar gap-4 md:gap-6 lg:gap-8 max-w-[1200px] mx-auto px-4 sm:px-0 pb-8 md:pb-0 pt-2">

                <!-- CARD 1: Redber Rouge -->
                <!-- Penambahan shrink-0 w-[85vw] snap-center agar jadi slide proporsional di mobile -->
                <div class="shrink-0 w-[85vw] sm:w-[320px] md:w-auto snap-center group relative overflow-hidden rounded-[1.5rem] shadow-md shadow-[#39291F]/5 border border-[#D9C19B]/40 block transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl cursor-pointer"
                    @click="openModal('redber')">
                    <div class="block h-full w-full relative">
                        <div class="relative w-full aspect-[4/5] overflow-hidden bg-[#FFF8EB]">
                            <img src="{{ asset('asset/lp-Twilly Series 30 ML.svg') }}" alt="Redber Rouge"
                                class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#39291F]/90 via-[#39291F]/30 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                        </div>

                        <div class="absolute bottom-0 left-0 w-full p-6 sm:p-8 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-3xl md:text-[2rem] text-white leading-tight mb-4 drop-shadow-md transform transition-transform duration-500 group-hover:-translate-y-1">
                                Redber Rouge
                            </h3>
                            <div class="flex items-center justify-between overflow-hidden">
                                <span
                                    class="inline-block px-5 py-2 rounded-full border border-white/50 text-white text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.15em] bg-white/10 backdrop-blur-sm transform transition-all duration-500 group-hover:bg-[#A66C2D] group-hover:border-[#A66C2D] group-hover:text-white">
                                    Lihat Detail
                                </span>
                                <span
                                    class="w-10 h-10 rounded-full flex items-center justify-center text-white transform translate-x-12 opacity-0 transition-all duration-500 group-hover:translate-x-0 group-hover:opacity-100 bg-[#A66C2D]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Valency Noir (Center) -->
                <div class="shrink-0 w-[85vw] sm:w-[320px] md:w-auto snap-center group relative overflow-hidden rounded-[1.5rem] shadow-md shadow-[#39291F]/5 border border-[#D9C19B]/40 block transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl md:scale-[1.05] z-10 cursor-pointer"
                    @click="openModal('valency')">
                    <div class="block h-full w-full relative">
                        <div class="relative w-full aspect-[4/5] overflow-hidden bg-[#FFF8EB]">
                            <img src="{{ asset('asset/lp1.svg') }}" alt="Valency Noir"
                                class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#39291F]/90 via-[#39291F]/30 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                        </div>

                        <div class="absolute bottom-0 left-0 w-full p-6 sm:p-8 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-3xl md:text-[2rem] text-white leading-tight mb-4 drop-shadow-md transform transition-transform duration-500 group-hover:-translate-y-1">
                                Valency Noir
                            </h3>
                            <div class="flex items-center justify-between overflow-hidden">
                                <span
                                    class="inline-block px-5 py-2 rounded-full border border-white/50 text-white text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.15em] bg-white/10 backdrop-blur-sm transform transition-all duration-500 group-hover:bg-[#A66C2D] group-hover:border-[#A66C2D] group-hover:text-white">
                                    Lihat Detail
                                </span>
                                <span
                                    class="w-10 h-10 rounded-full flex items-center justify-center text-white transform translate-x-12 opacity-0 transition-all duration-500 group-hover:translate-x-0 group-hover:opacity-100 bg-[#A66C2D]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: Marva -->
                <div class="shrink-0 w-[85vw] sm:w-[320px] md:w-auto snap-center group relative overflow-hidden rounded-[1.5rem] shadow-md shadow-[#39291F]/5 border border-[#D9C19B]/40 block transform transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl cursor-pointer"
                    @click="openModal('marva')">
                    <div class="block h-full w-full relative">
                        <div class="relative w-full aspect-[4/5] overflow-hidden bg-[#FFF8EB]">
                            <img src="{{ asset('asset/lp-casa series 35,l.svg') }}" alt="Marva"
                                class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#39291F]/90 via-[#39291F]/30 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                        </div>

                        <div class="absolute bottom-0 left-0 w-full p-6 sm:p-8 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-3xl md:text-[2rem] text-white leading-tight mb-4 drop-shadow-md transform transition-transform duration-500 group-hover:-translate-y-1">
                                Marva
                            </h3>
                            <div class="flex items-center justify-between overflow-hidden">
                                <span
                                    class="inline-block px-5 py-2 rounded-full border border-white/50 text-white text-[10px] sm:text-[11px] font-bold uppercase tracking-[0.15em] bg-white/10 backdrop-blur-sm transform transition-all duration-500 group-hover:bg-[#A66C2D] group-hover:border-[#A66C2D] group-hover:text-white">
                                    Lihat Detail
                                </span>
                                <span
                                    class="w-10 h-10 rounded-full flex items-center justify-center text-white transform translate-x-12 opacity-0 transition-all duration-500 group-hover:translate-x-0 group-hover:opacity-100 bg-[#A66C2D]">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ================= PRODUCT MODAL (POPUP) ================= -->
        <!-- Overlay & Dialog -->
        <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 text-center sm:p-0">

                <!-- Background Backdrop Blur -->
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-[#1C1D21]/80 backdrop-blur-sm" aria-hidden="true"
                    @click="modalOpen = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Content Panel -->
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block w-full max-w-4xl text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle overflow-hidden border border-[#D9C19B]/40 relative">

                    <div class="flex flex-col md:flex-row h-full">

                        <!-- KIRI: Galeri Foto -->
                        <div
                            class="w-full md:w-1/2 bg-[#FFF8EB] p-6 sm:p-8 flex flex-col items-center justify-center relative border-b md:border-b-0 md:border-r border-[#D9C19B]/30">
                            <!-- Tombol Close (Mobile) -->
                            <button @click="modalOpen = false"
                                class="md:hidden absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-white text-[#39291F] border border-[#D9C19B]/50 z-10 shadow-sm">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>

                            <!-- Foto Utama -->
                            <div
                                class="w-full aspect-square rounded-2xl bg-white mb-4 flex items-center justify-center p-4 border border-[#D9C19B]/20 shadow-inner">
                                <img :src="activeImage"
                                    class="max-w-full max-h-full object-contain drop-shadow-[0_15px_15px_rgba(57,41,31,0.3)] transition-all duration-300">
                            </div>

                            <!-- Thumbnail Galeri (Bisa diklik) -->
                            <div class="flex gap-3 overflow-x-auto w-full pb-2 hide-scrollbar justify-center">
                                <template x-for="img in activeProduct?.gallery">
                                    <button @click="activeImage = img"
                                        :class="activeImage === img ? 'border-[#A66C2D] ring-2 ring-[#A66C2D]/20' :
                                            'border-[#D9C19B]/40 hover:border-[#A66C2D]'"
                                        class="w-16 h-16 shrink-0 rounded-xl bg-white border flex items-center justify-center p-1 cursor-pointer transition-all">
                                        <img :src="img" class="w-full h-full object-contain">
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- KANAN: Detail & Deskripsi -->
                        <div
                            class="w-full md:w-1/2 p-6 sm:p-10 lg:p-12 flex flex-col justify-center relative bg-white">
                            <!-- Tombol Close (Desktop) -->
                            <button @click="modalOpen = false"
                                class="hidden md:flex absolute top-6 right-6 w-10 h-10 items-center justify-center rounded-full bg-[#FFF8EB] text-[#39291F] hover:bg-[#A66C2D] hover:text-white border border-[#D9C19B]/50 transition-colors z-10 shadow-sm">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>

                            <span class="text-[#8A5B2D] text-[10px] font-extrabold uppercase tracking-[0.2em] mb-3"
                                x-text="activeProduct?.subtitle"></span>
                            <h2 class="font-luxury text-4xl lg:text-5xl text-[#39291F] mb-4"
                                x-text="activeProduct?.title"></h2>

                            <div class="w-10 h-1 bg-[#D9C19B] rounded-full mb-6"></div>

                            <p class="text-[#554333] text-[13px] md:text-[14px] leading-relaxed font-medium mb-8 pb-8 border-b border-[#D9C19B]/30"
                                x-text="activeProduct?.desc"></p>

                            <!-- Tombol Aksi -->
                            <div class="mt-auto">
                                <a href="#contact-form" @click="modalOpen = false"
                                    class="flex w-full h-14 items-center justify-center gap-2 rounded-xl bg-[#A66C2D] text-white hover:bg-[#845323] transition-all font-bold text-[12px] uppercase tracking-widest shadow-lg shadow-[#A66C2D]/20 transform active:scale-[0.98]">
                                    Pesan Aroma Ini
                                    <i class="bi bi-whatsapp ml-1 text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- ================= COLLECTIONS SECTION (MOBILE-FIRST & ENHANCED TYPOGRAPHY) ================= -->
    <section id="collections" class="py-16 lg:py-24 bg-[#FFFAF5] border-y border-[#D9C19B]/30">
        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12 md:mb-20">
                <p class="text-[11px] md:text-xs font-bold uppercase tracking-[0.3em] text-[#8A5B2D] mb-3 md:mb-4">
                    Discover Our Series</p>
                <h2 class="font-luxury text-4xl sm:text-5xl lg:text-6xl text-[#39291F] mb-4 md:mb-5">
                    Temukan Koleksi Favoritmu
                </h2>
                <p class="text-[14px] sm:text-base text-[#554333] leading-relaxed font-medium px-2">
                    Dari aroma yang segar dan ringan hingga hangat dan berkarakter, jelajahi beragam koleksi ZEEPERFUME
                    dan temukan wangi yang paling mewakili dirimu.
                </p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

                <!-- 1. Redber Rouge (Large Card - Wide) -->
                <div class="md:col-span-2 lg:row-span-2 rounded-[2rem] p-6 sm:p-8 md:p-10 flex flex-col justify-between overflow-hidden group hover:shadow-2xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[380px] lg:min-h-[660px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/ok1.svg') }}');">

                    <!-- Overlay Gradasi Gelap/Oranye (Kiri ke Kanan) -->
                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-700">
                    </div>

                    <!-- Top Badge -->
                    <div>
                        <span
                            class="inline-block px-4 py-2 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[10px] md:text-[11px] font-extrabold uppercase tracking-[0.2em] text-white shadow-sm border border-white/30">
                            Modern & Berkelas
                        </span>
                    </div>

                    <!-- Content Split Row -->
                    <div class="grid grid-cols-12 gap-4 items-end mt-6 h-full">
                        <div class="col-span-7 sm:col-span-8 flex flex-col justify-end h-full">
                            <h3
                                class="font-luxury text-3xl sm:text-4xl lg:text-5xl text-white mb-3 md:mb-4 leading-tight drop-shadow-md">
                                Eau De Parfum <br>
                                <span class="italic text-[#FFE6AC]">Redber Rouge</span>
                            </h3>
                            <p
                                class="text-[13px] md:text-[15px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-4 lg:mb-8 pr-2 drop-shadow-sm">
                                Sensasi kemewahan dalam setiap semprotan. Aroma modern berkarakter kuat yang siap
                                menjadikan Anda pusat perhatian di berbagai momen istimewa.
                            </p>
                        </div>

                        <div
                            class="col-span-5 sm:col-span-4 flex justify-center items-end h-[180px] sm:h-[260px] lg:h-[360px]">
                            {{-- <img src="{{ asset('asset/botolbg.svg') }}" alt="Redber Rouge"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_20px_25px_rgba(0,0,0,.5)]"> --}}
                        </div>
                    </div>
                </div>

                <!-- 2. Romantic Wishy (Small Card) -->
                <div class="col-span-1 rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[300px] md:min-h-[320px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/Dew-Kissed Flowers and Lychees at Sunset.png') }}');">

                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-500">
                    </div>

                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-white shadow-sm border border-white/30">
                            Lembut & Manis
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-3xl md:text-[2rem] text-white mb-2 leading-tight drop-shadow-md">
                                Romantic<br>Wishy
                            </h3>
                            <p
                                class="text-[12px] md:text-[13px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-2 drop-shadow-sm">
                                Pesona floral dan sentuhan manis yang membangkitkan nuansa romantis dan keanggunan
                                sejati.
                            </p>
                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/03_Romantic_wishy_HD.png') }}" alt="Romantic Wishy"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_12px_15px_rgba(0,0,0,.4)]">
                        </div>
                    </div>
                </div>

                <!-- 3. Valency Noir (Tall Card) -->
                <div class="col-span-1 md:col-span-2 lg:col-span-1 lg:row-span-2 rounded-[2rem] p-6 sm:p-8 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[340px] lg:min-h-[660px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/Vanilla Orchids and Amber at Golden Hour.png') }}');">

                    <!-- Overlay Gradasi BAWAH ke ATAS -->
                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-t from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-500">
                    </div>

                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-white shadow-sm border border-white/30">
                            Misterius & Elegan
                        </span>
                    </div>
                    <div class="my-auto py-6 flex justify-center items-center h-[200px] md:h-[220px] lg:h-[280px]">
                        <img src="{{ asset('asset/02_Valency_Noir_HD.png') }}" alt="Valency Noir"
                            class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_18px_20px_rgba(0,0,0,.5)]">
                    </div>
                    <div>
                        <h3 class="font-luxury text-4xl lg:text-[2.5rem] text-white mb-2 leading-none drop-shadow-md">
                            Valency<br>Noir</h3>
                        <p
                            class="text-[13px] md:text-[14px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-2 drop-shadow-sm">
                            Aroma malam yang memikat. Perpaduan sempurna untuk menciptakan impresi klasik yang sulit
                            dilupakan.
                        </p>
                    </div>
                </div>

                <!-- 4. Swett Nagita (Small Card) -->
                <div class="col-span-1 rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[300px] md:min-h-[320px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/Mix Background.jpg') }}');">

                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-500">
                    </div>

                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-white shadow-sm border border-white/30">
                            Ceria & Memikat
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-3xl md:text-[2rem] text-white mb-2 leading-tight drop-shadow-md">
                                Swett<br>Nagita</h3>
                            <p
                                class="text-[12px] md:text-[13px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-2 drop-shadow-sm">
                                Pancarkan energi ceria lewat aroma manis yang segar dan tahan lama sepanjang hari.
                            </p>
                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/04_Swett_Nagita_HD.png') }}" alt="Swett Nagita"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_12px_15px_rgba(0,0,0,.4)]">
                        </div>
                    </div>
                </div>

                <!-- 5. Scandaleousy (Wide Card) -->
                <div class="md:col-span-2 lg:row-span-1 rounded-[2rem] p-6 sm:p-8 md:p-10 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[340px] md:min-h-[360px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/Vanilla Orchids and Amber at Golden Hour.png') }}');">

                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-500">
                    </div>

                    <div>
                        <span
                            class="inline-block px-4 py-2 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[10px] md:text-[11px] font-extrabold uppercase tracking-[0.18em] text-white shadow-sm border border-white/30">
                            Berani & Percaya Diri
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-5 items-end mt-4">
                        <div class="col-span-7 sm:col-span-8 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-4xl sm:text-5xl lg:text-[3.5rem] text-white mb-3 leading-none drop-shadow-md">
                                Scandaleousy</h3>
                            <p
                                class="text-[13px] md:text-[15px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-4 pr-2 drop-shadow-sm">
                                Dirancang untuk Anda yang berani mengekspresikan diri. Aroma berkelas yang siap
                                meninggalkan jejak memori tak terlupakan.
                            </p>
                        </div>
                        <div
                            class="col-span-5 sm:col-span-4 flex justify-center items-end h-[160px] sm:h-[200px] lg:h-[220px]">
                            <img src="{{ asset('asset/05_Scandaleousy_HD.png') }}" alt="Scandaleousy"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_18px_20px_rgba(0,0,0,.5)]">
                        </div>
                    </div>
                </div>

                <!-- 6. Flower Kiss (Small Card) -->
                <div class="col-span-1 rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[300px] md:min-h-[320px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/Kiss Qua.jpg') }}');">

                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-500">
                    </div>

                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-white shadow-sm border border-white/30">
                            Anggun & Alami
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-3xl md:text-[2rem] text-white mb-2 leading-tight drop-shadow-md">
                                Flower<br>Kiss</h3>
                            <p
                                class="text-[12px] md:text-[13px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-2 drop-shadow-sm">
                                Keharuman bunga murni layaknya taman musim semi yang menenangkan hati.
                            </p>
                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/06_Flower_kiss_HD.png') }}" alt="Flower Kiss"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_12px_15px_rgba(0,0,0,.4)]">
                        </div>
                    </div>
                </div>

                <!-- 7. All About You (Small Card) -->
                <div class="col-span-1 rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[300px] md:min-h-[320px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/Sunset Berry Meadow by the Lake.png') }}');">

                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-500">
                    </div>

                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-white shadow-sm border border-white/30">
                            Personal & Intim
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-3xl md:text-[2rem] text-white mb-2 leading-tight drop-shadow-md">
                                All About<br>You</h3>
                            <p
                                class="text-[12px] md:text-[13px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-2 drop-shadow-sm">
                                Wewangian personal yang diracik seolah khusus mewakili karakter unik Anda.
                            </p>
                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/07_All_about_you_HD.png') }}" alt="All About You"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_12px_15px_rgba(0,0,0,.4)]">
                        </div>
                    </div>
                </div>

                <!-- 8. Marva (Wide Card) -->
                <div class="md:col-span-2 lg:row-span-1 rounded-[2rem] p-6 sm:p-8 md:p-10 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[340px] md:min-h-[360px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/Golden Roses Over a Misty Valley.png') }}');">

                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-500">
                    </div>

                    <div>
                        <span
                            class="inline-block px-4 py-2 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[10px] md:text-[11px] font-extrabold uppercase tracking-[0.18em] text-white shadow-sm border border-white/30">
                            Eksklusif & Karismatik
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-5 items-end mt-4">
                        <div class="col-span-7 sm:col-span-8 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-4xl sm:text-5xl lg:text-[3.5rem] text-white mb-3 leading-none drop-shadow-md">
                                Marva</h3>
                            <p
                                class="text-[13px] md:text-[15px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-5 md:mb-6 pr-2 drop-shadow-sm">
                                Hadirkan pesona karismatik dan wibawa dalam rutinitas Anda. Marva memadukan nuansa
                                elegan yang mendalam dan berkelas.
                            </p>
                            <a href="#contact-form"
                                class="inline-flex items-center gap-2 text-[11px] md:text-xs font-bold uppercase tracking-widest text-[#FFE6AC] hover:text-white transition-colors w-max drop-shadow-sm">
                                Pesan Sekarang <i class="bi bi-arrow-right text-lg"></i>
                            </a>
                        </div>
                        <div
                            class="col-span-5 sm:col-span-4 flex justify-center items-end h-[160px] sm:h-[200px] lg:h-[220px]">
                            <img src="{{ asset('asset/08_Marva_HD.png') }}" alt="Marva"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_18px_20px_rgba(0,0,0,.5)]">
                        </div>
                    </div>
                </div>

                <!-- 9. Vanilla Cake (Small Card) -->
                <!-- Anda juga bisa mencoba background baru di sini -->
                <div class="col-span-1 rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[300px] md:min-h-[320px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/Sunset Berry Meadow by the Lake.png') }}');">

                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-500">
                    </div>

                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-white shadow-sm border border-white/30">
                            Warm & Gourmand
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-3xl md:text-[2rem] text-white mb-2 leading-tight drop-shadow-md">
                                Vanilla<br>Cake</h3>
                            <p
                                class="text-[12px] md:text-[13px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-2 drop-shadow-sm">
                                Aroma manis vanilla yang lezat dan menghangatkan, membangkitkan *mood* positif.
                            </p>
                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/09_Vanilla_cake_HD.png') }}" alt="Vanilla Cake"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_12px_15px_rgba(0,0,0,.4)]">
                        </div>
                    </div>
                </div>

                <!-- 10. Blackcurrant (Small Card) -->
                <div class="col-span-1 rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:shadow-[#39291F]/20 hover:-translate-y-1 transition-all duration-500 border border-[#D9C19B]/40 min-h-[300px] md:min-h-[320px] relative isolate bg-cover bg-center"
                    style="background-image: url('{{ asset('asset/Sunset Berry Meadow by the Lake.png') }}');">

                    <div
                        class="absolute inset-0 -z-10 bg-gradient-to-r from-[#39291F]/90 via-[#39291F]/60 to-[#A66C2D]/20 group-hover:from-[#39291F]/95 transition-all duration-500">
                    </div>

                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-[#FFF8EB]/20 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-white shadow-sm border border-white/30">
                            Fruity & Energetik
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-3xl md:text-[2rem] text-white mb-2 leading-tight drop-shadow-md">
                                Black<br>Currant</h3>
                            <p
                                class="text-[12px] md:text-[13px] text-[#FFFAF5]/90 font-medium leading-relaxed mb-2 drop-shadow-sm">
                                Kesegaran buah berry yang cerah, cocok untuk menemani hari yang aktif.
                            </p>
                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/10_Blackcurrant_HD.png') }}" alt="Blackcurrant"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-700 drop-shadow-[0_12px_15px_rgba(0,0,0,.4)]">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= ABOUT & PHILOSOPHY (TENTANG KAMI) ================= -->
    <section id="about" class="py-16 md:py-20 lg:py-28 bg-white border-y border-[#D9C19B]/30 overflow-hidden">
        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
                <!-- KONTEN TEKS (KIRI) -->
                <div class="lg:col-span-7 flex flex-col justify-center order-2 lg:order-1">
                    <!-- Header & Deskripsi -->
                    <div class="mb-8">
                        <p class="text-[10px] md:text-[11px] font-bold uppercase tracking-[0.3em] text-[#8A5B2D] mb-3">
                            Tentang ZEEPERFUME
                        </p>
                        <h2
                            class="font-luxury text-4xl sm:text-5xl lg:text-[3.5rem] leading-[1.15] tracking-tight text-[#39291F] mb-5 md:mb-6">
                            Lebih dari Sekadar <span class="italic text-[#A66C2D] block sm:inline">Wewangian.</span>
                        </h2>
                        <div class="space-y-4">
                            <p class="text-[13px] md:text-[14.5px] text-[#554333] leading-relaxed font-medium">
                                Berawal pada tahun 2023, ZEEPERFUME hadir dari keinginan sederhana: membantu setiap
                                orang menemukan aroma yang sesuai dengan karakter, kebutuhan, dan ceritanya. Kami
                                menyediakan beragam pilihan parfum untuk penggunaan pribadi hingga kebutuhan usaha,
                                termasuk parfum retail, grosir, dan pewangi laundry.
                            </p>
                            <p class="text-[13px] md:text-[14.5px] text-[#554333] leading-relaxed font-medium">
                                Dengan pilihan aroma yang terus berkembang, harga yang terjangkau, serta pelayanan yang
                                ramah dan profesional, kami berkomitmen menjadi pilihan tepercaya bagi pelanggan dan
                                mitra yang ingin tumbuh bersama.
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mb-10 lg:mb-12">
                        <a href="#contact"
                            class="inline-flex items-center justify-center gap-2 h-12 md:h-14 px-8 rounded-full bg-[#A66C2D] text-white text-[10px] md:text-[11px] font-bold uppercase tracking-widest hover:bg-[#845323] shadow-lg shadow-[#68431F]/20 transition-all transform active:scale-95">
                            Contact ZEEPERFUME
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                    <!-- Layout Visi & Misi (Cards) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5 pt-8 border-t border-[#D9C19B]/40">

                        <!-- Card Visi -->
                        <div
                            class="bg-white p-6 md:p-7 rounded-[1.5rem] border border-[#D9C19B]/40 shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
                            <h3
                                class="font-bold text-[#39291F] mb-3 flex items-center gap-2.5 text-[13px] md:text-[14px] uppercase tracking-wide">
                                <div
                                    class="w-8 h-8 rounded-[10px] bg-[#FFF8EB] border border-[#D9C19B]/50 text-[#A66C2D] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                Visi Kami
                            </h3>
                            <p class="text-[12px] md:text-[13px] text-[#554333] leading-relaxed font-medium">
                                Menjadi brand parfum lokal unggulan yang dikenal melalui kualitas, inovasi, dan
                                integritas, serta memberikan dampak positif bagi pelanggan, mitra, karyawan, dan
                                masyarakat.
                            </p>
                        </div>

                        <!-- Card Misi -->
                        <div
                            class="bg-white p-6 md:p-7 rounded-[1.5rem] border border-[#D9C19B]/40 shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
                            <h3
                                class="font-bold text-[#39291F] mb-3 flex items-center gap-2.5 text-[13px] md:text-[14px] uppercase tracking-wide">
                                <div
                                    class="w-8 h-8 rounded-[10px] bg-[#FFF8EB] border border-[#D9C19B]/50 text-[#A66C2D] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                Misi Kami
                            </h3>
                            <ul
                                class="text-[12px] md:text-[12.5px] text-[#554333] leading-relaxed font-medium space-y-3">
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-check2-circle text-[#A66C2D] mt-0.5 text-base flex-shrink-0"></i>
                                    <span>Menghadirkan wewangian berkualitas dengan karakter aroma istimewa dan harga
                                        yang terjangkau.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-check2-circle text-[#A66C2D] mt-0.5 text-base flex-shrink-0"></i>
                                    <span>Memberikan pelayanan yang jujur, ramah, profesional, dan berorientasi pada
                                        kepuasan pelanggan.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-check2-circle text-[#A66C2D] mt-0.5 text-base flex-shrink-0"></i>
                                    <span>Membuka peluang kemitraan bagi reseller, agen, dan distributor untuk tumbuh
                                        bersama.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-check2-circle text-[#A66C2D] mt-0.5 text-base flex-shrink-0"></i>
                                    <span>Membangun bisnis yang tidak hanya bertumbuh, tetapi juga memberikan manfaat
                                        bagi lingkungan.</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <!-- KONTEN GAMBAR (KANAN) -->
                <div
                    class="lg:col-span-5 order-1 lg:order-2 w-full h-[350px] sm:h-[450px] lg:h-full lg:min-h-[750px] bg-[#FFF8EB] rounded-[2rem] md:rounded-[2.5rem] border border-[#D9C19B]/50 flex items-center justify-center relative overflow-hidden shadow-sm lg:sticky lg:top-24 group">

                    <!-- FOTO TOKO -->
                    <img src="{{ asset('asset/Toko Zeeperfume dari dalam.jpg') }}" alt="Tentang ZEEPERFUME"
                        class="object-cover w-full h-full absolute inset-0 group-hover:scale-105 transition-transform duration-700">

                    <!-- Gradient Agar Teks Badge Terbaca -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-[#39291F]/70 via-transparent to-transparent pointer-events-none">
                    </div>

                    <!-- Overlay Badge Floating -->
                    <div
                        class="absolute bottom-6 sm:bottom-8 left-6 sm:left-8 right-6 sm:right-8 bg-white/95 backdrop-blur-md p-5 rounded-2xl shadow-xl border border-[#D9C19B]/50 text-center transform group-hover:-translate-y-2 transition-transform duration-500 cursor-default">
                        <div
                            class="w-10 h-10 md:w-12 md:h-12 mx-auto bg-[#FFF8EB] border border-[#D9C19B]/40 text-[#A66C2D] rounded-full flex items-center justify-center mb-3">
                            <i class="bi bi-shop text-lg md:text-xl"></i>
                        </div>
                        <p
                            class="text-[10px] md:text-[11px] font-extrabold uppercase tracking-[0.15em] text-[#39291F] mb-1.5">
                            Peluang Kemitraan
                        </p>
                        <p
                            class="text-[12px] md:text-[13px] text-[#554333] font-medium leading-snug max-w-[80%] mx-auto">
                            Tersedia Harga Khusus Grosir, Agen & Reseller
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= WHY CHOOSE US (CLEAN LIGHT LAYOUT) ================= -->
    <section id="why-us" class="relative py-16 md:py-20 lg:py-28 bg-[#FFFAF5] overflow-hidden z-10">
        <!-- Dekorasi Background (Warm Glow) -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[80%] h-full bg-[#D8B985] opacity-[0.12] blur-[120px] rounded-full pointer-events-none -z-10">
        </div>

        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16">
                <p class="text-[10px] md:text-[11px] font-bold uppercase tracking-[0.3em] text-[#8A5B2D] mb-3 md:mb-4">
                    Mengapa Memilih ZEEPERFUME?
                </p>
                <h2 class="font-luxury text-3xl md:text-4xl lg:text-5xl text-[#39291F] tracking-wide mb-4">
                    Wewangian Berkualitas, Pelayanan Sepenuh Hati
                </h2>
                <p class="text-[13px] md:text-[14px] text-[#554333] leading-relaxed font-medium px-2">
                    Kami percaya pengalaman memilih parfum harus terasa mudah dan menyenangkan. Karena itu, ZEEPERFUME
                    menghadirkan pilihan lengkap, harga kompetitif, serta pelayanan yang membantu Anda menemukan aroma
                    terbaik.
                </p>
            </div>

            <!-- Features Container -->
            <div
                class="bg-white rounded-[2rem] p-6 sm:p-10 lg:p-12 shadow-xl shadow-[#39291F]/5 border border-[#D9C19B]/40">

                <div class="flex flex-wrap justify-center gap-6 sm:gap-8 lg:gap-10">

                    <!-- Card 1: Kualitas Terjaga -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-1.5rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-[#FFF8EB] border border-[#D9C19B]/30 text-[#A66C2D] flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-[#A66C2D] group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-[#39291F] mb-1.5 tracking-wide">
                                Kualitas Terjaga</h3>
                            <p class="text-[12px] md:text-[12.5px] text-[#554333] leading-relaxed font-medium">
                                Kami memilih produk dengan standar kualitas yang baik agar setiap aroma memberikan
                                pengalaman yang memuaskan.
                            </p>
                        </div>
                    </div>

                    <!-- Card 2: Pilihan Selalu Berkembang -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-1.5rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-[#FFF8EB] border border-[#D9C19B]/30 text-[#A66C2D] flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-[#A66C2D] group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-[#39291F] mb-1.5 tracking-wide">
                                Pilihan Selalu Berkembang</h3>
                            <p class="text-[12px] md:text-[12.5px] text-[#554333] leading-relaxed font-medium">
                                Koleksi kami terus diperbarui mengikuti kebutuhan dan selera pelanggan yang beragam.
                            </p>
                        </div>
                    </div>

                    <!-- Card 3: Harga Kompetitif -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-1.5rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-[#FFF8EB] border border-[#D9C19B]/30 text-[#A66C2D] flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-[#A66C2D] group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-[#39291F] mb-1.5 tracking-wide">Harga
                                Kompetitif</h3>
                            <p class="text-[12px] md:text-[12.5px] text-[#554333] leading-relaxed font-medium">
                                Nikmati pilihan wewangian berkualitas dengan harga yang bersahabat untuk pembelian
                                retail maupun grosir.
                            </p>
                        </div>
                    </div>

                    <!-- Card 4: Pelayanan Tepercaya -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-1.5rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-[#FFF8EB] border border-[#D9C19B]/30 text-[#A66C2D] flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-[#A66C2D] group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-[#39291F] mb-1.5 tracking-wide">
                                Pelayanan Tepercaya</h3>
                            <p class="text-[12px] md:text-[12.5px] text-[#554333] leading-relaxed font-medium">
                                Tim kami siap melayani dengan ramah, cepat, jujur, dan profesional sejak konsultasi
                                hingga pembelian.
                            </p>
                        </div>
                    </div>

                    <!-- Card 5: Peluang Tumbuh Bersama -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-1.5rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-[#FFF8EB] border border-[#D9C19B]/30 text-[#A66C2D] flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-[#A66C2D] group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-[#39291F] mb-1.5 tracking-wide">
                                Peluang Tumbuh Bersama</h3>
                            <p class="text-[12px] md:text-[12.5px] text-[#554333] leading-relaxed font-medium">
                                Kami membuka kesempatan kemitraan bagi reseller, agen, dan distributor yang ingin
                                mengembangkan usaha wewangian.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- ================= TESTIMONIAL SECTION ================= -->
    <section id="testimonials" class="py-16 md:py-24 bg-[#FFFAF5] border-y border-[#D9C19B]/30 overflow-hidden"
        x-data="{
            isPressed: false,
            isHovered: false,
            scrollNext() { $refs.slider.scrollBy({ left: $refs.slider.firstElementChild.getBoundingClientRect().width + 24, behavior: 'smooth' }); },
            scrollPrev() { $refs.slider.scrollBy({ left: -($refs.slider.firstElementChild.getBoundingClientRect().width + 24), behavior: 'smooth' }); }
        }">
        <div class="max-w-[1380px] mx-auto px-0 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-center">

                <!-- KIRI: Header & Navigasi -->
                <div class="w-full px-6 lg:px-0 lg:w-[35%] flex flex-col justify-center text-center lg:text-left z-10">
                    <div class="flex items-center justify-center lg:justify-start gap-3 mb-4">
                        <div class="hidden lg:block w-8 h-[2px] bg-[#A66C2D]"></div>
                        <p class="text-[10px] md:text-[11px] font-bold uppercase tracking-[0.3em] text-[#8A5B2D]">
                            Cerita Pelanggan
                        </p>
                    </div>

                    <h2
                        class="font-luxury text-4xl sm:text-5xl lg:text-[3.5rem] leading-[1.1] tracking-tight text-[#39291F] mb-2 lg:mb-6">
                        Aroma Favorit Mereka,<br class="hidden lg:block">
                        <span class="italic text-[#A66C2D]">Mungkin Juga Favoritmu.</span>
                    </h2>

                    <div class="hidden lg:flex gap-3 mt-6">
                        <button type="button" aria-label="Testimoni sebelumnya" @click="scrollPrev"
                            class="w-12 h-12 rounded-full border-2 border-[#39291F] flex items-center justify-center text-[#39291F] hover:bg-[#39291F] hover:text-white transition-all transform active:scale-95 bg-transparent">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button type="button" aria-label="Testimoni berikutnya" @click="scrollNext"
                            class="w-12 h-12 rounded-full border-2 border-[#39291F] flex items-center justify-center text-[#39291F] hover:bg-[#39291F] hover:text-white transition-all transform active:scale-95 bg-white shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- KANAN: Track Testimoni -->
                <div class="w-full lg:w-[65%] relative px-4 lg:px-0" @pointerdown="isPressed = true"
                    @pointerup.window="isPressed = false" @pointercancel.window="isPressed = false"
                    @pointerenter="isHovered = ($event.pointerType === 'mouse')" @pointerleave="isHovered = false"
                    @blur.window="isPressed = false; isHovered = false">

                    <div
                        class="hidden lg:block absolute top-0 right-0 w-24 h-full bg-gradient-to-l from-[#FFFAF5] to-transparent z-10 pointer-events-none">
                    </div>

                    <!-- Perbaikan Padding Mobile agar tidak terpotong bayangannya -->
                    <div x-ref="slider"
                        class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar gap-4 md:gap-6 py-8 pl-1 pr-4 lg:pl-0 lg:pr-24">

                        <!-- Card 1 -->
                        <div
                            class="shrink-0 w-[280px] md:w-[340px] snap-center bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-[#39291F]/5 relative border border-[#D9C19B]/40 flex flex-col group hover:-translate-y-2 transition-transform duration-500 cursor-pointer">
                            <i
                                class="bi bi-quote absolute top-6 right-6 text-6xl text-[#D9C19B]/30 z-0 group-hover:text-[#A66C2D]/10 transition-colors"></i>
                            <div class="flex items-center gap-1 text-[#F59E0B] mb-5 text-sm relative z-10">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                                <span class="ml-2 text-[#39291F] font-bold text-xs">5.0</span>
                            </div>
                            <p
                                class="text-[13px] md:text-[14px] text-[#554333] font-medium leading-relaxed mb-8 relative z-10 flex-grow">
                                "Cocok banget! Wanginya sesuai dengan yang saya suka, sampai nggak mau pindah ke lain
                                hati."
                            </p>
                            <div class="flex items-center gap-3 relative z-10 border-t border-[#D9C19B]/40 pt-5">
                                <div
                                    class="w-10 h-10 rounded-full bg-[#FFF8EB] border border-[#D9C19B]/50 flex items-center justify-center text-[#A66C2D] font-black text-lg">
                                    L</div>
                                <div>
                                    <h4 class="font-bold text-[13px] text-[#39291F]">Lusi</h4>
                                    <p class="text-[9px] text-[#8A5B2D] uppercase tracking-widest font-bold">Pelanggan
                                        Setia</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div
                            class="shrink-0 w-[280px] md:w-[340px] snap-center bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-[#39291F]/5 relative border border-[#D9C19B]/40 flex flex-col group hover:-translate-y-2 transition-transform duration-500 cursor-pointer">
                            <i
                                class="bi bi-quote absolute top-6 right-6 text-6xl text-[#D9C19B]/30 z-0 group-hover:text-[#A66C2D]/10 transition-colors"></i>
                            <div class="flex items-center gap-1 text-[#F59E0B] mb-5 text-sm relative z-10">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                                <span class="ml-2 text-[#39291F] font-bold text-xs">5.0</span>
                            </div>
                            <p
                                class="text-[13px] md:text-[14px] text-[#554333] font-medium leading-relaxed mb-8 relative z-10 flex-grow">
                                "Varian Nagita wanginya enak banget. Baru pertama coba dan langsung suka dengan
                                aromanya."
                            </p>
                            <div class="flex items-center gap-3 relative z-10 border-t border-[#D9C19B]/40 pt-5">
                                <div
                                    class="w-10 h-10 rounded-full bg-[#FFF8EB] border border-[#D9C19B]/50 flex items-center justify-center text-[#A66C2D] font-black text-lg">
                                    S</div>
                                <div>
                                    <h4 class="font-bold text-[13px] text-[#39291F]">Siti Rahma</h4>
                                    <p class="text-[9px] text-[#8A5B2D] uppercase tracking-widest font-bold">Pelanggan
                                        Baru</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div
                            class="shrink-0 w-[280px] md:w-[340px] snap-center bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-[#39291F]/5 relative border border-[#D9C19B]/40 flex flex-col group hover:-translate-y-2 transition-transform duration-500 cursor-pointer">
                            <i
                                class="bi bi-quote absolute top-6 right-6 text-6xl text-[#D9C19B]/30 z-0 group-hover:text-[#A66C2D]/10 transition-colors"></i>
                            <div class="flex items-center gap-1 text-[#F59E0B] mb-5 text-sm relative z-10">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                                <span class="ml-2 text-[#39291F] font-bold text-xs">5.0</span>
                            </div>
                            <p
                                class="text-[13px] md:text-[14px] text-[#554333] font-medium leading-relaxed mb-8 relative z-10 flex-grow">
                                "Wanginya enak, produknya rapi, dan aromanya tahan lama. Dipakai dari pagi pun masih
                                terasa. Recommended!"
                            </p>
                            <div class="flex items-center gap-3 relative z-10 border-t border-[#D9C19B]/40 pt-5">
                                <div
                                    class="w-10 h-10 rounded-full bg-[#FFF8EB] border border-[#D9C19B]/50 flex items-center justify-center text-[#A66C2D] font-black text-lg">
                                    Y</div>
                                <div>
                                    <h4 class="font-bold text-[13px] text-[#39291F]">Yaya</h4>
                                    <p class="text-[9px] text-[#8A5B2D] uppercase tracking-widest font-bold">Verified
                                        Buyer</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CONTACT FORM SECTION ================= -->
    <section id="contact-form" class="py-16 md:py-24 px-4 sm:px-6 lg:px-8 relative z-10 overflow-hidden bg-white">
        <div
            class="absolute -top-24 left-1/2 -translate-x-1/2 w-[80%] h-64 bg-[#D8B985] opacity-[0.15] blur-[100px] rounded-full pointer-events-none -z-10">
        </div>
        <div class="max-w-[700px] mx-auto relative z-10">

            <div class="text-center mb-10 md:mb-12">
                <p class="text-[10px] md:text-[11px] uppercase tracking-[0.3em] text-[#8A5B2D] font-extrabold mb-3">
                    Mulai Cerita Aromamu
                </p>
                <h2 class="font-luxury text-3xl sm:text-4xl text-[#39291F] mb-4">
                    Hubungi ZEEPERFUME
                </h2>
                <p class="text-[#554333] text-sm md:text-[15px] font-medium max-w-[400px] mx-auto">
                    Isi kebutuhanmu, lalu lanjutkan dan kirim pesan melalui WhatsApp.
                </p>
            </div>

            <div
                class="bg-white rounded-[2rem] p-6 sm:p-10 shadow-2xl shadow-[#39291F]/10 border border-[#D9C19B]/50 relative">
                <form id="waForm" onsubmit="sendToWhatsApp(event)" class="flex flex-col gap-5 md:gap-7">

                    <!-- Input Nama -->
                    <div>
                        <label for="wa_name"
                            class="block text-[11px] font-bold text-[#39291F] uppercase tracking-widest mb-2.5">
                            Nama Lengkap / Brand
                        </label>
                        <input type="text" id="wa_name" name="name" autocomplete="name" maxlength="120"
                            required placeholder="Contoh: Sarah / Toko Wangi"
                            class="w-full h-12 md:h-14 px-4 sm:px-5 rounded-xl border border-[#D9C19B]/60 bg-[#FFFAF5] text-[#39291F] text-sm font-medium focus:outline-none focus:border-[#A66C2D] focus:ring-1 focus:ring-[#A66C2D] transition-all placeholder:text-[#554333]/50">
                    </div>

                    <!-- Input Dropdowns -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                        <div>
                            <label for="wa_service"
                                class="block text-[11px] font-bold text-[#39291F] uppercase tracking-widest mb-2.5">
                                Tujuan Menghubungi
                            </label>
                            <div class="relative">
                                <select id="wa_service" required
                                    class="w-full h-12 md:h-14 px-4 sm:px-5 pr-10 rounded-xl border border-[#D9C19B]/60 bg-[#FFFAF5] text-[#39291F] text-sm font-medium focus:outline-none focus:border-[#A66C2D] focus:ring-1 focus:ring-[#A66C2D] transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Kebutuhan --</option>
                                    <option value="Konsultasi Aroma Pribadi">Konsultasi Aroma Pribadi</option>
                                    <option value="Pemesanan Parfum Retail">Pemesanan Parfum Retail (Satuan)</option>
                                    <option value="Pemesanan Parfum Grosir / Agen">Pemesanan Grosir / Agen (Harga
                                        Khusus)</option>
                                    <option value="Pemesanan Parfum Laundry">Pemesanan Parfum Laundry</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#8A5B2D]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="wa_source"
                                class="block text-[11px] font-bold text-[#39291F] uppercase tracking-widest mb-2.5">
                                Tahu Kami Dari Mana?
                            </label>
                            <div class="relative">
                                <select id="wa_source" required
                                    class="w-full h-12 md:h-14 px-4 sm:px-5 pr-10 rounded-xl border border-[#D9C19B]/60 bg-[#FFFAF5] text-[#39291F] text-sm font-medium focus:outline-none focus:border-[#A66C2D] focus:ring-1 focus:ring-[#A66C2D] transition-all appearance-none cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Sumber --</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="TikTok">TikTok</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Rekomendasi Teman / Keluarga">Rekomendasi Teman / Keluarga</option>
                                    <option value="Pencarian Google">Pencarian Google</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#8A5B2D]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Input Pesan -->
                    <div>
                        <label for="wa_message"
                            class="block text-[11px] font-bold text-[#39291F] uppercase tracking-widest mb-2.5">
                            Detail Pesan / Pertanyaan
                        </label>
                        <textarea id="wa_message" name="message" maxlength="2000" required rows="4"
                            placeholder="Ceritakan detail parfum yang kamu cari atau tanyakan apa saja di sini..."
                            class="w-full p-4 sm:p-5 rounded-xl border border-[#D9C19B]/60 bg-[#FFFAF5] text-[#39291F] text-sm font-medium focus:outline-none focus:border-[#A66C2D] focus:ring-1 focus:ring-[#A66C2D] transition-all resize-y placeholder:text-[#554333]/50"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit"
                            class="group w-full h-14 md:h-16 rounded-xl bg-[#A66C2D] text-white flex items-center justify-center gap-3 text-[12px] font-bold uppercase tracking-widest hover:bg-[#845323] shadow-lg shadow-[#68431F]/20 transition-all transform active:scale-[0.98]">
                            Lanjutkan ke WhatsApp
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER / KONTAK ================= -->
    <footer id="contact" class="bg-[#FFF8EB] pt-16 pb-8 border-t border-[#D9C19B]/40">
        <div class="max-w-[1180px] mx-auto px-6 sm:px-8 lg:px-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-6 mb-12">

                <!-- Kolom 1 -->
                <div class="md:col-span-4">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="ZEEPERFUME Logo"
                            class="h-8 w-auto">
                        <span class="text-sm font-bold uppercase tracking-widest text-[#39291F]">ZEEPERFUME</span>
                    </div>
                    <p class="text-xs text-[#554333] leading-relaxed mb-6 font-medium pr-4">
                        Menjadi brand parfum lokal unggulan yang mengedepankan kualitas, inovasi, dan integritas. Open
                        Distributor & Reseller Resmi.
                    </p>
                    <div class="flex items-center gap-3">
                        <a aria-label="Instagram ZEEPERFUME" href="https://instagram.com/zeeperfume" target="_blank"
                            rel="noopener noreferrer"
                            class="w-8 h-8 rounded-full bg-white border border-[#D9C19B]/60 flex items-center justify-center text-[#8A5B2D] hover:bg-[#A66C2D] hover:text-white hover:border-[#A66C2D] transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                        <a aria-label="TikTok ZEEPERFUME" href="https://tiktok.com/@ZeePerfumePontianak"
                            target="_blank" rel="noopener noreferrer"
                            class="w-8 h-8 rounded-full bg-white border border-[#D9C19B]/60 flex items-center justify-center text-[#8A5B2D] hover:bg-[#A66C2D] hover:text-white hover:border-[#A66C2D] transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 003.5 15.7a6.33 6.33 0 0010.86 4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1.54-.56z" />
                            </svg>
                        </a>
                        <a aria-label="Shopee ZEEPERFUME" href="https://shopee.co.id/zeeperfume" target="_blank"
                            rel="noopener noreferrer"
                            class="w-8 h-8 rounded-full bg-white border border-[#D9C19B]/60 flex items-center justify-center text-[#8A5B2D] hover:bg-[#A66C2D] hover:text-white hover:border-[#A66C2D] transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Cabang Pusat -->
                <div class="md:col-span-4 pt-6 md:pt-0 border-t border-[#D9C19B]/40 md:border-none">
                    <h3
                        class="text-xs font-black text-[#39291F] uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#A66C2D]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Cabang Pusat
                    </h3>
                    <p class="text-xs text-[#554333] leading-relaxed font-medium mb-2">Jalan Tanjung Raya II, Saigon,
                        Kec. Pontianak Timur, Kota Pontianak, Kalimantan Barat</p>
                    <a href="https://maps.app.goo.gl/sMSEqxmpigTNRUm18" target="_blank" rel="noopener noreferrer"
                        class="text-[10px] font-bold text-[#8A5B2D] hover:text-[#A66C2D] hover:underline uppercase tracking-wider transition-colors inline-block">Buka
                        di Google Maps &rarr;</a>
                </div>

                <!-- Kolom 3: Cabang Jungkat -->
                <div class="md:col-span-4 pt-6 md:pt-0 border-t border-[#D9C19B]/40 md:border-none">
                    <h3
                        class="text-xs font-black text-[#39291F] uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#A66C2D]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Cabang Jungkat
                    </h3>
                    <p class="text-xs text-[#554333] leading-relaxed font-medium mb-2">Jalan Raya Jungkat (Depan Masjid
                        Sahibul Kahfi), Kec. Jongkat, Kabupaten Mempawah, Kalbar</p>
                    <a href="https://maps.app.goo.gl/pjTf5m5dpiEeKD6E9" target="_blank" rel="noopener noreferrer"
                        class="text-[10px] font-bold text-[#8A5B2D] hover:text-[#A66C2D] hover:underline uppercase tracking-wider mb-5 transition-colors inline-block">Buka
                        di Google Maps &rarr;</a>

                    <div class="pt-4 border-t border-[#D9C19B]/40 flex flex-col gap-2.5">
                        <div class="flex items-center gap-2.5 text-xs text-[#554333] font-bold">
                            <div
                                class="w-6 h-6 rounded-full bg-[#FFF8EB] border border-[#D9C19B]/50 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-[#A66C2D]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <span>0896-9386-5056 / 0852-5056-6867</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-xs text-[#554333] font-bold">
                            <div
                                class="w-6 h-6 rounded-full bg-[#FFF8EB] border border-[#D9C19B]/50 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-[#A66C2D]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span>Buka Setiap Hari (08.00 - 22.00 WIB)</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="border-t border-[#D9C19B]/40 pt-6 text-center">
                <p class="text-[10px] text-[#8A5B2D] font-bold tracking-widest uppercase">
                    © {{ date('Y') }} ZEEPERFUME. ALL RIGHTS RESERVED.
                </p>
            </div>
        </div>
    </footer>
    <!-- Scripts -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuToggle && mobileMenu) {
            const setMenu = (open) => {
                mobileMenu.classList.toggle('hidden', !open);
                menuToggle.setAttribute('aria-expanded', String(open));
                menuToggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
            };
            menuToggle.addEventListener('click', () => setMenu(mobileMenu.classList.contains('hidden')));
            mobileMenu.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setMenu(false)));
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                    setMenu(false);
                    menuToggle.focus();
                }
            });
            document.addEventListener('click', event => {
                if (!mobileMenu.contains(event.target) && !menuToggle.contains(event.target)) setMenu(false);
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) setMenu(false);
            });
        }
    </script>
    <script>
        function sendToWhatsApp(event) {
            // Mencegah form melakukan refresh halaman
            event.preventDefault();
            if (!event.currentTarget.reportValidity()) return;

            // Nomor WhatsApp pertama dari informasi kontak ZEEPERFUME
            const waNumber = "6289693865056";

            // Mengambil nilai dari form
            const name = document.getElementById('wa_name').value.trim();
            const service = document.getElementById('wa_service').value;
            const source = document.getElementById('wa_source').value;
            const message = document.getElementById('wa_message').value.trim();

            // Menyusun format pesan
            const textTemplate =
                `Halo admin *ZEEPERFUME*,\n\nPerkenalkan, saya *${name}*. Saya menghubungi karena tertarik terkait:\n*${service}*\n\n*Mengetahui ZEEPERFUME dari:*\n${source}\n\n*Detail Pesan:* \n${message}\n\nMohon informasi lebih lanjut. Terima kasih!`;

            // Mengubah format teks menjadi URL
            const encodedText = encodeURIComponent(textTemplate);

            // Membuka tab baru yang langsung mengarah ke chat WhatsApp
            window.open(`https://wa.me/${waNumber}?text=${encodedText}`, '_blank', 'noopener,noreferrer');
        }
    </script>
</body>

</html>
