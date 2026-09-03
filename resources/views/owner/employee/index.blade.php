@extends('template.sidebar')
@section('title', 'Manajemen Pegawai')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Manajemen Pegawai</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola akses akun Admin dan Kasir untuk seluruh outlet Anda.</p>
        </div>
        <a href="#" class="bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center gap-2 text-sm shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah Pegawai
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-blue-50 text-blue-500 p-3 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Total Pegawai</p>
                <h4 class="text-lg font-bold text-gray-900">18 Orang</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-purple-50 text-purple-500 p-3 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Admin Sistem</p>
                <h4 class="text-lg font-bold text-gray-900">3 Orang</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-green-50 text-green-500 p-3 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Kasir Aktif</p>
                <h4 class="text-lg font-bold text-gray-900">15 Orang</h4>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] sm:text-sm transition" placeholder="Cari nama atau username...">
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <select class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863]">
                <option>Semua Role</option>
                <option>Admin</option>
                <option>Kasir</option>
            </select>
            <select class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863]">
                <option>Semua Outlet</option>
                <option>Outlet Pusat</option>
                <option>Cabang 1</option>
            </select>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider text-left border-y border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Profil Pegawai</th>
                        <th class="px-6 py-4">Role Akses</th>
                        <th class="px-6 py-4">Penempatan Outlet</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                    <!-- Row 1: Admin -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?img=12" alt="Avatar" class="w-10 h-10 rounded-full border border-gray-200 shrink-0">
                            <div>
                                <p class="font-bold text-gray-900">Farhan M.</p>
                                <p class="text-xs text-gray-400">@farhan_admin</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                Admin
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-600">Semua Outlet (Pusat)</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">Aktif</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="#" class="text-gray-400 hover:text-blue-500 transition p-1" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button class="text-gray-400 hover:text-red-500 transition p-1" title="Hapus (Perlu Verifikasi)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2: Kasir -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?img=32" alt="Avatar" class="w-10 h-10 rounded-full border border-gray-200 shrink-0">
                            <div>
                                <p class="font-bold text-gray-900">Dina Salsabila</p>
                                <p class="text-xs text-gray-400">@dina_ksr1</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                Kasir
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-600">Outlet Pusat</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">Aktif</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="#" class="text-gray-400 hover:text-blue-500 transition p-1" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button class="text-gray-400 hover:text-red-500 transition p-1" title="Hapus (Perlu Verifikasi)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
