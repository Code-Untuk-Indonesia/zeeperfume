@extends('template.kasir')
@section('title', 'Tambah Produk Custom')

@section('content')
<div class="flex-1 overflow-y-auto p-4 lg:p-8 bg-[#FAFAFA] w-full">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ url('kasir/pos') }}" class="hover:text-[#CC9863] transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali ke POS
                </a>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Racik Produk Custom</h1>
            <p class="text-gray-500 text-sm mt-1">Tambahkan item di luar katalog utama, seperti racikan parfum atau isi ulang (refill).</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col xl:flex-row gap-6 max-w-5xl mx-auto">

        <!-- KOLOM KIRI: Form Input Custom -->
        <div class="flex-1 bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm">
            <form action="#" method="POST">
                @csrf
                <div class="space-y-6">

                    <!-- Tipe Custom -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Tipe Item Custom</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="cursor-pointer relative">
                                <input type="radio" name="custom_type" value="racikan" class="peer sr-only" checked>
                                <div class="px-4 py-3 border-2 border-gray-100 rounded-xl text-center peer-checked:border-[#CC9863] peer-checked:bg-orange-50 hover:bg-gray-50 transition">
                                    <span class="block text-sm font-bold text-gray-900 peer-checked:text-[#CC9863]">Racikan Mix</span>
                                </div>
                            </label>
                            <label class="cursor-pointer relative">
                                <input type="radio" name="custom_type" value="refill" class="peer sr-only">
                                <div class="px-4 py-3 border-2 border-gray-100 rounded-xl text-center peer-checked:border-[#CC9863] peer-checked:bg-orange-50 hover:bg-gray-50 transition">
                                    <span class="block text-sm font-bold text-gray-900 peer-checked:text-[#CC9863]">Refill / ML</span>
                                </div>
                            </label>
                            <label class="cursor-pointer relative md:col-span-1 col-span-2">
                                <input type="radio" name="custom_type" value="lainnya" class="peer sr-only">
                                <div class="px-4 py-3 border-2 border-gray-100 rounded-xl text-center peer-checked:border-[#CC9863] peer-checked:bg-orange-50 hover:bg-gray-50 transition">
                                    <span class="block text-sm font-bold text-gray-900 peer-checked:text-[#CC9863]">Lainnya</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Nama Produk -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk / Deskripsi Singkat <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Baccarat + Vanilla (50ml)" required>
                    </div>

                    <!-- Harga & Qty -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-semibold text-sm">Rp</span>
                                </div>
                                <input type="number" name="price" class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="0" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah (Qty) <span class="text-red-500">*</span></label>
                            <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50 overflow-hidden focus-within:ring-2 focus-within:ring-[#CC9863]/50 focus-within:border-[#CC9863] transition">
                                <button type="button" class="px-4 py-3 text-gray-500 hover:bg-gray-200 transition font-bold">−</button>
                                <input type="number" name="qty" value="1" min="1" class="w-full py-3 bg-transparent text-center font-bold focus:outline-none" required>
                                <button type="button" class="px-4 py-3 text-gray-500 hover:bg-gray-200 transition font-bold">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan / Komposisi -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Komposisi / Catatan (Opsional)</label>
                        <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Baccarat 30ml + Absolute 20ml..."></textarea>
                        <p class="text-[10px] text-gray-400 mt-1">Catatan ini akan muncul di struk pelanggan agar mereka tahu racikannya.</p>
                    </div>

                </div>
            </form>
        </div>

        <!-- KOLOM KANAN: Preview & Submit -->
        <div class="w-full xl:w-[360px] shrink-0 space-y-6">
            <div class="bg-[#1C1D21] p-6 rounded-3xl border border-gray-800 shadow-lg text-white">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-700 pb-3">Preview Item</h3>

                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center text-[#CC9863] shrink-0 border border-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div>
                        <p class="font-bold text-white leading-tight">Racikan Custom</p>
                        <p class="text-xs text-gray-400 mt-1">Qty: 1</p>
                    </div>
                </div>

                <div class="space-y-2 mb-6 text-sm">
                    <div class="flex justify-between text-gray-400">
                        <span>Harga Satuan</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-white pt-2 border-t border-gray-700 mt-2">
                        <span>Total Harga</span>
                        <span class="text-[#CC9863] text-lg">Rp 0</span>
                    </div>
                </div>

                <!-- Tombol Action -->
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-[#CC9863] text-white py-3.5 rounded-xl font-bold hover:bg-[#b58555] transition shadow-[0_4px_15px_rgba(204,152,99,0.4)] flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Tambah ke Keranjang
                    </button>
                    <a href="{{ url('kasir/pos') }}" class="w-full bg-gray-800 text-gray-300 py-3.5 rounded-xl font-bold hover:bg-gray-700 hover:text-white transition text-center border border-gray-700">
                        Batal
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
