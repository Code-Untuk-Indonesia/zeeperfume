@extends('template.kasir')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="flex-1 overflow-y-auto p-4 lg:p-8 bg-[#FAFAFA] w-full relative">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-500 text-sm mt-1">Daftar transaksi yang Anda proses pada shift hari ini.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="bg-white border border-gray-200 px-4 py-2.5 rounded-xl font-bold text-gray-700 shadow-sm flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5 text-[#CC9863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ \Carbon\Carbon::parse($today)->translatedFormat('d M Y') }}
            </div>
            <a href="{{ url('kasir/pos') }}" class="bg-[#1C1D21] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-gray-800 transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Kembali ke POS
            </a>
        </div>
    </div>

    <!-- Quick Stats Khusus Kasir -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="col-span-2 lg:col-span-1 bg-[#1C1D21] p-4 rounded-2xl shadow-sm text-white flex flex-col justify-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pendapatan</p>
            <h3 class="text-xl font-extrabold text-[#CC9863]">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Transaksi</p>
            <h3 class="text-xl font-extrabold text-gray-900">{{ $totalTransaksi }} Trx</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Penerimaan Tunai</p>
            <h3 class="text-xl font-extrabold text-gray-900">Rp {{ number_format($tunai, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Penerimaan QRIS/TF</p>
            <h3 class="text-xl font-extrabold text-gray-900">Rp {{ number_format($qrisTransfer, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-red-100 bg-red-50/30 shadow-sm flex flex-col justify-center">
            <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-1">Kasbon / Tempo</p>
            <h3 class="text-xl font-extrabold text-red-600">Rp {{ number_format($tempo, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form id="filter-form" class="bg-white p-4 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4 justify-between items-center">
        <!-- Search -->
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="search-input" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] sm:text-sm transition" placeholder="Cari No. Invoice pelanggan...">
        </div>

        <!-- Filters -->
        <div class="flex gap-3 w-full md:w-auto">
            <select id="metode-filter" class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                <option value="semua" {{ request('metode') == 'semua' ? 'selected' : '' }}>Semua Metode</option>
                <option value="cash" {{ request('metode') == 'cash' ? 'selected' : '' }}>Tunai</option>
                <option value="qris_transfer" {{ request('metode') == 'qris_transfer' ? 'selected' : '' }}>QRIS / Transfer</option>
                <option value="tempo" {{ request('metode') == 'tempo' ? 'selected' : '' }}>Tempo</option>
            </select>
        </div>
    </form>

    <!-- Table Section -->
    <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden relative pb-16 md:pb-0">

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="absolute inset-0 bg-white/60 backdrop-blur-sm z-10 hidden flex flex-col items-center justify-center">
            <svg class="animate-spin h-8 w-8 text-[#CC9863] mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider text-left border-y border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">No. Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4 text-right">Total Transaksi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body" class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('H:i') }} WIB</td>
                        <td class="px-6 py-4 font-bold text-gray-900">{{ $trx->nomor_nota }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $trx->member->nama ?? 'Pelanggan Umum' }}
                            @if($trx->member)
                                <span class="text-[10px] text-yellow-700 bg-yellow-100 px-1.5 py-0.5 rounded ml-1 font-bold">Member</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($trx->metode_bayar == 'cash')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100 uppercase">Tunai</span>
                            @elseif(in_array($trx->metode_bayar, ['qris', 'transfer']))
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-600 border border-green-100 uppercase">{{ $trx->metode_bayar }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase">Tempo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button type="button" onclick="openTransactionDetail({{ $trx->id }})"
                                    class="text-[#CC9863] bg-orange-50 hover:bg-orange-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#CC9863] focus-visible:ring-offset-2 px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                                    title="Lihat detail transaksi" aria-label="Lihat detail transaksi {{ $trx->nomor_nota }}">Detail</button>
                                <a href="{{ url('kasir/pos/success?trx_id=' . $trx->id) }}" target="_blank" rel="noopener"
                                    class="text-gray-500 hover:text-gray-900 bg-gray-50 hover:bg-gray-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1"
                                    title="Cetak struk lagi" aria-label="Cetak struk {{ $trx->nomor_nota }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Cetak
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            Belum ada transaksi tercatat untuk hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- DETAIL TRANSAKSI -->
<div id="transaction-detail-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm"
    aria-hidden="true" onclick="if (event.target === this) closeTransactionDetail()">
    <div class="w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5"
        role="dialog" aria-modal="true" aria-labelledby="transaction-detail-title">
        <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4 sm:px-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#CC9863]">Rincian transaksi</p>
                <h2 id="transaction-detail-title" class="mt-1 text-xl font-extrabold text-gray-900">Detail transaksi</h2>
            </div>
            <button type="button" onclick="closeTransactionDetail()"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#CC9863]"
                aria-label="Tutup detail transaksi">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"></path>
                </svg>
            </button>
        </div>

        <div id="transaction-detail-content" class="max-h-[calc(90vh-8rem)] overflow-y-auto px-5 py-5 sm:px-6" aria-live="polite">
            <div class="flex items-center justify-center gap-3 py-12 text-sm font-semibold text-gray-500">
                <svg class="h-5 w-5 animate-spin text-[#CC9863]" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                Memuat detail transaksi...
            </div>
        </div>

        <div class="flex justify-end border-t border-gray-100 bg-gray-50/70 px-5 py-3 sm:px-6">
            <button type="button" onclick="closeTransactionDetail()"
                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-bold text-white transition hover:bg-gray-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-900 focus-visible:ring-offset-2">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- SCRIPT AJAX SEARCH & FILTER -->
<script>
    const transactionDetailModal = document.getElementById('transaction-detail-modal');
    const transactionDetailContent = document.getElementById('transaction-detail-content');
    const transactionDetailUrl = '{{ url('kasir/transaction') }}';
    let transactionDetailPreviousFocus = null;

    function formatTransactionCurrency(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value) || 0);
    }

    function escapeTransactionHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function transactionMethodLabel(method) {
        return {
            cash: 'Tunai',
            qris: 'QRIS',
            transfer: 'Transfer Bank',
            tempo: 'Tempo',
            cash_tempo: 'Tempo',
        }[method] || method || '-';
    }

    function transactionDateLabel(value) {
        if (!value) return '-';

        const date = new Date(String(value).replace(' ', 'T'));
        if (Number.isNaN(date.getTime())) return value;

        return new Intl.DateTimeFormat('id-ID', {
            dateStyle: 'medium',
            timeStyle: 'short'
        }).format(date) + ' WIB';
    }

    function transactionStatusMarkup(transaction) {
        const tempo = transaction.cash_tempo;
        const isTempo = ['tempo', 'cash_tempo'].includes(transaction.metode_bayar);
        const isUnpaid = isTempo && tempo && Number(tempo.sisa_piutang) > 0;
        const label = isUnpaid ? 'Belum lunas' : 'Lunas';
        const classes = isUnpaid ? 'bg-red-50 text-red-700 border-red-100' : 'bg-green-50 text-green-700 border-green-100';

        return `<span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-bold ${classes}">${label}</span>`;
    }

    function renderTransactionDetail(transaction) {
        const details = Array.isArray(transaction.details) ? transaction.details : [];
        const member = transaction.member;
        const tempo = transaction.cash_tempo;
        const shipment = transaction.shipment;
        const detailRows = details.length
            ? details.map((item) => {
                const productName = item.nama_produk || 'Produk';
                const variantName = item.nama_varian || 'Varian tidak tersedia';
                const unit = item.satuan || 'pcs';
                const discount = Number(item.diskon_satuan) || 0;
                const discountPercent = Number(item.diskon_persen) || 0;
                const discountMarkup = discount > 0
                    ? `<p class="mt-1 text-xs font-semibold text-red-600">Diskon item${discountPercent > 0 ? ` (${discountPercent}%)` : ''}: - ${formatTransactionCurrency(discount)}</p>`
                    : '';

                return `
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 py-3 last:border-b-0">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-gray-900">${escapeTransactionHtml(productName)} · ${escapeTransactionHtml(variantName)}</p>
                            <p class="mt-1 text-xs text-gray-500">${escapeTransactionHtml(item.qty)} ${escapeTransactionHtml(unit)} × ${formatTransactionCurrency(item.harga_satuan)}</p>
                            ${discountMarkup}
                        </div>
                        <p class="shrink-0 text-sm font-extrabold text-gray-900">${formatTransactionCurrency(item.subtotal)}</p>
                    </div>`;
            }).join('')
            : '<p class="py-6 text-center text-sm text-gray-500">Detail produk tidak tersedia.</p>';

        const tempoMarkup = tempo ? `
            <div class="mt-4 rounded-xl border border-red-100 bg-red-50/70 p-4">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-xs font-extrabold uppercase tracking-wide text-red-700">Informasi tempo</p>
                    ${transactionStatusMarkup(transaction)}
                </div>
                <div class="mt-3 grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">
                    <div><p class="text-xs text-red-600">Jatuh tempo</p><p class="mt-1 font-bold text-gray-900">${escapeTransactionHtml(tempo.tanggal_jatuh_tempo || '-')}</p></div>
                    <div><p class="text-xs text-red-600">Jumlah piutang</p><p class="mt-1 font-bold text-gray-900">${formatTransactionCurrency(tempo.jumlah_piutang)}</p></div>
                    <div><p class="text-xs text-red-600">Sisa piutang</p><p class="mt-1 font-bold text-gray-900">${formatTransactionCurrency(tempo.sisa_piutang)}</p></div>
                </div>
            </div>` : '';

        const shipmentMarkup = shipment ? `
            <div class="mt-4 rounded-xl border border-orange-100 bg-orange-50/70 p-4">
                <p class="text-xs font-extrabold uppercase tracking-wide text-orange-700">Informasi pengiriman</p>
                <div class="mt-3 grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                    <div><p class="text-xs text-gray-500">Penerima</p><p class="mt-1 font-bold text-gray-900">${escapeTransactionHtml(shipment.nama_penerima)}</p></div>
                    <div><p class="text-xs text-gray-500">Kurir</p><p class="mt-1 font-bold text-gray-900">${escapeTransactionHtml(shipment.jenis_pengiriman || '-')}</p></div>
                    <div><p class="text-xs text-gray-500">Nomor resi</p><p class="mt-1 font-bold text-gray-900">${escapeTransactionHtml(shipment.no_resi || 'Belum diinput')}</p></div>
                    <div><p class="text-xs text-gray-500">Telepon</p><p class="mt-1 font-bold text-gray-900">${escapeTransactionHtml(shipment.no_telepon_penerima || '-')}</p></div>
                </div>
                <p class="mt-3 text-sm leading-relaxed text-gray-700">${escapeTransactionHtml(shipment.alamat_tujuan || '-')}</p>
            </div>` : '';

        transactionDetailContent.innerHTML = `
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-gray-500">${escapeTransactionHtml(transaction.nomor_nota)}</p>
                    <p class="mt-1 text-sm text-gray-500">${escapeTransactionHtml(transactionDateLabel(transaction.tanggal_waktu))}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-700">${escapeTransactionHtml(transactionMethodLabel(transaction.metode_bayar))}</span>
                    ${transactionStatusMarkup(transaction)}
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-3 rounded-xl border border-gray-100 bg-gray-50/70 p-4 text-sm sm:grid-cols-2">
                <div><p class="text-xs text-gray-500">Pelanggan</p><p class="mt-1 font-bold text-gray-900">${escapeTransactionHtml(member?.nama || 'Pelanggan Umum')}</p></div>
                <div><p class="text-xs text-gray-500">Nomor telepon</p><p class="mt-1 font-bold text-gray-900">${escapeTransactionHtml(member?.no_telp || '-')}</p></div>
            </div>

            <div class="mt-5">
                <h3 class="text-xs font-extrabold uppercase tracking-wide text-gray-500">Daftar produk</h3>
                <div class="mt-2 rounded-xl border border-gray-100 px-4">${detailRows}</div>
            </div>

            <div class="mt-5 space-y-2 border-t border-gray-100 pt-4 text-sm">
                <div class="flex justify-between gap-4 text-gray-600"><span>Subtotal</span><span class="font-semibold text-gray-900">${formatTransactionCurrency(transaction.subtotal)}</span></div>
                <div class="flex justify-between gap-4 text-gray-600"><span>Diskon</span><span class="font-semibold text-red-600">- ${formatTransactionCurrency(transaction.diskon_nominal)}</span></div>
                <div class="flex justify-between gap-4 border-t border-gray-100 pt-3 text-base"><span class="font-extrabold text-gray-900">Total</span><span class="font-black text-[#CC9863]">${formatTransactionCurrency(transaction.total_belanja)}</span></div>
                <div class="flex justify-between gap-4 text-gray-600"><span>Dibayar</span><span class="font-semibold text-gray-900">${formatTransactionCurrency(transaction.nominal_bayar)}</span></div>
                ${Number(transaction.kembalian) > 0 ? `<div class="flex justify-between gap-4 text-gray-600"><span>Kembalian</span><span class="font-semibold text-green-600">${formatTransactionCurrency(transaction.kembalian)}</span></div>` : ''}
            </div>

            ${tempoMarkup}
            ${shipmentMarkup}`;
    }

    function openTransactionDetail(transactionId) {
        transactionDetailPreviousFocus = document.activeElement;
        transactionDetailModal.classList.remove('hidden');
        transactionDetailModal.classList.add('flex');
        transactionDetailModal.setAttribute('aria-hidden', 'false');
        transactionDetailModal.querySelector('button[aria-label="Tutup detail transaksi"]').focus();
        transactionDetailContent.innerHTML = `
            <div class="flex items-center justify-center gap-3 py-12 text-sm font-semibold text-gray-500">
                <svg class="h-5 w-5 animate-spin text-[#CC9863]" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                Memuat detail transaksi...
            </div>`;

        fetch(`${transactionDetailUrl}/${transactionId}/detail`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => response.json().then(data => ({ response, data })))
            .then(({ response, data }) => {
                if (!response.ok || !data.success) throw new Error(data.message || 'Detail transaksi tidak dapat dimuat.');
                renderTransactionDetail(data.data);
            })
            .catch(error => {
                transactionDetailContent.innerHTML = `
                    <div class="py-10 text-center">
                        <p class="text-sm font-bold text-red-600">${escapeTransactionHtml(error.message)}</p>
                        <button type="button" onclick="openTransactionDetail(${transactionId})" class="mt-4 rounded-lg bg-gray-900 px-4 py-2 text-sm font-bold text-white hover:bg-gray-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-900 focus-visible:ring-offset-2">Coba lagi</button>
                    </div>`;
            });
    }

    function closeTransactionDetail() {
        transactionDetailModal.classList.add('hidden');
        transactionDetailModal.classList.remove('flex');
        transactionDetailModal.setAttribute('aria-hidden', 'true');

        if (transactionDetailPreviousFocus && typeof transactionDetailPreviousFocus.focus === 'function') {
            transactionDetailPreviousFocus.focus();
        }
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !transactionDetailModal.classList.contains('hidden')) {
            closeTransactionDetail();
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById('search-input');
        const metodeFilter = document.getElementById('metode-filter');
        const tableBody = document.getElementById('table-body');
        const paginationContainer = document.getElementById('pagination-container');
        const loadingOverlay = document.getElementById('loading-overlay');

        let timeout = null;

        function fetchData(url) {
            loadingOverlay.classList.remove('hidden');

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newTbody = doc.getElementById('table-body');
                const newPagination = doc.getElementById('pagination-container');

                if(newTbody) tableBody.innerHTML = newTbody.innerHTML;
                if(newPagination) paginationContainer.innerHTML = newPagination.innerHTML;

                window.history.pushState({}, '', url);
            })
            .catch(error => console.error('Error fetching data:', error))
            .finally(() => loadingOverlay.classList.add('hidden'));
        }

        function getFilterUrl() {
            const url = new URL('{{ route('kasir.transaction.index') }}');
            if (searchInput.value) url.searchParams.append('search', searchInput.value);
            if (metodeFilter.value) url.searchParams.append('metode', metodeFilter.value);
            return url.toString();
        }

        metodeFilter.addEventListener('change', () => fetchData(getFilterUrl()));

        searchInput.addEventListener('keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => fetchData(getFilterUrl()), 500);
        });

        paginationContainer.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (link) {
                e.preventDefault();
                fetchData(link.href);
            }
        });
    });
</script>
@endsection
