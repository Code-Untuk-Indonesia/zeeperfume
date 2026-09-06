@extends('template.sidebar')
@section('title', 'Riwayat Transaksi')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Transaksi</h1>
            <p class="text-gray-500 text-sm mt-1 font-medium">Pantau seluruh aktivitas penjualan dan kelola otorisasi perubahan data.</p>
        </div>
        <div class="flex gap-3 w-full sm:w-auto">
            <button class="bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-[#CC9863]/20 hover:bg-[#b58555] transition transform active:scale-95 flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF/Excel
            </button>
        </div>
    </div>

    <!-- ================= SESSION ALERTS ================= -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- ================= ALERT NOTIFIKASI APPROVAL ================= -->
    @if(isset($pendingApprovals) && $pendingApprovals->count() > 0)
    <div class="bg-orange-50 border border-orange-200 rounded-3xl p-5 md:p-6 mb-8 shadow-sm relative overflow-hidden">
        <!-- Dekorasi bg -->
        <div class="absolute -right-4 -top-4 text-orange-200/40 pointer-events-none">
            <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path></svg>
        </div>

        <div class="flex items-start gap-4 relative z-10">
            <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 shrink-0 border border-orange-200 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-black text-gray-900">Menunggu Otorisasi Anda ({{ $pendingApprovals->count() }})</h3>
                <p class="text-sm font-medium text-gray-600 mt-1 mb-4">Admin mengajukan perubahan data pada transaksi yang sudah selesai.</p>

                <div class="space-y-3">
                    @foreach($pendingApprovals as $pending)
                        <div class="bg-white p-4 rounded-2xl border border-orange-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4 shadow-sm hover:border-orange-300 transition-colors">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900">
                                    Pengajuan 
                                    @if($pending->approval_status === 'pending_delete')
                                        <span class="text-red-500 uppercase tracking-wider text-[11px] bg-red-50 px-2 py-0.5 rounded ml-1">Hapus Transaksi</span>
                                    @else
                                        <span class="text-blue-500 uppercase tracking-wider text-[11px] bg-blue-50 px-2 py-0.5 rounded ml-1">Edit Transaksi</span>
                                    @endif 
                                    <span class="text-gray-400 ml-1">({{ $pending->nomor_nota }})</span>
                                </p>
                                <p class="text-xs font-semibold text-gray-500 mt-1.5 flex items-center gap-2">
                                    <span class="bg-gray-100 px-2 py-1 rounded text-gray-700">Oleh: {{ $pending->requester->nama_lengkap ?? 'Admin' }}</span> 
                                    <span class="italic text-gray-600">"{{ $pending->approval_reason }}"</span>
                                </p>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <form action="{{ route('owner.transaction.approve', $pending->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-5 py-2.5 {{ $pending->approval_status === 'pending_delete' ? 'bg-red-500 hover:bg-red-600 shadow-red-500/20' : 'bg-blue-500 hover:bg-blue-600 shadow-blue-500/20' }} text-white text-xs font-bold rounded-xl transition shadow-lg flex items-center gap-1.5 transform active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $pending->approval_status === 'pending_delete' ? 'Izinkan Hapus' : 'Izinkan Edit' }}
                                    </button>
                                </form>
                                <form action="{{ route('owner.transaction.reject', $pending->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50 transition shadow-sm transform active:scale-95">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- ================= FILTER & PENCARIAN ================= -->
    <form action="{{ route('owner.transaction.index') }}" method="GET" class="bg-white p-4 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-11 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 text-sm font-semibold transition" placeholder="Cari No. Invoice atau Pelanggan..." onblur="this.form.submit()">
        </div>

        <!-- Dropdown Filters -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 w-full md:w-2/3">
            <select name="cabang" onchange="this.form.submit()" class="block w-full px-3 py-3 text-sm font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863] cursor-pointer">
                <option value="all">Semua Outlet</option>
                @if(isset($branches))
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('cabang') == $branch->id ? 'selected' : '' }}>{{ $branch->nama_cabang }}</option>
                    @endforeach
                @endif
            </select>
            <select name="tanggal" onchange="this.form.submit()" class="block w-full px-3 py-3 text-sm font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863] cursor-pointer">
                <option value="all">Semua Tanggal</option>
                <option value="hari_ini" {{ request('tanggal') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                <option value="kemarin" {{ request('tanggal') == 'kemarin' ? 'selected' : '' }}>Kemarin</option>
                <option value="7_hari" {{ request('tanggal') == '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                <option value="bulan_ini" {{ request('tanggal') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
            <select name="metode" onchange="this.form.submit()" class="block w-full px-3 py-3 text-sm font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863] cursor-pointer">
                <option value="all">Semua Pembayaran</option>
                <option value="cash" {{ request('metode') == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                <option value="qris" {{ request('metode') == 'qris' ? 'selected' : '' }}>QRIS</option>
                <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="tempo" {{ request('metode') == 'tempo' ? 'selected' : '' }}>Tempo (Kasbon)</option>
            </select>
            
            @if(request()->anyFilled(['search', 'cabang', 'tanggal', 'metode']))
                <div class="col-span-2 lg:col-span-2 flex">
                    <a href="{{ route('owner.transaction.index') }}" class="w-full flex items-center justify-center bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 transition">Reset Filter</a>
                </div>
            @endif
        </div>
    </form>

    <!-- ================= TRANSACTION TABLE ================= -->
    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-xs font-extrabold uppercase tracking-wider text-left border-y border-gray-200">
                    <tr>
                        <th class="px-6 py-5">Waktu & Invoice</th>
                        <th class="px-6 py-5">Kasir & Cabang</th>
                        <th class="px-6 py-5">Pelanggan</th>
                        <th class="px-6 py-5 text-center">Metode</th>
                        <th class="px-6 py-5 text-right">Total Transaksi</th>
                        <th class="px-6 py-5 text-center">Status Transaksi</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                    @if(isset($transactions))
                        @forelse($transactions as $trx)
                            @php
                                $isTempo = strtolower($trx->metode_bayar) === 'tempo' || strtolower($trx->metode_bayar) === 'cash_tempo';
                                $belumLunas = $isTempo && $trx->cashTempo && $trx->cashTempo->sisa_piutang > 0;
                            @endphp
                            <tr class="hover:bg-gray-50/80 transition-colors {{ $belumLunas ? 'bg-red-50/10' : '' }}">
                                <!-- Waktu & Invoice -->
                                <td class="px-6 py-4">
                                    <p class="font-black text-[#CC9863]">{{ $trx->nomor_nota }}</p>
                                    <p class="text-xs font-semibold text-gray-400 mt-1">{{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('d M Y, H:i') }} WIB</p>
                                </td>
                                <!-- Kasir & Cabang -->
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ $trx->cashier->nama_lengkap ?? 'Kasir' }}</p>
                                    <p class="text-[11px] font-semibold text-gray-500 flex items-center gap-1 mt-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        {{ $trx->branch->nama_cabang ?? 'Outlet Pusat' }}
                                    </p>
                                </td>
                                <!-- Pelanggan -->
                                <td class="px-6 py-4">
                                    @if($trx->member)
                                        <p class="font-bold text-gray-900">{{ $trx->member->nama }}</p>
                                        <p class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md inline-block mt-1">Member Terdaftar</p>
                                    @else
                                        <p class="font-semibold text-gray-500 italic">Pelanggan Umum</p>
                                    @endif
                                </td>
                                <!-- Metode -->
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $methodColors = [
                                            'cash' => 'bg-green-50 text-green-600 border-green-100',
                                            'qris' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'transfer' => 'bg-purple-50 text-purple-600 border-purple-100',
                                            'tempo' => 'bg-red-50 text-red-600 border-red-100',
                                            'cash_tempo' => 'bg-red-50 text-red-600 border-red-100'
                                        ];
                                        $colorClass = $methodColors[strtolower($trx->metode_bayar)] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold border uppercase tracking-wider {{ $colorClass }}">
                                        {{ str_replace('_', ' ', $trx->metode_bayar) }}
                                    </span>
                                </td>
                                <!-- Nominal -->
                                <td class="px-6 py-4 text-right">
                                    <p class="font-black text-gray-900 text-base">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</p>
                                    @if($belumLunas)
                                        <span class="text-[10px] text-red-600 font-bold bg-red-100 px-2 py-0.5 rounded-md inline-block mt-1">Sisa: Rp {{ number_format($trx->cashTempo->sisa_piutang, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <!-- Status Approval -->
                                <td class="px-6 py-4 text-center">
                                    @if($trx->approval_status === 'approved_edit')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-blue-50 text-blue-600 border border-blue-100">
                                            Akses Edit Terbuka
                                        </span>
                                    @elseif($trx->approval_status === 'pending_edit' || $trx->approval_status === 'pending_delete')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-orange-50 text-orange-600 border border-orange-100">
                                            Menunggu Anda
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-green-50 text-green-600 border border-green-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            Sah / Selesai
                                        </span>
                                    @endif
                                </td>
                                <!-- Aksi -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="#" class="text-gray-500 hover:text-[#CC9863] bg-gray-50 hover:bg-orange-50 p-2.5 rounded-xl transition border border-gray-200 hover:border-[#CC9863]" title="Lihat Detail Transaksi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <button class="text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-200 p-2.5 rounded-xl transition border border-gray-200" title="Cetak / Download Struk PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center bg-gray-50/50">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 text-gray-300 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-gray-900 font-bold text-base mb-1">Belum Ada Transaksi</h3>
                                        <p class="text-gray-500 text-sm max-w-sm">Data pencarian atau riwayat penjualan tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    @endif

                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($transactions) && $transactions->hasPages())
        <div class="px-6 py-5 border-t border-gray-100 flex items-center justify-between bg-white">
            <span class="text-sm font-semibold text-gray-500">Menampilkan <span class="text-gray-900">{{ $transactions->firstItem() }}</span> - <span class="text-gray-900">{{ $transactions->lastItem() }}</span> dari total <span class="text-gray-900">{{ $transactions->total() }}</span> transaksi</span>
            <div>
                {{ $transactions->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
    </div>

</main>
@endsection