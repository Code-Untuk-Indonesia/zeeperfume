@extends('template.sidebar')
@section('title', 'Riwayat Perpindahan Stok')

@section('content')
    @php
        $movementClasses = [
            'masuk' => 'bg-green-50 text-green-700 border-green-200',
            'keluar' => 'bg-red-50 text-red-700 border-red-200',
            'rusak' => 'bg-orange-50 text-orange-700 border-orange-200',
            'penyesuaian' => 'bg-blue-50 text-blue-700 border-blue-200',
            'penjualan' => 'bg-purple-50 text-purple-700 border-purple-200',
        ];
    @endphp

    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-10 py-6 lg:py-8 w-full">
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route($rolePrefix . '.stock.index') }}" class="font-semibold transition hover:text-[#CC9863]">Stok Barang</a>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="font-bold text-[#CC9863]">Riwayat Perpindahan</span>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 md:text-3xl">Riwayat Perpindahan Stok</h1>
            <p class="mt-1 text-sm font-medium text-gray-500">Pantau setiap perubahan stok di seluruh outlet, termasuk penerimaan, transfer, penjualan, dan penyesuaian.</p>
        </div>

        <form action="{{ route($rolePrefix . '.stock.history') }}" method="GET" class="mb-6 rounded-3xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="grid grid-cols-1 gap-3 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-3">
                    <label for="search" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wide text-gray-600">Cari</label>
                    <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Produk, SKU, outlet, nota..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-800 placeholder-gray-400 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                </div>
                <div class="lg:col-span-2">
                    <label for="cabang_id" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wide text-gray-600">Outlet</label>
                    <select id="cabang_id" name="cabang_id" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-3 text-sm font-semibold text-gray-800 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                        <option value="">Semua outlet</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected((string) request('cabang_id') === (string) $branch->id)>{{ $branch->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-3">
                    <label for="varian_id" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wide text-gray-600">Varian</label>
                    <select id="varian_id" name="varian_id" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-3 text-sm font-semibold text-gray-800 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                        <option value="">Semua varian</option>
                        @foreach ($variants as $variant)
                            <option value="{{ $variant->id }}" @selected((string) request('varian_id') === (string) $variant->id)>
                                {{ $variant->nama_produk }} - {{ $variant->nama_varian }}{{ $variant->sku ? ' (' . $variant->sku . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label for="jenis_riwayat" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wide text-gray-600">Jenis</label>
                    <select id="jenis_riwayat" name="jenis_riwayat" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-3 text-sm font-semibold text-gray-800 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                        <option value="">Semua jenis</option>
                        @foreach ($movementTypes as $value => $label)
                            <option value="{{ $value }}" @selected(request('jenis_riwayat') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label for="tanggal_mulai" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wide text-gray-600">Tanggal mulai</label>
                    <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-3 text-sm font-semibold text-gray-800 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                </div>
                <div class="lg:col-span-2">
                    <label for="tanggal_selesai" class="mb-1.5 block text-xs font-extrabold uppercase tracking-wide text-gray-600">Tanggal selesai</label>
                    <input id="tanggal_selesai" type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-3 text-sm font-semibold text-gray-800 transition focus:border-[#CC9863] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20">
                </div>
                <div class="flex gap-2 lg:col-span-2">
                    <button type="submit" class="min-h-[44px] flex-1 rounded-xl bg-[#1C1D21] px-4 py-3 text-sm font-extrabold text-white transition hover:bg-black focus:outline-none focus:ring-2 focus:ring-[#CC9863] focus:ring-offset-2">Terapkan</button>
                    @if (request()->hasAny(['search', 'cabang_id', 'varian_id', 'jenis_riwayat', 'tanggal_mulai', 'tanggal_selesai']))
                        <a href="{{ route($rolePrefix . '.stock.history') }}" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-bold text-[#374151] transition hover:bg-red-50 hover:text-[#B91C1C]">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        <section class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm" aria-labelledby="history-table-title">
            <div class="flex flex-col gap-1 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 id="history-table-title" class="text-base font-extrabold text-gray-900">Aktivitas stok</h2>
                    <p class="mt-1 text-xs font-semibold text-gray-500">Menampilkan {{ $histories->total() }} catatan berdasarkan filter yang dipilih.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-left">
                    <thead class="border-b border-gray-100 bg-gray-50/70 text-[10px] font-extrabold uppercase tracking-wider text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Produk / Varian</th>
                            <th class="px-6 py-4">Outlet</th>
                            <th class="px-6 py-4 text-center">Jenis</th>
                            <th class="px-6 py-4 text-right">Perubahan</th>
                            <th class="px-6 py-4">Pelaksana / Referensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                        @forelse ($histories as $history)
                            @php
                                $type = strtolower($history->jenis_riwayat);
                                $qtyClass = $history->qty > 0 ? 'text-green-700' : ($history->qty < 0 ? 'text-red-700' : 'text-gray-700');
                                $qtyPrefix = $history->qty > 0 ? '+' : '';
                            @endphp
                            <tr class="transition hover:bg-gray-50/70">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($history->waktu)->format('d M Y') }}</p>
                                    <p class="mt-1 text-[11px] font-semibold text-gray-500">{{ \Carbon\Carbon::parse($history->waktu)->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-extrabold text-gray-900">{{ $history->nama_produk }}</p>
                                    <p class="mt-1 text-xs font-semibold text-gray-500">{{ $history->nama_varian }}{{ $history->sku ? ' · ' . $history->sku : '' }}</p>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $history->nama_cabang }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex rounded-lg border px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide {{ $movementClasses[$type] ?? 'border-gray-200 bg-gray-50 text-gray-700' }}">
                                        {{ $movementTypes[$type] ?? str_replace('_', ' ', $type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="font-black {{ $qtyClass }}">{{ $qtyPrefix }}{{ number_format($history->qty, 0, ',', '.') }} {{ $history->satuan }}</p>
                                    @if ($history->keterangan)
                                        <p class="mt-1 max-w-[180px] truncate text-[11px] font-medium text-gray-500" title="{{ $history->keterangan }}">{{ $history->keterangan }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">{{ $history->nama_lengkap ?? 'Sistem' }}</p>
                                    @if ($history->nomor_nota)
                                        <p class="mt-1 text-[11px] font-semibold text-[#CC9863]">Nota {{ $history->nomor_nota }}</p>
                                    @elseif ($history->keterangan)
                                        <p class="mt-1 text-[11px] font-semibold text-gray-500">Perubahan stok</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="mx-auto max-w-md">
                                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5h6m-7 4h8m-8 4h5m-7 6h12a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="font-extrabold text-gray-900">Belum ada riwayat yang cocok</h3>
                                        <p class="mt-1 text-sm font-medium text-gray-500">Coba ubah filter atau lakukan perubahan stok untuk membuat catatan baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($histories->hasPages())
                <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 px-6 py-4 sm:flex-row">
                    <p class="text-sm font-semibold text-gray-500">Menampilkan {{ $histories->firstItem() }} - {{ $histories->lastItem() }} dari {{ $histories->total() }} catatan</p>
                    {{ $histories->links('pagination::tailwind') }}
                </div>
            @endif
        </section>
    </main>
@endsection
