@extends('template.sidebar')
@section('title', 'Edit Transaksi')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('admin.transaction.index') }}" class="hover:text-[#CC9863] transition font-semibold">Riwayat Transaksi</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#CC9863] font-bold">Edit Transaksi</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Edit Transaksi: {{ $transaction->nomor_nota }}</h1>
        <p class="text-gray-500 text-sm mt-1">Perbarui detail pembayaran. Menyimpan perubahan akan mengunci kembali transaksi ini.</p>
    </div>

    <!-- Alert Keamanan -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 shadow-sm flex items-start gap-3">
        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0 mt-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <h3 class="text-sm font-black text-blue-900">Akses Edit Terbuka (Otorisasi Owner)</h3>
            <p class="text-xs text-blue-700 mt-1 font-medium">Anda sedang mengedit transaksi yang sudah disetujui. Setelah menekan tombol <b>Simpan</b>, transaksi ini akan otomatis terkunci kembali.</p>
        </div>
    </div>

    <form id="transaction-edit-form" action="{{ route('admin.transaction.update', $transaction->id) }}" method="POST"
        class="flex flex-col xl:flex-row gap-6"
        data-feedback-confirm
        data-feedback-confirm-title="Konfirmasi penyimpanan"
        data-feedback-confirm-message="Pastikan data edit sudah benar. Setelah disimpan, transaksi akan dikunci kembali."
        data-feedback-confirm-label="Simpan & kunci"
        data-feedback-confirm-tone="danger">
        @csrf
        <!-- Gunakan method POST sesuai definisi route update Anda -->

        <!-- ================= KOLOM KIRI: RINCIAN PRODUK (READONLY) ================= -->
        <div class="flex-1 space-y-6">
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-900">Rincian Barang Terjual</h2>
                    <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg">{{ \Carbon\Carbon::parse($transaction->tanggal_waktu)->format('d M Y, H:i') }}</span>
                </div>

                <!-- List Produk -->
                <div class="space-y-4">
                    @forelse($transaction->details as $detail)
                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl bg-gray-50">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-orange-100 text-[#CC9863] flex items-center justify-center font-black text-sm border border-orange-200">
                                    {{ $detail->qty }}x
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $detail->variant->nama_varian ?? 'Produk Dihapus' }}</p>
                                    <p class="text-[11px] font-semibold text-gray-500 mt-0.5">@ Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }} / {{ $detail->variant->satuan ?? 'pcs' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 text-sm italic">Detail transaksi tidak ditemukan.</p>
                    @endforelse
                </div>

                <!-- Total Belanja Kalkulasi -->
                <div class="mt-6 pt-5 border-t-2 border-dashed border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Total Tagihan Asli</span>
                        <span class="text-2xl font-black text-[#CC9863]">Rp {{ number_format($transaction->total_belanja, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= KOLOM KANAN: FORM EDIT METODE & BAYAR ================= -->
        <div class="w-full xl:w-[380px] space-y-6 shrink-0">
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h2 class="text-base font-extrabold text-gray-900 mb-5 border-b border-gray-100 pb-3">Edit Pembayaran</h2>
                
                <div class="space-y-5">
                    
                    <!-- Edit Pelanggan -->
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Pelanggan Terkait</label>
                        <select name="member_id" class="w-full px-4 py-3.5 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800">
                            <option value="">-- Pelanggan Umum (Tanpa Member) --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ $transaction->member_id == $member->id ? 'selected' : '' }}>
                                    {{ $member->nama }} ({{ $member->no_telp }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Edit Metode Pembayaran -->
                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="metode_bayar" id="metode_bayar" onchange="toggleTempoDate()" class="w-full px-4 py-3.5 border-2 border-gray-100 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] transition font-bold text-gray-800" required>
                            <option value="cash" {{ $transaction->metode_bayar === 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                            <option value="qris" {{ $transaction->metode_bayar === 'qris' ? 'selected' : '' }}>QRIS</option>
                            <option value="transfer" {{ $transaction->metode_bayar === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="cash_tempo" {{ in_array($transaction->metode_bayar, ['tempo', 'cash_tempo']) ? 'selected' : '' }}>Cash Tempo / Kasbon (Hutang)</option>
                        </select>
                    </div>

                    <!-- TANGGAL JATUH TEMPO (Muncul jika pilih Cash Tempo) -->
                    <div id="tempo_date_container" class="{{ in_array($transaction->metode_bayar, ['tempo', 'cash_tempo']) ? '' : 'hidden' }}">
                        <label class="block text-xs font-extrabold text-red-600 uppercase tracking-wide mb-1.5">Tanggal Jatuh Tempo <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" 
                               value="{{ $transaction->cashTempo ? \Carbon\Carbon::parse($transaction->cashTempo->tanggal_jatuh_tempo)->format('Y-m-d') : \Carbon\Carbon::now()->addDays(30)->format('Y-m-d') }}" 
                               class="w-full px-4 py-3.5 border-2 border-red-100 rounded-xl bg-red-50 focus:bg-white focus:outline-none focus:border-red-400 transition font-bold text-red-900">
                        <p class="text-[10px] text-red-500 mt-1 font-semibold">*Wajib diisi untuk pengingat hutang pelanggan.</p>
                    </div>

                    <!-- CATATAN PENAGIHAN (Muncul jika pilih Cash Tempo) -->
                    <div id="tempo_note_container" class="{{ in_array($transaction->metode_bayar, ['tempo', 'cash_tempo']) ? '' : 'hidden' }}">
                        <label class="block text-xs font-extrabold text-red-600 uppercase tracking-wide mb-1.5">Catatan Penagihan</label>
                        <textarea name="catatan_penagihan" id="catatan_penagihan" rows="3" maxlength="1000"
                                  class="w-full px-4 py-3 border-2 border-red-100 rounded-xl bg-red-50 focus:bg-white focus:outline-none focus:border-red-400 transition font-medium text-red-900"
                                  placeholder="Catatan untuk penagihan pelanggan">{{ old('catatan_penagihan', optional($transaction->cashTempo)->catatan_penagihan) }}</textarea>
                    </div>

                    <!-- Edit Nominal Bayar -->
                    <div>
                        <label id="nominal_label" class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Nominal Dibayar (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="nominal_bayar" value="{{ old('nominal_bayar', $transaction->nominal_bayar) }}" class="w-full px-4 py-3.5 border-2 border-gray-100 rounded-xl bg-white focus:outline-none focus:border-[#CC9863] transition font-black text-xl text-gray-900 tracking-wider" required>
                        <p id="nominal_hint" class="text-[10px] text-gray-500 mt-1 font-semibold">Ubah jika kasir salah memasukkan jumlah uang yang diterima dari pelanggan.</p>
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-3 sticky top-6">
                <button type="submit" form="transaction-edit-form" class="w-full bg-[#1C1D21] text-white py-4 rounded-2xl font-extrabold text-sm hover:bg-black transition-all shadow-xl shadow-black/10 flex justify-center items-center gap-2 transform active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Simpan & Kunci Transaksi
                </button>
                <a href="{{ route('admin.transaction.index') }}" class="w-full bg-gray-50 text-gray-600 text-center py-3.5 rounded-2xl font-bold text-sm hover:bg-red-50 hover:text-red-600 transition-colors border border-gray-200">
                    Batal Edit
                </a>
            </div>

        </div>
    </form>
</main>

<script>
    // Memunculkan kolom cash tempo jika metode yang dipilih adalah Cash Tempo
    function toggleTempoDate() {
        const metode = document.getElementById('metode_bayar').value;
        const container = document.getElementById('tempo_date_container');
        const noteContainer = document.getElementById('tempo_note_container');
        const inputDate = document.getElementById('tanggal_jatuh_tempo');
        const nominalLabel = document.getElementById('nominal_label');
        const nominalHint = document.getElementById('nominal_hint');

        if (metode === 'cash_tempo') {
            container.classList.remove('hidden');
            noteContainer.classList.remove('hidden');
            inputDate.required = true;
            nominalLabel.firstChild.textContent = 'Pembayaran Awal Cash Tempo (Rp) ';
            nominalHint.textContent = 'Boleh sebagian dari total tagihan. Sisa akan dicatat sebagai piutang.';
        } else {
            container.classList.add('hidden');
            noteContainer.classList.add('hidden');
            inputDate.required = false;
            nominalLabel.firstChild.textContent = 'Nominal Dibayar (Rp) ';
            nominalHint.textContent = 'Ubah jika kasir salah memasukkan jumlah uang yang diterima dari pelanggan.';
        }
    }

    // Inisialisasi saat pertama load
    document.addEventListener("DOMContentLoaded", function() {
        toggleTempoDate();
    });
</script>
@endsection
