@extends('template.kasir')
@section('title', 'Daftarkan Member Baru')

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
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Daftarkan Member Baru</h1>
            <p class="text-gray-500 text-sm mt-1">Tambahkan data pelanggan untuk program loyalitas dan diskon khusus.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col xl:flex-row gap-6 max-w-5xl mx-auto">

        <!-- KOLOM KIRI: Form Input Data Pelanggan -->
        <div class="flex-1 bg-white p-6 md:p-8 rounded-3xl border border-gray-100 shadow-sm">
            <form action="#" method="POST">
                @csrf
                <div class="space-y-6">

                    <!-- Peringatan Kasir -->
                    <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100 flex gap-3">
                        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Pastikan nomor WhatsApp aktif untuk pengiriman struk digital dan promo ulang tahun.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Rina Melati" required onkeyup="updateCardName(this.value)">
                        </div>

                        <!-- No. HP / WhatsApp -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="08xxx" required onkeyup="updateCardPhone(this.value)">
                        </div>

                        <!-- Tanggal Lahir (Opsional tapi bagus untuk CRM) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="date" name="dob" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-700">
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Singkat <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="address" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Jl. Ahmad Yani, Pontianak">
                        </div>
                    </div>

                </div>
        </div>

        <!-- KOLOM KANAN: Preview Kartu & Submit -->
        <div class="w-full xl:w-[380px] shrink-0 space-y-6">

            <!-- Virtual Card Preview -->
            <div class="bg-gradient-to-br from-[#1C1D21] to-gray-800 p-6 rounded-3xl shadow-xl text-white relative overflow-hidden h-52 flex flex-col justify-between">
                <!-- Ornamen Garis Estetik -->
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-[#CC9863]/20 rounded-full blur-2xl"></div>

                <div class="relative z-10 flex justify-between items-start">
                    <div class="flex items-center gap-2 font-bold text-lg tracking-wide text-white">
                        <svg class="w-5 h-5 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        ZeePerfume
                    </div>
                    <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm rounded text-[10px] font-bold tracking-widest uppercase">
                        Reguler
                    </span>
                </div>

                <div class="relative z-10">
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Member Name</p>
                    <h3 id="card-name" class="text-xl font-bold text-white uppercase tracking-wider mb-2 truncate">NAMA PELANGGAN</h3>

                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-0.5">Phone / WA</p>
                            <p id="card-phone" class="text-sm font-medium text-gray-300 tracking-widest">-</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-0.5">Member ID</p>
                            <p class="text-sm font-bold text-[#CC9863]">NEW-ID</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-4 border-b border-gray-100 pb-3">Konfirmasi Pendaftaran</h3>
                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-[#CC9863] text-white py-3.5 rounded-xl font-bold hover:bg-[#b58555] transition shadow-[0_4px_15px_rgba(204,152,99,0.4)] flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Simpan & Gunakan Member
                    </button>
                    <a href="{{ url('kasir/pos') }}" class="w-full bg-white text-gray-700 py-3.5 rounded-xl font-bold hover:bg-gray-50 transition text-center border border-gray-200">
                        Batal
                    </a>
                </div>
            </div>
            </form>
        </div>

    </div>
</div>

<!-- SCRIPT UNTUK LIVE PREVIEW KARTU -->
<script>
    function updateCardName(val) {
        const nameElement = document.getElementById('card-name');
        nameElement.innerText = val ? val : 'NAMA PELANGGAN';
    }

    function updateCardPhone(val) {
        const phoneElement = document.getElementById('card-phone');
        phoneElement.innerText = val ? val : '-';
    }
</script>
@endsection
