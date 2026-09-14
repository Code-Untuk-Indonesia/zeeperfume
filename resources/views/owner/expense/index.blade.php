@extends('template.sidebar')
@section('title', 'Kelola Pengeluaran')

@section('content')
<main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full relative min-h-screen">

    <!-- ================= HEADER & FILTER ================= -->
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-end gap-5 mb-8">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-widest text-gray-400 mb-2">
                <a href="{{ url(auth()->user()->role->nama_role . '/dashboard') }}" class="hover:text-[#CC9863] transition-colors">Dashboard</a>
                <span class="text-gray-300">/</span>
                <span class="text-[#CC9863]">Pengeluaran</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Biaya Operasional</h1>
            <p class="text-gray-500 text-sm mt-1 font-medium">Pantau & kelola seluruh catatan beban biaya operasional outlet.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
            <!-- Filter Bulan & Tahun -->
            <form action="{{ url(auth()->user()->role->nama_role . '/expense') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                <select name="month" onchange="this.form.submit()" class="flex-1 sm:flex-none bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm focus:outline-none focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 cursor-pointer transition-all">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <select name="year" onchange="this.form.submit()" class="flex-1 sm:flex-none bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm focus:outline-none focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 cursor-pointer transition-all">
                    @for($y = now()->year + 1; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>

            <!-- Tombol Tambah (Warna Coklat ZeePerfume) -->
            <button onclick="openModal('addModal')" class="w-full sm:w-auto bg-[#CC9863] text-white px-5 py-2.5 rounded-xl font-extrabold shadow-md shadow-[#CC9863]/20 hover:bg-[#b58555] transition-all transform active:scale-95 flex items-center justify-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Catat Pengeluaran
            </button>
        </div>
    </div>

    <!-- ================= INFO CARD (TOTAL PENGELUARAN) ================= -->
    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6 mb-8 relative overflow-hidden group hover:shadow-md hover:border-red-200 transition-all">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-red-50 rounded-full blur-3xl opacity-70 group-hover:bg-red-100 transition-colors duration-500"></div>
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center border border-red-100/50 group-hover:bg-red-500 group-hover:text-white transition-colors shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1.5">Total Beban ({{ \Carbon\Carbon::create(null, $month)->translatedFormat('F') }} {{ $year }})</p>
                <h2 class="text-3xl font-black text-rose-600">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>

    <!-- ================= TABLE RIWAYAT PENGELUARAN ================= -->
    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
            <div>
                <h2 class="text-base font-black text-gray-900">Riwayat Pengeluaran</h2>
                <p class="text-[11px] font-medium text-gray-500 mt-1">Daftar beban operasional bulan ini.</p>
            </div>
            <span class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-[10px] font-bold border border-gray-200">
                {{ $expenses->total() ?? 0 }} Catatan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">Tanggal</th>
                        <th class="px-6 py-5">Kategori & Keterangan</th>
                        <th class="px-6 py-5">Lokasi (Outlet)</th>
                        <th class="px-6 py-5">Otorisasi</th>
                        <th class="px-6 py-5 text-right">Nominal</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                    @forelse($expenses as $exp)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($exp->tanggal_pengeluaran)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">{{ $exp->nama_pengeluaran }}</p>
                            <span class="inline-block mt-1 text-[9px] font-extrabold uppercase tracking-wider bg-[#CC9863]/10 text-[#CC9863] border border-[#CC9863]/20 px-2.5 py-0.5 rounded-md">
                                {{ $exp->kategori_pengeluaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-600">
                            {{ $exp->branch->nama_cabang ?? 'Pusat' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-[10px] font-bold uppercase shrink-0 border border-gray-200">
                                    {{ substr($exp->user->nama_lengkap ?? 'A', 0, 1) }}
                                </div>
                                <p class="font-bold text-gray-800 text-xs">{{ explode(' ', $exp->user->nama_lengkap ?? 'Unknown')[0] }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-rose-500 text-[15px]">
                            Rp {{ number_format($exp->nominal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal({{ $exp }})" class="text-gray-400 hover:text-[#CC9863] bg-white border border-gray-200 hover:border-[#CC9863] hover:bg-orange-50 p-2 rounded-xl transition-all shadow-sm" title="Edit Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ url(auth()->user()->role->nama_role . '/expense/' . $exp->id) }}" method="POST" class="inline-block"
                                    data-feedback-confirm
                                    data-feedback-confirm-title="Hapus Pengeluaran?"
                                    data-feedback-confirm-message="Catatan biaya ini akan dihapus secara permanen dari laporan."
                                    data-feedback-confirm-label="Ya, Hapus"
                                    data-feedback-confirm-tone="danger">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-white bg-white border border-gray-200 hover:border-red-500 hover:bg-red-500 p-2 rounded-xl transition-all shadow-sm" title="Hapus Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 mx-auto bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-center text-gray-400 mb-4 shadow-sm">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="font-black text-gray-900 text-base">Belum Ada Pengeluaran</h3>
                            <p class="text-xs text-gray-500 font-medium mt-1">Tidak ada catatan operasional pada bulan ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($expenses) && $expenses->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/30">
                {{ $expenses->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    <!-- ================= MODAL TAMBAH/EDIT ================= -->
    <div id="addModal" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300" id="addCard">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-xl font-black text-gray-900 tracking-tight" id="modalTitle">Tambah Pengeluaran</h3>
                <button type="button" onclick="closeModal('addModal')" class="text-gray-400 hover:text-red-500 bg-white border border-gray-200 w-8 h-8 rounded-full flex items-center justify-center font-bold transition hover:bg-red-50 hover:border-red-100 focus:outline-none">&times;</button>
            </div>

            <!-- Route Dinamis Berdasarkan Role -->
            <form id="expenseForm" method="POST" action="{{ url(auth()->user()->role->nama_role . '/expense') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_pengeluaran" id="input_tanggal" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-bold text-sm transition-colors cursor-pointer" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Outlet <span class="text-red-500">*</span></label>
                            <select name="cabang_id" id="input_cabang" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-bold text-sm transition-colors cursor-pointer" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Kategori Biaya <span class="text-red-500">*</span></label>
                        <select name="kategori_pengeluaran" id="input_kategori" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-bold text-sm transition-colors cursor-pointer" required>
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
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Keterangan Pengeluaran <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pengeluaran" id="input_nama" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-bold text-sm transition-colors" placeholder="Contoh: Bayar Listrik Outlet A" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Nominal (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 font-bold text-rose-500">Rp</span>
                            <input type="number" name="nominal" id="input_nominal" class="w-full pl-11 pr-4 py-3 border border-rose-200 rounded-xl bg-rose-50/30 focus:bg-white focus:outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-200 font-black text-rose-600 text-lg transition-colors" placeholder="0" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea name="keterangan" id="input_keterangan" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:outline-none focus:border-[#CC9863] focus:ring-2 focus:ring-[#CC9863]/20 font-medium text-sm transition-colors" placeholder="Tulis catatan jika ada..."></textarea>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50/50 flex gap-3">
                    <button type="button" onclick="closeModal('addModal')" class="flex-1 py-3.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-100 hover:text-gray-900 transition-all focus:outline-none">Batal</button>
                    <!-- Tombol Simpan Diperbarui (Warna Coklat ZeePerfume) -->
                    <button type="submit" class="flex-1 py-3.5 bg-[#CC9863] text-white rounded-xl font-extrabold text-sm hover:bg-[#b58555] transition-all shadow-md shadow-[#CC9863]/20 flex items-center justify-center gap-2 transform active:scale-95 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>

<script>
    // Ambil Role yang login dari blade
    const userRole = "{{ auth()->user()->role->nama_role ?? 'owner' }}";

    function openModal(id) {
        document.getElementById('modalTitle').innerText = 'Catat Pengeluaran Baru';
        document.getElementById('expenseForm').action = `/${userRole}/expense`;
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
        // Set action untuk mode Edit (PUT)
        document.getElementById('expenseForm').action = `/${userRole}/expense/${data.id}`;
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
