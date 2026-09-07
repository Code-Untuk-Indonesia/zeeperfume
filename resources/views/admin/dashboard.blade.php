@extends('template.sidebar')
@section('title', 'Dashboard Admin')

@section('content')
<div class="flex flex-col xl:flex-row flex-1 overflow-y-auto w-full bg-[#FAFAFA]">

    <main class="flex-1 px-4 lg:px-10 py-6 lg:py-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau operasional kasir, pendapatan harian, dan peringatan stok.</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button class="text-sm bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-semibold shadow-sm flex items-center justify-center gap-2 flex-1 sm:flex-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ $today->format('d M Y') }}
                </button>
                <a href="{{ route('admin.transaction.index') }}" class="text-sm bg-[#1C1D21] text-white px-4 py-2.5 rounded-xl font-semibold shadow-sm hover:bg-gray-800 flex items-center justify-center gap-2 flex-1 sm:flex-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Rekap Riwayat
                </a>
            </div>
        </div>

        <!-- REKAP TOP CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-3xl border border-[#CC9863]/30 shadow-[0_4px_20px_rgb(0,0,0,0.05)] flex items-center gap-4 relative z-10">
                <div class="bg-orange-50 p-3.5 rounded-2xl text-[#CC9863]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Transaksi Hari Ini</p>
                    <h3 class="text-xl font-extrabold text-gray-900">{{ $totalTransaksi }} Trx</h3>
                </div>
            </div>

            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                <div class="bg-green-50 p-3.5 rounded-2xl text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Omzet Hari Ini</p>
                    <h3 class="text-xl font-extrabold text-gray-900">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</h3>
                </div>
            </div>

            <a href="{{ route('admin.transaction.index') }}?metode=online" class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition block cursor-pointer">
                <div class="bg-blue-50 p-3.5 rounded-2xl text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Pesanan Online</p>
                    <div class="flex items-center gap-2">
                        <h3 class="text-xl font-extrabold text-gray-900">{{ $pesananOnline }}</h3>
                        @if($perluCetakResi > 0)
                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 py-0.5 rounded font-bold">{{ $perluCetakResi }} Perlu Resi</span>
                        @endif
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.stock.index') }}" class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-4 hover:shadow-md transition cursor-pointer">
                <div class="bg-red-50 p-3.5 rounded-2xl text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-0.5">Stok Menipis</p>
                    <h3 class="text-xl font-extrabold {{ $jumlahStokMenipis > 0 ? 'text-red-500' : 'text-gray-900' }}">{{ $jumlahStokMenipis }} Item</h3>
                </div>
            </a>
        </div>

        <!-- LAPORAN CABANG -->
        <h2 class="text-lg font-bold text-gray-900 mb-4">Laporan Pendapatan Hari Ini</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            @foreach($laporanCabang as $cabang)
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-[#CC9863] font-bold text-xs uppercase">
                                {{ substr($cabang['nama'], 0, 3) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $cabang['nama'] }}</h3>
                                <p class="text-xs text-gray-500">Kasir: {{ explode(' ', trim($cabang['kasir']))[0] }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold {{ $cabang['setoran'] > 0 ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }} px-2 py-1 rounded-md">
                            {{ $cabang['setoran'] > 0 ? 'Aktif' : 'Standby' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-xs text-gray-500">Total Setoran</span>
                        <span class="text-xl font-extrabold text-gray-900">Rp {{ number_format($cabang['setoran'], 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mb-1">
                        <div class="bg-[#CC9863] h-1.5 rounded-full" style="width: {{ $cabang['persentase'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 text-right">{{ $cabang['persentase'] }}% dari target (5Jt)</p>
                </div>
            @endforeach
        </div>

        <!-- STOK MENIPIS TABLE -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                <div>
                    <h3 class="font-bold text-lg text-gray-900">Perhatian Stok & Operasional</h3>
                    <p class="text-xs text-gray-500 mt-1">Item yang membutuhkan tindakan segera (Sisa < 5).</p>
                </div>
                <a href="{{ route('admin.stock.index') }}" class="text-sm text-[#CC9863] font-semibold hover:underline bg-white px-3 py-1.5 rounded-lg border border-gray-200">Kelola Stok</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap text-sm text-left">
                    <thead class="bg-white text-gray-400 text-[10px] font-extrabold uppercase tracking-wider border-y border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Nama Produk & Varian</th>
                            <th class="px-6 py-3">Lokasi Cabang</th>
                            <th class="px-6 py-3 text-center">Sisa Stok</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($stokMenipis as $stok)
                            @php $isHabis = $stok->stok <= 0; @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-2 h-2 rounded-full {{ $isHabis ? 'bg-red-500 animate-pulse' : 'bg-orange-400' }}"></span>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $stok->variant->product->nama_produk ?? '' }} - {{ $stok->variant->nama_varian ?? 'Unknown' }}</p>
                                            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-0.5">{{ $isHabis ? 'STOK HABIS' : 'STOK MENIPIS' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-700">{{ $stok->branch->nama_cabang ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-black {{ $isHabis ? 'text-red-500' : 'text-orange-600' }}">{{ $stok->stok }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.stock.index') }}" class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg shadow-sm hover:bg-gray-200 font-bold transition">Isi Stok</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400 font-medium italic">
                                    Semua stok aman terkendali.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <aside class="w-full xl:w-[360px] bg-white xl:bg-[#FDFBF9] px-4 lg:px-8 py-6 lg:py-8 border-t xl:border-t-0 xl:border-l border-gray-100 shrink-0">
        <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-6">Aktivitas Sistem</h2>

        <!-- AKTIVITAS LIVE -->
        <div>
            <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Transaksi Terakhir (Live)</h3>
                <a href="{{ route('admin.transaction.index') }}" class="text-xs text-[#CC9863] font-bold hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($transaksiLive as $trx)
                    <a href="{{ route('admin.transaction.show', $trx->id) }}" class="block bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md hover:border-[#CC9863]/30 transition cursor-pointer">
                        <div class="flex items-center gap-3">
                            @if($trx->shipment)
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 font-bold text-xs shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-[#CC9863] font-bold text-xs uppercase shrink-0">
                                    {{ substr($trx->cashier->nama_lengkap ?? 'A', 0, 2) }}
                                </div>
                            @endif

                            <div>
                                <h4 class="font-black text-sm text-gray-900">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</h4>
                                <p class="text-[10px] text-gray-500 font-semibold mt-0.5">
                                    {{ $trx->shipment ? 'Online (' . $trx->shipment->jenis_pengiriman . ')' : 'POS (' . ($trx->branch->nama_cabang ?? 'Pusat') . ')' }}
                                </p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 text-right max-w-16">
                            {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->diffForHumans() }}
                        </span>
                    </a>
                @empty
                    <div class="text-center py-6 text-xs text-gray-400 italic">Belum ada transaksi hari ini.</div>
                @endforelse
            </div>
        </div>
    </aside>

</div>
@endsection
