@extends('template.sidebar')
@section('title', 'Tambah Pegawai')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('owner.employee.index') }}" class="hover:text-[#CC9863] transition">Manajemen Pegawai</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-semibold">Tambah</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Pegawai Baru</h1>
        <p class="text-gray-500 text-sm mt-1">Buat kredensial login dan tentukan akses serta penempatan outlet.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            <p class="font-bold">Data belum dapat disimpan.</p>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.employee.store') }}" method="POST" class="flex flex-col xl:flex-row gap-6">
        @csrf

        <div class="flex-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Profil &amp; Login Akses</h2>

                <div class="space-y-4">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Contoh: Dina Salsabila" required autofocus>
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Untuk login pegawai" required>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                        <input id="password" type="password" name="password" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Minimal 8 karakter" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full xl:w-[400px] space-y-6 shrink-0">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pengaturan Otoritas</h2>

                <div class="space-y-4">
                    <div>
                        <label for="role_id" class="block text-sm font-semibold text-gray-700 mb-1">Role / Jabatan <span class="text-red-500">*</span></label>
                        <select id="role_id" name="role_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-900" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ ucfirst($role->nama_role) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="cabang_id" class="block text-sm font-semibold text-gray-700 mb-1">Penempatan Outlet</label>
                        <select id="cabang_id" name="cabang_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-900">
                            <option value="">Semua Outlet (Pusat)</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(old('cabang_id') == $branch->id)>{{ $branch->nama_cabang }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">Admin dapat menggunakan akses global. Kasir sebaiknya ditempatkan pada outlet aktif.</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('owner.employee.index') }}" class="flex-1 bg-white border border-gray-200 text-center text-gray-700 py-3.5 rounded-2xl font-bold hover:bg-gray-50 transition shadow-sm">Batal</a>
                <button type="submit" class="flex-1 bg-[#1C1D21] text-white py-3.5 rounded-2xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-900/20">Simpan Data</button>
            </div>
        </div>
    </form>
</main>
@endsection
