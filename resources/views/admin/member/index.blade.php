@extends('template.sidebar')
@section('title', 'Data Member Pelanggan')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 sm:px-6 lg:px-10 py-6 lg:py-8 w-full">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 lg:mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Member</h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Kelola data pelanggan dan riwayat belanja member.</p>
        </div>
        <a href="{{ route('admin.member.create') }}" class="w-full md:w-auto bg-[#CC9863] text-white px-5 py-3 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Member
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] lg:text-xs text-gray-400 font-bold uppercase tracking-wider">Total Member</p>
            <h4 class="text-base lg:text-lg font-extrabold text-gray-900">{{ number_format($totalMembers, 0, ',', '.') }} Orang</h4>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] lg:text-xs text-gray-400 font-bold uppercase tracking-wider">Member VIP</p>
            <h4 class="text-base lg:text-lg font-extrabold text-gray-900">{{ number_format($vipMembers, 0, ',', '.') }} Orang</h4>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] lg:text-xs text-gray-400 font-bold uppercase tracking-wider">Total Belanja Member</p>
            <h4 class="text-base lg:text-lg font-extrabold text-gray-900">Rp {{ number_format($totalBelanjaSemua, 0, ',', '.') }}</h4>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.member.index') }}" class="bg-white p-4 lg:p-5 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-3 justify-between">
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <input type="search" name="search" value="{{ request('search') }}" class="w-full sm:w-96 px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/30 text-sm" placeholder="Cari nama, no HP, atau kode member...">
            <select name="tipe" class="px-3 py-3 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:border-[#CC9863] focus:bg-white">
                <option value="semua">Semua Tipe</option>
                <option value="reguler" @selected(request('tipe') === 'reguler')>Reguler</option>
                <option value="vip" @selected(request('tipe') === 'vip')>VIP Member</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-5 py-3 rounded-xl bg-[#1C1D21] text-white text-sm font-bold hover:bg-black">Cari</button>
            @if (request()->hasAny(['search', 'tipe']))
                <a href="{{ route('admin.member.index') }}" class="px-5 py-3 rounded-xl border border-gray-200 text-gray-600 text-sm font-bold hover:bg-gray-50">Reset</a>
            @endif
        </div>
    </form>

    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
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
                <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                    @include('admin.member.partials.table-rows')
                </tbody>
            </table>
        </div>
        <div class="px-4 md:px-6 py-4 border-t border-gray-100">
            {{ $members->links() }}
        </div>
    </div>
</main>

@include('admin.member.partials.toast')
@endsection
