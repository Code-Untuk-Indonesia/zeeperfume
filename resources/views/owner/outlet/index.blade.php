@extends('template.sidebar')
@section('title', 'Manajemen Outlet')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Manajemen Outlet</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola informasi, lokasi, dan status operasional seluruh cabang toko.</p>
        </div>
        <a href="{{ route('owner.outlet.create') }}" class="bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm shrink-0 w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Outlet Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 font-semibold uppercase">Total Outlet</p>
            <h4 class="text-lg font-bold text-gray-900">{{ number_format($totalOutlets, 0, ',', '.') }} Cabang</h4>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 font-semibold uppercase">Outlet Aktif</p>
            <h4 class="text-lg font-bold text-gray-900">{{ number_format($activeOutlets, 0, ',', '.') }} Cabang</h4>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-400 font-semibold uppercase">Total Pegawai Outlet</p>
            <h4 class="text-lg font-bold text-gray-900">{{ number_format($totalEmployees, 0, ',', '.') }} Orang</h4>
        </div>
    </div>

    <form method="GET" action="{{ route('owner.outlet.index') }}" class="bg-white p-4 lg:p-5 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-3 justify-between">
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <input type="search" name="search" value="{{ request('search') }}" class="w-full sm:w-96 px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/30 text-sm" placeholder="Cari nama, telepon, atau alamat outlet...">
            <select name="status" class="px-3 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863] focus:bg-white">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(request('status') === 'aktif')>Aktif Beroperasi</option>
                <option value="tutup" @selected(request('status') === 'tutup')>Tidak Aktif</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-3 rounded-xl bg-[#1C1D21] text-white text-sm font-bold hover:bg-black">Cari</button>
            @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('owner.outlet.index') }}" class="px-5 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50">Reset</a>
            @endif
        </div>
    </form>

    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-y border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Informasi Outlet</th>
                        <th class="px-6 py-4">Kontak &amp; Alamat</th>
                        <th class="px-6 py-4">Pegawai Aktif</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse ($outlets as $outlet)
                        @php($isActive = $outlet->deleted_at === null)
                        <tr class="hover:bg-gray-50 transition {{ $isActive ? '' : 'bg-gray-50/50' }}">
                            <td class="px-6 py-4 {{ $isActive ? '' : 'opacity-60' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl {{ $isActive ? 'bg-orange-50 text-[#CC9863]' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center font-bold shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-base">{{ $outlet->nama_cabang }}</p>
                                        <p class="text-xs text-gray-400 font-medium">ID: OTL-{{ str_pad($outlet->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 {{ $isActive ? '' : 'opacity-60' }}">
                                <p class="font-semibold text-gray-700">{{ $outlet->no_telepon ?: 'Nomor telepon belum diisi' }}</p>
                                <p class="text-xs text-gray-500 max-w-[220px] truncate" title="{{ $outlet->alamat }}">{{ $outlet->alamat ?: 'Alamat belum diisi' }}</p>
                            </td>
                            <td class="px-6 py-4 {{ $isActive ? '' : 'opacity-60' }}">
                                <span class="font-bold text-gray-900">{{ number_format($outlet->users_count, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-500">Orang</span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($isActive)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-green-50 text-green-700 border border-green-100"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('owner.outlet.edit', $outlet->id) }}" class="inline-block text-gray-400 hover:text-blue-500 transition p-1.5 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    @if ($isActive)
                                        <form method="POST" action="{{ route('owner.outlet.destroy', $outlet->id) }}" data-outlet-action="delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1.5 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50" title="Nonaktifkan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('owner.outlet.restore', $outlet->id) }}" data-outlet-action="restore">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-gray-400 hover:text-green-500 transition p-1.5 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50" title="Aktifkan Kembali">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Tidak ada data outlet yang ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">{{ $outlets->links() }}</div>
    </div>
</main>
@include('owner.outlet.partials.toast')
@endsection
