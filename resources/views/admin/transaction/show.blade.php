@extends('template.sidebar')
@section('title', 'Detail Transaksi')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative print:p-0 print:bg-white">

        <!-- Header & Breadcrumb (Sembunyi saat di-print) -->
        <div class="mb-8 print:hidden">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('admin.transaction.index') }}" class="hover:text-[#CC9863] transition font-semibold">Riwayat
                    Transaksi</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#CC9863] font-bold">{{ $transaction->nomor_nota }}</span>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Detail Transaksi</h1>
                    <p class="text-gray-500 text-sm mt-1">Rincian pesanan pelanggan, status pembayaran, dan pengiriman.</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2 w-full md:w-auto">
                    <!-- Tombol Ajukan Edit (Hanya jika belum diajukan/diizinkan) -->
                    @if (!in_array($transaction->approval_status, ['pending_edit', 'approved_edit']))
                        <button onclick="alert('Fitur pengajuan edit dapat dilakukan melalui halaman Riwayat Transaksi.')"
                            class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-50 transition flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Ajukan Edit
                        </button>
                    @elseif($transaction->approval_status === 'approved_edit')
                        <a href="{{ route('admin.transaction.edit', $transaction->id) }}"
                            class="bg-green-600 border border-green-700 text-white px-4 py-2.5 rounded-xl font-bold shadow-sm hover:bg-green-700 transition flex items-center gap-2 text-sm">
                            Lanjut Edit
                        </a>
                    @endif

                    <!-- Tombol Cetak Struk -->
                    <button onclick="window.print()"
                        class="bg-[#1C1D21] text-white px-4 py-2.5 rounded-xl font-bold shadow-sm hover:bg-black transition flex items-center gap-2 text-sm print-struk-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Cetak Struk
                    </button>

                    <!-- Tombol Cetak Resi (Hanya Muncul Jika Ada Shipment/Online) -->
                    @if ($transaction->shipment)
                        <button onclick="printResi()"
                            class="bg-[#CC9863] text-white px-4 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center gap-2 text-sm print-resi-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2">
                                </path>
                            </svg>
                            Cetak Resi Pengiriman
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT (Akan menyesuaikan berdasarkan class print yang aktif) -->
        <div id="print-area" class="flex flex-col xl:flex-row gap-6 print:block">

            <!-- TAMPILAN RESI KHUSUS (Hanya tampil saat tombol Cetak Resi diklik) -->
            @if ($transaction->shipment)
                <div class="hidden print-resi-only w-full border-2 border-black p-4 mb-4">
                    <h1 class="text-3xl font-black text-center mb-2 uppercase tracking-widest border-b-2 border-black pb-2">
                        Label Pengiriman</h1>

                    <div class="flex justify-between items-center mb-4">
                        <p class="font-extrabold text-xl uppercase px-4 py-1 border border-black">
                            {{ $transaction->shipment->jenis_pengiriman }}</p>
                        <div class="text-right">
                            <p class="font-bold text-sm">NO. NOTA</p>
                            <p class="font-black text-lg">{{ $transaction->nomor_nota }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h2 class="font-bold text-sm uppercase tracking-widest text-gray-500 mb-1">Kepada (Penerima)</h2>
                        <p class="font-black text-2xl uppercase">{{ $transaction->shipment->nama_penerima }}</p>
                        <p class="font-bold text-lg mb-1">{{ $transaction->shipment->no_telepon_penerima }}</p>
                        <p class="font-semibold text-base leading-snug">{{ $transaction->shipment->alamat_tujuan }}</p>
                    </div>

                    <div class="border-t border-dashed border-gray-400 pt-3">
                        <p class="font-bold text-sm uppercase tracking-widest text-gray-500 mb-1">Catatan / Sumber</p>
                        <p class="font-bold text-sm">{{ $transaction->shipment->catatan_kurir ?? '-' }}</p>
                    </div>
                </div>
            @endif

            <!-- ================= KOLOM KIRI: Daftar Produk ================= -->
            <div class="flex-1 print-struk-only">
                <div
                    class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden print:rounded-none print:border-none print:shadow-none">

                    <!-- Info Struk -->
                    <div
                        class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 print:bg-white print:border-black print:p-0 print:pb-4 print:mb-4">
                        <div>
                            <h2 class="font-extrabold text-gray-900 text-xl print:text-2xl">{{ $transaction->nomor_nota }}
                            </h2>
                            <p class="text-sm font-semibold text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($transaction->tanggal_waktu)->format('d M Y, H:i') }} WIB</p>
                        </div>

                        @php
                            $isTempo = in_array(strtolower($transaction->metode_bayar), ['tempo', 'cash_tempo']);
                            $belumLunas =
                                $isTempo && $transaction->cashTempo && $transaction->cashTempo->sisa_piutang > 0;
                        @endphp

                        @if ($belumLunas)
                            <span
                                class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider print:border print:border-black print:bg-white print:text-black">
                                Belum Lunas
                            </span>
                        @else
                            <span
                                class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider print:border print:border-black print:bg-white print:text-black">
                                Lunas
                            </span>
                        @endif
                    </div>

                    <!-- Tabel Item -->
                    <div class="p-6 print:p-0">
                        <h3 class="text-xs font-extrabold text-gray-500 mb-4 uppercase tracking-wider print:text-black">
                            Ringkasan Pesanan</h3>

                        <div class="space-y-4 mb-6 border-b border-gray-100 pb-6 print:border-black print:space-y-2">
                            @foreach ($transaction->details as $item)
                                    @php
                                        $variantName = $item->variant->nama_varian ?? 'Produk Terhapus';
                                        $productName = $item->variant->product->nama_produk ?? '';
                                        $satuan = strtolower($item->variant->satuan ?? 'pcs');
                                        $itemDiscount = (float) ($item->diskon_satuan ?? 0);
                                        $itemDiscountPercent = (float) ($item->diskon_persen ?? 0);
                                    @endphp
                                <div class="flex justify-between items-start">
                                    <div class="flex gap-3">
                                        <div
                                            class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-[#CC9863] font-bold text-sm shrink-0 print:hidden uppercase">
                                            {{ $satuan }}
                                        </div>
                                        <div class="print:block hidden font-black text-sm w-6 text-right mr-1">
                                            {{ $item->qty }}x</div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm print:text-xs">{{ $productName }} -
                                                {{ $variantName }}</p>
                                            <p
                                                class="text-[11px] font-semibold text-gray-500 mt-0.5 print:text-xs print:text-gray-800">
                                                Rp {{ number_format($item->harga_satuan, 0, ',', '.') }} <span
                                                    class="print:hidden">x {{ $item->qty }} {{ $satuan }}</span>
                                            </p>
                                            @if ($itemDiscount > 0)
                                                <p class="mt-1 text-[11px] font-semibold text-red-600 print:text-xs print:text-black">
                                                    Diskon item{{ $itemDiscountPercent > 0 ? ' (' . rtrim(rtrim(number_format($itemDiscountPercent, 2, '.', ''), '0'), '.') . '%)' : '' }}:
                                                    - Rp {{ number_format($itemDiscount, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="font-black text-gray-900 text-sm print:text-xs">Rp
                                        {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Kalkulasi Total -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm text-gray-600 print:text-black">
                                <p class="font-semibold">Subtotal Produk</p>
                                <p class="font-bold text-gray-900">Rp
                                    {{ number_format($transaction->subtotal, 0, ',', '.') }}</p>
                            </div>

                            @if ($transaction->diskon_nominal > 0)
                                <div class="flex justify-between text-sm text-gray-600 print:text-black">
                                    <p class="font-semibold">Diskon ({{ $transaction->deskripsi_diskon }})</p>
                                    <p class="font-bold text-red-500 print:text-black">- Rp
                                        {{ number_format($transaction->diskon_nominal, 0, ',', '.') }}</p>
                                </div>
                            @endif

                            @if ($transaction->shipment && $transaction->shipment->biaya_kirim > 0)
                                <div class="flex justify-between text-sm text-gray-600 print:text-black">
                                    <p class="font-semibold">Biaya Pengiriman (Ongkir)</p>
                                    <p class="font-bold text-gray-900">Rp
                                        {{ number_format($transaction->shipment->biaya_kirim, 0, ',', '.') }}</p>
                                </div>
                            @endif

                            <div
                                class="pt-4 mt-2 border-t border-gray-100 flex justify-between items-end print:border-black">
                                <p class="font-black text-gray-900 uppercase">Total Dibayar</p>
                                <p class="text-2xl font-black text-[#CC9863] print:text-xl print:text-black">Rp
                                    {{ number_format($transaction->total_belanja, 0, ',', '.') }}</p>
                            </div>

                            @if ($belumLunas)
                                <div class="flex justify-between items-end mt-1 text-red-600 print:text-black">
                                    <p class="font-bold text-xs">Sisa Hutang</p>
                                    <p class="text-sm font-black">Rp
                                        {{ number_format($transaction->cashTempo->sisa_piutang, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= KOLOM KANAN: Detail Pelanggan, Kasir & Pengiriman ================= -->
            <div class="w-full xl:w-[400px] space-y-6 shrink-0 print:hidden">

                <!-- Detail Informasi Sistem -->
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h3
                        class="text-xs font-extrabold text-gray-500 mb-4 uppercase tracking-wider border-b border-gray-100 pb-3">
                        Informasi Sistem</h3>

                    <div class="space-y-4">
                        <!-- Info Pelanggan -->
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider">Pelanggan</p>
                                @if ($transaction->member)
                                    <p class="text-sm font-bold text-gray-900">{{ $transaction->member->nama }} <span
                                            class="text-[10px] text-blue-600 font-bold bg-blue-100 px-1.5 py-0.5 rounded ml-1">(Member)</span>
                                    </p>
                                @elseif($transaction->shipment)
                                    <p class="text-sm font-bold text-gray-900">{{ $transaction->shipment->nama_penerima }}
                                    </p>
                                @else
                                    <p class="text-sm font-bold text-gray-900">Pelanggan Umum</p>
                                @endif
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-500 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider">Metode Bayar
                                </p>
                                <p class="text-sm font-bold text-gray-900 uppercase">
                                    {{ str_replace('_', ' ', $transaction->metode_bayar) }}</p>
                            </div>
                        </div>

                        <!-- Kasir / Cabang -->
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-extrabold uppercase tracking-wider">Diproses Oleh
                                </p>
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $transaction->cashier->nama_lengkap ?? 'Admin' }} <span
                                        class="text-gray-500 font-semibold text-[11px]">({{ $transaction->branch->nama_cabang ?? 'Pusat' }})</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pengiriman (Jika Online) -->
                @if ($transaction->shipment)
                    <div class="bg-orange-50/50 p-6 rounded-3xl border border-orange-100 shadow-sm">
                        <div class="flex justify-between items-center mb-4 border-b border-orange-200/60 pb-3">
                            <h3 class="text-xs font-extrabold text-gray-900 uppercase tracking-wider">Info Pengiriman</h3>
                            <span
                                class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">Pesanan
                                Online</span>
                        </div>

                        <div class="space-y-4">
                            <!-- Kurir & Resi -->
                            <div class="flex justify-between gap-4">
                                <div class="flex-1">
                                    <p class="text-[10px] text-gray-500 font-extrabold uppercase tracking-wider mb-1">Kurir
                                    </p>
                                    <p class="text-sm font-black text-[#CC9863] uppercase">
                                        {{ $transaction->shipment->jenis_pengiriman }}</p>
                                </div>
                                <div class="flex-1 text-right">
                                    <p class="text-[10px] text-gray-500 font-extrabold uppercase tracking-wider mb-1">Nomor
                                        Resi</p>
                                    @if ($transaction->shipment->no_resi)
                                        <p
                                            class="text-sm font-bold text-gray-900 bg-white border border-orange-200 px-2 py-1 rounded inline-block">
                                            {{ $transaction->shipment->no_resi }}</p>
                                    @else
                                        <p class="text-xs font-bold text-red-500 italic">Belum Diinput</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div>
                                <p class="text-[10px] text-gray-500 font-extrabold uppercase tracking-wider mb-1">Alamat
                                    Tujuan & Kontak</p>
                                <div class="bg-white p-3 rounded-xl border border-orange-100 text-sm">
                                    <p class="font-bold text-gray-900">{{ $transaction->shipment->nama_penerima }}</p>
                                    <p class="font-semibold text-blue-600 mb-1.5">
                                        {{ $transaction->shipment->no_telepon_penerima }}</p>
                                    <p class="font-medium text-gray-700 leading-relaxed">
                                        {{ $transaction->shipment->alamat_tujuan }}</p>
                                </div>
                            </div>

                            <!-- Catatan -->
                            @if ($transaction->shipment->catatan_kurir)
                                <div>
                                    <p class="text-[10px] text-gray-500 font-extrabold uppercase tracking-wider mb-1">
                                        Catatan Tambahan</p>
                                    <p
                                        class="text-xs font-medium text-gray-700 bg-white p-2.5 rounded-lg border border-orange-100">
                                        {{ $transaction->shipment->catatan_kurir }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>

    <style>
        /* KONFIGURASI CSS PRINT */
        @media print {
            @page {
                margin: 0;
            }

            body {
                background: white;
                margin: 0;
                padding: 0;
            }

            /* Sembunyikan semua elemen kecuali area print */
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 10px;
            }

            .print-struk-only,
            .print-struk-only * {
                border-radius: 0 !important;
            }

            /* Logic toggle: Cetak Resi vs Cetak Struk */
            body.printing-resi .print-struk-only {
                display: none !important;
            }

            body.printing-resi .print-resi-only {
                display: block !important;
            }

            body.printing-struk .print-resi-only {
                display: none !important;
            }

            body.printing-struk .print-struk-only {
                display: block !important;
                width: 100%;
            }

            /* Typography Penyesuaian Thermal / A6 */
            p,
            span,
            h1,
            h2,
            h3,
            h4 {
                color: black !important;
            }
        }
    </style>

    <script>
        // Fungsi untuk cetak RESI (Menambahkan class khusus ke body)
        function printResi() {
            document.body.classList.add('printing-resi');
            document.body.classList.remove('printing-struk');
            window.print();
        }

        // Secara default, jika menekan Ctrl+P biasa, anggap sebagai cetak STRUK
        window.addEventListener('beforeprint', function(event) {
            if (!document.body.classList.contains('printing-resi')) {
                document.body.classList.add('printing-struk');
            }
        });

        window.addEventListener('afterprint', function(event) {
            // Bersihkan class setelah selesai mencetak
            document.body.classList.remove('printing-resi', 'printing-struk');
        });
    </script>
@endsection
