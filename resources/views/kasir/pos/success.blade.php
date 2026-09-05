@extends('template.kasir')
@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="flex-1 overflow-y-auto p-4 lg:p-8 bg-[#FAFAFA] w-full flex items-center justify-center h-full">

    <div class="bg-white rounded-3xl border border-gray-100 shadow-xl w-full max-w-lg overflow-hidden relative">

        <!-- Ornamen Background -->
        <div class="absolute top-0 left-0 w-full h-32 bg-green-50/50"></div>
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-100 rounded-full blur-2xl opacity-60"></div>

        <div class="p-8 md:p-10 relative z-10 flex flex-col items-center text-center">

            <!-- Icon Success Animasi -->
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-6 shadow-sm ring-8 ring-white relative">
                <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-20"></div>
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Pembayaran Berhasil!</h1>
            <p class="text-gray-500 text-sm mb-8">No. Invoice: <span class="font-bold text-gray-700">{{ $transaction->nomor_nota }}</span></p>

            <!-- Rincian Pembayaran Box -->
            <div class="w-full bg-gray-50 rounded-2xl p-5 mb-8 border border-gray-100">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-semibold text-gray-500">Metode Bayar</span>
                    <span class="text-sm font-bold text-gray-900 uppercase">{{ $transaction->metode_bayar }}</span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-semibold text-gray-500">Total Tagihan</span>
                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($transaction->total_belanja, 0, ',', '.') }}</span>
                </div>

                @if($transaction->metode_bayar === 'cash')
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-semibold text-gray-500">Uang Diterima (Tunai)</span>
                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($transaction->nominal_bayar, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="w-full h-px bg-gray-200 border-dashed border-b border-gray-300 mb-4"></div>

                <div class="flex justify-between items-center {{ $transaction->kembalian > 0 ? 'bg-green-50/50 border-green-100 text-green-600' : 'bg-gray-100 border-gray-200 text-gray-700' }} p-3 rounded-xl border">
                    <span class="text-sm font-bold">Kembalian</span>
                    <span class="text-2xl font-extrabold">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="w-full space-y-3">
                <!-- Tombol Cetak -->
                <button onclick="window.print()" class="w-full bg-[#1C1D21] text-white py-4 rounded-xl font-bold text-base hover:bg-gray-800 transition shadow-lg flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Struk (Kertas)
                </button>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Tombol Kirim WA Dinamis -->
                    @php
                        // Format nomor telepon member (ganti 0 di depan jadi 62)
                        $phone = $transaction->member ? preg_replace('/^0/', '62', $transaction->member->no_telp) : '';
                        $waLink = $phone ? "https://wa.me/{$phone}?text=Halo%20kak,%20berikut%20adalah%20detail%20pembayaran%20Anda%20dengan%20No%20Invoice:%20{$transaction->nomor_nota}" : '#';
                    @endphp

                    <a href="{{ $waLink }}" target="_blank" class="w-full {{ $phone ? 'bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366]/20' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }} border border-transparent py-3.5 rounded-xl font-bold transition flex justify-center items-center gap-2 text-sm" {!! !$phone ? 'onclick="event.preventDefault(); alert(\'Nomor pelanggan tidak tersedia\');"' : '' !!}>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Kirim via WA
                    </a>

                    <!-- Link Kembali ke POS -->
                    <a href="{{ route('kasir.pos') }}" class="w-full bg-[#CC9863] text-white py-3.5 rounded-xl font-bold hover:bg-[#b58555] transition flex justify-center items-center text-sm shadow-sm">
                        Transaksi Baru
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
