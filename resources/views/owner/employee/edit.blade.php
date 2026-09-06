@extends('template.sidebar')
@section('title', 'Edit Data Pegawai')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('owner.employee.index') }}" class="hover:text-[#CC9863] transition">Manajemen Pegawai</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-semibold">Edit Data</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Pegawai: {{ $employee->nama_lengkap }}</h1>
        <p class="text-gray-500 text-sm mt-1">Perbarui informasi, penempatan outlet, atau password pegawai.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            <p class="font-bold">Data belum dapat diperbarui.</p>
            <ul class="mt-1 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('owner.employee.update', $employee->id) }}" method="POST" class="flex flex-col xl:flex-row gap-6">
        @csrf
        @method('PUT')

        <div class="flex-1 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Profil &amp; Login Akses</h2>

                <div class="space-y-4">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $employee->nama_lengkap) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" required autofocus>
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                        <input id="username" type="text" name="username" value="{{ old('username', $employee->username) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" required>
                    </div>

                    <div class="bg-orange-50/50 p-4 rounded-xl border border-orange-100">
                        <label for="password" class="block text-sm font-semibold text-gray-900 mb-1">Update Password</label>
                        <p class="text-[11px] text-gray-500 mb-2">Kosongkan jika password tidak ingin diubah.</p>
                        <input id="password" type="password" name="password" class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition" placeholder="Minimal 8 karakter">
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
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id', $employee->role_id) == $role->id)>{{ ucfirst($role->nama_role) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="cabang_id" class="block text-sm font-semibold text-gray-700 mb-1">Penempatan Outlet</label>
                        <select id="cabang_id" name="cabang_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-900">
                            <option value="">Semua Outlet (Pusat)</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(old('cabang_id', $employee->cabang_id) == $branch->id)>{{ $branch->nama_cabang }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">Admin dapat menggunakan akses global. Kasir sebaiknya ditempatkan pada outlet aktif.</p>
                    </div>

                    <div>
                        <label for="status_aktif" class="block text-sm font-semibold text-gray-700 mb-1">Status Akun</label>
                        <select id="status_aktif" name="status_aktif" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/50 focus:border-[#CC9863] transition text-gray-900">
                            <option value="1" @selected((string) old('status_aktif', (int) $employee->status_aktif) === '1')>Aktif (Bisa Login)</option>
                            <option value="0" @selected((string) old('status_aktif', (int) $employee->status_aktif) === '0')>Nonaktif / Diblokir</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('owner.employee.index') }}" class="flex-1 min-w-[8rem] bg-white border border-gray-200 text-center text-gray-700 py-3.5 rounded-2xl font-bold hover:bg-gray-50 transition shadow-sm">Batal</a>
                <button type="submit" class="flex-1 min-w-[8rem] bg-[#1C1D21] text-white py-3.5 rounded-2xl font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-900/20">Update Data</button>
            </div>
        </div>
    </form>
</main>

@include('owner.employee.partials.toast')
@endsection
