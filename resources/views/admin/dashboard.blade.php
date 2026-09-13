@extends('template.sidebar')
@section('title', 'Dashboard Admin')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 w-full relative min-h-screen">

        <!-- HEADER & GREETING -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">
                    Welcome Back, {{ explode(' ', trim(auth()->user()->nama_lengkap ?? 'Admin'))[0] }}
                </h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Kelola operasional dan pantau performa toko harian.</p>
            </div>

            <!-- FILTER DROPDOWN DENGAN ALPINE JS -->
            <div x-data="{ filterType: '{{ request('filter', 'today') }}' }">
                <form action="{{ route('admin.dashboard') }}" method="GET"
                    class="flex flex-wrap sm:flex-nowrap gap-2 bg-white p-1.5 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="relative flex-1 sm:flex-none">
                        <select name="filter" x-model="filterType" @change="if(filterType !== 'custom') $el.form.submit()"
                            class="w-full sm:w-[160px] h-[38px] px-3 bg-gray-50 border border-transparent rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/30 focus:border-[#CC9863] focus:bg-white transition-colors cursor-pointer appearance-none">
                            <option value="today">Hari Ini</option>
                            <option value="this_week">Minggu Ini</option>
                            <option value="this_month">Bulan Ini</option>
                            <option value="this_year">Tahun Ini</option>
                            <option value="custom">Pilih Tanggal...</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- Input Kalender (Muncul Jika Pilih 'Pilih Tanggal...') -->
                    <template x-if="filterType === 'custom'">
                        <div class="flex items-center gap-1.5">
                            <input type="date" name="tanggal_spesifik" value="{{ request('tanggal_spesifik') }}"
                                class="h-[38px] px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:outline-none focus:border-[#CC9863] transition-colors cursor-pointer">
                            <button type="submit"
                                class="h-[38px] px-4 bg-[#CC9863] text-white rounded-xl font-bold hover:bg-[#b58555] transition text-xs shrink-0 shadow-sm shadow-[#CC9863]/20">
                                Cari
                            </button>
                        </div>
                    </template>
                </form>
            </div>
        </div>

        <div class="mb-5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Periode Data:</span>
                <span
                    class="bg-[#CC9863]/15 text-[#CC9863] font-black tracking-wide text-[11px] px-3 py-1 rounded-lg">{{ $filterLabel ?? 'Hari Ini' }}</span>
            </div>
        </div>

        <!-- ================= 4 TOP STAT CARDS ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 mb-8">
            <!-- 1. Omzet -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <p
                        class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest group-hover:text-gray-900 transition-colors">
                        Total Omzet</p>
                    <div
                        class="w-10 h-10 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center border border-green-100 group-hover:bg-green-500 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-gray-900 mb-4">Rp {{ number_format($omzet, 0, ',', '.') }}
                </h3>
                <div class="flex items-center gap-2">
                    <span
                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold flex items-center gap-1.5 border {{ $trendOmzet >= 0 ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="{{ $trendOmzet >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}">
                            </path>
                        </svg>
                        {{ number_format(abs($trendOmzet), 1) }}%
                    </span>
                    <span class="text-[10px] text-gray-400 font-semibold">vs periode lalu</span>
                </div>
            </div>

            <!-- 2. Transaksi -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <p
                        class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest group-hover:text-gray-900 transition-colors">
                        Total Transaksi</p>
                    <div
                        class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center border border-blue-100 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-gray-900 mb-4">
                    {{ number_format($totalTransaksi, 0, ',', '.') }} <span
                        class="text-sm text-gray-400 font-bold ml-0.5">Nota</span></h3>
                <div class="flex items-center gap-2">
                    <span
                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold flex items-center gap-1.5 border {{ $trendTransaksi >= 0 ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="{{ $trendTransaksi >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}">
                            </path>
                        </svg>
                        {{ number_format(abs($trendTransaksi), 1) }}%
                    </span>
                    <span class="text-[10px] text-gray-400 font-semibold">vs periode lalu</span>
                </div>
            </div>

            <!-- 3. Pesanan Online -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all group">
                <div class="flex justify-between items-start mb-3">
                    <p
                        class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest group-hover:text-gray-900 transition-colors">
                        Pesanan Online</p>
                    <div
                        class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center border border-indigo-100 group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-gray-900 mb-4">
                    {{ number_format($pesananOnline, 0, ',', '.') }} <span
                        class="text-sm text-gray-400 font-bold ml-0.5">Paket</span></h3>
                <div class="flex items-center gap-2">
                    <span
                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold flex items-center gap-1.5 border {{ $trendOnline >= 0 ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="{{ $trendOnline >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}">
                            </path>
                        </svg>
                        {{ number_format(abs($trendOnline), 1) }}%
                    </span>
                    <span class="text-[10px] text-gray-400 font-semibold">vs periode lalu</span>
                </div>
            </div>

            <!-- 4. Peringatan Stok -->
            <a href="{{ route('admin.stock.index') }}"
                class="bg-white p-6 rounded-3xl border border-red-100 shadow-sm hover:shadow-md transition-all group flex flex-col justify-between border-b-4 border-b-red-500 min-h-[160px]">
                <div class="flex justify-between items-start mb-3">
                    <p
                        class="text-[11px] font-extrabold text-red-500 uppercase tracking-widest group-hover:text-red-600 transition-colors">
                        Peringatan Stok</p>
                    <div
                        class="w-10 h-10 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center border border-red-100 group-hover:bg-red-500 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3
                        class="text-2xl lg:text-3xl font-black {{ $jumlahStokMenipis > 0 ? 'text-red-600' : 'text-gray-900' }} mb-3">
                        {{ $jumlahStokMenipis }} <span class="text-sm text-gray-400 font-bold ml-0.5">Item Kritis</span>
                    </h3>
                    <div class="flex items-center gap-2">
                        <span
                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-gray-50 border border-gray-100 text-gray-600 group-hover:text-[#CC9863] group-hover:border-[#CC9863]/30 transition-colors">
                            Cek Gudang &rarr;
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- ================= MIDDLE SECTION (TOP PRODUCTS & CABANG) ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            <!-- TABLE SETORAN CABANG -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <div>
                        <h2 class="text-base font-black text-gray-900">Setoran per Outlet</h2>
                        <p class="text-[11px] font-medium text-gray-500 mt-1">Total pendapatan cabang berdasar filter
                            aktif.</p>
                    </div>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-5">Nama Outlet</th>
                                <th class="px-6 py-5 text-right">Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse ($laporanCabang as $cabang)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-xl {{ $cabang['setoran'] > 0 ? 'bg-[#1C1D21] text-[#CC9863]' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center font-black text-[10px] uppercase shadow-sm border border-gray-100">
                                                {{ substr($cabang['nama'], 0, 2) }}
                                            </div>
                                            <span class="font-bold text-gray-900">{{ $cabang['nama'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900 text-base">
                                        Rp {{ number_format($cabang['setoran'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2"
                                        class="px-6 py-12 text-center text-gray-400 font-bold text-xs italic">Belum ada
                                        data cabang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TOP PRODUCTS -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <div>
                        <h2 class="text-base font-black text-gray-900">Top 5 Produk Terlaris</h2>
                        <p class="text-[11px] font-medium text-gray-500 mt-1">Produk penyumbang omzet terbanyak.</p>
                    </div>
                    <a href="{{ route('admin.stock.index') }}"
                        class="text-[10px] font-bold text-[#CC9863] hover:text-white bg-orange-50 hover:bg-[#CC9863] px-3.5 py-1.5 rounded-lg transition-colors shadow-sm">
                        Kelola Stok &rarr;
                    </a>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest bg-white border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-5">Produk & Varian</th>
                                <th class="px-6 py-5 text-center">Terjual</th>
                                <th class="px-6 py-5 text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse ($topProducts as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">
                                            {{ $item->variant->product->nama_produk ?? 'Unknown' }}</p>
                                        <p class="text-[10px] text-gray-500 font-semibold mt-0.5">
                                            {{ $item->variant->nama_varian ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="bg-gray-100 border border-gray-200 text-gray-700 px-2.5 py-1 rounded-lg font-black text-xs shadow-sm">
                                            {{ $item->total_qty }} <span
                                                class="text-[9px] font-semibold text-gray-500">PCS</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-[#CC9863] text-base">
                                        Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-12 text-center text-gray-400 font-bold text-xs italic">Belum ada
                                        penjualan produk di periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= RECENT TRANSACTIONS TABLE ================= -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <div>
                    <h2 class="text-base font-black text-gray-900">Transaksi Terakhir</h2>
                    <p class="text-[11px] text-gray-500 font-medium mt-1">Aktivitas riil kasir di seluruh cabang saat ini.
                    </p>
                </div>
                <a href="{{ route('admin.transaction.index') }}"
                    class="text-[11px] font-extrabold text-[#1C1D21] hover:text-white bg-white border border-gray-200 hover:bg-[#1C1D21] px-4 py-2 rounded-xl transition-all shadow-sm">
                    Lihat Semua Riwayat &rarr;
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead
                        class="text-[10px] text-gray-400 font-extrabold uppercase tracking-widest bg-white border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-5">Waktu & Nota</th>
                            <th class="px-6 py-5 text-center">Status Origin</th>
                            <th class="px-6 py-5 text-center">Metode Bayar</th>
                            <th class="px-6 py-5 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-[#FDFBF7] transition-colors cursor-pointer group"
                                onclick="window.location='{{ route('admin.transaction.show', $trx->id) }}'">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900 group-hover:text-[#CC9863] transition-colors">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('d M Y, H:i') }}</p>
                                    <p class="text-[11px] text-[#CC9863] font-black mt-0.5">{{ $trx->nomor_nota }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($trx->shipment)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-lg text-[9px] font-black uppercase tracking-wider">
                                            Online
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-orange-50 border border-orange-100 text-[#CC9863] rounded-lg text-[9px] font-black uppercase tracking-wider">
                                            Outlet
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="font-black text-gray-600 uppercase text-[9px] tracking-wider bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-lg">
                                        {{ str_replace('_', ' ', $trx->metode_bayar) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="font-black text-gray-900 text-[15px]">Rp
                                        {{ number_format($trx->total_belanja, 0, ',', '.') }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div
                                        class="w-14 h-14 mx-auto bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-center text-gray-300 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-gray-400 italic">Belum ada transaksi di sistem.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
@endsection
