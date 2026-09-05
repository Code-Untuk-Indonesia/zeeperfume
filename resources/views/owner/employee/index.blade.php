@extends('template.sidebar')
@section('title', 'Manajemen Pegawai')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 sm:px-6 lg:px-10 py-6 w-full relative">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 lg:mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Manajemen Pegawai</h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Kelola akses akun Admin dan Kasir untuk seluruh outlet Anda.</p>
        </div>
        <a href="{{ route('owner.employee.create') }}" class="w-full md:w-auto bg-[#CC9863] text-white px-5 py-3 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah Pegawai
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-blue-50 text-blue-500 p-3 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] lg:text-xs text-gray-400 font-bold uppercase tracking-wider">Total Pegawai</p>
                <h4 class="text-base lg:text-lg font-extrabold text-gray-900">{{ $totalEmployees ?? 0 }} Orang</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-purple-50 text-purple-500 p-3 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] lg:text-xs text-gray-400 font-bold uppercase tracking-wider">Admin Sistem</p>
                <h4 class="text-base lg:text-lg font-extrabold text-gray-900">{{ $totalAdmins ?? 0 }} Orang</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-green-50 text-green-500 p-3 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] lg:text-xs text-gray-400 font-bold uppercase tracking-wider">Kasir Aktif</p>
                <h4 class="text-base lg:text-lg font-extrabold text-gray-900">{{ $totalCashiers ?? 0 }} Orang</h4>
            </div>
        </div>
    </div>

    <!-- Filter Bar Form -->
    <form id="filter-form" class="bg-white p-4 lg:p-5 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col xl:flex-row gap-4 justify-between items-center">
        <!-- Search -->
        <div class="relative w-full xl:w-96 shrink-0">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" id="search-input" value="{{ request('search') }}" class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/30 text-sm transition" placeholder="Cari nama atau username...">
        </div>

        <!-- Filters Dropdown -->
        <div class="grid grid-cols-2 md:flex gap-3 w-full xl:w-auto">
            <select name="role_id" id="role-filter" class="block w-full pl-3 pr-8 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863] focus:bg-white transition cursor-pointer appearance-none">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ ucfirst($role->nama_role) }}
                    </option>
                @endforeach
            </select>

            <select name="cabang_id" id="cabang-filter" class="block w-full pl-3 pr-8 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863] focus:bg-white transition cursor-pointer appearance-none">
                <option value="">Semua Outlet</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('cabang_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->nama_cabang }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <!-- Table Section Dinamis -->
    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden mb-6 relative">

        <!-- Loading Overlay (Disembunyikan default) -->
        <div id="loading-overlay" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 hidden flex flex-col items-center justify-center">
            <svg class="animate-spin h-8 w-8 text-[#CC9863] mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-xs font-bold text-gray-500">Memuat Data...</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 text-[10px] md:text-xs font-bold uppercase tracking-wider border-y border-gray-100">
                    <tr>
                        <th class="px-4 md:px-6 py-3 md:py-4">Profil Pegawai</th>
                        <th class="px-4 md:px-6 py-3 md:py-4">Role Akses</th>
                        <th class="px-4 md:px-6 py-3 md:py-4 min-w-[150px]">Penempatan Outlet</th>
                        <th class="px-4 md:px-6 py-3 md:py-4">Status</th>
                        <th class="px-4 md:px-6 py-3 md:py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body" class="text-sm text-gray-700">
                    <!-- Looping Data Dimasukkan Langsung Disini -->
                    @forelse($employees as $emp)
                    <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
                        <td class="px-4 md:px-6 py-4 flex items-center gap-3 min-w-[200px]">
                            <div class="w-10 h-10 rounded-full border border-[#CC9863] bg-orange-50 flex items-center justify-center text-[#CC9863] font-bold shrink-0">
                                {{ mb_strtoupper(mb_substr($emp->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 truncate max-w-[150px] md:max-w-xs">{{ $emp->nama_lengkap }}</p>
                                <p class="text-xs text-gray-400">{{ '@' . $emp->username }}</p>
                            </div>
                        </td>
                        <td class="px-4 md:px-6 py-4">
                            @if(strtolower($emp->role->nama_role ?? '') == 'admin')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] sm:text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                    {{ ucfirst($emp->role->nama_role ?? 'Tidak ada') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] sm:text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ ucfirst($emp->role->nama_role ?? 'Tidak ada') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 md:px-6 py-4 font-medium text-gray-600 text-xs sm:text-sm">
                            {{ $emp->branch ? $emp->branch->nama_cabang : 'Semua Outlet (Pusat)' }}
                        </td>
                        <td class="px-4 md:px-6 py-4">
                            @if($emp->status_aktif)
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[9px] sm:text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wider">Aktif</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[9px] sm:text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-wider">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="px-4 md:px-6 py-4 text-center">
                            <div class="flex justify-center gap-1 sm:gap-2">
                                <a href="#" class="text-gray-400 hover:text-blue-500 transition p-1.5 bg-gray-50 hover:bg-blue-50 rounded-lg" title="Edit Data">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button class="text-gray-400 hover:text-red-500 transition p-1.5 bg-gray-50 hover:bg-red-50 rounded-lg" title="Nonaktifkan Pegawai">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center w-full">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                Tidak ada data pegawai yang ditemukan.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div id="pagination-container" class="px-4 md:px-6 py-4 border-t border-gray-100 flex justify-center sm:justify-start">
            {{ $employees->appends(request()->query())->links() }}
        </div>
    </div>
</main>

<!-- SCRIPT AJAX SEARCH & FILTER (TIDAK BUTUH PARTIAL) -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById('search-input');
        const roleFilter = document.getElementById('role-filter');
        const cabangFilter = document.getElementById('cabang-filter');
        const tableBody = document.getElementById('table-body');
        const paginationContainer = document.getElementById('pagination-container');
        const loadingOverlay = document.getElementById('loading-overlay');

        let timeout = null;

        // Fungsi fetch data via AJAX (Fetch satu halaman penuh, lalu iris HTML-nya)
        function fetchData(url) {
            loadingOverlay.classList.remove('hidden');

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse HTML yg diterima dari server
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Ekstrak HANYA bagian tabel (tbody) dan pagination dari respons HTML
                const newTbody = doc.getElementById('table-body');
                const newPagination = doc.getElementById('pagination-container');

                if(newTbody) tableBody.innerHTML = newTbody.innerHTML;
                if(newPagination) paginationContainer.innerHTML = newPagination.innerHTML;

                // Update URL di browser bar tanpa refresh
                window.history.pushState({}, '', url);
            })
            .catch(error => console.error('Error fetching data:', error))
            .finally(() => {
                loadingOverlay.classList.add('hidden');
            });
        }

        // Fungsi menyusun URL dari input form
        function getFilterUrl() {
            const url = new URL('{{ route('owner.employee.index') }}');
            if (searchInput.value) url.searchParams.append('search', searchInput.value);
            if (roleFilter.value) url.searchParams.append('role_id', roleFilter.value);
            if (cabangFilter.value) url.searchParams.append('cabang_id', cabangFilter.value);
            return url.toString();
        }

        // Trigger pencarian saat filter Dropdown berubah
        roleFilter.addEventListener('change', () => fetchData(getFilterUrl()));
        cabangFilter.addEventListener('change', () => fetchData(getFilterUrl()));

        // Trigger pencarian saat input Search diketik (menggunakan Debounce)
        searchInput.addEventListener('keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchData(getFilterUrl());
            }, 500); // Tunggu 500ms setelah user berhenti mengetik
        });

        // Event Delegation untuk Pagination Links agar di-fetch via AJAX
        paginationContainer.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (link) {
                e.preventDefault();
                fetchData(link.href);
            }
        });
    });
</script>
@endsection
