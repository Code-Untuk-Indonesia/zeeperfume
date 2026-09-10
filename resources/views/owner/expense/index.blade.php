@extends('template.sidebar')
@section('title', 'Kelola Pengeluaran - Owner')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full relative">

    <!-- Header & Filter -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Pengeluaran Operasional</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau & kelola seluruh beban biaya operasional toko.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('owner.expense.index') }}" method="GET" class="flex gap-2">
                <select name="month" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm focus:outline-none focus:border-[#CC9863]">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm focus:outline-none focus:border-[#CC9863]">
                    <option value="2026" {{ $year == '2026' ? 'selected' : '' }}>2026</option>
                    <option value="2025" {{ $year == '2025' ? 'selected' : '' }}>2025</option>
                </select>
            </form>

            <button onclick="openModal('addModal')" class="bg-[#1C1D21] text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-black transition flex items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Pengeluaran
            </button>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 mb-6 flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">Total Pengeluaran ({{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }})</p>
            <h2 class="text-3xl font-black text-gray-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Kategori & Nama</th>
                        <th class="px-6 py-4">Lokasi (Cabang)</th>
                        <th class="px-6 py-4">Diinput Oleh</th>
                        <th class="px-6 py-4 text-right">Nominal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                    @forelse($expenses as $exp)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($exp->tanggal_pengeluaran)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">{{ $exp->nama_pengeluaran }}</p>
                            <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                {{ $exp->kategori_pengeluaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            {{ $exp->branch->nama_cabang ?? 'Pusat' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-[#CC9863] text-white flex items-center justify-center text-[10px] font-bold uppercase shrink-0">
                                    {{ substr($exp->user->nama_lengkap ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">{{ explode(' ', $exp->user->nama_lengkap ?? 'Unknown')[0] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-red-500">
                            Rp {{ number_format($exp->nominal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ $exp }})" class="text-gray-400 hover:text-[#CC9863] bg-gray-50 hover:bg-orange-50 p-2 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ route('owner.expense.destroy', $exp->id) }}" method="POST" class="inline-block"
                                    data-feedback-confirm
                                    data-feedback-confirm-title="Konfirmasi penghapusan"
                                    data-feedback-confirm-message="Data pengeluaran ini akan dihapus dari daftar."
                                    data-feedback-confirm-label="Hapus pengeluaran"
                                    data-feedback-confirm-tone="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="font-medium italic">Tidak ada catatan pengeluaran bulan ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $expenses->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    <!-- ================= MODAL TAMBAH/EDIT ================= -->
    <div id="addModal" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300" id="addCard">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-xl font-black text-gray-900" id="modalTitle">Tambah Pengeluaran</h3>
                <button type="button" onclick="closeModal('addModal')" class="text-gray-400 hover:text-red-500 bg-white border border-gray-200 w-8 h-8 rounded-full flex items-center justify-center font-bold transition">×</button>
            </div>

            <form id="expenseForm" method="POST" action="{{ route('owner.expense.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pengeluaran" id="input_tanggal" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] font-semibold text-sm" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Cabang/Outlet <span class="text-red-500">*</span></label>
                            <select name="cabang_id" id="input_cabang" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] font-semibold text-sm" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Kategori Biaya <span class="text-red-500">*</span></label>
                        <select name="kategori_pengeluaran" id="input_kategori" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] font-semibold text-sm" required>
                            <option value="Operasional (Listrik/Air/Internet)">Operasional (Listrik, Air, Internet)</option>
                            <option value="Gaji & Bonus Karyawan">Gaji & Bonus Karyawan</option>
                            <option value="Sewa Tempat">Sewa Tempat / Ruko</option>
                            <option value="Marketing & Iklan">Marketing & Iklan</option>
                            <option value="Perlengkapan Toko (ATK/Plastik)">Perlengkapan Toko (ATK, Plastik)</option>
                            <option value="Maintenance / Perbaikan">Maintenance / Perbaikan</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Nama Pengeluaran <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pengeluaran" id="input_nama" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] font-semibold text-sm" placeholder="Contoh: Bayar Tagihan Listrik PLN" required>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Nominal (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="nominal" id="input_nominal" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-red-400 font-bold text-red-600 text-lg" placeholder="0" required>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wide mb-1.5">Keterangan / Catatan Tambahan</label>
                        <textarea name="keterangan" id="input_keterangan" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] font-medium text-sm" placeholder="Opsional..."></textarea>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3">
                    <button type="button" onclick="closeModal('addModal')" class="flex-1 py-3.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-100 transition">Batal</button>
                    <button type="submit" class="flex-1 py-3.5 bg-[#1C1D21] text-white rounded-xl font-bold text-sm hover:bg-black transition shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>

<script>
    function openModal(id) {
        document.getElementById('modalTitle').innerText = 'Tambah Pengeluaran';
        document.getElementById('expenseForm').action = "{{ route('owner.expense.store') }}";
        document.getElementById('formMethod').value = 'POST';

        // Reset Form
        document.getElementById('input_nama').value = '';
        document.getElementById('input_nominal').value = '';
        document.getElementById('input_keterangan').value = '';
        document.getElementById('input_tanggal').value = "{{ date('Y-m-d') }}";

        const modal = document.getElementById(id);
        const card = document.getElementById('addCard');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            card.classList.remove('scale-95');
        }, 10);
    }

    function openEditModal(data) {
        document.getElementById('modalTitle').innerText = 'Edit Pengeluaran';
        document.getElementById('expenseForm').action = `/owner/expense/${data.id}`;
        document.getElementById('formMethod').value = 'PUT';

        // Isi form dengan data
        document.getElementById('input_tanggal').value = data.tanggal_pengeluaran.split('T')[0];
        document.getElementById('input_cabang').value = data.cabang_id;
        document.getElementById('input_kategori').value = data.kategori_pengeluaran;
        document.getElementById('input_nama').value = data.nama_pengeluaran;
        document.getElementById('input_nominal').value = data.nominal;
        document.getElementById('input_keterangan').value = data.keterangan || '';

        const modal = document.getElementById('addModal');
        const card = document.getElementById('addCard');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            card.classList.remove('scale-95');
        }, 10);
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        const card = document.getElementById('addCard');
        modal.classList.add('opacity-0');
        card.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>
@endsection
