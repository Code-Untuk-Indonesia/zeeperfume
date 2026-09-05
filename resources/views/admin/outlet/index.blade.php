@extends('template.sidebar')
@section('title', 'Manajemen Outlet')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Manajemen Outlet</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola informasi, lokasi, dan status operasional seluruh cabang toko Anda.</p>
            </div>
            <a href="{{ route('admin.outlet.create') }}"
                class="bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm shrink-0 w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Outlet Baru
            </a>
        </div>

        <!-- Quick Stats Dinamis -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="bg-blue-50 text-blue-500 p-3 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Total Outlet</p>
                    <h4 class="text-lg font-bold text-gray-900">{{ $totalOutlets ?? 0 }} Cabang</h4>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="bg-green-50 text-green-500 p-3 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Outlet Aktif</p>
                    <h4 class="text-lg font-bold text-gray-900">{{ $activeOutlets ?? 0 }} Cabang</h4>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="bg-orange-50 text-[#CC9863] p-3 rounded-xl shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Total Pegawai Outlet</p>
                    <h4 class="text-lg font-bold text-gray-900">{{ $totalEmployees ?? 0 }} Orang</h4>
                </div>
            </div>
        </div>

        <!-- Filter Bar Form -->
        <form id="filter-form"
            class="bg-white p-4 lg:p-5 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4 justify-between items-center">
            <!-- Search -->
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="search-input" value="{{ request('search') }}"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] sm:text-sm transition"
                    placeholder="Cari nama atau lokasi outlet...">
            </div>

            <!-- Filters -->
            <div class="flex gap-3 w-full md:w-auto">
                <select id="status-filter"
                    class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863]">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif Beroperasi</option>
                    <option value="tutup" {{ request('status') == 'tutup' ? 'selected' : '' }}>Tutup Sementara</option>
                </select>
            </div>
        </form>

        <!-- Table Section Dinamis -->
        <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden relative">

            <!-- Loading Overlay (Disembunyikan default) -->
            <div id="loading-overlay"
                class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 hidden flex flex-col items-center justify-center">
                <svg class="animate-spin h-8 w-8 text-[#CC9863] mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="text-xs font-bold text-gray-500">Memuat Data...</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead
                        class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Informasi Outlet</th>
                            <th class="px-6 py-4">Kontak & Alamat</th>
                            <th class="px-6 py-4">Pegawai Aktif</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="divide-y divide-gray-100 text-sm text-gray-700">

                        @forelse($outlets ?? [] as $outlet)
                            <tr class="hover:bg-gray-50 transition {{ method_exists($outlet, 'trashed') && $outlet->trashed() ? 'bg-gray-50/50' : '' }}">
                                <td class="px-6 py-4 {{ method_exists($outlet, 'trashed') && $outlet->trashed() ? 'opacity-60' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 rounded-xl {{ method_exists($outlet, 'trashed') && $outlet->trashed() ? 'bg-gray-200 text-gray-500' : 'bg-orange-50 text-[#CC9863]' }} flex items-center justify-center font-bold shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-base">{{ $outlet->nama_cabang }}</p>
                                            <p class="text-xs text-gray-400 font-medium">ID: OTL-00{{ $outlet->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 {{ method_exists($outlet, 'trashed') && $outlet->trashed() ? 'opacity-60' : '' }}">
                                    <p class="font-semibold text-gray-700">{{ $outlet->no_telepon ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 max-w-[200px] truncate"
                                        title="{{ $outlet->alamat }}">{{ $outlet->alamat ?? 'Belum ada alamat' }}</p>
                                </td>
                                <td class="px-6 py-4 {{ method_exists($outlet, 'trashed') && $outlet->trashed() ? 'opacity-60' : '' }}">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900">{{ $outlet->users_count ?? 0 }}</span>
                                        <span class="text-xs text-gray-500">Orang</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if (!method_exists($outlet, 'trashed') || !$outlet->trashed())
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Tutup Sementara
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.outlet.edit') }}"
                                            class="inline-block text-gray-400 hover:text-blue-500 transition p-1.5 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50"
                                            title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        @if (!method_exists($outlet, 'trashed') || !$outlet->trashed())
                                            <button
                                                class="text-gray-400 hover:text-red-500 transition p-1.5 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50"
                                                title="Nonaktifkan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                    </path>
                                                </svg>
                                            </button>
                                        @else
                                            <button
                                                class="text-gray-400 hover:text-green-500 transition p-1.5 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50"
                                                title="Aktifkan Kembali">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center w-full">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        Tidak ada data outlet yang ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if(isset($outlets) && method_exists($outlets, 'links'))
            <div id="pagination-container" class="px-6 py-4 border-t border-gray-100">
                {{ $outlets->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </main>

    <!-- SCRIPT AJAX SEARCH & FILTER -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('search-input');
            const statusFilter = document.getElementById('status-filter');
            const tableBody = document.getElementById('table-body');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingOverlay = document.getElementById('loading-overlay');

            let timeout = null;

            function fetchData(url) {
                loadingOverlay.classList.remove('hidden');

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newTbody = doc.getElementById('table-body');
                        const newPagination = doc.getElementById('pagination-container');

                        if (newTbody) tableBody.innerHTML = newTbody.innerHTML;
                        if (newPagination && paginationContainer) paginationContainer.innerHTML = newPagination.innerHTML;

                        window.history.pushState({}, '', url);
                    })
                    .catch(error => console.error('Error fetching data:', error))
                    .finally(() => {
                        loadingOverlay.classList.add('hidden');
                    });
            }

            function getFilterUrl() {
                // MENGGUNAKAN ROUTE ADMIN (TIDAK LAGI OWNER)
                const url = new URL('{{ route('admin.outlet.index') }}');
                if (searchInput.value) url.searchParams.append('search', searchInput.value);
                if (statusFilter.value) url.searchParams.append('status', statusFilter.value);
                return url.toString();
            }

            statusFilter.addEventListener('change', () => fetchData(getFilterUrl()));

            searchInput.addEventListener('keyup', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    fetchData(getFilterUrl());
                }, 500);
            });

            if (paginationContainer) {
                paginationContainer.addEventListener('click', function(e) {
                    const link = e.target.closest('a');
                    if (link) {
                        e.preventDefault();
                        fetchData(link.href);
                    }
                });
            }
        });
    </script>
@endsection
