@extends('template.sidebar')
@section('title', 'Data Member Pelanggan')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Member</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola data pelanggan setia, reseller, dan riwayat poin belanja mereka.</p>
        </div>
        <div class="flex gap-3 w-full sm:w-auto">
            <button class="bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Member
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-purple-50 text-purple-500 p-3 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Total Member</p>
                <h4 class="text-lg font-bold text-gray-900">850 Orang</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-yellow-50 text-yellow-600 p-3 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Member VIP / Reseller</p>
                <h4 class="text-lg font-bold text-gray-900">45 Orang</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-green-50 text-green-500 p-3 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase">Total Belanja Member</p>
                <h4 class="text-lg font-bold text-gray-900">Rp 128.5M</h4>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4 justify-between items-center">
        <!-- Search -->
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] sm:text-sm transition" placeholder="Cari nama, no HP, atau ID Member...">
        </div>

        <!-- Dropdown Filters -->
        <div class="flex gap-3 w-full md:w-auto">
            <select class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                <option>Semua Tipe</option>
                <option>Reguler</option>
                <option>VIP</option>
                <option>Reseller</option>
            </select>
            <select class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                <option>Status Aktif</option>
                <option>Diblokir</option>
            </select>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider text-left border-y border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Informasi Member</th>
                        <th class="px-6 py-4">Tipe Member</th>
                        <th class="px-6 py-4">Total Belanja</th>
                        <th class="px-6 py-4">Poin Reward</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                    <!-- Row 1: VIP/Reseller -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold shrink-0">
                                BS
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Budi Santoso</p>
                                <p class="text-xs text-gray-400">0812-3456-7890 • ID: MBR-001</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                Reseller
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">Rp 15.450.000</p>
                            <p class="text-xs text-gray-400">42 Transaksi</p>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#CC9863]">
                            1,250 Pts
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-gray-400 hover:text-blue-500 transition p-1" title="Edit Member">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button class="text-gray-400 hover:text-red-500 transition p-1" title="Blokir/Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2: Reguler Member -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <img src="https://i.pravatar.cc/150?img=5" alt="Member" class="w-10 h-10 rounded-full border border-gray-200 shrink-0">
                            <div>
                                <p class="font-bold text-gray-900">Rina Melati</p>
                                <p class="text-xs text-gray-400">0857-1122-3344 • ID: MBR-142</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                Reguler
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">Rp 850.000</p>
                            <p class="text-xs text-gray-400">3 Transaksi</p>
                        </td>
                        <td class="px-6 py-4 font-bold text-[#CC9863]">
                            85 Pts
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="text-gray-400 hover:text-blue-500 transition p-1" title="Edit Member">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button class="text-gray-400 hover:text-red-500 transition p-1" title="Blokir/Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <span class="text-sm text-gray-500">Menampilkan 1 hingga 2 dari 850 member</span>
            <div class="flex gap-1">
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-400 hover:bg-gray-50 disabled:opacity-50" disabled>Previous</button>
                <button class="px-3 py-1.5 text-sm bg-[#CC9863] text-white rounded-lg font-medium">1</button>
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">2</button>
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">3</button>
                <button class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Next</button>
            </div>
        </div>
    </div>

</main>
@endsection
