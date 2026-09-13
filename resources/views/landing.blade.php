<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zee Perfume | Signature Collection</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Google Fonts: Playfair Display (Luxury Serif) & Plus Jakarta Sans (Clean Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F2F0EC;
        }

        .font-luxury {
            font-family: 'Playfair Display', serif;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Premium Fade Animation */
        .hero-fade {
            animation: heroFade 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        @keyframes heroFade {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Subtle Floating Effect */
        .product-float {
            animation: productFloat 6s ease-in-out infinite;
        }

        @keyframes productFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        /* Elegant Noise Texture */
        .texture-overlay {
            background-image: radial-gradient(rgba(0, 0, 0, 0.02) 1px, transparent 1px);
            background-size: 8px 8px;
        }
    </style>
</head>

<body class="text-[#1C1D21] antialiased overflow-x-hidden selection:bg-[#CC9863] selection:text-white">

    <!-- ================= FIXED NAVBAR ================= -->
    <header class="fixed top-0 inset-x-0 z-50 px-4 sm:px-6 lg:px-8 pt-5">
        <nav
            class="max-w-[1380px] mx-auto bg-white/90 backdrop-blur-xl border border-white/50 shadow-[0_4px_30px_rgba(0,0,0,0.03)] rounded-2xl transition-all">
            <div class="h-[64px] px-6 lg:px-8 flex items-center justify-between">

                <!-- LOGO -->
                <a href="#home" class="flex items-center gap-3 shrink-0">
                    <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="Zee Perfume"
                        class="h-8 w-auto object-contain">
                    <span class="hidden sm:block text-[13px] font-bold uppercase tracking-widest text-gray-900 mt-0.5">
                        Zee Perfume
                    </span>
                </a>

                <!-- DESKTOP NAV -->
                <div class="hidden lg:flex items-center gap-8">
                    <a href="#home"
                        class="text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#1C1D21] hover:text-[#CC9863] transition-colors">Beranda</a>
                    <a href="#signature"
                        class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-[#CC9863] transition-colors">Signature</a>
                    <a href="#story"
                        class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-[#CC9863] transition-colors">Our
                        Story</a>
                </div>

                <!-- RIGHT ACTION -->
                <div class="flex items-center gap-3">
                    <a href="#signature"
                        class="hidden sm:flex items-center gap-2 h-9 px-5 rounded-lg bg-[#1C1D21] text-white text-[9px] font-extrabold uppercase tracking-widest hover:bg-[#CC9863] transition-colors">
                        Discover
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>

                    <!-- MOBILE MENU BTN -->
                    <button id="menu-toggle" type="button"
                        class="lg:hidden w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center text-gray-600 hover:text-[#CC9863] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- MOBILE NAV CONTENT -->
            <div id="mobile-menu" class="hidden lg:hidden border-t border-gray-100 px-4 py-4">
                <div class="flex flex-col gap-1">
                    <a href="#home"
                        class="px-4 py-3 rounded-xl bg-gray-50 text-xs font-bold text-gray-900">Beranda</a>
                    <a href="#signature"
                        class="px-4 py-3 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-50">Signature</a>
                    <a href="#story" class="px-4 py-3 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-50">Our
                        Story</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- ================= HERO SECTION ================= -->
    <main id="home" class="pt-[90px] px-4 sm:px-6 lg:px-8 pb-8">

        <!-- MAIN CONTAINER WITH ALPINE CAROUSEL -->
        <section
            class="max-w-[1380px] mx-auto min-h-[calc(100vh-120px)] bg-[#FCFBF8] rounded-[2.5rem] overflow-hidden shadow-[0_10px_50px_rgba(0,0,0,0.03)] border border-white relative texture-overlay flex flex-col justify-center"
            x-data="{
                active: 0,
                timer: null,
                slides: [{
                        eyebrow: 'Zee Signature No. 01',
                        title: 'A Quiet',
                        accent: 'Statement.',
                        desc: 'Aroma yang tidak perlu berteriak untuk meninggalkan kesan. Dirancang untuk karakter yang tenang, modern dan percaya diri.',
                        notes: ['Warm Amber', 'Soft Woods', 'Velvet Musk'],
                        image: '{{ asset('asset/product (1).png') }}'
                    },
                    {
                        eyebrow: 'Zee Signature No. 02',
                        title: 'Made To Be',
                        accent: 'Remembered.',
                        desc: 'Komposisi yang bersih namun dalam. Membuka dengan kesegaran lembut lalu berubah menjadi jejak aroma yang hangat dan elegan.',
                        notes: ['Fresh Citrus', 'White Floral', 'Clean Musk'],
                        image: '{{ asset('asset/product (2).png') }}'
                    },
                    {
                        eyebrow: 'Zee Signature No. 03',
                        title: 'Elegance,',
                        accent: 'Reimagined.',
                        desc: 'Interpretasi modern tentang kemewahan: minimal, personal dan timeless. Sebuah fragrance yang terasa dekat dengan karakter pemakainya.',
                        notes: ['Spiced Woods', 'Amber', 'Smooth Vanilla'],
                        image: '{{ asset('asset/product (3).png') }}'
                    }
                ],
                next() { this.active = (this.active + 1) % this.slides.length },
                prev() { this.active = (this.active - 1 + this.slides.length) % this.slides.length },
                start() { this.timer = setInterval(() => this.next(), 6000) },
                stop() { clearInterval(this.timer) }
            }" x-init="start()" @mouseenter="stop()" @mouseleave="start()">

            <!-- TOP META TAGS -->
            <div
                class="absolute top-0 inset-x-0 px-8 lg:px-14 pt-10 flex items-center justify-between z-20 pointer-events-none">
                <div class="flex items-center gap-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#CC9863]"></span>
                    <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-gray-400">Eau de Parfum ·
                        Signature</span>
                </div>
                <span class="hidden md:block text-[9px] font-bold uppercase tracking-[0.2em] text-gray-400">Est. 2024 /
                    Indonesia</span>
            </div>

            <!-- HERO GRID -->
            <div
                class="grid grid-cols-1 lg:grid-cols-12 min-h-[600px] lg:min-h-[680px] w-full items-center pt-16 lg:pt-0">

                <!-- LEFT CONTENT (TEXT) -->
                <div
                    class="lg:col-span-5 px-6 sm:px-10 lg:pl-16 lg:pr-8 relative z-20 flex flex-col justify-center order-2 lg:order-1 pb-10 lg:pb-0">
                    <div class="w-full max-w-[480px]" x-key="active">

                        <!-- Eyebrow -->
                        <p class="hero-fade text-[10px] uppercase tracking-[0.25em] text-[#CC9863] font-extrabold mb-5"
                            x-text="slides[active].eyebrow"></p>

                        <!-- Luxury Title -->
                        <h1
                            class="hero-fade delay-100 font-luxury text-5xl sm:text-6xl lg:text-7xl xl:text-[5.5rem] leading-[1.05] tracking-tight text-[#1C1D21] mb-6">
                            <span class="block italic" x-text="slides[active].title"></span>
                            <span class="block text-[#CC9863]" x-text="slides[active].accent"></span>
                        </h1>

                        <!-- Description -->
                        <p class="hero-fade delay-200 text-[13px] sm:text-sm text-gray-500 leading-relaxed font-medium max-w-[400px]"
                            x-text="slides[active].desc"></p>

                        <!-- Notes Tags -->
                        <div class="hero-fade delay-200 flex flex-wrap gap-2 mt-8">
                            <template x-for="note in slides[active].notes">
                                <span
                                    class="px-4 py-2 rounded-md bg-white border border-gray-100 text-[9px] uppercase tracking-[0.15em] text-gray-600 font-bold shadow-sm"
                                    x-text="note"></span>
                            </template>
                        </div>

                        <!-- CTA Buttons -->
                        <div class="hero-fade delay-200 flex flex-wrap items-center gap-4 mt-10">
                            <a href="#signature"
                                class="group h-12 px-8 rounded-full bg-[#1C1D21] text-white flex items-center gap-3 text-[10px] font-extrabold uppercase tracking-widest hover:bg-[#CC9863] shadow-lg shadow-black/10 transition-all">
                                Discover
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- RIGHT CONTENT (VISUAL / CAROUSEL) -->
                <div
                    class="lg:col-span-7 relative h-[450px] lg:h-full order-1 lg:order-2 flex justify-center items-center">

                    <!-- Background Aesthetic Geometry -->
                    <div
                        class="absolute inset-4 lg:inset-y-6 lg:left-0 lg:right-6 rounded-[2rem] bg-[#EAE5DF] overflow-hidden">

                        <!-- Subtle Giant Watermark -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span
                                class="font-luxury italic text-[25vw] lg:text-[14rem] text-white/40 select-none">Zee</span>
                        </div>

                        <!-- Elegant Gold Circle Base -->
                        <div
                            class="absolute -bottom-[20%] left-1/2 -translate-x-1/2 w-[300px] h-[300px] lg:w-[500px] lg:h-[500px] rounded-full bg-[#CC9863] opacity-90 blur-[2px]">
                        </div>
                    </div>

                    <!-- Dynamic Product Image -->
                    <div class="absolute inset-0 flex items-end justify-center pb-8 lg:pb-12 z-10 pointer-events-none">
                        <template x-for="(slide, index) in slides" :key="index">
                            <img x-show="active === index" x-transition:enter="transition ease-out duration-700"
                                x-transition:enter-start="opacity-0 translate-y-8"
                                x-transition:enter-end="opacity-100 translate-y-0" :src="slide.image"
                                :alt="slide.eyebrow"
                                class="product-float h-[340px] sm:h-[400px] lg:h-[540px] object-contain drop-shadow-[0_25px_35px_rgba(0,0,0,0.2)]">
                        </template>
                    </div>

                    <!-- Minimalist Carousel Controls -->
                    <div
                        class="absolute bottom-10 lg:bottom-12 right-10 lg:right-16 z-30 flex items-center gap-4 bg-white/80 backdrop-blur-md px-4 py-2.5 rounded-full border border-white shadow-sm">
                        <button @click="prev()" class="text-gray-400 hover:text-[#CC9863] transition-colors"><svg
                                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg></button>

                        <div class="flex items-center gap-1.5">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button @click="active = index" class="h-1.5 rounded-full transition-all duration-300"
                                    :class="active === index ? 'w-5 bg-[#1C1D21]' : 'w-1.5 bg-gray-300'"></button>
                            </template>
                        </div>

                        <button @click="next()" class="text-gray-400 hover:text-[#CC9863] transition-colors"><svg
                                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg></button>
                    </div>

                </div>
            </div>

            <!-- FEATURE STRIP BOTTOM -->
            <div
                class="absolute bottom-0 inset-x-0 border-t border-white/50 bg-white/30 backdrop-blur-sm px-6 py-4 hidden sm:block">
                <div class="max-w-4xl mx-auto flex justify-between items-center">
                    <div class="flex items-center gap-2 text-gray-500"><span
                            class="text-[#CC9863] text-xs">✦</span><span
                            class="text-[9px] font-extrabold uppercase tracking-widest">Premium Quality</span></div>
                    <div class="flex items-center gap-2 text-gray-500"><span
                            class="text-[#CC9863] text-xs">✦</span><span
                            class="text-[9px] font-extrabold uppercase tracking-widest">Long Lasting</span></div>
                    <div class="flex items-center gap-2 text-gray-500"><span
                            class="text-[#CC9863] text-xs">✦</span><span
                            class="text-[9px] font-extrabold uppercase tracking-widest">Personal Character</span></div>
                </div>
            </div>

        </section>
    </main>

    <!-- ================= SIGNATURE PHILOSOPHY ================= -->
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
                <!-- Aesthetic Placeholder for Image/Video -->
                <div
                    class="w-full aspect-[4/3] bg-[#EAE5DF] rounded-3xl border border-white/50 flex items-center justify-center relative overflow-hidden">
                    <span class="font-luxury italic text-2xl text-gray-400">Visual Story Placeholder</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-white border-t border-gray-100 py-10">
        <div class="max-w-[1180px] mx-auto px-6 lg:px-10 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('asset/zeeperfume_logo.svg') }}" alt="Zee Perfume"
                    class="h-6 w-auto grayscale opacity-70">
                <span class="text-[11px] font-extrabold uppercase tracking-widest text-gray-400">Zee Perfume</span>
            </div>
            <p class="text-[10px] text-gray-400 font-semibold tracking-wider uppercase">
                © {{ date('Y') }} Zee Perfume. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
            });
        }
    </script>
</body>

</html>
