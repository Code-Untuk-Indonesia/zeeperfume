<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zee Perfume | Signature Collection</title>

    <!-- Memanggil Tailwind CSS via CDN (Untuk kemudahan test) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-[#FAFAFA] text-gray-800 antialiased selection:bg-[#CC9863] selection:text-white">

    <!-- ================= NAVBAR (Minimalis) ================= -->
    <nav class="absolute top-0 inset-x-0 z-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center w-full">
            <!-- Brand Logo -->
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-[#CC9863] flex items-center justify-center text-white font-black text-xl">
                    Z
                </div>
                <span class="font-extrabold text-xl tracking-tight text-[#1C1D21]">Zee Perfume.</span>
            </div>

            <!-- Menu Links (Desktop) -->
            <div class="hidden md:flex items-center gap-8 font-bold text-sm text-gray-500">
                <a href="#" class="text-[#1C1D21] hover:text-[#CC9863] transition-colors">Beranda</a>
                <a href="#" class="hover:text-[#CC9863] transition-colors">Koleksi</a>
                <a href="#" class="hover:text-[#CC9863] transition-colors">Tentang Kami</a>
            </div>

            <!-- Login Button -->
            <div>
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl border-2 border-gray-200 text-[#1C1D21] font-bold text-sm hover:border-[#CC9863] hover:text-[#CC9863] transition-all">
                    Masuk Kasir
                </a>
            </div>
        </div>
    </nav>

    <!-- ================= HERO SECTION ================= -->
    <!-- pt-28 untuk memberi ruang pada absolute navbar di atasnya -->
    <section class="min-h-screen flex items-center pt-28 pb-10 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

                <!-- ================= KOLOM KIRI (MAIN BANNER & SMALL CARD) ================= -->
                <div class="lg:col-span-9 flex flex-col gap-6">

                    <!-- 1. BIG HERO BANNER -->
                    <div class="relative bg-[#FDF8F4] rounded-[2.5rem] p-8 md:p-12 overflow-hidden flex flex-col md:flex-row items-center border border-[#CC9863]/10 shadow-sm">

                        <!-- Decorative Background Circle -->
                        <div class="absolute -right-20 -top-20 w-96 h-96 bg-[#CC9863]/10 rounded-full blur-3xl"></div>

                        <!-- Text Watermark (Background) -->
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-5 pointer-events-none whitespace-nowrap -rotate-12">
                            <span class="text-8xl font-black text-[#CC9863] uppercase tracking-widest">Premium Perfume</span>
                        </div>

                        <!-- Text Content (Left) -->
                        <div class="relative z-10 flex-1 w-full text-center md:text-left mb-10 md:mb-0">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-[#CC9863]/10 text-[#CC9863] text-xs font-bold uppercase tracking-wider mb-6">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#CC9863]"></span>
                                Signature Collection
                            </div>

                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-[#1C1D21] leading-[1.1] mb-6 tracking-tight">
                                Aroma Mewah,<br>
                                Karakter <span class="text-[#CC9863]">Tak Terlupakan.</span>
                            </h1>

                            <ul class="space-y-3 mb-8 inline-block text-left">
                                <li class="flex items-center gap-3 text-gray-600 font-medium">
                                    <div class="w-5 h-5 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm">
                                        <svg class="w-3 h-3 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    Wangi tahan lama hingga 12 jam
                                </li>
                                <li class="flex items-center gap-3 text-gray-600 font-medium">
                                    <div class="w-5 h-5 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm">
                                        <svg class="w-3 h-3 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    100% Konsentrat murni berkualitas
                                </li>
                                <li class="flex items-center gap-3 text-gray-600 font-medium">
                                    <div class="w-5 h-5 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm">
                                        <svg class="w-3 h-3 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    Aman dan lembut untuk kulit
                                </li>
                            </ul>

                            <div>
                                <a href="#" class="inline-flex items-center justify-center gap-3 bg-[#1C1D21] text-white px-8 py-4 rounded-2xl font-bold hover:bg-[#CC9863] transition-all transform hover:-translate-y-1 shadow-xl shadow-black/10 text-sm md:text-base w-full md:w-auto">
                                    Lihat Produk Kami
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Image Content (Right) -->
                        <div class="relative z-10 flex-1 flex justify-center w-full">
                            <!-- Background Shape untuk botol -->
                            <div class="absolute inset-0 bg-white/40 rounded-full blur-xl transform scale-75"></div>
                            <img src="https://images.unsplash.com/photo-1594035910387-fea47794261f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Botol Parfum" class="relative z-10 h-64 md:h-80 lg:h-96 object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>

                    <!-- 2. SMALL PRODUCT CARD -->
                    <div class="w-full md:w-2/3 bg-white p-4 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                        <!-- Thumbnail -->
                        <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center p-2 shrink-0 border border-gray-100">
                            <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80" alt="Produk Kecil" class="w-full h-full object-contain mix-blend-multiply">
                        </div>
                        <!-- Info -->
                        <div class="flex-1">
                            <span class="text-[10px] font-extrabold text-[#CC9863] uppercase tracking-wider">Terlaris Bulan Ini</span>
                            <h4 class="text-sm md:text-base font-black text-gray-900 mt-0.5 line-clamp-1">Baccarat Rouge 540 Extrait</h4>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="font-extrabold text-[#1C1D21]">Rp 150.000</span>
                                <span class="text-xs font-semibold text-gray-400 line-through">Rp 200.000</span>
                            </div>
                        </div>
                        <!-- Action Button -->
                        <button class="w-12 h-12 rounded-2xl bg-gray-50 hover:bg-[#CC9863] text-gray-400 hover:text-white flex items-center justify-center transition-colors shrink-0 border border-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </button>
                    </div>

                </div>

                <!-- ================= KOLOM KANAN (FEATURES SIDEBAR) ================= -->
                <div class="lg:col-span-3 flex flex-col gap-4">

                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center text-center hover:border-[#CC9863]/30 transition-colors group">
                        <div class="w-14 h-14 bg-orange-50 text-[#CC9863] group-hover:bg-[#CC9863] group-hover:text-white rounded-2xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h4 class="text-sm font-extrabold text-gray-900 mb-1.5">Jaminan Original</h4>
                        <p class="text-[11px] text-gray-500 font-medium leading-relaxed">Bahan baku 100% asli dan berkualitas tinggi.</p>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center text-center hover:border-[#CC9863]/30 transition-colors group">
                        <div class="w-14 h-14 bg-orange-50 text-[#CC9863] group-hover:bg-[#CC9863] group-hover:text-white rounded-2xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-sm font-extrabold text-gray-900 mb-1.5">Tahan Lama</h4>
                        <p class="text-[11px] text-gray-500 font-medium leading-relaxed">Formulasi khusus agar wangi awet menempel seharian.</p>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center text-center hover:border-[#CC9863]/30 transition-colors group">
                        <div class="w-14 h-14 bg-orange-50 text-[#CC9863] group-hover:bg-[#CC9863] group-hover:text-white rounded-2xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                        </div>
                        <h4 class="text-sm font-extrabold text-gray-900 mb-1.5">Konsultasi Aroma</h4>
                        <p class="text-[11px] text-gray-500 font-medium leading-relaxed">Bantu temukan wangi yang pas dengan kepribadianmu.</p>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col items-center text-center hover:border-[#CC9863]/30 transition-colors group">
                        <div class="w-14 h-14 bg-orange-50 text-[#CC9863] group-hover:bg-[#CC9863] group-hover:text-white rounded-2xl flex items-center justify-center mb-4 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <h4 class="text-sm font-extrabold text-gray-900 mb-1.5">Diskon Spesial</h4>
                        <p class="text-[11px] text-gray-500 font-medium leading-relaxed">Dapatkan potongan harga khusus untuk member.</p>
                    </div>

                </div>

            </div>
        </div>
    </section>

</body>
</html>
