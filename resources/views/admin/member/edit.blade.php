@extends('template.sidebar')
@section('title', 'Edit Member')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 sm:px-6 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 lg:mb-8">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2 font-medium">
                <a href="{{ route('owner.member.index') }}" class="hover:text-[#CC9863] transition">Member</a>
                <span>/</span>
                <span class="text-gray-900">Edit Data</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Member</h1>
        </div>
        <a href="{{ route('owner.member.index') }}" class="bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition flex items-center justify-center gap-2 text-sm shrink-0">
            Batal & Kembali
        </a>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden max-w-3xl">
        <div class="p-6 md:p-8">
            <form action="#" method="POST" class="space-y-6">
                <!-- Data ini hanya simulasi UI (tidak terhubung ke database) -->

                <!-- Alert/Status Box (Opsional, khusus edit) -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="text-sm font-bold text-blue-800">Total Belanja Member: Rp 15.450.000</p>
                        <p class="text-[10px] text-blue-600 mt-1">Status member tidak dapat diubah menjadi reguler karena total belanja sudah memenuhi syarat VIP.</p>
                    </div>
                </div>

                <!-- Grup Input: Informasi Dasar -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-[#CC9863] uppercase tracking-wider mb-2">Informasi Pribadi</h3>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <!-- Anggap data lama adalah "Budi Santoso" -->
                        <input type="text" value="Budi Santoso" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] transition bg-gray-50 focus:bg-white">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Nomor HP (WhatsApp) <span class="text-red-500">*</span></label>
                            <input type="text" value="081234567890" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] transition bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Email <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="email" value="budi@example.com" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] transition bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                </div>

                <!-- Pemisah -->
                <hr class="border-gray-100">

                <!-- Grup Input: Pengaturan Akun -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-[#CC9863] uppercase tracking-wider mb-2">Pengaturan Member</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Kode Member (ID) <span class="text-red-500">*</span></label>
                            <input type="text" value="MBR-001" readonly class="w-full border border-gray-200 rounded-xl p-3 text-sm bg-gray-100 text-gray-500 font-bold cursor-not-allowed outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Tipe / Klasifikasi</label>
                            <select class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] transition bg-gray-50 focus:bg-white appearance-none">
                                <option value="reguler">Reguler Member</option>
                                <option value="vip" selected>VIP Member</option>
                                <option value="reseller">Reseller Khusus</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Poin Saat Ini</label>
                            <input type="number" value="1250" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] transition bg-gray-50 focus:bg-white">
                            <p class="text-[10px] text-gray-400 mt-1">Ubah manual poin pelanggan jika diperlukan.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Status Akun</label>
                            <div class="flex items-center gap-4 mt-3">
                                <label class="flex items-center gap-2 text-sm font-semibold cursor-pointer">
                                    <input type="radio" name="status" checked class="w-4 h-4 text-[#CC9863] focus:ring-[#CC9863]">
                                    Aktif
                                </label>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-500 cursor-pointer">
                                    <input type="radio" name="status" class="w-4 h-4 text-red-500 focus:ring-red-500">
                                    Diblokir
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-6 mt-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <button type="button" onclick="confirm('Yakin ingin mereset/menghapus data member ini?')" class="text-red-500 text-sm font-bold hover:underline order-2 md:order-1">
                        Hapus Member
                    </button>
                    <button type="button" onclick="alert('Perubahan data berhasil disimpan secara visual (UI).')" class="w-full md:w-auto bg-[#CC9863] text-white px-8 py-3.5 rounded-xl font-bold hover:bg-[#b58555] transition shadow-lg shadow-[#CC9863]/30 order-1 md:order-2 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>
@endsection
