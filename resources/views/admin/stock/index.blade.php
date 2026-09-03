@extends('template.sidebar')
@section('title', 'Kelola Stok Barang')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Stok Barang</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau dan kelola persediaan produk parfum di semua cabang outlet Anda.
                </p>
            </div>
            <a href="{{ url('admin/stock/create') }}"
                class="bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm shrink-0 w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Produk
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="bg-blue-50 text-blue-500 p-3 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Total Produk</p>
                    <h4 class="text-lg font-bold text-gray-900">142 Item</h4>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="bg-red-50 text-red-500 p-3 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Stok Menipis</p>
                    <h4 class="text-lg font-bold text-gray-900">8 Item</h4>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="bg-green-50 text-green-500 p-3 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Status Sinkronisasi</p>
                    <h4 class="text-lg font-bold text-gray-900">Real-time</h4>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div
            class="bg-white p-4 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4 justify-between items-center">
            <!-- Search -->
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] sm:text-sm transition"
                    placeholder="Cari nama parfum, SKU...">
            </div>
            <!-- Filters -->
            <div class="flex gap-3 w-full md:w-auto">
                <select
                    class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                    <option>Semua Outlet</option>
                    <option>Outlet Pusat</option>
                    <option>Cabang 1</option>
                    <option>Cabang 2</option>
                </select>
                <select
                    class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                    <option>Semua Kategori</option>
                    <option>Eau de Parfum (EDP)</option>
                    <option>Eau de Toilette (EDT)</option>
                    <option>Body Mist</option>
                    <option>Essential Oil</option>
                </select>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead
                        class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider text-left border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Produk Parfum</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Harga Jual</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4">Outlet</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                        <!-- Row 1: Normal Stock -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600 font-bold shrink-0">
                                    MA</div>
                                <div>
                                    <p class="font-bold text-gray-900">Midnight Amber 50ml</p>
                                    <p class="text-xs text-gray-400">SKU: PRF-001</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">Eau de Parfum</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">Rp 250.000</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> 45 Botol
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">Outlet Pusat</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ url('admin/stock/edit') }}"
                                        class="inline-block text-gray-400 hover:text-blue-500 transition p-1"
                                        title="Edit Data">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button class="text-gray-400 hover:text-red-500 transition p-1" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 2: Low Stock -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-brown-100 flex items-center justify-center text-yellow-700 bg-yellow-50 font-bold shrink-0">
                                    VC</div>
                                <div>
                                    <p class="font-bold text-gray-900">Vanilla Clouds 100ml</p>
                                    <p class="text-xs text-gray-400">SKU: PRF-012</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">Body Mist</td>
                            <td class="px-6 py-4 font-semibold text-gray-900">Rp 85.000</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> 5 Botol
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">Outlet Pusat</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="text-gray-400 hover:text-blue-500 transition p-1" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button class="text-gray-400 hover:text-red-500 transition p-1" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Row 3: Out of Stock -->
                        <tr class="hover:bg-gray-50 transition bg-gray-50/50">
                            <td class="px-6 py-4 flex items-center gap-3 opacity-60">
                                <div
                                    class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center text-gray-600 font-bold shrink-0">
                                    OB</div>
                                <div>
                                    <p class="font-bold text-gray-900">Ocean Breeze 30ml</p>
                                    <p class="text-xs text-gray-400">SKU: PRF-008</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 opacity-60">Eau de Toilette</td>
                            <td class="px-6 py-4 font-semibold text-gray-900 opacity-60">Rp 145.000</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Habis
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">Cabang 1</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ url('admin/stock/edit') }}"
                                        class="inline-block text-gray-400 hover:text-blue-500 transition p-1"
                                        title="Edit Data">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button class="text-gray-400 hover:text-red-500 transition p-1" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination Mockup -->
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-sm text-gray-500">Menampilkan 1 hingga 3 dari 142 entri</span>
                <div class="flex gap-1">
                    <button
                        class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-400 hover:bg-gray-50 disabled:opacity-50"
                        disabled>Previous</button>
                    <button class="px-3 py-1.5 text-sm bg-[#CC9863] text-white rounded-lg font-medium">1</button>
                    <button
                        class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">2</button>
                    <button
                        class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">3</button>
                    <button
                        class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">Next</button>
                </div>
            </div>
        </div>
    </main>
@endsection
