<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zee Perfume | Signature Collection</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js untuk fungsionalitas Slider/Carousel Otomatis -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts: Playfair Display (Luxury Serif) & Plus Jakarta Sans (Clean Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F2F0EC; }
        .font-luxury { font-family: 'Playfair Display', serif; }
        [x-cloak] { display: none !important; }

        /* Animasi Lembut untuk Teks Masuk */
        .hero-fade {
            animation: heroFade 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }

        @keyframes heroFade {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Animasi Melayang (Floating) Khusus untuk Botol */
        .product-float {
            animation: productFloat 6s ease-in-out infinite;
        }

        @keyframes productFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        /* Tekstur Titik Halus di Background */
        .texture-overlay {
            background-image: radial-gradient(rgba(0, 0, 0, 0.02) 1px, transparent 1px);
            background-size: 8px 8px;
        }
    </style>
</head>

<body class="text-[#1C1D21] antialiased overflow-x-hidden selection:bg-[#CC9863] selection:text-white">

    <!-- ================= FIXED NAVBAR ================= -->
    <header class="fixed top-0 inset-x-0 z-50 px-4 sm:px-6 lg:px-8 pt-5">
        <nav class="max-w-[1380px] mx-auto bg-white/90 backdrop-blur-xl border border-white/50 shadow-[0_4px_30px_rgba(0,0,0,0.03)] rounded-2xl transition-all">
            <div class="h-[64px] px-6 lg:px-8 flex items-center justify-between">

                <!-- LOGO -->
                <a href="#home" class="flex items-center gap-3 shrink-0">
                    <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="Zee Perfume Logo" class="h-8 w-auto object-contain">
                    <span class="hidden sm:block text-[13px] font-bold uppercase tracking-widest text-gray-900 mt-0.5">
                        Zee Perfume
                    </span>
                </a>

                <!-- DESKTOP NAV -->
                <div class="hidden lg:flex items-center gap-8">
                    <a href="#home" class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#1C1D21] hover:text-[#CC9863] transition-colors">Beranda</a>
                    <a href="#signature" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-[#CC9863] transition-colors">Signature</a>
                    <a href="#story" class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-[#CC9863] transition-colors">Our Story</a>
                </div>

                <!-- RIGHT ACTION -->
                <div class="flex items-center gap-3">
                    <a href="#signature" class="hidden sm:flex items-center gap-2 h-9 px-5 rounded-lg bg-[#1C1D21] text-white text-[9px] font-extrabold uppercase tracking-widest hover:bg-[#CC9863] transition-colors shadow-sm">
                        Discover
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>

                    <!-- MOBILE MENU BTN -->
                    <button id="menu-toggle" type="button" class="lg:hidden w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center text-gray-600 hover:text-[#CC9863] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- MOBILE NAV CONTENT -->
            <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-100 px-4 py-4">
                <div class="flex flex-col gap-1">
                    <a href="#home" class="px-4 py-3 rounded-xl bg-gray-50 text-xs font-bold text-gray-900">Beranda</a>
                    <a href="#signature" class="px-4 py-3 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-50">Signature</a>
                    <a href="#story" class="px-4 py-3 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-50">Our Story</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- ================= HERO SECTION ================= -->
    <main id="home" class="pt-[90px] px-4 sm:px-6 lg:px-8 pb-8">

        <!-- MAIN CONTAINER -->
        <section class="max-w-[1380px] mx-auto min-h-[calc(100vh-120px)] bg-[#FCFBF8] rounded-[2.5rem] overflow-hidden shadow-[0_10px_50px_rgba(0,0,0,0.03)] border border-white relative texture-overlay flex flex-col justify-center">

            <!-- HERO GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[600px] lg:min-h-[680px] w-full items-center pt-16 lg:pt-0">

                <!-- LEFT CONTENT -->
                <div class="lg:col-span-5 px-6 sm:px-10 lg:pl-16 lg:pr-8 relative z-20 flex flex-col justify-center order-2 lg:order-1 pb-10 lg:pb-0">
                    <div class="w-full max-w-[480px]">

                        <!-- Eyebrow -->
                        <p class="hero-fade text-[10px] uppercase tracking-[0.25em] text-[#CC9863] font-extrabold mb-5">
                            Zee Signature Collection
                        </p>

                        <!-- Luxury Title -->
                        <h1 class="hero-fade delay-100 font-luxury text-5xl sm:text-6xl lg:text-7xl xl:text-[5.5rem] leading-[1.05] tracking-tight text-[#1C1D21] mb-6">
                            <span class="block italic">Reimagined</span>
                            <span class="block text-[#CC9863]">Elegance.</span>
                        </h1>

                        <!-- Description -->
                        <p class="hero-fade delay-200 text-[13px] sm:text-sm text-gray-500 leading-relaxed font-medium max-w-[400px]">
                            Sebuah mahakarya aroma yang mendefinisikan ulang kemewahan. Diciptakan dari 100% konsentrat
                            murni untuk menghadirkan karakter yang tenang, modern, dan tak terlupakan.
                        </p>

                        <!-- Notes Tags -->
                        <div class="hero-fade delay-200 flex flex-wrap gap-2 mt-8">
                            <span class="px-4 py-2 rounded-md bg-white border border-gray-100 text-[9px] uppercase tracking-[0.15em] text-gray-600 font-bold shadow-sm">Timeless</span>
                            <span class="px-4 py-2 rounded-md bg-white border border-gray-100 text-[9px] uppercase tracking-[0.15em] text-gray-600 font-bold shadow-sm">Authentic</span>
                            <span class="px-4 py-2 rounded-md bg-white border border-gray-100 text-[9px] uppercase tracking-[0.15em] text-gray-600 font-bold shadow-sm">Long Lasting</span>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="hero-fade delay-200 flex flex-wrap items-center gap-4 mt-10">
                            <a href="#signature" class="group h-12 px-8 rounded-full bg-[#1C1D21] text-white flex items-center gap-3 text-[10px] font-extrabold uppercase tracking-widest hover:bg-[#CC9863] shadow-lg shadow-black/10 transition-all transform active:scale-95">
                                Cek Product Sekarang
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- RIGHT CONTENT (CAROUSEL) -->
                <div class="lg:col-span-7 relative h-[450px] lg:h-full order-1 lg:order-2 flex justify-center items-center"
                    x-data="{
                        activeSlide: 0,
                        timer: null,
                        images: [
                            '{{ asset('asset/product (1).png') }}',
                            '{{ asset('asset/product (2).png') }}',
                            '{{ asset('asset/product (3).png') }}'
                        ],
                        next() { this.activeSlide = (this.activeSlide + 1) % this.images.length },
                        prev() { this.activeSlide = (this.activeSlide - 1 + this.images.length) % this.images.length },
                        start() { this.timer = setInterval(() => this.next(), 5000) },
                        stop() { clearInterval(this.timer) }
                    }" x-init="start()" @mouseenter="stop()" @mouseleave="start()">

                    <div class="absolute inset-4 lg:inset-y-6 lg:left-0 lg:right-6 rounded-[2rem] bg-[#EAE5DF] overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="font-luxury italic text-[25vw] lg:text-[14rem] text-white/40 select-none">Zee</span>
                        </div>
                        <div class="absolute -bottom-[20%] left-1/2 -translate-x-1/2 w-[300px] h-[300px] lg:w-[500px] lg:h-[500px] rounded-full bg-[#CC9863] opacity-90 blur-[2px]">
                        </div>
                    </div>

                    <div class="absolute inset-0 flex items-end justify-center pb-8 lg:pb-12 z-10 pointer-events-none">
                        <template x-for="(img, index) in images" :key="index">
                            <img x-show="activeSlide === index" x-transition:enter="transition ease-out duration-700"
                                x-transition:enter-start="opacity-0 translate-y-8"
                                x-transition:enter-end="opacity-100 translate-y-0" :src="img"
                                alt="Zee Perfume Product"
                                class="product-float h-[340px] sm:h-[400px] lg:h-[540px] object-contain drop-shadow-[0_25px_35px_rgba(0,0,0,0.2)] mix-blend-multiply">
                        </template>
                    </div>

                    <div class="absolute bottom-10 lg:bottom-12 right-10 lg:right-16 z-30 flex items-center gap-4 bg-white/80 backdrop-blur-md px-4 py-2.5 rounded-full border border-white shadow-sm">
                        <button @click="prev()" class="text-gray-400 hover:text-[#CC9863] transition-colors focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <div class="flex items-center gap-1.5">
                            <template x-for="(img, index) in images" :key="index">
                                <button @click="activeSlide = index" class="h-1.5 rounded-full transition-all duration-300 focus:outline-none" :class="activeSlide === index ? 'w-5 bg-[#1C1D21]' : 'w-1.5 bg-gray-300'"></button>
                            </template>
                        </div>
                        <button @click="next()" class="text-gray-400 hover:text-[#CC9863] transition-colors focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- FEATURE STRIP BOTTOM -->
            <div class="absolute bottom-0 inset-x-0 border-t border-white/50 bg-white/30 backdrop-blur-sm px-6 py-4 hidden sm:block">
                <div class="max-w-4xl mx-auto flex justify-between items-center">
                    <div class="flex items-center gap-2 text-gray-500"><span class="text-[#CC9863] text-xs">✦</span><span class="text-[9px] font-extrabold uppercase tracking-widest">Premium Quality</span></div>
                    <div class="flex items-center gap-2 text-gray-500"><span class="text-[#CC9863] text-xs">✦</span><span class="text-[9px] font-extrabold uppercase tracking-widest">100% Extract</span></div>
                    <div class="flex items-center gap-2 text-gray-500"><span class="text-[#CC9863] text-xs">✦</span><span class="text-[9px] font-extrabold uppercase tracking-widest">Long Lasting</span></div>
                </div>
            </div>

        </section>
    </main>

    <!-- ================= TOP PRODUCTS SECTION (API BINDING) ================= -->
    <section id="signature" class="py-20 lg:py-28 bg-white border-b border-gray-100" x-data="topProductsComponent()" x-init="fetchTopProducts()">
        <div class="max-w-[1380px] mx-auto px-6 sm:px-8 lg:px-10">

            <!-- Header Section -->
            <div class="text-center mb-16">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#CC9863] mb-4">Discover The Finest</p>
                <h2 class="font-luxury text-4xl sm:text-5xl font-bold text-gray-900">Bestselling Collection</h2>
            </div>

            <!-- Loading Skeleton -->
            <div x-show="isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <template x-for="i in 4">
                    <div class="animate-pulse">
                        <div class="bg-gray-200 rounded-2xl aspect-[4/5] mb-6"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4 mx-auto mb-3"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/2 mx-auto mb-4"></div>
                        <div class="h-5 bg-gray-200 rounded w-1/3 mx-auto"></div>
                    </div>
                </template>
            </div>

            <!-- Products Grid -->
            <div x-show="!isLoading" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                <template x-if="products.length === 0">
                    <div class="col-span-full text-center text-gray-400 py-10 italic">
                        Koleksi sedang diperbarui. Silakan kembali lagi nanti.
                    </div>
                </template>

                <!-- Mapping Data Product -->
                <template x-for="product in products" :key="product.id || Math.random()">
                    <div class="group cursor-pointer">
                        <div class="relative bg-[#F8F9FA] rounded-2xl aspect-[4/5] flex items-center justify-center p-6 mb-6 overflow-hidden transition-all group-hover:bg-[#F2F0EC]">
                            <!-- Gambar Sementara Merujuk Ke Folder Asset Lokal -->
                            <img :src="product.local_image"
                                :alt="product.nama_produk"
                                class="object-contain w-full h-full mix-blend-multiply group-hover:scale-105 transition-transform duration-700">

                            <div class="absolute top-4 left-4">
                                <span class="bg-white text-gray-900 text-[9px] font-extrabold px-3 py-1 rounded-full uppercase tracking-widest shadow-sm">Top Rated</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-gray-900 text-base line-clamp-1" x-text="product.nama_produk || 'Zee Perfume'"></h3>
                            <p class="text-[11px] text-gray-500 font-semibold mt-1" x-text="product.nama_varian || 'Signature Edition'"></p>
                            <p class="font-black text-[#CC9863] mt-3 text-lg" x-text="formatRp(product.harga_jual || 0)"></p>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </section>

    <!-- ================= SIGNATURE PHILOSOPHY (STORY) ================= -->
    <section id="story" class="py-20 lg:py-28 bg-[#F2F0EC]">
        <div class="max-w-[1180px] mx-auto px-6 sm:px-8 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.25em] text-[#CC9863] mb-4">Our
                        Philosophy</p>
                    <h2 class="font-luxury text-4xl sm:text-5xl lg:text-6xl leading-[1.1] text-gray-900 mb-6">
                        More Than <br><span class="italic text-[#CC9863]">A Scent.</span>
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium mb-8">
                        Kami melihat parfum bukan sekadar aroma, tetapi sebagai bagian dari bagaimana seseorang hadir,
                        dikenali dan diingat. Zee Perfume lahir dari keinginan untuk membuat aroma yang dekat dengan
                        karakter Anda.
                    </p>
                    <a href="#signature"
                        class="inline-flex items-center gap-2 border-b border-[#1C1D21] pb-1 text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#1C1D21] hover:text-[#CC9863] hover:border-[#CC9863] transition-all">
                        Explore Collection
                    </a>
                </div>
                <div class="w-full aspect-[4/3] bg-[#EAE5DF] rounded-3xl border border-white/50 flex items-center justify-center relative overflow-hidden shadow-sm">
                    <span class="font-luxury italic text-2xl text-gray-400">Visual Story Placeholder</span>
                </div>
            </div>
        </div>
    </section>

  <!-- ================= FOOTER ================= -->
    <footer class="bg-[#FAFAFA] border-t border-gray-200 pt-16 pb-8 mt-10">
        <div class="max-w-[1180px] mx-auto px-6 sm:px-8 lg:px-10">

            <!-- TOP: Link Columns -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-y-10 gap-x-6 mb-12">

                <!-- Column 1 -->
                <div>
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-5">Koleksi Kami</h3>
                    <div class="space-y-3.5">
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Signature Series</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Fresh & Floral</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Woody Notes</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Best Sellers</a>
                    </div>
                </div>

                <!-- Column 2 -->
                <div>
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-5">Solusi Layanan</h3>
                    <div class="space-y-3.5">
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Konsultasi Aroma</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Kustomisasi Bingkisan</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Reseller Program</a>
                    </div>
                </div>

                <!-- Column 3 -->
                <div>
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-5">Tentang Zee</h3>
                    <div class="space-y-3.5">
                        <a href="#story" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Filosofi Kami</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Jurnal & Blog</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Bahan Berkualitas</a>
                    </div>
                </div>

                <!-- Column 4 -->
                <div>
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-5">Bantuan</h3>
                    <div class="space-y-3.5">
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Cara Pemesanan</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Kebijakan Pengiriman</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Lacak Pesanan</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">FAQ</a>
                    </div>
                </div>

                <!-- Column 5 -->
                <div>
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-5">Perusahaan</h3>
                    <div class="space-y-3.5">
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Hubungi Kami</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Syarat & Ketentuan</a>
                        <a href="#" class="block text-[13px] font-semibold text-gray-500 hover:text-[#CC9863] transition-colors">Kebijakan Privasi</a>
                    </div>
                </div>

            </div>

            <!-- MIDDLE: Contact Info Separated by Lines -->
            <div class="border-y border-gray-200 py-8 mb-10">
                <div class="flex flex-wrap justify-center items-center gap-6 sm:gap-10 md:gap-16">

                    <!-- Phone -->
                    <div class="flex items-center gap-3 group cursor-default">
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-[#CC9863] group-hover:border-[#CC9863] transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-700">0811 2222 3333</span>
                    </div>

                    <!-- Email -->
                    <div class="flex items-center gap-3 group cursor-default">
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-[#CC9863] group-hover:border-[#CC9863] transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-700">hello@zeeperfume.id</span>
                    </div>

                    <!-- Address -->
                    <div class="flex items-center gap-3 group cursor-default">
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-[#CC9863] group-hover:border-[#CC9863] transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span class="text-[13px] font-bold text-gray-700">Pontianak,Kalimantan Barat</span>
                    </div>

                </div>
            </div>

            <!-- BOTTOM: Logo, Desc, Socials, Copyright -->
            <div class="flex flex-col items-center text-center">

                <!-- Center Logo -->
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center p-3 shadow-md border border-gray-100 mb-5">
                    <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="Zee Perfume Logo" class="w-full h-full object-contain">
                </div>

                <!-- Short Desc -->
                <p class="max-w-xl text-sm font-medium text-gray-500 leading-relaxed mb-8">
                    Sebuah mahakarya aroma yang mendefinisikan ulang kemewahan. Zee Perfume hadir memberikan pengalaman tak terlupakan melalui koleksi wewangian premium.
                </p>

                <!-- Social Icons -->
                <div class="flex items-center gap-4 mb-8">
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-[#CC9863] hover:text-white transition-all shadow-sm">
                        <!-- Facebook Icon -->
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-[#CC9863] hover:text-white transition-all shadow-sm">
                        <!-- Instagram Icon -->
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-[#CC9863] hover:text-white transition-all shadow-sm">
                        <!-- Twitter/X Icon -->
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-[#CC9863] hover:text-white transition-all shadow-sm">
                        <!-- YouTube Icon -->
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>

                <!-- Copyright -->
                <p class="text-[10px] text-gray-400 font-bold tracking-widest uppercase">
                    © {{ date('Y') }} Zee Perfume. All rights reserved.
                </p>

            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
            });
        }

        // Script untuk Fetch Top Products API
        function topProductsComponent() {
            return {
                isLoading: true,
                products: [],

                formatRp(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(angka);
                },

                async fetchTopProducts() {
                    try {
                        const response = await fetch('/api/top-products');

                        if(response.ok) {
                            const result = await response.json();
                            let dataProduk = result.data || result;

                            // Map local image dynamically based on array index (1, 2, 3)
                            // as a temporary fallback mechanism for the view
                            this.products = dataProduk.map((item, index) => {
                                let imgId = (index % 3) + 1; // akan mengulang 1, 2, 3, 1, 2...
                                return {
                                    ...item,
                                    local_image: `{{ asset('asset/product (') }}${imgId}{{ ').png' }}`
                                };
                            });

                        } else {
                            throw new Error('Gagal mengambil data dari server');
                        }
                    } catch (error) {
                        console.error('Error fetching top products:', error);

                        // ===== DUMMY DATA JIKA API BELUM SIAP =====
                        this.products = [
                            { id: 1, nama_produk: 'Baccarat Rouge 540', nama_varian: 'Extrait de Parfum - 50ml', harga_jual: 250000, local_image: "{{ asset('asset/product (1).png') }}" },
                            { id: 2, nama_produk: 'Black Opium', nama_varian: 'Eau de Parfum - 30ml', harga_jual: 150000, local_image: "{{ asset('asset/product (2).png') }}" },
                            { id: 3, nama_produk: 'Savage Dior', nama_varian: 'Eau de Toilette - 50ml', harga_jual: 200000, local_image: "{{ asset('asset/product (3).png') }}" },
                            { id: 4, nama_produk: 'Jo Malone English Pear', nama_varian: 'Cologne - 30ml', harga_jual: 125000, local_image: "{{ asset('asset/product (1).png') }}" },
                        ];
                        // ==========================================
                    } finally {
                        setTimeout(() => {
                            this.isLoading = false;
                        }, 800);
                    }
                }
            }
        }
    </script>
</body>

</html>
