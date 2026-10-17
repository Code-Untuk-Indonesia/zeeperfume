@extends('template.sidebar')
@section('title', 'Detail Member')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 sm:px-6 lg:px-10 py-6 lg:py-8 w-full relative">

        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('admin.member.index') }}" class="hover:text-[#CC9863] transition font-semibold">Kelola
                    Member</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#CC9863] font-bold">Detail Member</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">{{ $memberData->nama }}</h1>
            <p class="text-gray-500 text-sm mt-1">Informasi lengkap dan riwayat transaksi member.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          <!-- KOLOM KIRI: INFO MEMBER -->
        <div class="lg:col-span-1 space-y-6" x-data="{ openPointModal: false }">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-[#CC9863]/10 rounded-bl-full -z-0"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-xl font-bold text-gray-500 uppercase shrink-0">
                            {{ substr($memberData->nama, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-lg">{{ $memberData->nama }}</h3>
                            <p class="text-xs text-gray-500 font-medium">{{ $memberData->kode_member }}</p>
                            @if($memberData->poin >= 500)
                                <span class="inline-block mt-1 px-2 py-0.5 bg-yellow-100 text-yellow-800 text-[10px] font-bold uppercase rounded border border-yellow-200">VIP Member</span>
                            @else
                                <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase rounded border border-gray-200">Reguler</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Kontak</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $memberData->no_telp ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $memberData->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Bergabung Sejak</p>
                            <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($memberData->tanggal_bergabung)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Poin Saat Ini</p>
                                <p class="text-lg font-black text-[#CC9863]">{{ number_format($memberData->poin, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Belanja</p>
                                <p class="text-base font-black text-gray-900">Rp {{ number_format($memberData->total_belanja, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col gap-2">
                        <button type="button" @click="openPointModal = true" class="w-full text-center py-2.5 bg-orange-50 hover:bg-orange-100 text-[#CC9863] border border-[#CC9863]/30 rounded-xl text-xs font-bold transition flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tukar Reward (Kurangi Poin)
                        </button>
                        <a href="{{ route('admin.member.edit', $memberData->id) }}" class="w-full text-center py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 rounded-xl text-xs font-bold transition">Edit Data Member</a>
                    </div>
                </div>
            </div>

            <!-- MODAL TUKAR POIN ALPINE JS -->
            <div x-cloak x-show="openPointModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openPointModal = false" x-show="openPointModal" x-transition.opacity></div>

                <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden" x-show="openPointModal" x-transition.scale.90>
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-base font-black text-gray-900">Tukar Poin Reward</h3>
                        <button type="button" @click="openPointModal = false" class="text-gray-400 hover:text-red-500 font-bold text-xl leading-none">&times;</button>
                    </div>

                    <form action="{{ route('admin.member.deduct_point', $memberData->id) }}" method="POST">
                        @csrf
                        <div class="p-6 space-y-4">
                            <div class="bg-orange-50 border border-orange-100 rounded-xl p-3 flex justify-between items-center">
                                <span class="text-xs font-bold text-gray-600 uppercase">Sisa Poin</span>
                                <span class="text-lg font-black text-[#CC9863]">{{ number_format($memberData->poin, 0, ',', '.') }}</span>
                            </div>

                            <div>
                                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-2">Jumlah Poin Dipotong <span class="text-red-500">*</span></label>
                                <input type="number" name="jumlah_poin" min="1" max="{{ $memberData->poin }}" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-black text-lg text-center"
                                    placeholder="Contoh: 150">
                            </div>

                            <div>
                                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-2">Keterangan / Nama Reward</label>
                                <input type="text" name="keterangan"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 text-sm font-medium"
                                    placeholder="Cth: Penukaran 1 Botol Parfum Gratis">
                            </div>
                        </div>

                        <div class="p-5 border-t border-gray-100 bg-gray-50 flex gap-3">
                            <button type="button" @click="openPointModal = false" class="flex-1 py-3 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-50">Batal</button>
                            <button type="submit" class="flex-1 py-3 bg-[#CC9863] text-white rounded-xl font-bold text-sm hover:bg-[#b58555] flex items-center justify-center gap-2 transition-colors">
                                Potong Poin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

            <!-- KOLOM KANAN: RIWAYAT TRANSAKSI -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h2 class="text-base font-black text-gray-900">Riwayat Transaksi
                            ({{ $memberData->transactions_count }})</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead
                                class="bg-gray-50 text-gray-400 text-[10px] font-bold uppercase tracking-wider border-y border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Waktu & Nota</th>
                                    <th class="px-6 py-4">Metode Bayar</th>
                                    <th class="px-6 py-4 text-right">Total</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                                @forelse($transactions as $trx)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-900">
                                                {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->translatedFormat('d M Y') }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 font-semibold mt-0.5">
                                                {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('H:i') }}
                                                <span class="text-gray-700">{{ $trx->nomor_nota }}</span>
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-[10px] font-bold uppercase">{{ str_replace('_', ' ', $trx->metode_bayar) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <p class="font-black text-gray-900">Rp
                                                {{ number_format($trx->total_belanja, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('admin.transaction.show', $trx->id) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:text-white hover:bg-[#CC9863] transition-colors border border-gray-200 hover:border-[#CC9863]"
                                                title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm italic">
                                            Member ini belum memiliki riwayat transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($transactions->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-white">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
@endsection
