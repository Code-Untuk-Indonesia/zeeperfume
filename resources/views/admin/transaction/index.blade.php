@extends('template.sidebar')
@section('title', 'Riwayat Transaksi')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Transaksi</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau seluruh aktivitas penjualan offline dan online dari semua cabang
                    secara real-time.</p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <!-- Tombol Pesanan Online Baru -->
                <a href="{{ route('admin.transaction.online') }}"
                    class="bg-[#1C1D21] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-black transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                    + Pesanan Online
                </a>
                <button
                    class="bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-[#b58555] transition flex items-center justify-center gap-2 text-sm flex-1 sm:flex-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export Laporan
                </button>
            </div>
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('admin.transaction.index') }}" method="GET"
            class="bg-white p-4 rounded-t-3xl border border-gray-100 border-b-0 flex flex-col md:flex-row gap-4 shadow-sm relative z-10">
            <!-- Search -->
            <div class="relative w-full md:w-1/3">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-11 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#CC9863] focus:ring-1 focus:ring-[#CC9863] text-sm font-semibold transition"
                    placeholder="Cari No. Invoice atau Pelanggan..." onblur="this.form.submit()">
            </div>

            <!-- Dropdown Filters -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 w-full md:w-2/3">
                <select name="cabang" onchange="this.form.submit()"
                    class="block w-full pl-3 pr-8 py-3 text-sm font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                    <option value="all">Semua Outlet</option>
                    @if (isset($branches))
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('cabang') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->nama_cabang }}</option>
                        @endforeach
                    @endif
                </select>
                <select name="tanggal" onchange="this.form.submit()"
                    class="block w-full pl-3 pr-8 py-3 text-sm font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                    <option value="all">Semua Waktu</option>
                    <option value="hari_ini" {{ request('tanggal') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="kemarin" {{ request('tanggal') == 'kemarin' ? 'selected' : '' }}>Kemarin</option>
                    <option value="7_hari" {{ request('tanggal') == '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="bulan_ini" {{ request('tanggal') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
                <select name="metode" onchange="this.form.submit()"
                    class="block w-full pl-3 pr-8 py-3 text-sm font-semibold border border-gray-200 rounded-xl bg-gray-50 focus:outline-none focus:ring-1 focus:ring-[#CC9863] focus:border-[#CC9863]">
                    <option value="all">Semua Metode</option>
                    <option value="cash" {{ request('metode') == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="qris" {{ request('metode') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="cash_tempo" {{ request('metode') == 'cash_tempo' ? 'selected' : '' }}>Cash Tempo (Kasbon)</option>
                </select>

                <!-- Reset Button -->
                @if (request()->anyFilled(['search', 'cabang', 'tanggal', 'metode']) && request('cabang') !== 'all')
                    <a href="{{ route('admin.transaction.index') }}"
                        class="flex items-center justify-center bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 transition">Reset</a>
                @endif
            </div>
        </form>

        <!-- Transaction Table -->
        <div class="bg-white rounded-b-3xl border border-gray-100 shadow-sm overflow-hidden mb-6 relative z-0">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead
                        class="bg-gray-50 text-gray-500 text-xs font-extrabold uppercase tracking-wider text-left border-y border-gray-200">
                        <tr>
                            <th class="px-6 py-4">Waktu & Invoice</th>
                            <th class="px-6 py-4">Outlet / Platform</th>
                            <th class="px-6 py-4">Detail Produk</th>
                            <th class="px-6 py-4 text-center">Metode</th>
                            <th class="px-6 py-4 text-right">Total Tagihan</th>
                            <th class="px-6 py-4 text-center">Aksi (Detail & CUD)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                        @if (isset($transactions))
                            @forelse($transactions as $trx)
                                @php
                                    $isTempo =
                                        strtolower($trx->metode_bayar) === 'tempo' ||
                                        strtolower($trx->metode_bayar) === 'cash_tempo';
                                    $belumLunas = $isTempo && $trx->cashTempo && $trx->cashTempo->sisa_piutang > 0;

                                    // Pesanan online dikenali dari relasi shipment, bukan format nomor invoice.
                                    $shipment = $trx->shipment;
                                    $isOnline = $shipment !== null;

                                    if ($isOnline) {
                                        // Mengambil nama platform dari catatan_kurir (format: Sumber: SHOPEE | Catatan: xxx)
                                        $platformName = 'Online';
                                        if ($shipment && str_contains($shipment->catatan_kurir, 'Sumber:')) {
                                            preg_match('/Sumber:\s(.*?)\s\|/', $shipment->catatan_kurir, $matches);
                                            if (isset($matches[1])) {
                                                $platformName = $matches[1];
                                            }
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors {{ $belumLunas ? 'bg-red-50/10' : '' }}">
                                    <td class="px-6 py-4">
                                        <p class="font-extrabold text-gray-900">{{ $trx->nomor_nota }}</p>
                                        <p class="text-[11px] font-semibold text-gray-400 mt-1">
                                            {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('d M Y, H:i') }} WIB</p>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($isOnline)
                                            <!-- Tampilan Khusus Pesanan Online -->
                                            <p class="font-bold text-gray-900 flex items-center gap-1.5">
                                                <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
                                                Pesanan Online
                                            </p>
                                            <p
                                                class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded w-fit mt-1 uppercase tracking-wider border border-blue-100">
                                                {{ $platformName }}
                                            </p>
                                        @else
                                            <!-- Tampilan Toko Offline -->
                                            <p class="font-bold text-gray-700">
                                                {{ $trx->branch->nama_cabang ?? 'Outlet Pusat' }}</p>
                                            <p
                                                class="text-[11px] font-semibold text-gray-500 flex items-center gap-1 mt-0.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                                {{ $trx->cashier->nama_lengkap ?? 'Kasir' }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($trx->details->count() > 0)
                                            @php
                                                $firstItem = $trx->details->first();
                                                $moreCount = $trx->details->count() - 1;
                                            @endphp
                                            <p class="text-sm font-bold text-gray-800">{{ $firstItem->qty }}x
                                                {{ $firstItem->variant->nama_varian ?? 'Produk' }}</p>
                                            @if ($moreCount > 0)
                                                <p class="text-[11px] font-semibold text-[#CC9863] mt-0.5">
                                                    +{{ $moreCount }} item lainnya</p>
                                            @endif
                                        @else
                                            <span class="text-xs italic text-gray-400">Tidak ada detail</span>
                                        @endif

                                        @if ($trx->member)
                                            <p
                                                class="text-[10px] font-bold text-blue-600 flex items-center gap-1 mt-1 bg-blue-50 w-fit px-1.5 py-0.5 rounded">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                </svg>
                                                Plg: {{ $trx->member->nama }}
                                            </p>
                                        @elseif($isOnline && isset($shipment))
                                            <p
                                                class="text-[10px] font-bold text-gray-600 flex items-center gap-1 mt-1 bg-gray-100 w-fit px-1.5 py-0.5 rounded">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                </svg>
                                                Plg: {{ $shipment->nama_penerima }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $methodColors = [
                                                'cash' => 'bg-green-50 text-green-600 border-green-100',
                                                'qris' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'transfer' => 'bg-purple-50 text-purple-600 border-purple-100',
                                                'ewallet' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                'cod' => 'bg-yellow-50 text-yellow-600 border-yellow-200',
                                                'tempo' => 'bg-red-50 text-red-600 border-red-100',
                                                'cash_tempo' => 'bg-red-50 text-red-600 border-red-100',
                                            ];
                                            $colorClass =
                                                $methodColors[strtolower($trx->metode_bayar)] ??
                                                'bg-gray-100 text-gray-600 border-gray-200';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold border uppercase tracking-wider {{ $colorClass }}">
                                            {{ str_replace('_', ' ', $trx->metode_bayar) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <p class="font-black text-gray-900 text-base">Rp
                                            {{ number_format($trx->total_belanja, 0, ',', '.') }}</p>
                                        @if ($belumLunas)
                                            <span
                                                class="text-[10px] text-red-600 font-bold bg-red-100 px-2 py-0.5 rounded-md inline-block mt-1">Sisa:
                                                Rp {{ number_format($trx->cashTempo->sisa_piutang, 0, ',', '.') }}</span>
                                        @else
                                            <span
                                                class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-md inline-block mt-1 border border-green-100">Lunas</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            <!-- Tombol Detail / Cetak Struk & Resi -->
                                            <a href="{{ route('admin.transaction.show', $trx->id) }}"
                                                class="text-gray-500 hover:text-blue-600 bg-gray-50 hover:bg-blue-50 px-3 py-1.5 rounded-lg text-xs font-bold transition border border-gray-200 hover:border-blue-200 flex items-center gap-1.5 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Detail & Cetak
                                            </a>

                                            <div class="w-px h-5 bg-gray-200"></div> <!-- Divider -->

                                            <!-- Otorisasi Edit / Hapus -->
                                            @if ($trx->approval_status === 'approved_edit')
                                                <!-- Jika Diizinkan Owner -->
                                                <a href="{{ route('admin.transaction.edit', $trx->id) }}"
                                                    class="text-green-600 bg-green-50 hover:bg-green-100 hover:shadow-sm px-3 py-1.5 rounded-lg text-[11px] font-extrabold transition border border-green-200 flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    Lanjut Edit
                                                </a>
                                            @elseif($trx->approval_status === 'pending_edit' || $trx->approval_status === 'pending_delete')
                                                <!-- Jika Sedang Menunggu -->
                                                <span
                                                    class="text-orange-500 bg-orange-50 px-3 py-1.5 rounded-lg text-[10px] font-extrabold border border-orange-100">
                                                    Menunggu Owner
                                                </span>
                                            @else
                                                <!-- Keadaan Normal Terkunci -->
                                                <button onclick="openRequestModal('{{ $trx->id }}', 'edit')"
                                                    class="text-gray-500 hover:text-orange-500 bg-gray-50 hover:bg-orange-50 p-2 rounded-lg transition border border-gray-200 hover:border-orange-200 shadow-sm"
                                                    title="Ajukan Edit Transaksi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button onclick="openRequestModal('{{ $trx->id }}', 'delete')"
                                                    class="text-gray-500 hover:text-red-500 bg-gray-50 hover:bg-red-50 p-2 rounded-lg transition border border-gray-200 hover:border-red-200 shadow-sm"
                                                    title="Ajukan Hapus Transaksi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-gray-500 font-medium">Belum ada transaksi ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        @endif

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if (isset($transactions) && $transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-500">Menampilkan {{ $transactions->firstItem() }} -
                        {{ $transactions->lastItem() }} dari {{ $transactions->total() }}</span>
                    <div>
                        {{ $transactions->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        </div>

        <!-- ================= MODAL PENGAJUAN (REQUEST) ================= -->
        <div id="requestModal"
            class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300"
                id="requestCard">
                <div class="p-6 bg-orange-50 border-b border-orange-100 flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center shadow-inner shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900" id="modalTitle">Ajukan Otorisasi</h3>
                        <p class="text-xs text-gray-600 mt-0.5 font-medium">Sertakan alasan yang jelas untuk mempercepat
                            persetujuan Owner.</p>
                    </div>
                </div>
                <div class="p-6">
                    <input type="hidden" id="targetTrxId">
                    <input type="hidden" id="targetAction">

                    <div class="mb-5">
                        <label class="block text-sm font-extrabold text-gray-800 mb-2">Alasan Pengajuan <span
                                class="text-red-500">*</span></label>
                        <textarea id="requestReason" rows="3"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 font-medium focus:outline-none focus:border-orange-500 focus:bg-white transition"
                            placeholder="Contoh: Kasir salah input metode pembayaran..."></textarea>
                        <p id="requestErrorMsg" class="text-xs text-red-500 mt-2 font-bold hidden"></p>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="closeRequestModal()"
                            class="flex-1 px-4 py-3 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition">Batal</button>
                        <button onclick="submitRequest()" id="btnSubmitRequest"
                            class="flex-1 px-4 py-3 bg-[#CC9863] text-white font-bold rounded-xl hover:bg-[#b58555] shadow-lg shadow-[#CC9863]/30 transition flex items-center justify-center gap-2">
                            Kirim Pengajuan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        // Buka Modal & Set Parameter
        function openRequestModal(trxId, action) {
            document.getElementById('targetTrxId').value = trxId;
            document.getElementById('targetAction').value = action;
            document.getElementById('requestReason').value = '';
            document.getElementById('requestErrorMsg').classList.add('hidden');

            // Ubah Judul Modal dinamis
            const title = action === 'delete' ? 'Ajukan Hapus Transaksi' : 'Ajukan Edit Transaksi';
            document.getElementById('modalTitle').innerText = title;

            const modal = document.getElementById('requestModal');
            const card = document.getElementById('requestCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-95');
            }, 10);
            document.getElementById('requestReason').focus();
        }

        // Tutup Modal
        function closeRequestModal() {
            const modal = document.getElementById('requestModal');
            const card = document.getElementById('requestCard');
            modal.classList.add('opacity-0');
            card.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // Submit via AJAX
        function submitRequest() {
            const reason = document.getElementById('requestReason').value;
            const trxId = document.getElementById('targetTrxId').value;
            const actionType = document.getElementById('targetAction').value;
            const btn = document.getElementById('btnSubmitRequest');
            const errorMsg = document.getElementById('requestErrorMsg');

            if (reason.trim() === '') {
                errorMsg.innerText = "Alasan tidak boleh kosong.";
                errorMsg.classList.remove('hidden');
                return;
            }

            btn.innerHTML =
                '<svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>';
            btn.disabled = true;

            fetch('{{ route('admin.transaction.request_approval') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        trx_id: trxId,
                        action: actionType,
                        reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        AppFeedback.success('Pengajuan berhasil dikirim! Silakan tunggu konfirmasi dari Owner.');
                        window.setTimeout(() => location.reload(), 1200);
                    } else {
                        errorMsg.innerText = data.message || "Gagal mengirim pengajuan.";
                        errorMsg.classList.remove('hidden');
                    }
                })
                .catch(err => {
                    errorMsg.innerText = "Terjadi kesalahan server.";
                    errorMsg.classList.remove('hidden');
                })
                .finally(() => {
                    btn.innerHTML = 'Kirim Pengajuan';
                    btn.disabled = false;
                });
        }
    </script>
@endsection
