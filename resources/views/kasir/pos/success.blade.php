@extends('template.kasir')
@section('title', 'Pembayaran Berhasil')

@section('content')

{{-- ========================================================= --}}
{{-- AREA NON-PRINT (TAMPILAN WEB) --}}
{{-- ========================================================= --}}
<div id="non-print-area" class="flex-1 overflow-y-auto p-4 lg:p-8 bg-[#FAFAFA] w-full flex items-center justify-center h-full">

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
                    <!-- Tombol Kirim WA Dinamis dengan Teks Rinci -->
                    @php
                        // Format nomor telepon member (ganti 0 di depan jadi 62)
                        $phone = $transaction->member ? preg_replace('/^0/', '62', $transaction->member->no_telp) : '';

                        // Membuat pesan WhatsApp yang Rapi
                        $waText = "Halo Kak" . ($transaction->member ? " " . $transaction->member->nama : "") . ",\n\n";
                        $waText .= "Terima kasih telah berbelanja di *ZeePerfume*.\n";
                        $waText .= "Berikut adalah detail transaksi Anda:\n\n";
                        $waText .= "🧾 *No. Invoice:* " . $transaction->nomor_nota . "\n";
                        $waText .= "📅 *Tanggal:* " . \Carbon\Carbon::parse($transaction->tanggal_waktu)->format('d M Y H:i') . "\n";
                        $waText .= "💳 *Metode:* " . strtoupper($transaction->metode_bayar) . "\n";
                        $waText .= "🛒 *Total Tagihan:* Rp " . number_format($transaction->total_belanja, 0, ',', '.') . "\n";

                        if($transaction->metode_bayar === 'cash') {
                            $waText .= "💵 *Uang Diterima:* Rp " . number_format($transaction->nominal_bayar, 0, ',', '.') . "\n";
                            $waText .= "🔄 *Kembalian:* Rp " . number_format($transaction->kembalian, 0, ',', '.') . "\n";
                        }

                        $waText .= "\nSemoga harimu menyenangkan! ✨";

                        // Encode text agar aman ditaruh di URL
                        $waLink = $phone ? "https://wa.me/{$phone}?text=" . urlencode($waText) : '#';
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

{{-- ========================================================= --}}
{{-- AREA PRINT (KHUSUS PRINTER THERMAL) --}}
{{-- ========================================================= --}}
<div id="receipt-area" class="hidden text-black font-mono text-[12px] leading-tight">

    <!-- Header Struk -->
    <div style="text-align: center; margin-bottom: 10px;">
        <h2 style="font-size: 16px; font-weight: bold; margin:0;">ZeePerfume</h2>
        <p style="margin: 2px 0;">Jl. Contoh Alamat Toko No. 123</p>
        <p style="margin: 2px 0;">Telp: 0812-3456-7890</p>
    </div>

    <div style="border-bottom: 1px dashed #000; margin-bottom: 8px; padding-bottom: 8px;">
        <p style="margin: 2px 0;">No   : {{ $transaction->nomor_nota }}</p>
        <p style="margin: 2px 0;">Tgl  : {{ \Carbon\Carbon::parse($transaction->tanggal_waktu)->format('d/m/Y H:i') }}</p>
        <p style="margin: 2px 0;">Kasir: {{ $transaction->kasir->nama_lengkap ?? 'Kasir' }}</p>
        <p style="margin: 2px 0;">Plgn : {{ $transaction->member->nama ?? 'Umum' }}</p>
    </div>

    <!-- Daftar Item -->
    <div style="border-bottom: 1px dashed #000; margin-bottom: 8px; padding-bottom: 4px;">
        <!-- PASTIKAN ANDA SUDAH ME-LOAD RELATION 'details' PADA CONTROLLER (with('details')) -->
        @if(isset($transaction->details))
            @foreach($transaction->details as $item)
            <div style="margin-bottom: 6px;">
                <p style="margin: 0; font-weight: bold;">{{ $item->variant->nama_varian ?? 'Nama Produk' }}</p>
                <div style="display: flex; justify-content: space-between;">
                    <span>{{ $item->qty }} x {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                    <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        @else
            <!-- Fallback jika detail tidak di-load dari controller -->
            <div style="display: flex; justify-content: space-between;">
                <span>Total Item Belanja</span>
                <span>{{ number_format($transaction->total_belanja, 0, ',', '.') }}</span>
            </div>
        @endif
    </div>

    <!-- Ringkasan Total -->
    <div style="border-bottom: 1px dashed #000; margin-bottom: 8px; padding-bottom: 8px;">
        <div style="display: flex; justify-content: space-between;">
            <span>Subtotal</span>
            <span>{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>
        @if($transaction->diskon_nominal > 0)
        <div style="display: flex; justify-content: space-between;">
            <span>Diskon</span>
            <span>-{{ number_format($transaction->diskon_nominal, 0, ',', '.') }}</span>
        </div>
        @endif
        <div style="display: flex; justify-content: space-between; font-weight: bold; margin-top: 4px; font-size: 14px;">
            <span>TOTAL</span>
            <span>{{ number_format($transaction->total_belanja, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Pembayaran -->
    <div style="border-bottom: 1px dashed #000; margin-bottom: 8px; padding-bottom: 8px;">
        <div style="display: flex; justify-content: space-between;">
            <span>Metode</span>
            <span style="text-transform: uppercase;">{{ $transaction->metode_bayar }}</span>
        </div>
        @if($transaction->metode_bayar === 'cash')
        <div style="display: flex; justify-content: space-between;">
            <span>Bayar (Tunai)</span>
            <span>{{ number_format($transaction->nominal_bayar, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span>Kembali</span>
            <span>{{ number_format($transaction->kembalian, 0, ',', '.') }}</span>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div style="text-align: center; margin-top: 10px;">
        <p style="margin: 2px 0;">Terima Kasih Atas Kunjungan Anda</p>
        <p style="margin: 2px 0; font-size: 10px;">Barang yang sudah dibeli</p>
        <p style="margin: 2px 0; font-size: 10px;">tidak dapat ditukar/dikembalikan</p>
    </div>
</div>

<style>
    /* CSS Khusus untuk mengatur Tampilan Printer Thermal */
    @media print {
        /* Reset margin browser */
        @page {
            margin: 0;
            padding: 0;
        }

        /* Sembunyikan elemen Web (Sidebar, Background, dll) */
        body * {
            visibility: hidden;
        }

        #non-print-area {
            display: none !important;
        }

        /* Hanya tampilkan Struk Thermal (lebar 58mm / 80mm) */
        #receipt-area, #receipt-area * {
            visibility: visible;
        }

        #receipt-area {
            display: block !important;
            position: absolute;
            left: 0;
            top: 0;
            width: 58mm; /* Lebar default printer kasir kecil */
            padding: 5mm;
            background: white !important;
            color: black !important;
        }
    }
</style>

@endsection
