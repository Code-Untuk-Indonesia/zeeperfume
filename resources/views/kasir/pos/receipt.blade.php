<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $transaction->nomor_nota }}</title>
    <style>
        /* CSS KHUSUS UNTUK PRINTER THERMAL MOBILE & WEB */
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace; /* Font monospace terbaik untuk struk */
            font-size: 12px;
            color: #000;
            background: #fff;
            line-height: 1.3;
        }
        .receipt-container {
            width: 58mm; /* Sesuaikan dengan ukuran kertas printer Bluetooth Anda (misal 58mm atau 80mm) */
            margin: 0 auto;
            padding: 5mm;
            background: #fff;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mt-2 { margin-top: 8px; }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        /* Auto print dialog when opened */
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()"> <!-- Script auto print saat halaman dimuat (Optional, bisa dihapus jika dipanggil dari Mobile SDK) -->

    <div class="receipt-container">
        <!-- HEADER -->
        <div class="text-center mb-2">
            <!-- Ganti dengan URL logo Anda -->
            <img src="{{ asset('asset/zeeperfume.png') }}" alt="Logo" style="width: 45px; height: 45px; margin: 0 auto 5px; display: block; border-radius: 50%; filter: grayscale(100%);">
            <div style="font-size: 16px; font-weight: bold;">ZeePerfume</div>
            <div style="font-size: 10px;">Cabang: {{ $transaction->branch->nama_cabang ?? 'Pusat' }}</div>
            <div style="font-size: 10px;">{{ $transaction->branch->alamat ?? 'Jl. Contoh Alamat Toko' }}</div>
            <div style="font-size: 10px;">Telp: {{ $transaction->branch->no_telp ?? '0812-3456-7890' }}</div>
        </div>

        <div class="divider"></div>

        <!-- INFO TRANSAKSI -->
        <div>
            <div class="flex-between"><span>No</span><span>: {{ $transaction->nomor_nota }}</span></div>
            <div class="flex-between"><span>Tgl</span><span>: {{ \Carbon\Carbon::parse($transaction->tanggal_waktu)->format('d/m/Y H:i') }}</span></div>
            <div class="flex-between"><span>Kasir</span><span>: {{ explode(' ', $transaction->kasir->nama_lengkap ?? 'Kasir')[0] }}</span></div>
            <div class="flex-between"><span>Plgn</span><span>: {{ $transaction->member->nama ?? 'Umum' }}</span></div>
        </div>

        <div class="divider"></div>

        <!-- DAFTAR BARANG -->
        <div class="mb-2">
            @if(isset($transaction->details))
                @foreach($transaction->details as $item)
                    @php
                        $itemDiscount = (float) ($item->diskon_satuan ?? 0);
                        $itemDiscountPercent = (float) ($item->diskon_persen ?? 0);
                    @endphp
                    <div class="mb-1">
                        <div class="font-bold">{{ $item->variant->product->nama_produk ?? 'Produk' }} - {{ $item->variant->nama_varian ?? 'Item' }}</div>
                        <div class="flex-between">
                            <span>{{ $item->qty }} x {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                            <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($itemDiscount > 0)
                        <div class="flex-between" style="font-size: 10px;">
                            <span>Diskon{{ $itemDiscountPercent > 0 ? ' ('.$itemDiscountPercent.'%)' : '' }}</span>
                            <span>-{{ number_format($itemDiscount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="flex-between">
                    <span>Total Item Belanja</span>
                    <span>{{ number_format($transaction->total_belanja, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

        <div class="divider"></div>

        <!-- TOTAL -->
        <div>
            <div class="flex-between">
                <span>Subtotal</span>
                <span>{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->diskon_nominal > 0)
            <div class="flex-between" style="font-size: 10px;">
                <span>Diskon{{ $transaction->deskripsi_diskon ? ' ('.$transaction->deskripsi_diskon.')' : '' }}</span>
                <span>-{{ number_format($transaction->diskon_nominal, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex-between font-bold mt-2" style="font-size: 14px;">
                <span>TOTAL</span>
                <span>{{ number_format($transaction->total_belanja, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- PEMBAYARAN -->
        <div>
            <div class="flex-between">
                <span>Metode</span>
                <span style="text-transform: uppercase;">{{ $transaction->metode_bayar === 'cash_tempo' ? 'TEMPO' : str_replace('_', ' ', $transaction->metode_bayar) }}</span>
            </div>

            @if($transaction->metode_bayar === 'cash')
                <div class="flex-between">
                    <span>Tunai</span>
                    <span>{{ number_format($transaction->nominal_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex-between">
                    <span>Kembali</span>
                    <span>{{ number_format($transaction->kembalian, 0, ',', '.') }}</span>
                </div>
            @endif

            @if($transaction->cashTempo)
                <div class="flex-between mt-2">
                    <span>Sisa Piutang</span>
                    <span class="font-bold">{{ number_format($transaction->cashTempo->sisa_piutang, 0, ',', '.') }}</span>
                </div>
                <div class="flex-between">
                    <span>Jatuh Tempo</span>
                    <span>{{ \Carbon\Carbon::parse($transaction->cashTempo->tanggal_jatuh_tempo)->format('d/m/Y') }}</span>
                </div>
            @endif
        </div>

        <div class="divider"></div>

        <!-- FOOTER -->
        <div class="text-center mt-2">
            <div class="font-bold">Terima Kasih</div>
            <div style="font-size: 10px; margin-top: 4px;">Barang yang sudah dibeli</div>
            <div style="font-size: 10px;">tidak dapat ditukar/dikembalikan</div>
        </div>
    </div>

</body>
</html>
