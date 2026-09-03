@extends('template.sidebar')
@section('title', 'Tambah Outlet Baru')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ url('owner/outlet') }}" class="hover:text-[#CC9863] transition">Manajemen Outlet</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-semibold">Tambah Outlet</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Outlet Baru</h1>
        <p class="text-gray-500 text-sm mt-1">Masukkan informasi detail lokasi dan kontak cabang baru Anda.</p>
    </div>

    <!-- Form Container -->
    <form action="#" method="POST" class="flex flex-col xl:flex-row gap-6">
        @csrf

        <!-- ================= KOLOM KIRI: INFORMASI DASAR ================= -->
        <div class="flex-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Dasar & Kontak</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Cabang / Outlet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Cabang Ayani Megamall" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Outlet</label>
                            <input type="text" name="code" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: OTL-004">
                            <p class="text-[10px] text-gray-400 mt-1">Biarkan kosong untuk *generate* otomatis.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon / WA <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="08xxx" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= KOLOM KANAN: ALAMAT & STATUS ================= -->
        <div class="w-full xl:w-[400px] space-y-6 shrink-0">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Lokasi & Operasional</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Tuliskan alamat lengkap cabang..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Operasional <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-900" required>
                            <option value="active">Aktif Beroperasi</option>
                            <option value="inactive">Persiapan / Belum Buka</option>
                            <option value="closed">Tutup Sementara</option>
                        </select>
                        <p class="text-[11px] text-[#CC9863] mt-1 font-medium">Jika belum buka, outlet tidak akan muncul di opsi Kasir.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ url('owner/outlet') }}" class="flex-1 bg-white border border-gray-200 text-center text-gray-700 py-3.5 rounded-2xl font-bold hover:bg-gray-50 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="flex-1 bg-[#1C1D21] text-white py-3.5 rounded-2xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-900/20">
                    Simpan Outlet
                </button>
            </div>
        </div>
    </form>

</main>
@endsection
