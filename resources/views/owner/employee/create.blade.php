@extends('template.sidebar')
@section('title', 'Tambah Pegawai')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">

    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="#" class="hover:text-[#CC9863] transition">Manajemen Pegawai</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-semibold">Tambah</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Pegawai Baru</h1>
        <p class="text-gray-500 text-sm mt-1">Buat kredensial login dan tentukan akses serta penempatan outlet.</p>
    </div>

    <form action="#" method="POST" class="flex flex-col xl:flex-row gap-6">
        @csrf

        <!-- KOLOM KIRI: Data Pribadi & Kredensial -->
        <div class="flex-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Profil & Login Akses</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Dina Salsabila" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                            <input type="text" name="username" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Untuk login kasir" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP</label>
                            <input type="text" name="phone" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="08xxx">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Akses & Penempatan -->
        <div class="w-full xl:w-[400px] space-y-6 shrink-0">

            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pengaturan Otoritas</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Role / Jabatan <span class="text-red-500">*</span></label>
                        <select name="role" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-900" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="kasir">Kasir (Akses Transaksi POS)</option>
                            <option value="admin">Admin (Akses Stok & Laporan)</option>
                            <option value="owner">Owner (Akses Penuh)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Penempatan Outlet <span class="text-red-500">*</span></label>
                        <select name="outlet_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-900" required>
                            <option value="">-- Pilih Outlet --</option>
                            <option value="all">Semua Outlet (Khusus Admin/Owner)</option>
                            <option value="1">Outlet Pusat</option>
                            <option value="2">Cabang 1</option>
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">Pilih "Semua Outlet" jika role adalah Admin agar bisa mengelola stok global.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="#" class="flex-1 bg-white border border-gray-200 text-center text-gray-700 py-3.5 rounded-2xl font-bold hover:bg-gray-50 transition shadow-sm">Batal</a>
                <button type="submit" class="flex-1 bg-[#1C1D21] text-white py-3.5 rounded-2xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-900/20">
                    Simpan Data
                </button>
            </div>
        </div>
    </form>
</main>
@endsection
