@extends('template.sidebar')
@section('title', 'Data Member Pelanggan')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 sm:px-6 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 lg:mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Member</h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Kelola data pelanggan setia, klasifikasi member, dan riwayat belanja.</p>
        </div>
        <button class="w-full md:w-auto bg-[#CC9863] text-white px-5 py-3 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Member
        </button>
    </div>

    <!-- Quick Stats Dinamis -->
    @php
        function formatSingkatan($num) {
            if($num >= 1000000000) return round($num / 1000000000, 1) . ' M';
            if($num >= 1000000) return round($num / 1000000, 1) . ' Jt';
            return number_format($num, 0, ',', '.');
        }
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-purple-50 text-purple-500 p-3 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] lg:text-xs text-gray-400 font-bold uppercase tracking-wider">Total Member</p>
                <h4 class="text-base lg:text-lg font-extrabold text-gray-900">{{ number_format($totalMembers) }} Orang</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-yellow-50 text-yellow-600 p-3 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] lg:text-xs text-gray-400 font-bold uppercase tracking-wider">Member VIP</p>
                <h4 class="text-base lg:text-lg font-extrabold text-gray-900">{{ number_format($vipMembers) }} Orang</h4>
            </div>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="bg-green-50 text-green-500 p-3 rounded-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] lg:text-xs text-gray-400 font-bold uppercase tracking-wider">Total Belanja</p>
                <h4 class="text-base lg:text-lg font-extrabold text-gray-900">Rp {{ formatSingkatan($totalBelanjaSemua) }}</h4>
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
            <input type="text" id="search-input" name="search" value="{{ request('search') }}" class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/30 text-sm transition" placeholder="Cari nama, no HP, atau ID...">
        </div>

        <!-- Dropdown Filters -->
        <div class="grid grid-cols-1 md:flex gap-3 w-full xl:w-auto">
            <select id="tipe-filter" name="tipe" class="block w-full pl-3 pr-8 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863] focus:bg-white transition cursor-pointer appearance-none">
                <option value="semua">Semua Tipe</option>
                <option value="reguler" {{ request('tipe') == 'reguler' ? 'selected' : '' }}>Reguler</option>
                <option value="vip" {{ request('tipe') == 'vip' ? 'selected' : '' }}>VIP Member</option>
            </select>
        </div>
    </form>

    <!-- Table Section Dinamis -->
    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden mb-6 relative">

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 hidden flex flex-col items-center justify-center">
            <svg class="animate-spin h-8 w-8 text-[#CC9863] mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span class="text-xs font-bold text-gray-500">Memuat Data...</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-400 text-[10px] md:text-xs font-bold uppercase tracking-wider border-y border-gray-100">
                    <tr>
                        <th class="px-4 md:px-6 py-3 md:py-4">Informasi Member</th>
                        <th class="px-4 md:px-6 py-3 md:py-4">Tipe Member</th>
                        <th class="px-4 md:px-6 py-3 md:py-4">Riwayat Belanja</th>
                        <th class="px-4 md:px-6 py-3 md:py-4">Poin Reward</th>
                        <th class="px-4 md:px-6 py-3 md:py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body" class="text-sm text-gray-700 divide-y divide-gray-100">
                    @include('owner.member.partials.table-rows')
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="px-4 md:px-6 py-4 border-t border-gray-100 flex justify-center sm:justify-start">
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>

</main>

<!-- SCRIPT AJAX SEARCH & FILTER -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById('search-input');
        const tipeFilter = document.getElementById('tipe-filter');
        const tableBody = document.getElementById('table-body');
        const paginationContainer = document.getElementById('pagination-container');
        const loadingOverlay = document.getElementById('loading-overlay');

        let timeout = null;

        function fetchData(url) {
            loadingOverlay.classList.remove('hidden');

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const hiddenPagination = doc.getElementById('pagination-links-data');

                if (hiddenPagination) {
                    paginationContainer.innerHTML = hiddenPagination.innerHTML;
                    hiddenPagination.remove();
                } else {
                    paginationContainer.innerHTML = '';
                }

                tableBody.innerHTML = doc.body.innerHTML;
                window.history.pushState({}, '', url);
            })
            .catch(error => console.error('Error fetching data:', error))
            .finally(() => {
                loadingOverlay.classList.add('hidden');
            });
        }

        function getFilterUrl() {
            const url = new URL('{{ route('owner.member.index') }}'); // Pastikan route name sesuai
            if (searchInput.value) url.searchParams.append('search', searchInput.value);
            if (tipeFilter.value && tipeFilter.value !== 'semua') url.searchParams.append('tipe', tipeFilter.value);
            return url.toString();
        }

        tipeFilter.addEventListener('change', () => fetchData(getFilterUrl()));

        searchInput.addEventListener('keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                fetchData(getFilterUrl());
            }, 500);
        });

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
