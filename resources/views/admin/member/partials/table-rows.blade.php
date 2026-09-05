@forelse ($members as $member)
    @php($isVip = $member->poin >= 500)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-4 md:px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full {{ $isVip ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center font-bold shrink-0">
                    {{ mb_strtoupper(mb_substr($member->nama, 0, 2)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-900">{{ $member->nama }}</p>
                    <p class="text-xs text-gray-400">{{ $member->no_telp ?: 'Nomor telepon belum diisi' }} &bull; ID: {{ $member->kode_member }}</p>
                </div>
            </div>
        </td>
        <td class="px-4 md:px-6 py-4">
            @if ($isVip)
                <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] sm:text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">VIP Member</span>
            @else
                <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] sm:text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">Reguler</span>
            @endif
        </td>
        <td class="px-4 md:px-6 py-4">
            <p class="font-bold text-gray-900">Rp {{ number_format($member->total_belanja, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400">{{ number_format($member->transactions_count, 0, ',', '.') }} Transaksi</p>
        </td>
        <td class="px-4 md:px-6 py-4 font-bold text-[#CC9863]">{{ number_format($member->poin, 0, ',', '.') }} Pts</td>
        <td class="px-4 md:px-6 py-4">
            <div class="flex justify-center gap-2">
                <a href="{{ route('admin.member.edit', $member->id) }}" class="text-gray-400 hover:text-blue-500 transition p-1.5 bg-gray-50 hover:bg-blue-50 rounded-lg" title="Edit Member">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </a>
                <form method="POST" action="{{ route('admin.member.destroy', $member->id) }}" data-delete-member>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1.5 bg-gray-50 hover:bg-red-50 rounded-lg" title="Hapus Member">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-10 text-center text-gray-500">Tidak ada data member yang ditemukan.</td>
    </tr>
@endforelse
