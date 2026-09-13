@extends('template.sidebar')
@section('title', 'Riwayat Transaksi')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Transaksi</h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Pantau seluruh aktivitas penjualan dan kelola otorisasi perubahan data.</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full sm:w-auto">
                <a href="{{ route('owner.transaction.print', request()->only(['search', 'cabang', 'metode', 'tanggal', 'tanggal_spesifik'])) }}"
                    target="_blank" rel="noopener"
                    class="bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-50 hover:text-[#CC9863] hover:border-[#CC9863] transition-all transform active:scale-95 flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Cetak Laporan
                </a>
                <a href="{{ route('owner.transaction.export', request()->only(['search', 'cabang', 'metode', 'tanggal', 'tanggal_spesifik'])) }}"
                    class="bg-[#1C1D21] text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-gray-900/20 hover:bg-[#CC9863] transition-all transform active:scale-95 flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707 0.293l5.414 5.414a1 1 0 01.293 0.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>

        <!-- ================= ALERT NOTIFIKASI APPROVAL ================= -->
        @if (isset($pendingApprovals) && $pendingApprovals->count() > 0)
            <div class="bg-orange-50/80 border border-orange-200/60 rounded-3xl p-6 mb-8 shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-orange-200/30 pointer-events-none">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
                </div>

                <div class="flex flex-col md:flex-row items-start md:items-center gap-5 relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-orange-500 shrink-0 shadow-sm border border-orange-100">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-gray-900 leading-tight">Butuh Otorisasi Anda ({{ $pendingApprovals->count() }})</h3>
                        <p class="text-sm font-medium text-gray-600 mt-1">Admin mengajukan perubahan/penghapusan pada transaksi yang sudah sah.</p>
                    </div>
                </div>

                <div class="mt-5 space-y-3 relative z-10">
                    @foreach ($pendingApprovals as $pending)
                        <div class="bg-white p-4 rounded-2xl border border-gray-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:border-orange-200 transition-colors">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="text-sm font-extrabold text-gray-900">{{ $pending->nomor_nota }}</span>
                                    @if ($pending->approval_status === 'pending_delete')
                                        <span class="text-red-600 uppercase tracking-wider text-[9px] font-black bg-red-50 px-2 py-1 rounded-md border border-red-100">Hapus Transaksi</span>
                                    @else
                                        <span class="text-blue-600 uppercase tracking-wider text-[9px] font-black bg-blue-50 px-2 py-1 rounded-md border border-blue-100">Edit Transaksi</span>
                                    @endif
                                </div>
                                <p class="text-xs font-semibold text-gray-500 flex items-center gap-2">
                                    <span class="bg-gray-50 border border-gray-100 px-2 py-1 rounded-md text-gray-700">Oleh: {{ $pending->requester->nama_lengkap ?? 'Admin' }}</span>
                                    <span class="italic text-gray-600">"{{ $pending->approval_reason }}"</span>
                                </p>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <form action="{{ route('owner.transaction.approve', $pending->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-5 py-2.5 {{ $pending->approval_status === 'pending_delete' ? 'bg-red-500 hover:bg-red-600' : 'bg-[#CC9863] hover:bg-[#b58555]' }} text-white text-xs font-extrabold tracking-wide rounded-xl transition shadow-md flex items-center gap-1.5 transform active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        Izinkan
                                    </button>
                                </form>
                                <form action="{{ route('owner.transaction.reject', $pending->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 text-xs font-extrabold tracking-wide rounded-xl hover:bg-gray-50 hover:text-red-500 hover:border-red-200 transition shadow-sm transform active:scale-95">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- ================= FILTER & PENCARIAN ================= -->
        <div class="bg-white p-5 rounded-t-[2rem] border border-gray-100 border-b-0 shadow-sm" x-data="{ filterTanggal: '{{ request('tanggal', 'all') }}' }">
            <form action="{{ route('owner.transaction.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">

                <!-- Search -->
                <div class="relative w-full md:w-1/3">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-2xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-4 focus:ring-[#CC9863]/10 text-sm font-semibold transition-all"
                        placeholder="Cari Invoice atau Pelanggan..." onblur="this.form.submit()">
                </div>

                <!-- Dropdown Filters -->
                <div class="flex-1 flex flex-wrap lg:flex-nowrap gap-3">
                    <select name="cabang" onchange="this.form.submit()" class="flex-1 min-w-[130px] px-4 py-3.5 text-sm font-semibold border border-gray-200 rounded-2xl bg-white focus:outline-none focus:border-[#CC9863] hover:border-gray-300 cursor-pointer transition-colors appearance-none">
                        <option value="all">Semua Outlet</option>
                        @if (isset($branches))
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('cabang') == $branch->id ? 'selected' : '' }}>{{ $branch->nama_cabang }}</option>
                            @endforeach
                        @endif
                    </select>

                    <select name="tanggal" x-model="filterTanggal" @change="if(filterTanggal !== 'custom') $el.form.submit()" class="flex-1 min-w-[130px] px-4 py-3.5 text-sm font-semibold border border-gray-200 rounded-2xl bg-white focus:outline-none focus:border-[#CC9863] hover:border-gray-300 cursor-pointer transition-colors appearance-none">
                        <option value="all">Semua Waktu</option>
                        <option value="hari_ini">Hari Ini</option>
                        <option value="kemarin">Kemarin</option>
                        <option value="7_hari">7 Hari Terakhir</option>
                        <option value="bulan_ini">Bulan Ini</option>
                        <option value="custom">Pilih Tanggal...</option>
                    </select>

                    <template x-if="filterTanggal === 'custom'">
                        <input type="date" name="tanggal_spesifik" value="{{ request('tanggal_spesifik') }}" onchange="this.form.submit()" class="flex-1 min-w-[140px] px-4 py-3.5 text-sm font-semibold border border-[#CC9863] rounded-2xl bg-orange-50 focus:outline-none focus:ring-4 focus:ring-[#CC9863]/10 cursor-pointer transition-all">
                    </template>

                    <select name="metode" onchange="this.form.submit()" class="flex-1 min-w-[130px] px-4 py-3.5 text-sm font-semibold border border-gray-200 rounded-2xl bg-white focus:outline-none focus:border-[#CC9863] hover:border-gray-300 cursor-pointer transition-colors appearance-none">
                        <option value="all">Semua Pembayaran</option>
                        <option value="cash" {{ request('metode') == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                        <option value="qris" {{ request('metode') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="tempo" {{ request('metode') == 'tempo' ? 'selected' : '' }}>Tempo (Kasbon)</option>
                    </select>

                    @if (request()->anyFilled(['search', 'cabang', 'tanggal', 'metode', 'tanggal_spesifik']))
                        <a href="{{ route('owner.transaction.index') }}" class="px-5 py-3.5 flex items-center justify-center bg-gray-50 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-100 hover:text-gray-900 transition-colors whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- ================= TRANSACTION TABLE ================= -->
        <div class="bg-white rounded-b-[2rem] border border-gray-100 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-left">
                    <thead class="bg-gray-50/50 text-gray-400 text-[10px] font-black uppercase tracking-widest border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-5">Detail Transaksi</th>
                            <th class="px-6 py-5">Kasir / Outlet</th>
                            <th class="px-6 py-5">Pelanggan</th>
                            <th class="px-6 py-5">Pembayaran</th>
                            <th class="px-6 py-5 text-right">Total (Rp)</th>
                            <th class="px-6 py-5 text-center">Status</th>
                            <th class="px-6 py-5 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">

                        @if (isset($transactions) && $transactions->count() > 0)
                            @foreach ($transactions as $trx)
                                @php
                                    $isTempo = in_array(strtolower($trx->metode_bayar), ['tempo', 'cash_tempo']);
                                    $belumLunas = $isTempo && $trx->cashTempo && $trx->cashTempo->sisa_piutang > 0;
                                @endphp

                                <!-- Baris dibuat klik-able secara penuh -->
                                <tr onclick="window.location='{{ url(auth()->user()->role->nama_role . '/transaction/' . $trx->id . '/detail') }}'"
                                    class="group cursor-pointer hover:bg-[#FDFBF7] transition-colors duration-200 {{ $belumLunas ? 'bg-red-50/30' : 'bg-white' }}">

                                    <!-- Waktu & Invoice -->
                                    <td class="px-6 py-4">
                                        <p class="font-extrabold text-gray-900 group-hover:text-[#CC9863] transition-colors">{{ $trx->nomor_nota }}</p>
                                        <p class="text-[11px] font-semibold text-gray-400 mt-1">
                                            {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('d M Y, H:i') }} WIB
                                        </p>
                                    </td>

                                    <!-- Kasir & Cabang -->
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-800">{{ $trx->cashier->nama_lengkap ?? 'Kasir' }}</p>
                                        <p class="text-[11px] font-bold text-gray-400 mt-1 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                            {{ $trx->branch->nama_cabang ?? 'Outlet Pusat' }}
                                        </p>
                                    </td>

                                    <!-- Pelanggan -->
                                    <td class="px-6 py-4">
                                        @if ($trx->member)
                                            <p class="font-bold text-gray-900">{{ $trx->member->nama }}</p>
                                            <p class="text-[9px] font-extrabold text-blue-600 bg-blue-50/80 px-2 py-0.5 rounded-md inline-block mt-1 uppercase tracking-wider">Member</p>
                                        @else
                                            <p class="font-bold text-gray-500">Pelanggan Umum</p>
                                        @endif
                                    </td>

                                    <!-- Metode -->
                                    <td class="px-6 py-4">
                                        @php
                                            $methodColors = [
                                                'cash' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'qris' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                'transfer' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                'tempo' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                'cash_tempo' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            ];
                                            $colorClass = $methodColors[strtolower($trx->metode_bayar)] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black border uppercase tracking-widest {{ $colorClass }}">
                                            {{ str_replace('_', ' ', $trx->metode_bayar) }}
                                        </span>
                                    </td>

                                    <!-- Nominal -->
                                    <td class="px-6 py-4 text-right">
                                        <p class="font-black text-gray-900 text-[15px]">
                                            {{ number_format($trx->total_belanja, 0, ',', '.') }}
                                        </p>
                                        @if ($belumLunas)
                                            <p class="text-[10px] text-rose-600 font-bold mt-1">
                                                Sisa: {{ number_format($trx->cashTempo->sisa_piutang, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    </td>

                                    <!-- Status Approval -->
                                    <td class="px-6 py-4 text-center">
                                        @if ($trx->approval_status === 'approved_edit')
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-blue-600">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Edit Terbuka
                                            </span>
                                        @elseif($trx->approval_status === 'pending_edit' || $trx->approval_status === 'pending_delete')
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-orange-500">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span> Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-600">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi / Indikator -->
                                    <td class="px-6 py-4 text-right pr-8">
                                        <div class="inline-flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-widest text-gray-400 group-hover:text-[#CC9863] transition-colors">
                                            Detail
                                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-gray-900 font-bold text-base mb-1">Tidak Ada Transaksi</h3>
                                        <p class="text-gray-500 text-sm max-w-sm">Data pencarian atau riwayat penjualan tidak ditemukan pada filter yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (isset($transactions) && $transactions->hasPages())
                <div class="px-6 py-5 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50/30">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">
                        Halaman <span class="text-gray-900">{{ $transactions->currentPage() }}</span> dari <span class="text-gray-900">{{ $transactions->lastPage() }}</span>
                    </span>
                    <div>
                        {{ $transactions->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        </div>

    </main>
@endsection
