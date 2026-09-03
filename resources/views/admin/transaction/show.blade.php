@extends('template.sidebar')
@section('title', 'Detail Transaksi')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ url('admin/transaction') }}" class="hover:text-[#CC9863] transition">Riwayat Transaksi</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-semibold">INV-2609-001</span>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Transaksi</h1>
                <p class="text-gray-500 text-sm mt-1">Rincian pesanan pelanggan, status pembayaran, dan pengiriman.</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                <!-- Tombol Ajukan Edit (Mengingat Admin butuh approval Owner) -->
                <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Ajukan Edit
                </button>

                <!-- Tombol Cetak Struk (Transaksi Langsung) -->
                <button class="bg-[#1C1D21] text-white px-4 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-800 transition flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Struk
                </button>

                <!-- Tombol Cetak Resi (Pesanan Online/Luar Kota) -->
                <button class="bg-[#CC9863] text-white px-4 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                    Cetak Resi
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col xl:flex-row gap-6">

        <!-- KOLOM KIRI: Daftar Produk (Struk Detail) -->
        <div class="flex-1">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <!-- Info Struk -->
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h2 class="font-bold text-gray-900 text-lg">INV-2609-001</h2>
                        <p class="text-sm text-gray-500 mt-1">03 Sep 2026, 14:30 WIB</p>
                    </div>
                    <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider">
                        Lunas
                    </span>
                </div>

                <!-- Tabel Item -->
                <div class="p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">Ringkasan Pesanan</h3>
                    <div class="space-y-4 mb-6 border-b border-gray-100 pb-6">

                        <!-- Item 1 -->
                        <div class="flex justify-between items-start">
                            <div class="flex gap-3">
                                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-[#CC9863] font-bold text-sm shrink-0">MA</div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Midnight Amber 50ml</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Rp 250.000 x 1</p>
                                </div>
                            </div>
                            <p class="font-bold text-gray-900 text-sm">Rp 250.000</p>
                        </div>

                        <!-- Item 2 -->
                        <div class="flex justify-between items-start">
                            <div class="flex gap-3">
                                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 font-bold text-sm shrink-0">VC</div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">Vanilla Clouds 100ml</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Rp 85.000 x 2</p>
                                </div>
                            </div>
                            <p class="font-bold text-gray-900 text-sm">Rp 170.000</p>
                        </div>
                    </div>

                    <!-- Kalkulasi Total -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <p>Subtotal (3 Item)</p>
                            <p class="font-semibold text-gray-900">Rp 420.000</p>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <p>Diskon Transaksi</p>
                            <p class="font-semibold text-red-500">- Rp 0</p>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <p>Biaya Pengiriman (Ongkir)</p>
                            <p class="font-semibold text-gray-900">Rp 25.000</p>
                        </div>
                        <div class="pt-4 mt-2 border-t border-gray-100 flex justify-between items-end">
                            <p class="font-bold text-gray-900">Total Dibayar</p>
                            <p class="text-2xl font-extrabold text-[#CC9863]">Rp 445.000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Detail Pelanggan, Kasir & Pengiriman -->
        <div class="w-full xl:w-[400px] space-y-6 shrink-0">

            <!-- Detail Informasi Umum -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider border-b border-gray-100 pb-3">Informasi Utama</h3>

                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold">Pelanggan</p>
                            <p class="text-sm font-bold text-gray-900">Andi Saputra (Member Reguler)</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold">Metode Pembayaran</p>
                            <p class="text-sm font-bold text-gray-900">Transfer Bank (BCA)</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-semibold">Diproses Oleh</p>
                            <p class="text-sm font-bold text-gray-900">Admin Farhan (Outlet Pusat)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pengiriman (Khusus Online) -->
            <div class="bg-orange-50/50 p-6 rounded-3xl border border-orange-100 shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b border-orange-200/60 pb-3">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Informasi Pengiriman</h3>
                    <span class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-[10px] font-bold">Pesanan Online</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold mb-1">Kurir & Layanan</p>
                        <p class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            J&T Express - EZ (Reguler)
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold mb-1">Nomor Resi</p>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-bold text-gray-900 bg-white border border-orange-200 px-3 py-1 rounded-lg">JP1234567890</p>
                            <button class="text-[#CC9863] hover:text-[#b58555] p-1" title="Copy Resi">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold mb-1">Alamat Tujuan</p>
                        <p class="text-sm font-medium text-gray-800 leading-relaxed bg-white p-3 rounded-xl border border-orange-100">
                            Jl. Ahmad Yani No. 45, RT 01/RW 02, Kec. Pontianak Selatan, Kota Pontianak, Kalimantan Barat 78124
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
