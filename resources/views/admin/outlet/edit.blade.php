@extends('template.sidebar')
@section('title', 'Edit Outlet')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ url('owner/outlet') }}" class="hover:text-[#CC9863] transition">Manajemen Outlet</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-semibold">Edit Outlet</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Outlet: Cabang 1 (Mall)</h1>
        <p class="text-gray-500 text-sm mt-1">Perbarui informasi kontak, alamat, atau ubah status operasional cabang.</p>
    </div>

    <!-- Gunakan method PUT untuk update data -->
    <form action="#" method="POST" class="flex flex-col xl:flex-row gap-6">
        @csrf
        @method('PUT')

        <!-- ================= KOLOM KIRI: INFORMASI DASAR ================= -->
        <div class="flex-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Dasar & Kontak</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Cabang / Outlet <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="Cabang 1 (Mall)" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Outlet</label>
                            <input type="text" name="code" value="OTL-002" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-200 text-gray-500 cursor-not-allowed focus:outline-none" readonly>
                            <p class="text-[10px] text-gray-400 mt-1">Kode outlet tidak dapat diubah.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon / WA <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="0811-9988-7766" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" required>
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
                        <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" required>Ayani Megamall Lantai 1, Pontianak</textarea>
                    </div>

                    <hr class="border-gray-100 my-4">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Operasional <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-900" required>
                            <option value="active" selected>Aktif Beroperasi</option>
                            <option value="closed">Tutup Sementara / Renovasi</option>
                            <option value="inactive">Tutup Permanen</option>
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">Ubah ke "Tutup Sementara" jika sedang dalam tahap renovasi.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ url('owner/outlet') }}" class="flex-1 bg-white border border-gray-200 text-center text-gray-700 py-3.5 rounded-2xl font-bold hover:bg-gray-50 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="flex-1 bg-[#1C1D21] text-white py-3.5 rounded-2xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-900/20">
                    Update Outlet
                </button>
            </div>
        </div>
    </form>

</main>
@endsection
