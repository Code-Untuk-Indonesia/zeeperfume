@forelse($members as $member)
@php
    $isVIP = $member->poin >= 500; // Logika Tipe VIP
@endphp
<tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
    <td class="px-4 md:px-6 py-4 flex items-center gap-3 min-w-[250px]">
        <div class="w-10 h-10 rounded-full {{ $isVIP ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center font-bold shrink-0">
            {{ mb_strtoupper(mb_substr($member->nama, 0, 2)) }}
        </div>
        <div>
            <p class="font-bold text-gray-900 truncate max-w-[150px] md:max-w-xs">{{ $member->nama }}</p>
            <p class="text-xs text-gray-400">{{ $member->no_telp }} • ID: {{ $member->kode_member }}</p>
        </div>
    </td>
    <td class="px-4 md:px-6 py-4">
        @if($isVIP)
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] sm:text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                VIP Member
            </span>
        @else
            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] sm:text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                Reguler
            </span>
        @endif
    </td>
    <td class="px-4 md:px-6 py-4">
        <p class="font-bold text-gray-900 text-sm">Rp {{ number_format($member->total_belanja ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400">{{ $member->transactions_count }} Transaksi</p>
    </td>
    <td class="px-4 md:px-6 py-4 font-bold text-[#CC9863] text-sm">
        {{ number_format($member->poin, 0, ',', '.') }} Pts
    </td>
    <td class="px-4 md:px-6 py-4 text-center">
        <div class="flex justify-center gap-1 sm:gap-2">
            <button class="text-gray-400 hover:text-blue-500 transition p-1.5 bg-gray-50 hover:bg-blue-50 rounded-lg" title="Edit Member">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
            <button class="text-gray-400 hover:text-red-500 transition p-1.5 bg-gray-50 hover:bg-red-50 rounded-lg" title="Hapus">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="px-6 py-10 text-center text-gray-500 flex flex-col items-center justify-center w-full">
        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        Tidak ada data member yang ditemukan.
    </td>
</tr>
@endforelse

<!-- Data Pagination Tersembunyi untuk Update UI via AJAX -->
<tr class="hidden" id="pagination-links-data">
    <td>{!! $members->appends(request()->query())->links() !!}</td>
</tr>
