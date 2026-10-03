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
        }

        body {
            font-family: var(--font-body);
            font-weight: 400;
            background-color: #FFFDFF;
            color: #020331;
        }

        /* Judul utama dan judul section */
        h1,
        h2,
        h3,
        .font-luxury {
            font-family: var(--font-heading);
        }

        /* Hero title seperti pada gambar */
        .hero-title {
            font-family: var(--font-heading);
            font-size: clamp(3rem, 6vw, 5.5rem);
            font-weight: 600;
            line-height: 0.95;
            letter-spacing: -0.035em;
        }

        /* Judul section */
        .section-title {
            font-family: var(--font-heading);
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 600;
            line-height: 1.05;
            letter-spacing: -0.02em;
        }

        /* Logo tulisan */
        .brand-logo {
            font-family: var(--font-logo);
            font-size: 2rem;
            font-weight: 400;
            line-height: 1;
        }

        /* Navigasi */
        nav,
        .nav-link {
            font-family: var(--font-body);
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        /* Paragraf */
        p,
        .body-text {
            font-family: var(--font-body);
            font-size: 0.875rem;
            font-weight: 300;
            line-height: 1.7;
        }

        /* Label dan kategori */
        .category-label,
        .eyebrow {
            font-family: var(--font-body);
            font-size: 0.75rem;
            font-weight: 600;
        }

        [x-cloak] {
            display: none !important;
        }

        .hero-fade {
            opacity: 0;
            transform: translateY(20px);
            animation: heroFade 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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

        /* Sembunyikan scrollbar */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Gerakan dari kiri ke kanan.
   Setengah gap ditambahkan agar sambungan kedua set tepat. */
        @keyframes zee-marquee-ltr {
            from {
                transform: translate3d(calc(-50% - var(--marquee-half-gap)), 0, 0);
            }

            to {
                transform: translate3d(0, 0, 0);
            }
        }

        .mobile-marquee-wrapper {
            min-width: 0;
            max-width: 100%;
        }

        @media (max-width: 1023px) {
            .mobile-marquee-wrapper {
                overflow: hidden;
                -webkit-mask-image: linear-gradient(to right,
                        transparent,
                        #000 16px,
                        #000 calc(100% - 16px),
                        transparent);
                mask-image: linear-gradient(to right,
                        transparent,
                        #000 16px,
                        #000 calc(100% - 16px),
                        transparent);
            }

            .mobile-marquee-track {
                --marquee-duration: 25s;
                --marquee-half-gap: 0.5rem;
                /* Setengah gap-4 */

                display: flex;
                flex-wrap: nowrap;
                width: max-content;
                max-width: none;

                animation: zee-marquee-ltr var(--marquee-duration) linear infinite;

                will-change: transform;
            }

            .mobile-marquee-track>* {
                flex-shrink: 0;
            }

            .mobile-marquee-wrapper:focus-within .mobile-marquee-track {
                animation-play-state: paused !important;
            }
        }

        /* Menyesuaikan md:gap-6 */
        @media (min-width: 768px) and (max-width: 1023px) {
            .mobile-marquee-track {
                --marquee-half-gap: 0.75rem;
            }
        }

        /* Desktop tetap menggunakan slider */
        @media (min-width: 1024px) {
            .mobile-marquee-track {
                animation: none;
                transform: none;
                will-change: auto;
            }
        }

        /* Jika pengguna memilih pengurangan animasi:
   matikan autoplay, izinkan geser manual. */
        @media (max-width: 1023px) and (prefers-reduced-motion: reduce) {
            .mobile-marquee-wrapper {
                overflow-x: auto;
                -webkit-mask-image: none;
                mask-image: none;
            }

            .mobile-marquee-track {
                animation: none;
                transform: none;
                will-change: auto;
            }

            .mobile-marquee-track> :nth-child(n + 4) {
                display: none;
            }
        }

        /* Anchor tetap terlihat di bawah navbar tetap. */
        [id] {
            scroll-margin-top: 100px;
        }

        :focus-visible {
            outline: 2px solid #3B75FD;
            outline-offset: 4px;
        }

        #testimonials .mobile-marquee-wrapper {
            min-width: 0;
        }

        @media (min-width: 1024px) {
            #testimonials .mobile-marquee-track {
                width: 100%;
            }

            #testimonials .mobile-marquee-track> :nth-child(n + 4) {
                display: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto !important;
            }

            .hero-fade {
                animation: none;
                opacity: 1;
                transform: none;
            }

            #testimonials .mobile-marquee-track {
                animation: none;
                transform: none;
            }
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
    <main id="home" class="pt-[80px] md:pt-[100px] px-4 sm:px-6 lg:px-8 pb-8 overflow-hidden relative z-10">

        <!-- =========================================
             MOBILE: HERO SECTION (APP-LIKE DESIGN)
             Ditampilkan pada layar di bawah 768px
        ========================================== -->
        <section class="block md:hidden w-full max-w-[480px] mx-auto pb-10">
            <!-- Teks Utama & Deskripsi -->
            <div class="mb-5 pt-2">
                <p class="hero-fade text-[10px] uppercase tracking-[0.3em] text-z-blue font-extrabold mb-2.5">
                    Satu Aroma, Satu Cerita
                </p>
                <h1 class="hero-fade delay-100 font-luxury text-[2.5rem] leading-[1.1] tracking-tight text-z-navy mb-3">
                    <span class="block">Aroma yang</span>
                    <span class="block italic text-z-blue">Menceritakan</span>
                    <span class="block">Siapa Dirimu.</span>
                </h1>
                <p class="hero-fade delay-200 text-[13px] text-z-muted font-medium leading-relaxed pr-4">
                    Temukan lebih dari 100 pilihan aroma untuk pria, wanita, dan kebutuhan laundry dengan harga
                    terjangkau.
                </p>
            </div>

            <!-- Hero Card (Landscape Banner ala Aplikasi Mobile) -->
            <div
                class="hero-fade delay-300 relative bg-gradient-to-br from-z-soft/30 to-z-white rounded-[1.5rem] p-6 shadow-lg shadow-z-blue/5 border border-z-soft/40 overflow-hidden flex flex-row min-h-[200px]">

                <!-- Dekorasi Background -->
                <div
                    class="absolute top-0 right-0 w-48 h-48 bg-z-white rounded-full blur-2xl pointer-events-none transform translate-x-1/3 -translate-y-1/3">
                </div>

                <!-- Konten Kiri (Teks & Tombol) -->
                <div class="relative z-10 w-3/5 flex flex-col justify-center pr-2">
                    <h2 class="font-luxury text-[22px] text-z-navy font-bold leading-[1.2] mb-1">
                        Wewangian<br>Eksklusif
                    </h2>
                    <p class="text-[9.5px] font-extrabold uppercase tracking-widest text-z-muted mb-5">
                        SIGNATURE SCENT
                    </p>

                    <!-- Tombol Utama di dalam Card -->
                    <a href="#collections"
                        class="inline-flex items-center justify-center gap-2 bg-z-navy text-z-white text-[9px] font-bold uppercase tracking-widest py-2.5 px-4 rounded-xl w-max hover:bg-opacity-90 transition-all shadow-md shadow-z-navy/20">
                        Lihat Koleksi
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Konten Kanan (Product Image) -->
                <div
                    class="absolute bottom-0 right-[-5%] w-[45%] h-full flex items-center justify-center pointer-events-none pb-2 pt-4">
                    <!-- FOTO PRODUK -->
                    <img src="{{ asset('asset/product (1).png') }}" alt="Featured Perfume"
                        class="w-[85%] max-h-full object-contain drop-shadow-2xl z-10 mix-blend-multiply transform scale-110 translate-y-2">
                </div>
            </div>

            <!-- Action Button 2 (Hubungi Kami) -->
            <div class="hero-fade delay-300 mt-4">
                <a href="#contact-form"
                    class="w-full bg-z-white border border-z-soft/60 shadow-sm rounded-xl py-3.5 px-4 flex items-center justify-center gap-2 text-[11px] font-bold text-z-navy uppercase tracking-widest hover:bg-z-soft/10 transition-colors">
                    Hubungi Kami
                </a>
            </div>

        </section>

        <!-- =========================================
             TABLET & DESKTOP: HERO LENGKAP
             Ditampilkan mulai layar 768px
        ========================================== -->
        <section
            class="hidden md:flex max-w-[1380px] mx-auto rounded-[2.5rem] bg-z-white border border-z-soft/30 overflow-hidden shadow-2xl shadow-z-navy/5 relative min-h-[680px] lg:min-h-[720px] flex-col justify-center">

            <!-- Dekorasi Lingkaran Bias Cahaya (Agar background putih tidak terlihat kaku/mati) -->
            <div
                class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-z-soft opacity-20 blur-[100px] rounded-full pointer-events-none">
            </div>
            <div
                class="absolute bottom-[-10%] right-[-5%] w-[600px] h-[600px] bg-z-blue opacity-[0.05] blur-[120px] rounded-full pointer-events-none">
            </div>

            <div
                class="w-full relative z-10 px-10 lg:px-16 py-12 lg:py-0 flex flex-row items-center justify-between min-h-[680px] lg:min-h-[720px]">

                <!-- LEFT CONTENT -->
                <div class="w-full lg:w-1/2 flex flex-col justify-center text-left pt-10 lg:pt-0">

                    <p class="hero-fade text-[11px] uppercase tracking-[0.3em] text-z-blue font-extrabold mb-4">
                        Satu Aroma, Satu Cerita
                    </p>

                    <h1
                        class="hero-fade delay-100 font-luxury text-5xl lg:text-6xl xl:text-[4.5rem] leading-[1.1] tracking-tight text-z-navy mb-5 lg:mb-6">
                        <span class="block">Aroma yang</span>
                        <span class="block italic text-z-blue">Menceritakan</span>
                        <span class="block">Siapa Dirimu.</span>
                    </h1>

                    <p
                        class="hero-fade delay-200 text-[14px] lg:text-[15px] text-z-muted leading-relaxed font-medium max-w-[460px]">
                        Temukan lebih dari 100 pilihan aroma untuk pria, wanita, dan kebutuhan laundry. Dari karakter
                        segar hingga mewah, ZEEPERFUME menghadirkan wewangian berkualitas dengan harga terjangkau.
                    </p>

                    <!-- Tombol -->
                    <div
                        class="hero-fade delay-300 flex flex-row items-center justify-start gap-4 mt-8 lg:mt-10 mb-8 lg:mb-12">
                        <a href="#collections"
                            class="group h-12 px-8 rounded-full bg-z-blue text-z-white flex items-center justify-center gap-3 text-[11px] font-bold uppercase tracking-widest hover:bg-opacity-90 shadow-lg shadow-z-blue/30 transition-all transform active:scale-95 border border-z-blue">
                            Temukan Aromamu
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>

                        <a href="#contact-form"
                            class="group h-12 px-8 rounded-full bg-z-white border border-z-soft/80 text-z-navy flex items-center justify-center gap-2.5 text-[11px] font-bold uppercase tracking-widest hover:bg-z-soft/10 transition-all transform active:scale-95 shadow-sm">
                            Hubungi Kami
                        </a>
                    </div>
                </div>

                <!-- RIGHT CONTENT (Gambar & Hotspot) -->
                <div class="w-1/2 h-full relative flex justify-center items-center pointer-events-auto">

                    <!-- FOTO PRODUK DESKTOP -->
                    <img src="{{ asset('asset/product (1).png') }}" alt="Featured Perfume"
                        class="hero-fade delay-200 w-[60%] lg:w-[50%] max-h-[80%] object-contain drop-shadow-2xl z-10 transition-transform duration-700 hover:scale-105">

                    <!-- Hotspot 1 (Cap Botol) -->
                    <div
                        class="absolute top-[25%] right-[20%] lg:right-[25%] flex items-center flex-col-reverse gap-2 hero-fade delay-300 z-20">
                        <div class="w-[1px] h-6 lg:h-10 bg-z-soft border-l border-dashed border-z-muted"></div>
                        <div
                            class="bg-z-white/95 backdrop-blur-md text-[9px] font-bold px-3 py-1.5 rounded-lg shadow-md text-z-navy tracking-wide border border-z-soft/50">
                            Kualitas Premium
                        </div>
                        <div
                            class="w-8 h-8 rounded-full bg-z-soft/20 backdrop-blur border border-z-blue/30 flex items-center justify-center shadow-sm cursor-pointer hover:scale-110 transition-transform mt-1">
                            <div class="w-2 h-2 rounded-full bg-z-blue shadow-lg shadow-z-blue"></div>
                        </div>
                    </div>

                    <!-- Hotspot 2 (Isi Botol) -->
                    <div
                        class="absolute top-[60%] left-[15%] lg:left-[20%] flex items-center flex-row gap-2 lg:gap-3 hero-fade delay-300 z-20">
                        <div
                            class="bg-z-blue text-[9px] font-bold px-3 py-1.5 rounded-lg shadow-md text-z-white tracking-wide border border-z-blue">
                            Tahan Lama & Khas
                        </div>
                        <div class="w-4 lg:w-8 h-[1px] bg-z-soft border-t border-dashed border-z-muted"></div>
                        <div
                            class="w-8 h-8 rounded-full bg-z-blue/10 backdrop-blur border border-z-blue/30 flex items-center justify-center shadow-sm cursor-pointer hover:scale-110 transition-transform">
                            <div class="w-2 h-2 rounded-full bg-z-blue shadow-lg shadow-z-blue/50"></div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- ================= QUICK STATS BANNER ================= -->
    <section class="relative z-20 -mt-8 sm:-mt-16 md:-mt-24 px-4 sm:px-6 lg:px-8 mb-16">
        <div
            class="max-w-[1050px] mx-auto bg-z-white/90 backdrop-blur-2xl border border-z-soft/60 rounded-3xl md:rounded-[2.5rem] shadow-2xl shadow-z-navy/10 py-8 md:py-12 px-6 sm:px-12 relative overflow-hidden">
            <!-- Dekorasi Bias Cahaya Lembut -->
            <div
                class="absolute -top-24 -right-24 w-64 h-64 bg-z-blue opacity-[0.06] blur-[80px] rounded-full pointer-events-none">
            </div>
            <div
                class="absolute -bottom-24 -left-24 w-64 h-64 bg-z-soft opacity-[0.25] blur-[80px] rounded-full pointer-events-none">
            </div>

            <div
                class="grid grid-cols-1 md:grid-cols-3 text-center relative z-10 divide-y md:divide-y-0 md:divide-x divide-z-soft/40">

                <!-- Stat 1 -->
                <div class="flex flex-col items-center justify-center pt-0 pb-6 md:py-4 group">
                    <p
                        class="font-luxury text-5xl md:text-6xl font-medium text-z-navy mb-2 md:mb-3 group-hover:scale-105 transition-transform duration-500 ease-out">
                        02
                    </p>
                    <p class="text-[10px] md:text-[11px] font-bold uppercase tracking-[0.3em] text-z-muted mb-1.5">
                        Cabang Resmi
                    </p>
                    <p class="text-[12px] md:text-[13px] text-z-blue italic font-luxury tracking-wide">
                        Hadir lebih dekat dengan Anda
                    </p>
                </div>

                <!-- Stat 2 -->
                <div class="flex flex-col items-center justify-center py-6 md:py-4 group">
                    <p
                        class="font-luxury text-5xl md:text-6xl font-medium text-z-navy mb-2 md:mb-3 group-hover:scale-105 transition-transform duration-500 ease-out flex items-start justify-center">
                        500<span class="text-3xl md:text-4xl text-z-blue ml-1 font-light">+</span>
                    </p>
                    <p class="text-[10px] md:text-[11px] font-bold uppercase tracking-[0.3em] text-z-muted mb-1.5">
                        Varian Aroma
                    </p>
                    <p class="text-[12px] md:text-[13px] text-z-blue italic font-luxury tracking-wide">
                        Eksplorasi karakter tanpa batas
                    </p>
                </div>

                <!-- Stat 3 -->
                <div class="flex flex-col items-center justify-center pt-6 pb-0 md:py-4 group">
                    <!-- Teks dibuat responsif (text-4xl di mobile) agar 100.000 tidak mepet ke tepi layar -->
                    <p
                        class="font-luxury text-4xl sm:text-5xl md:text-6xl font-medium text-z-navy mb-2 md:mb-3 group-hover:scale-105 transition-transform duration-500 ease-out flex items-start justify-center">
                        100.000<span class="text-2xl sm:text-3xl md:text-4xl text-z-blue ml-1 font-light">+</span>
                    </p>
                    <p class="text-[10px] md:text-[11px] font-bold uppercase tracking-[0.3em] text-z-muted mb-1.5">
                        Produk Terjual
                    </p>
                    <p class="text-[12px] md:text-[13px] text-z-blue italic font-luxury tracking-wide">
                        Simbol kepercayaan pelanggan
                    </p>
                </div>

            </div>
        </div>
    </section>
    <!-- ================= COLLECTIONS SECTION (MOBILE-FIRST & ENHANCED TYPOGRAPHY) ================= -->
    <section id="collections" class="py-16 lg:py-24 bg-z-white border-y border-z-soft/30">
        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12 md:mb-20">
                <p class="text-[11px] md:text-xs font-bold uppercase tracking-[0.3em] text-z-blue mb-3 md:mb-4">
                    Discover Our Series</p>
                <h2 class="font-luxury text-4xl sm:text-5xl lg:text-6xl text-z-navy mb-4 md:mb-5">Temukan Koleksi
                    Favoritmu</h2>
                <p class="text-[14px] sm:text-base text-z-muted leading-relaxed font-medium px-2">
                    Dari aroma yang segar dan ringan hingga hangat dan berkarakter, jelajahi beragam koleksi ZEEPERFUME
                    dan temukan wangi yang paling mewakili dirimu.
                </p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

                <!-- 1. Lelabo (Large Card - Wide) -->
                <div
                    class="md:col-span-2 lg:row-span-2 bg-z-surface rounded-[2rem] p-6 sm:p-8 md:p-10 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[380px] lg:min-h-[660px]">
                    <!-- Top Badge -->
                    <div>
                        <span
                            class="inline-block px-4 py-2 bg-z-white/80 backdrop-blur-md rounded-full text-[10px] md:text-[11px] font-extrabold uppercase tracking-[0.2em] text-z-blue shadow-sm border border-white/60">
                            Modern & Berkelas
                        </span>
                    </div>

                    <!-- Content Split Row -->
                    <div class="grid grid-cols-12 gap-4 items-end mt-6 h-full">
                        <!-- Left: Text Column (60%) -->
                        <div class="col-span-7 sm:col-span-8 flex flex-col justify-end h-full">
                            <h3
                                class="font-luxury text-4xl sm:text-5xl lg:text-6xl text-z-navy mb-3 md:mb-4 leading-none">
                                Lelabo<br>Series
                            </h3>
                            <p
                                class="text-[13px] md:text-[15px] text-z-navy/75 font-medium leading-relaxed mb-6 lg:mb-8 pr-2">
                                Aroma modern dengan karakter kuat dan berkelas, cocok untuk menemani aktivitas
                                sehari-hari maupun momen istimewa.
                            </p>
                        </div>
                        <!-- Right: Image Column (40%) -->
                        <div
                            class="col-span-5 sm:col-span-4 flex justify-center items-end h-[180px] sm:h-[260px] lg:h-[360px]">
                            <img src="{{ asset('asset/zbotol (3).png') }}" alt="Lelabo Series"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-2xl">
                        </div>
                    </div>
                </div>

                <!-- 2. Shimmer Series (Small Card) -->
                <div
                    class="col-span-1 bg-z-surface rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[300px] md:min-h-[320px]">
                    <!-- Top Badge -->
                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-z-white/80 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-z-blue shadow-sm border border-white/60">
                            Segar & Feminin
                        </span>
                    </div>

                    <!-- Content Split Row -->
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <!-- Left Text -->
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3 class="font-luxury text-3xl md:text-[2rem] text-z-navy mb-2 leading-tight">
                                Shimmer<br>Series</h3>
                            <p class="text-[12px] md:text-[13px] text-z-navy/75 font-medium leading-relaxed mb-4">
                                Wewangian segar dengan sentuhan feminin dan efek shimmer istimewa.
                            </p>

                        </div>
                        <!-- Right Image -->
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/zbotol (1).png') }}" alt="Shimmer Series"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-lg">
                        </div>
                    </div>
                </div>

                <!-- 3. Casa Series (Tall Card) -->
                <div
                    class="col-span-1 md:col-span-2 lg:col-span-1 lg:row-span-2 bg-z-surface rounded-[2rem] p-6 sm:p-8 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[340px] lg:min-h-[660px]">
                    <!-- Top Badge -->
                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-z-white/80 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-z-blue shadow-sm border border-white/60">
                            Timeless & Nyaman
                        </span>
                    </div>

                    <!-- Image Center for Tall Card -->
                    <div class="my-auto py-6 flex justify-center items-center h-[200px] md:h-[220px] lg:h-[280px]">
                        <img src="{{ asset('asset/zbotol (2).png') }}" alt="Casa Series"
                            class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-2xl">
                    </div>

                    <!-- Bottom Text Area -->
                    <div>
                        <h3 class="font-luxury text-4xl lg:text-[2.5rem] text-z-navy mb-2 leading-none">Casa<br>Series
                        </h3>
                        <p class="text-[13px] md:text-[14px] text-z-navy/75 font-medium leading-relaxed mb-5">
                            Aroma timeless yang nyaman dan stylish, pilihan tepat untuk kesan berkelas.
                        </p>

                    </div>
                </div>

                <!-- 4. Exclusive Series (Small Card) -->
                <div
                    class="col-span-1 bg-z-surface rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[300px] md:min-h-[320px]">
                    <!-- Top Badge -->
                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-z-white/80 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-z-blue shadow-sm border border-white/60">
                            Mewah & Percaya Diri
                        </span>
                    </div>

                    <!-- Content Split Row -->
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <!-- Left Text -->
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3 class="font-luxury text-3xl md:text-[2rem] text-z-navy mb-2 leading-tight">
                                Exclusive<br>Series</h3>
                            <p class="text-[12px] md:text-[13px] text-z-navy/75 font-medium leading-relaxed mb-4">
                                Koleksi aroma pilihan mewah untuk melengkapi setiap penampilan.
                            </p>

                        </div>
                        <!-- Right Image -->
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/zbotoll (3).png') }}" alt="Exclusive Series"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-lg">
                        </div>
                    </div>
                </div>

                <!-- 5. Prada Series (Wide Card) -->
                <div
                    class="md:col-span-2 lg:row-span-1 bg-z-surface rounded-[2rem] p-6 sm:p-8 md:p-10 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[340px] md:min-h-[360px]">
                    <!-- Top Badge -->
                    <div>
                        <span
                            class="inline-block px-4 py-2 bg-z-white/80 backdrop-blur-md rounded-full text-[10px] md:text-[11px] font-extrabold uppercase tracking-[0.18em] text-z-blue shadow-sm border border-white/60">
                            Modern & Refined
                        </span>
                    </div>

                    <!-- Content Split Row -->
                    <div class="grid grid-cols-12 gap-5 items-end mt-4">
                        <div class="col-span-7 sm:col-span-8 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-4xl sm:text-5xl lg:text-[3.5rem] text-z-navy mb-3 leading-none">
                                Prada<br>Series</h3>
                            <p
                                class="text-[13px] md:text-[15px] text-z-navy/75 font-medium leading-relaxed mb-5 md:mb-6 pr-2">
                                Perpaduan aroma modern yang menghadirkan kesan percaya diri dalam berbagai kesempatan.
                            </p>

                        </div>
                        <div
                            class="col-span-5 sm:col-span-4 flex justify-center items-end h-[160px] sm:h-[200px] lg:h-[220px]">
                            <img src="{{ asset('asset/botol (5).png') }}" alt="Prada Series"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-2xl">
                        </div>
                    </div>
                </div>

                <!-- 6. Luxury Series (Small Card) -->
                <div
                    class="col-span-1 bg-z-surface rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[300px] md:min-h-[320px]">
                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-z-white/80 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-z-blue shadow-sm border border-white/60">
                            Kaya & Memikat
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3 class="font-luxury text-3xl md:text-[2rem] text-z-navy mb-2 leading-tight">
                                Luxury<br>Series</h3>
                            <p class="text-[12px] md:text-[13px] text-z-navy/75 font-medium leading-relaxed mb-4">
                                Dirancang khusus untuk menciptakan kesan aroma yang sulit dilupakan.
                            </p>

                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/botol (4).png') }}" alt="Luxury Series"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-lg">
                        </div>
                    </div>
                </div>

                <!-- 7. Jo Malon Series (Small Card) -->
                <div
                    class="col-span-1 bg-z-surface rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[300px] md:min-h-[320px]">
                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-z-white/80 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-z-blue shadow-sm border border-white/60">
                            Fresh & Bersih
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3 class="font-luxury text-3xl md:text-[2rem] text-z-navy mb-2 leading-tight">Jo
                                Malon<br>Series</h3>
                            <p class="text-[12px] md:text-[13px] text-z-navy/75 font-medium leading-relaxed mb-4">
                                Aroma fresh dan refined untuk Anda yang menyukai kesederhanaan.
                            </p>
                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/zbotoll (3).png') }}" alt="Jo Malon Series"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-lg">
                        </div>
                    </div>
                </div>

                <!-- 8. Twilly Series (Wide Card) -->
                <div
                    class="md:col-span-2 lg:row-span-1 bg-z-surface rounded-[2rem] p-6 sm:p-8 md:p-10 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[340px] md:min-h-[360px]">
                    <div>
                        <span
                            class="inline-block px-4 py-2 bg-z-white/80 backdrop-blur-md rounded-full text-[10px] md:text-[11px] font-extrabold uppercase tracking-[0.18em] text-z-blue shadow-sm border border-white/60">
                            Playful & Ceria
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-5 items-end mt-4">
                        <div class="col-span-7 sm:col-span-8 flex flex-col justify-end">
                            <h3
                                class="font-luxury text-4xl sm:text-5xl lg:text-[3.5rem] text-z-navy mb-3 leading-none">
                                Twilly<br>Series</h3>
                            <p
                                class="text-[13px] md:text-[15px] text-z-navy/75 font-medium leading-relaxed mb-5 md:mb-6 pr-2">
                                Wewangian playful dengan sentuhan feminin, menghadirkan energi segar sepanjang hari.
                            </p>
                            <a href="#contact-form"
                                class="inline-flex items-center gap-2 text-[11px] md:text-xs font-bold uppercase tracking-widest text-z-navy hover:text-z-blue transition-colors w-max">
                                Pesan Sekarang <i class="bi bi-arrow-right text-lg"></i>
                            </a>
                        </div>
                        <div
                            class="col-span-5 sm:col-span-4 flex justify-center items-end h-[160px] sm:h-[200px] lg:h-[220px]">
                            <img src="{{ asset('asset/zbotol.png') }}" alt="Twilly Series"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-2xl">
                        </div>
                    </div>
                </div>

                <!-- 9. Roll On Series (Small Card) -->
                <div
                    class="col-span-1 bg-z-surface rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[300px] md:min-h-[320px]">
                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-z-white/80 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-z-blue shadow-sm border border-white/60">
                            Praktis & Ringkas
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3 class="font-luxury text-3xl md:text-[2rem] text-z-navy mb-2 leading-tight">Roll
                                On<br>Series</h3>
                            <p class="text-[12px] md:text-[13px] text-z-navy/75 font-medium leading-relaxed mb-4">
                                Kemasan praktis yang mudah dibawa dan digunakan kapan saja.
                            </p>
                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/zbotoll (2).png') }}" alt="Roll On Series"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-lg">
                        </div>
                    </div>
                </div>

                <!-- 10. Parfum Laundry (Small Card) -->
                <div
                    class="col-span-1 bg-z-surface rounded-[2rem] p-6 sm:p-7 flex flex-col justify-between overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500 border border-z-soft/30 min-h-[300px] md:min-h-[320px]">
                    <div>
                        <span
                            class="inline-block px-3 py-1.5 bg-z-white/80 backdrop-blur-md rounded-full text-[9px] md:text-[10px] font-extrabold uppercase tracking-[0.18em] text-z-blue shadow-sm border border-white/60">
                            Aroma Bersih
                        </span>
                    </div>
                    <div class="grid grid-cols-12 gap-3 items-end mt-4">
                        <div class="col-span-7 flex flex-col justify-end">
                            <h3 class="font-luxury text-3xl md:text-[2rem] text-z-navy mb-2 leading-tight">
                                Parfum<br>Laundry</h3>
                            <p class="text-[12px] md:text-[13px] text-z-navy/75 font-medium leading-relaxed mb-4">
                                Pewangi laundry yang menghadirkan aroma bersih dan segar pada pakaian.
                            </p>
                        </div>
                        <div class="col-span-5 flex justify-center items-end h-[140px] md:h-[150px]">
                            <img src="{{ asset('asset/zbotoll (1).png') }}" alt="Parfum Laundry"
                                class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500 drop-shadow-lg">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= ABOUT & PHILOSOPHY (TENTANG KAMI) ================= -->
    <section id="about" class="py-16 md:py-20 lg:py-28 bg-z-white border-y border-z-soft/40 overflow-hidden">
        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">
                <!-- KONTEN TEKS (KIRI) -->
                <div class="lg:col-span-7 flex flex-col justify-center order-2 lg:order-1">
                    <!-- Header & Deskripsi -->
                    <div class="mb-8">
                        <p class="text-[10px] md:text-[11px] font-bold uppercase tracking-[0.3em] text-z-blue mb-3">
                            Tentang ZEEPERFUME
                        </p>
                        <h2
                            class="font-luxury text-4xl sm:text-5xl lg:text-[3.5rem] leading-[1.15] tracking-tight text-z-navy mb-5 md:mb-6">
                            Lebih dari Sekadar <span class="italic text-z-blue block sm:inline">Wewangian.</span>
                        </h2>
                        <div class="space-y-4">
                            <p class="text-[13px] md:text-[14.5px] text-z-muted leading-relaxed font-medium">
                                Berawal pada tahun 2023, ZEEPERFUME hadir dari keinginan sederhana: membantu setiap
                                orang menemukan aroma yang sesuai dengan karakter, kebutuhan, dan ceritanya. Kami
                                menyediakan beragam pilihan parfum untuk penggunaan pribadi hingga kebutuhan usaha,
                                termasuk parfum retail, grosir, dan pewangi laundry.
                            </p>
                            <p class="text-[13px] md:text-[14.5px] text-z-muted leading-relaxed font-medium">
                                Dengan pilihan aroma yang terus berkembang, harga yang terjangkau, serta pelayanan yang
                                ramah dan profesional, kami berkomitmen menjadi pilihan tepercaya bagi pelanggan dan
                                mitra yang ingin tumbuh bersama.
                            </p>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="mb-10 lg:mb-12">
                        <a href="#contact"
                            class="inline-flex items-center justify-center gap-2 h-12 md:h-14 px-8 rounded-full bg-z-navy text-z-white text-[10px] md:text-[11px] font-bold uppercase tracking-widest hover:bg-opacity-90 shadow-lg shadow-z-navy/20 transition-all transform active:scale-95">
                            Contact ZEEPERFUME
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                    <!-- Layout Visi & Misi (Cards) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5 pt-8 border-t border-z-soft/40">

                        <!-- Card Visi -->
                        <div
                            class="bg-z-white p-6 md:p-7 rounded-[1.5rem] border border-z-soft/50 shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
                            <h3
                                class="font-bold text-z-navy mb-3 flex items-center gap-2.5 text-[13px] md:text-[14px] uppercase tracking-wide">
                                <div
                                    class="w-8 h-8 rounded-[10px] bg-z-blue/10 text-z-blue flex items-center justify-center shrink-0">
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
                            <p class="text-[12px] md:text-[13px] text-z-muted leading-relaxed font-medium">
                                Menjadi brand parfum lokal unggulan yang dikenal melalui kualitas, inovasi, dan
                                integritas, serta memberikan dampak positif bagi pelanggan, mitra, karyawan, dan
                                masyarakat.
                            </p>
                        </div>

                        <!-- Card Misi -->
                        <div
                            class="bg-z-white p-6 md:p-7 rounded-[1.5rem] border border-z-soft/50 shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
                            <h3
                                class="font-bold text-z-navy mb-3 flex items-center gap-2.5 text-[13px] md:text-[14px] uppercase tracking-wide">
                                <div
                                    class="w-8 h-8 rounded-[10px] bg-z-blue/10 text-z-blue flex items-center justify-center shrink-0">
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
                                class="text-[12px] md:text-[12.5px] text-z-muted leading-relaxed font-medium space-y-3">
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-check2-circle text-z-blue mt-0.5 text-base flex-shrink-0"></i>
                                    <span>Menghadirkan wewangian berkualitas dengan karakter aroma istimewa dan harga
                                        yang terjangkau.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-check2-circle text-z-blue mt-0.5 text-base flex-shrink-0"></i>
                                    <span>Memberikan pelayanan yang jujur, ramah, profesional, dan berorientasi pada
                                        kepuasan pelanggan.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-check2-circle text-z-blue mt-0.5 text-base flex-shrink-0"></i>
                                    <span>Membuka peluang kemitraan bagi reseller, agen, dan distributor untuk tumbuh
                                        bersama.</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <i class="bi bi-check2-circle text-z-blue mt-0.5 text-base flex-shrink-0"></i>
                                    <span>Membangun bisnis yang tidak hanya bertumbuh, tetapi juga memberikan manfaat
                                        bagi lingkungan.</span>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <!-- KONTEN GAMBAR (KANAN) -->
                <div
                    class="lg:col-span-5 order-1 lg:order-2 w-full h-[350px] sm:h-[450px] lg:h-full lg:min-h-[750px] bg-z-soft/20 rounded-[2rem] md:rounded-[2.5rem] border border-z-soft/50 flex items-center justify-center relative overflow-hidden shadow-sm lg:sticky lg:top-24">

                    <!-- FOTO TOKO -->
                    <img src="{{ asset('asset/Toko Zeeperfume dari dalam.jpg') }}" alt="Tentang ZEEPERFUME"
                        class="object-cover w-full h-full absolute inset-0 hover:scale-105 transition-transform duration-700">

                    <!-- Gradient Agar Teks Badge Terbaca -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-z-navy/60 via-transparent to-transparent pointer-events-none">
                    </div>

                    <!-- Overlay Badge Floating -->
                    <div
                        class="absolute bottom-6 sm:bottom-8 left-6 sm:left-8 right-6 sm:right-8 bg-z-white/95 backdrop-blur-md p-5 rounded-2xl shadow-xl border border-z-white/50 text-center transform hover:-translate-y-1 transition-transform duration-300 cursor-default">
                        <div
                            class="w-10 h-10 md:w-12 md:h-12 mx-auto bg-z-blue/10 text-z-blue rounded-full flex items-center justify-center mb-3">
                            <i class="bi bi-shop text-lg md:text-xl"></i>
                        </div>
                        <p
                            class="text-[10px] md:text-[11px] font-extrabold uppercase tracking-[0.15em] text-z-navy mb-1.5">
                            Peluang Kemitraan
                        </p>
                        <p
                            class="text-[12px] md:text-[13px] text-z-muted font-medium leading-snug max-w-[80%] mx-auto">
                            Tersedia Harga Khusus Grosir, Agen & Reseller
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ================= WHY CHOOSE US (CLEAN LIGHT LAYOUT) ================= -->
    <section id="why-us" class="relative py-16 md:py-20 lg:py-28 bg-z-surface overflow-hidden z-10">
        <!-- Dekorasi Background -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[80%] h-full bg-z-blue opacity-[0.03] blur-[100px] rounded-full pointer-events-none -z-10">
        </div>

        <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16">
                <p class="text-[10px] md:text-[11px] font-bold uppercase tracking-[0.3em] text-z-blue mb-3 md:mb-4">
                    Mengapa Memilih ZEEPERFUME?
                </p>
                <h2 class="font-luxury text-3xl md:text-4xl lg:text-5xl text-z-navy tracking-wide mb-4">
                    Wewangian Berkualitas, Pelayanan Sepenuh Hati
                </h2>
                <p class="text-[13px] md:text-[14px] text-z-muted leading-relaxed font-medium px-2">
                    Kami percaya pengalaman memilih parfum harus terasa mudah dan menyenangkan. Karena itu, ZEEPERFUME
                    menghadirkan pilihan lengkap, harga kompetitif, serta pelayanan yang membantu Anda menemukan aroma
                    terbaik.
                </p>
            </div>

            <!-- Features Container (Meniru "White Bar" pada Referensi) -->
            <div
                class="bg-z-white rounded-[2rem] p-6 sm:p-10 lg:p-12 shadow-xl shadow-z-navy/5 border border-z-soft/40">

                <!-- Menggunakan Flex Wrap agar 5 item bisa terdistribusi seimbang & rapi -->
                <div class="flex flex-wrap justify-center gap-8 lg:gap-10">

                    <!-- Card 1: Kualitas Terjaga -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-2rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-z-blue/10 text-z-blue flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-z-blue group-hover:text-z-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-z-navy mb-1.5 tracking-wide">Kualitas
                                Terjaga</h3>
                            <p class="text-[12px] md:text-[12.5px] text-z-muted leading-relaxed font-medium">
                                Kami memilih produk dengan standar kualitas yang baik agar setiap aroma memberikan
                                pengalaman yang memuaskan.
                            </p>
                        </div>
                    </div>

                    <!-- Card 2: Pilihan Selalu Berkembang -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-2rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-z-blue/10 text-z-blue flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-z-blue group-hover:text-z-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-z-navy mb-1.5 tracking-wide">Pilihan
                                Selalu Berkembang</h3>
                            <p class="text-[12px] md:text-[12.5px] text-z-muted leading-relaxed font-medium">
                                Koleksi kami terus diperbarui mengikuti kebutuhan dan selera pelanggan yang beragam.
                            </p>
                        </div>
                    </div>

                    <!-- Card 3: Harga Kompetitif -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-2rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-z-blue/10 text-z-blue flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-z-blue group-hover:text-z-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-z-navy mb-1.5 tracking-wide">Harga
                                Kompetitif</h3>
                            <p class="text-[12px] md:text-[12.5px] text-z-muted leading-relaxed font-medium">
                                Nikmati pilihan wewangian berkualitas dengan harga yang bersahabat untuk pembelian
                                retail maupun grosir.
                            </p>
                        </div>
                    </div>

                    <!-- Card 4: Pelayanan Tepercaya -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-2rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-z-blue/10 text-z-blue flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-z-blue group-hover:text-z-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-z-navy mb-1.5 tracking-wide">Pelayanan
                                Tepercaya</h3>
                            <p class="text-[12px] md:text-[12.5px] text-z-muted leading-relaxed font-medium">
                                Tim kami siap melayani dengan ramah, cepat, jujur, dan profesional sejak konsultasi
                                hingga pembelian.
                            </p>
                        </div>
                    </div>

                    <!-- Card 5: Peluang Tumbuh Bersama -->
                    <div
                        class="flex flex-col sm:flex-row items-start gap-4 text-left basis-full md:basis-[calc(50%-2rem)] lg:basis-[calc(33.333%-2.5rem)] group">
                        <div
                            class="w-12 h-12 rounded-[1rem] bg-z-blue/10 text-z-blue flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-z-blue group-hover:text-z-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[14px] md:text-[15px] text-z-navy mb-1.5 tracking-wide">Peluang
                                Tumbuh Bersama</h3>
                            <p class="text-[12px] md:text-[12.5px] text-z-muted leading-relaxed font-medium">
                                Kami membuka kesempatan kemitraan bagi reseller, agen, dan distributor yang ingin
                                mengembangkan usaha wewangian.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- ================= TESTIMONIAL SECTION (MOBILE MARQUEE & DESKTOP SLIDER) ================= -->
    <section id="testimonials" class="py-16 md:py-24 bg-z-surface border-y border-z-soft/30 overflow-hidden"
        x-data="{
            isPressed: false,
            isHovered: false,
            scrollNext() {
                $refs.slider.scrollBy({ left: $refs.slider.firstElementChild.getBoundingClientRect().width + (parseFloat(getComputedStyle($refs.slider).columnGap) || 0), behavior: 'smooth' });
            },
            scrollPrev() {
                $refs.slider.scrollBy({ left: -($refs.slider.firstElementChild.getBoundingClientRect().width + (parseFloat(getComputedStyle($refs.slider).columnGap) || 0)), behavior: 'smooth' });
            }
        }">
        <div class="max-w-[1380px] mx-auto px-0 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-center">

                <!-- KIRI: Header & Navigasi -->
                <div class="w-full px-6 lg:px-0 lg:w-[35%] flex flex-col justify-center text-center lg:text-left z-10">
                    <div class="flex items-center justify-center lg:justify-start gap-3 mb-4">
                        <div class="hidden lg:block w-8 h-[2px] bg-z-blue"></div>
                        <p class="text-[10px] md:text-[11px] font-bold uppercase tracking-[0.3em] text-z-blue">
                            Cerita Pelanggan
                        </p>
                    </div>

                    <h2
                        class="font-luxury text-4xl sm:text-5xl lg:text-[3.5rem] leading-[1.1] tracking-tight text-z-navy mb-2 lg:mb-6">
                        Aroma Favorit Mereka,<br class="hidden lg:block">
                        <span class="italic text-z-blue">Mungkin Juga Favoritmu.</span>
                    </h2>

                    <!-- Tombol Navigasi Desktop -->
                    <div class="hidden lg:flex gap-3 mt-6">
                        <button type="button" aria-label="Testimoni sebelumnya" @click="scrollPrev"
                            class="w-12 h-12 rounded-full border-2 border-z-navy flex items-center justify-center text-z-navy hover:bg-z-navy hover:text-z-white transition-all transform active:scale-95 bg-transparent">
                            <!-- SVG Arrow Left -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button type="button" aria-label="Testimoni berikutnya" @click="scrollNext"
                            class="w-12 h-12 rounded-full border-2 border-z-navy flex items-center justify-center text-z-navy hover:bg-z-navy hover:text-z-white transition-all transform active:scale-95 bg-z-white shadow-sm">
                            <!-- SVG Arrow Right -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- KANAN: Track Testimoni -->
                <!-- Wrapper: jeda selama sentuhan atau hover mouse dengan mengambil data dari x-data utama di atas -->
                <div class="w-full lg:w-[65%] relative mobile-marquee-wrapper px-4 lg:px-0"
                    @pointerdown="isPressed = true" @pointerup.window="isPressed = false"
                    @pointercancel.window="isPressed = false"
                    @pointerenter="isHovered = ($event.pointerType === 'mouse')" @pointerleave="isHovered = false"
                    @blur.window="isPressed = false; isHovered = false">

                    <!-- Overlay blur di ujung kanan untuk indikasi scroll Desktop -->
                    <div
                        class="hidden lg:block absolute top-0 right-0 w-24 h-full bg-gradient-to-l from-z-surface to-transparent z-10 pointer-events-none">
                    </div>

                    <!-- Track: melanjutkan animasi dari posisi terakhir setelah dilepas -->
                    <div x-ref="slider"
                        class="flex mobile-marquee-track lg:overflow-x-auto lg:snap-x lg:snap-mandatory hide-scrollbar gap-4 md:gap-6 pb-8 pt-4 lg:pr-24"
                        :style="(isPressed || isHovered) ? 'animation-play-state: paused;' : 'animation-play-state: running;'">

                        <!-- ================= SET 1 ================= -->
                        <!-- Card 1: Lusi -->
                        <div
                            class="shrink-0 w-[280px] md:w-[340px] lg:snap-center bg-z-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-z-navy/5 relative border border-z-soft/40 flex flex-col group hover:-translate-y-2 transition-transform duration-500 cursor-pointer">
                            <i
                                class="bi bi-quote absolute top-6 right-6 text-6xl text-z-soft/20 z-0 group-hover:text-z-blue/10 transition-colors"></i>
                            <div class="flex items-center gap-1 text-z-rating mb-5 text-sm relative z-10">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                                <span class="ml-2 text-z-navy font-bold text-xs">5.0</span>
                            </div>
                            <p
                                class="text-[13px] md:text-[14px] text-z-navy/80 font-medium leading-relaxed mb-8 relative z-10 flex-grow">
                                "Cocok banget! Wanginya sesuai dengan yang saya suka, sampai nggak mau pindah ke lain
                                hati."
                            </p>
                            <div class="flex items-center gap-3 relative z-10 border-t border-z-soft/40 pt-5">
                                <div
                                    class="w-10 h-10 rounded-full bg-z-blue/10 flex items-center justify-center text-z-blue font-black text-lg">
                                    L</div>
                                <div>
                                    <h4 class="font-bold text-[13px] text-z-navy">Lusi</h4>
                                    <p class="text-[9px] text-z-muted uppercase tracking-widest font-bold">Pelanggan
                                        Setia</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Siti Rahma -->
                        <div
                            class="shrink-0 w-[280px] md:w-[340px] lg:snap-center bg-z-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-z-navy/5 relative border border-z-soft/40 flex flex-col group hover:-translate-y-2 transition-transform duration-500 cursor-pointer">
                            <i
                                class="bi bi-quote absolute top-6 right-6 text-6xl text-z-soft/20 z-0 group-hover:text-z-blue/10 transition-colors"></i>
                            <div class="flex items-center gap-1 text-z-rating mb-5 text-sm relative z-10">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                                <span class="ml-2 text-z-navy font-bold text-xs">5.0</span>
                            </div>
                            <p
                                class="text-[13px] md:text-[14px] text-z-navy/80 font-medium leading-relaxed mb-8 relative z-10 flex-grow">
                                "Varian Nagita wanginya enak banget. Baru pertama coba dan langsung suka dengan
                                aromanya."
                            </p>
                            <div class="flex items-center gap-3 relative z-10 border-t border-z-soft/40 pt-5">
                                <div
                                    class="w-10 h-10 rounded-full bg-z-blue/10 flex items-center justify-center text-z-blue font-black text-lg">
                                    S</div>
                                <div>
                                    <h4 class="font-bold text-[13px] text-z-navy">Siti Rahma</h4>
                                    <p class="text-[9px] text-z-muted uppercase tracking-widest font-bold">Pelanggan
                                        Baru</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Yaya -->
                        <div
                            class="shrink-0 w-[280px] md:w-[340px] lg:snap-center bg-z-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-z-navy/5 relative border border-z-soft/40 flex flex-col group hover:-translate-y-2 transition-transform duration-500 cursor-pointer">
                            <i
                                class="bi bi-quote absolute top-6 right-6 text-6xl text-z-soft/20 z-0 group-hover:text-z-blue/10 transition-colors"></i>
                            <div class="flex items-center gap-1 text-z-rating mb-5 text-sm relative z-10">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                                <span class="ml-2 text-z-navy font-bold text-xs">5.0</span>
                            </div>
                            <p
                                class="text-[13px] md:text-[14px] text-z-navy/80 font-medium leading-relaxed mb-8 relative z-10 flex-grow">
                                "Wanginya enak, produknya rapi, dan aromanya tahan lama. Dipakai dari pagi pun masih
                                terasa. Recommended!"
                            </p>
                            <div class="flex items-center gap-3 relative z-10 border-t border-z-soft/40 pt-5">
                                <div
                                    class="w-10 h-10 rounded-full bg-z-blue/10 flex items-center justify-center text-z-blue font-black text-lg">
                                    Y</div>
                                <div>
                                    <h4 class="font-bold text-[13px] text-z-navy">Yaya</h4>
                                    <p class="text-[9px] text-z-muted uppercase tracking-widest font-bold">Verified
                                        Buyer</p>
                                </div>
                            </div>
                        </div>

                        <!-- ================= SET 2 (DUPLIKAT AGAR LOOPING TIDAK PUTUS DI MOBILE) ================= -->
                        <div
                            class="shrink-0 w-[280px] md:w-[340px] lg:snap-center bg-z-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-z-navy/5 relative border border-z-soft/40 flex flex-col group hover:-translate-y-2 transition-transform duration-500 cursor-pointer">
                            <i
                                class="bi bi-quote absolute top-6 right-6 text-6xl text-z-soft/20 z-0 group-hover:text-z-blue/10 transition-colors"></i>
                            <div class="flex items-center gap-1 text-z-rating mb-5 text-sm relative z-10">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                                <span class="ml-2 text-z-navy font-bold text-xs">5.0</span>
                            </div>
                            <p
                                class="text-[13px] md:text-[14px] text-z-navy/80 font-medium leading-relaxed mb-8 relative z-10 flex-grow">
                                "Cocok banget! Wanginya sesuai dengan yang saya suka, sampai nggak mau pindah ke lain
                                hati."
                            </p>
                            <div class="flex items-center gap-3 relative z-10 border-t border-z-soft/40 pt-5">
                                <div
                                    class="w-10 h-10 rounded-full bg-z-blue/10 flex items-center justify-center text-z-blue font-black text-lg">
                                    L</div>
                                <div>
                                    <h4 class="font-bold text-[13px] text-z-navy">Lusi</h4>
                                    <p class="text-[9px] text-z-muted uppercase tracking-widest font-bold">Pelanggan
                                        Setia</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="shrink-0 w-[280px] md:w-[340px] lg:snap-center bg-z-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-z-navy/5 relative border border-z-soft/40 flex flex-col group hover:-translate-y-2 transition-transform duration-500 cursor-pointer">
                            <i
                                class="bi bi-quote absolute top-6 right-6 text-6xl text-z-soft/20 z-0 group-hover:text-z-blue/10 transition-colors"></i>
                            <div class="flex items-center gap-1 text-z-rating mb-5 text-sm relative z-10">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                                <span class="ml-2 text-z-navy font-bold text-xs">5.0</span>
                            </div>
                            <p
                                class="text-[13px] md:text-[14px] text-z-navy/80 font-medium leading-relaxed mb-8 relative z-10 flex-grow">
                                "Varian Nagita wanginya enak banget. Baru pertama coba dan langsung suka dengan
                                aromanya."
                            </p>
                            <div class="flex items-center gap-3 relative z-10 border-t border-z-soft/40 pt-5">
                                <div
                                    class="w-10 h-10 rounded-full bg-z-blue/10 flex items-center justify-center text-z-blue font-black text-lg">
                                    S</div>
                                <div>
                                    <h4 class="font-bold text-[13px] text-z-navy">Siti Rahma</h4>
                                    <p class="text-[9px] text-z-muted uppercase tracking-widest font-bold">Pelanggan
                                        Baru</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="shrink-0 w-[280px] md:w-[340px] lg:snap-center bg-z-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-z-navy/5 relative border border-z-soft/40 flex flex-col group hover:-translate-y-2 transition-transform duration-500 cursor-pointer">
                            <i
                                class="bi bi-quote absolute top-6 right-6 text-6xl text-z-soft/20 z-0 group-hover:text-z-blue/10 transition-colors"></i>
                            <div class="flex items-center gap-1 text-z-rating mb-5 text-sm relative z-10">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                                <span class="ml-2 text-z-navy font-bold text-xs">5.0</span>
                            </div>
                            <p
                                class="text-[13px] md:text-[14px] text-z-navy/80 font-medium leading-relaxed mb-8 relative z-10 flex-grow">
                                "Wanginya enak, produknya rapi, dan aromanya tahan lama. Dipakai dari pagi pun masih
                                terasa. Recommended!"
                            </p>
                            <div class="flex items-center gap-3 relative z-10 border-t border-z-soft/40 pt-5">
                                <div
                                    class="w-10 h-10 rounded-full bg-z-blue/10 flex items-center justify-center text-z-blue font-black text-lg">
                                    Y</div>
                                <div>
                                    <h4 class="font-bold text-[13px] text-z-navy">Yaya</h4>
                                    <p class="text-[9px] text-z-muted uppercase tracking-widest font-bold">Verified
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
    <section id="contact-form" class="py-16 md:py-24 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-[700px] mx-auto">

            <!-- Section Header -->
            <div class="text-center mb-10 md:mb-12">
                <p class="text-[10px] md:text-[11px] uppercase tracking-[0.3em] text-z-blue font-extrabold mb-3">
                    Mulai Cerita Aromamu
                </p>
                <h2 class="font-luxury text-3xl sm:text-4xl text-z-navy mb-4">
                    Hubungi ZEEPERFUME
                </h2>
                <p class="text-z-muted text-sm md:text-[15px] font-medium max-w-[400px] mx-auto">
                    Isi kebutuhanmu, lalu lanjutkan dan kirim pesan melalui WhatsApp.
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-z-white rounded-[2rem] p-6 sm:p-10 shadow-xl shadow-z-navy/5 border border-z-soft/50">
                <form id="waForm" onsubmit="sendToWhatsApp(event)" class="flex flex-col gap-5 md:gap-6">

                    <!-- Input Nama -->
                    <div>
                        <label for="wa_name"
                            class="block text-[11px] font-bold text-z-navy uppercase tracking-widest mb-2">Nama Lengkap
                            / Brand</label>
                        <input type="text" id="wa_name" name="name" autocomplete="name" maxlength="120"
                            required placeholder="Contoh: Sarah / Toko Wangi"
                            class="w-full h-12 px-4 rounded-xl border border-z-soft bg-z-surface text-z-navy text-sm font-medium focus:outline-none focus:border-z-blue focus:ring-1 focus:ring-z-blue transition-all">
                    </div>

                    <!-- Input Kebutuhan (Dropdown) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                        <div>
                            <label for="wa_service"
                                class="block text-[11px] font-bold text-z-navy uppercase tracking-widest mb-2">Tujuan
                                Menghubungi</label>
                            <select id="wa_service" required
                                class="w-full h-12 px-4 rounded-xl border border-z-soft bg-z-surface text-z-navy text-sm font-medium focus:outline-none focus:border-z-blue focus:ring-1 focus:ring-z-blue transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Pilih Kebutuhan --</option>
                                <option value="Konsultasi Aroma Pribadi">Konsultasi Aroma Pribadi</option>
                                <option value="Pemesanan Parfum Retail">Pemesanan Parfum Retail (Satuan)</option>
                                <option value="Pemesanan Parfum Grosir / Agen">Pemesanan Grosir / Agen (Harga Khusus)
                                </option>
                                <option value="Pemesanan Parfum Laundry">Pemesanan Parfum Laundry</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <!-- Input Mengetahui Zeeperfume Dari Mana (Dropdown Baru) -->
                        <div>
                            <label for="wa_source"
                                class="block text-[11px] font-bold text-z-navy uppercase tracking-widest mb-2">Tahu
                                Kami Dari Mana?</label>
                            <select id="wa_source" required
                                class="w-full h-12 px-4 rounded-xl border border-z-soft bg-z-surface text-z-navy text-sm font-medium focus:outline-none focus:border-z-blue focus:ring-1 focus:ring-z-blue transition-all appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Pilih Sumber --</option>
                                <option value="Instagram">Instagram</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Rekomendasi Teman / Keluarga">Rekomendasi Teman / Keluarga</option>
                                <option value="Pencarian Google">Pencarian Google</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <!-- Input Pesan -->
                    <div>
                        <label for="wa_message"
                            class="block text-[11px] font-bold text-z-navy uppercase tracking-widest mb-2">Detail Pesan
                            / Pertanyaan</label>
                        <textarea id="wa_message" name="message" maxlength="2000" required rows="4"
                            placeholder="Ceritakan detail parfum yang kamu cari atau tanyakan apa saja di sini..."
                            class="w-full p-4 rounded-xl border border-z-soft bg-z-surface text-z-navy text-sm font-medium focus:outline-none focus:border-z-blue focus:ring-1 focus:ring-z-blue transition-all resize-y"></textarea>
                    </div>

                    <!-- Submit Button (Desain Profesional, tanpa ikon WA) -->
                    <div class="mt-2">
                        <button type="submit"
                            class="group w-full h-14 rounded-xl bg-z-navy text-z-white flex items-center justify-center gap-3 text-[12px] font-bold uppercase tracking-widest hover:bg-opacity-90 shadow-lg shadow-z-navy/30 transition-all transform active:scale-[0.98]">
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
    <footer id="contact" class="bg-z-soft/10 pt-16 pb-8 border-t border-z-soft/40">
        <div class="max-w-[1180px] mx-auto px-6 sm:px-8 lg:px-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-6 mb-12">

                <div class="md:col-span-4">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="ZEEPERFUME Logo"
                            class="h-8 w-auto">
                        <span class="text-sm font-bold uppercase tracking-widest text-z-navy">ZEEPERFUME</span>
                    </div>
                    <p class="text-xs text-z-muted leading-relaxed mb-6 font-medium pr-4">
                        Menjadi brand parfum lokal unggulan yang mengedepankan kualitas,
                        inovasi, dan integritas. Open
                        Distributor & Reseller Resmi.
                    </p>
                    <div class="flex items-center gap-3">
                        <a aria-label="Instagram ZEEPERFUME" href="https://instagram.com/zeeperfume" target="_blank"
                            rel="noopener noreferrer"
                            class="w-8 h-8 rounded-full bg-z-white border border-z-soft/60 flex items-center justify-center text-z-muted hover:bg-z-blue hover:text-z-white hover:border-z-blue transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">

                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />

                            </svg>
                        </a>
                        <a aria-label="TikTok ZEEPERFUME" href="https://tiktok.com/@ZeePerfumePontianak"
                            target="_blank" rel="noopener noreferrer"
                            class="w-8 h-8 rounded-full bg-z-white border border-z-soft/60 flex items-center justify-center text-z-muted hover:bg-z-blue hover:text-z-white hover:border-z-blue transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">

                                <path
                                    d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 003.5 15.7a6.33 6.33 0 0010.86 4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1.54-.56z" />

                            </svg>
                        </a>
                        <a aria-label="Shopee ZEEPERFUME" href="https://shopee.co.id/zeeperfume" target="_blank"
                            rel="noopener noreferrer"
                            class="w-8 h-8 rounded-full bg-z-white border border-z-soft/60 flex items-center justify-center text-z-muted hover:bg-z-blue hover:text-z-white hover:border-z-blue transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="md:col-span-4">
                    <h3 class="text-xs font-black text-z-navy uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-z-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                        Cabang Pusat
                    </h3>
                    <p class="text-xs text-z-muted leading-relaxed font-medium mb-2">Jalan Tanjung
                        Raya II, Saigon,
                        Kec. Pontianak Timur, Kota Pontianak, Kalimantan Barat</p>
                    <a href="https://maps.app.goo.gl/sMSEqxmpigTNRUm18" target="_blank" rel="noopener noreferrer"
                        class="text-[10px] font-bold text-z-blue hover:underline uppercase tracking-wider">Buka di
                        Google Maps &rarr;</a>
                </div>

                <div class="md:col-span-4">
                    <h3 class="text-xs font-black text-z-navy uppercase tracking-widest mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-z-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                        Cabang Jungkat
                    </h3>
                    <p class="text-xs text-z-muted leading-relaxed font-medium mb-2">Jalan Raya
                        Jungkat (Depan Masjid
                        Sahibul Kahfi), Kec. Jongkat, Kabupaten Mempawah, Kalbar</p>
                    <a href="https://maps.app.goo.gl/pjTf5m5dpiEeKD6E9" target="_blank" rel="noopener noreferrer"
                        class="text-[10px] font-bold text-z-blue hover:underline uppercase tracking-wider mb-5 block">Buka
                        di Google Maps &rarr;</a>

                    <div class="pt-4 border-t border-z-soft/40 flex flex-col gap-2">
                        <div class="flex items-center gap-2 text-xs text-z-muted font-bold">
                            <svg class="w-4 h-4 text-z-blue" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            0896-9386-5056 / 0852-5056-6867
                        </div>
                        <div class="flex items-center gap-2 text-xs text-z-muted font-bold">
                            <svg class="w-4 h-4 text-z-blue" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Buka Setiap Hari (08.00 - 22.00 WIB)
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-z-soft/40 pt-6 text-center">
                <p class="text-[10px] text-z-muted font-bold tracking-widest uppercase">
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
