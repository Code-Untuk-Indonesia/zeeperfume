@extends('template.sidebar')
@section('title', 'Dashboard Owner')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 w-full">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Selamat datang,
                    {{ auth()->user()->nama_lengkap ?? 'Owner' }}!</h1>
                <p class="text-gray-500 text-sm mt-1">Saat yang tepat untuk memantau performa bisnis parfum Anda.</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="bg-white p-1 rounded-xl border border-gray-200 flex text-sm font-semibold shadow-sm">
                    <button class="px-4 py-1.5 rounded-lg bg-[#CC9863] text-white shadow-sm transition">Keseluruhan</button>
                    <button class="px-4 py-1.5 rounded-lg text-gray-500 hover:text-gray-900 transition">Bulan Ini</button>
                </div>
            </div>
        </div>

        <!-- ================= STATS CARDS ================= -->
        <!-- Kini dibuat auto fit agar card ke-5 bisa menyesuaikan diri dengan rapi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">

            <!-- Card 1: Kas Diterima (Uang Nyata) -->
            <div
                class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-500">Kas Masuk (In Hand)</h3>
                    <div
                        class="w-7 h-7 rounded-full border border-gray-100 bg-green-50 flex items-center justify-center text-green-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl lg:text-2xl font-extrabold text-gray-900 mb-2">Rp
                        {{ number_format($kasDiterima ?? 0, 0, ',', '.') }}</h2>
                    <span class="text-[10px] text-gray-400">Di luar tagihan tempo</span>
                </div>
            </div>

            <!-- Card 2: Piutang Tempo -->
            <div
                class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-orange-600">Piutang (Tempo)</h3>
                    <div
                        class="w-7 h-7 rounded-full border border-orange-100 bg-orange-50 flex items-center justify-center text-orange-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl lg:text-2xl font-extrabold text-orange-600 mb-2">Rp
                        {{ number_format($totalPiutang ?? 0, 0, ',', '.') }}</h2>
                    <span class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded font-bold text-[10px]">Uang
                        Tertahan</span>
                </div>
            </div>

            <!-- Card 3: Total Aset Barang (Nilai Modal) -->
            <div
                class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-blue-600">Aset Stok (Modal)</h3>
                    <div
                        class="w-7 h-7 rounded-full border border-blue-100 bg-blue-50 flex items-center justify-center text-blue-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl lg:text-2xl font-extrabold text-blue-600 mb-2">Rp
                        {{ number_format($totalAsetModal ?? 0, 0, ',', '.') }}</h2>
                    <span class="text-[10px] text-gray-400">Total nilai modal stok aktif</span>
                </div>
            </div>

            <!-- Card 4: Total Transaksi -->
            <div
                class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-500">Total Transaksi</h3>
                    <div class="w-7 h-7 rounded-full border border-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl lg:text-2xl font-extrabold text-gray-900 mb-2">
                        {{ number_format($totalTransactions ?? 0, 0, ',', '.') }} Trx</h2>
                    <span class="text-[10px] text-gray-400">Keseluruhan order</span>
                </div>
            </div>

            <!-- Card 5: Peringatan Stok -->
            <div
                class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-red-500">Stok Kritis</h3>
                    <div
                        class="w-7 h-7 rounded-full border border-red-100 bg-red-50 flex items-center justify-center text-red-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl lg:text-2xl font-extrabold text-red-600 mb-2">
                        {{ isset($lowStocks) ? $lowStocks->count() : 0 }} Item</h2>
                    <span class="text-[10px] text-gray-400">Perlu restok segera</span>
                </div>
            </div>
        </div>

        <!-- ================= MIDDLE SECTION (Charts) ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div x-data="dashboardChart({ labels: @js($chartData['labels']), omzet: @js($chartData['omzet']), hpp: @js($chartData['hpp']) })" x-init="init()"
                class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Performa Penjualan</h2>
                        <p class="text-[10px] text-gray-400 mt-1">Minggu ini (Senin - Minggu)</p>
                    </div>
                    <div class="flex items-center gap-4 text-[10px] font-bold text-gray-500">
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span>
                            Omzet</div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-200"></span>
                            Modal (HPP)</div>
                    </div>
                </div>
                <div class="relative w-full flex-1 min-h-[220px]">
                    <canvas x-ref="salesChart"></canvas>
                </div>
            </div>

            <!-- Donut Chart Area -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Metode Bayar</h2>
                </div>
                <div class="flex-1 flex flex-col items-center justify-center">
                    @if (isset($paymentMethods) && $paymentMethods->count() > 0)
                        <div class="w-full space-y-3">
                            @foreach ($paymentMethods as $pm)
                                @php $pct = $totalTransactions > 0 ? round(($pm->total_transaksi / $totalTransactions) * 100) : 0; @endphp
                                <div>
                                    <div class="flex justify-between text-xs font-bold mb-1.5">
                                        <span
                                            class="text-gray-700 uppercase">{{ str_replace('_', ' ', $pm->metode_bayar) }}</span>
                                        <span class="text-gray-900">{{ $pm->total_transaksi }} Trx
                                            ({{ $pct }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                                        <div class="bg-[#CC9863] h-1.5 rounded-full" style="width: {{ $pct }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-center text-gray-400">Belum ada data pembayaran</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- ================= TOP PRODUCTS & CABANG ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- TABLE CABANG -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Pendapatan per Outlet</h2>
                        <p class="text-[11px] text-gray-500 mt-1">Kontribusi omzet dari masing-masing cabang.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-extrabold text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Nama Outlet</th>
                                <th class="px-6 py-4 text-center">Trx</th>
                                <th class="px-6 py-4 text-right">Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @foreach ($branchIncomes as $branch)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-black text-[10px] uppercase">
                                                {{ substr($branch->nama_cabang, 0, 2) }}</div>
                                            <span class="font-bold text-gray-900">{{ $branch->nama_cabang }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-bold text-gray-600">{{ $branch->total_trx }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900">Rp
                                        {{ number_format($branch->total_income, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TOP PRODUCTS -->
            <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Top 5 Produk Terlaris</h2>
                        <p class="text-[11px] text-gray-500 mt-1">Produk pencetak omzet tertinggi.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-extrabold text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Produk & Varian</th>
                                <th class="px-6 py-4 text-center">Terjual</th>
                                <th class="px-6 py-4 text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse ($topProducts as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">
                                            {{ $item->variant->product->nama_produk ?? 'Unknown' }}</p>
                                        <p class="text-[10px] text-gray-500 font-semibold">
                                            {{ $item->variant->nama_varian ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="bg-[#CC9863]/10 text-[#CC9863] px-2 py-1 rounded font-black text-xs">{{ $item->total_qty }}
                                            pcs</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900">Rp
                                        {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">Belum ada data
                                        penjualan produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= BOTTOM SECTION ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Transactions Table -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Transaksi Terakhir</h2>
                    <a href="{{ route('owner.transaction.index') }}"
                        class="text-[#CC9863] text-xs font-semibold hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            <tr>
                                <th class="pb-3 px-2">Waktu</th>
                                <th class="pb-3 px-2">Kasir / Cabang</th>
                                <th class="pb-3 px-2">Total Harga</th>
                                <th class="pb-3 px-2">Metode</th>
                                <th class="pb-3 px-2">Pelanggan</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                            @forelse($recentTransactions as $trx)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3 px-2 text-gray-500 text-xs">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->translatedFormat('d M H:i') }}</td>
                                    <td class="py-3 px-2 font-medium">
                                        {{ $trx->kasir->nama_lengkap ?? 'Kasir' }}
                                        <span
                                            class="text-[10px] text-gray-400">({{ $trx->cabang->nama_cabang ?? 'Pusat' }})</span>
                                    </td>
                                    <td class="py-3 px-2 font-bold text-gray-900">Rp
                                        {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                                    <td class="py-3 px-2">
                                        <span
                                            class="text-[10px] {{ in_array(strtolower($trx->metode_bayar), ['tempo', 'cash_tempo']) ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} px-2 py-1 rounded font-bold uppercase">
                                            {{ str_replace('_', ' ', $trx->metode_bayar) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2 text-gray-500 text-xs">{{ $trx->member->nama ?? 'Umum' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-sm text-gray-400 italic">Belum ada
                                        transaksi tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Warning Stock List -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Peringatan Stok</h2>
                        <p class="text-[10px] text-gray-500">Stok produk &lt; 10</p>
                    </div>
                    <div class="w-7 h-7 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3 overflow-y-auto flex-1 pr-2">
                    @forelse($lowStocks as $stok)
                        <div class="flex justify-between items-center bg-red-50/40 border border-red-50 p-3 rounded-xl">
                            <div>
                                <h4 class="text-sm font-bold text-gray-800 leading-tight">
                                    {{ $stok->variant->nama_varian ?? 'Produk Tidak Dikenal' }}</h4>
                                <p class="text-[10px] text-gray-500 mt-0.5 font-semibold">Cabang:
                                    {{ $stok->branch->nama_cabang ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-lg font-black {{ $stok->stok == 0 ? 'text-red-600' : 'text-orange-500' }}">{{ $stok->stok }}</span>
                                <p class="text-[9px] text-gray-400 font-bold uppercase">Sisa</p>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center opacity-60 mt-4">
                            <svg class="w-10 h-10 text-green-500 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs font-semibold text-gray-600">Semua stok aman.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardChart', (data) => ({
                chartInstance: null,
                init() {
                    this.$nextTick(() => {
                        const ctx = this.$refs.salesChart.getContext('2d');
                        this.chartInstance = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                        label: 'Omzet',
                                        data: data.omzet,
                                        backgroundColor: '#CC9863',
                                        borderRadius: 4,
                                        barPercentage: 0.6,
                                        categoryPercentage: 0.8
                                    },
                                    {
                                        label: 'Modal (HPP)',
                                        data: data.hpp,
                                        backgroundColor: '#BFDBFE',
                                        borderRadius: 4,
                                        barPercentage: 0.6,
                                        categoryPercentage: 0.8
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        backgroundColor: '#111827',
                                        padding: 10,
                                        cornerRadius: 8,
                                        callbacks: {
                                            label: ctx =>
                                                ` ${ctx.dataset.label}: Rp ${Number(ctx.raw).toLocaleString('id-ID')}`
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        border: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#9CA3AF',
                                            font: {
                                                size: 10,
                                                weight: '600'
                                            }
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        border: {
                                            display: false
                                        },
                                        grid: {
                                            color: '#F3F4F6'
                                        },
                                        ticks: {
                                            color: '#9CA3AF',
                                            font: {
                                                size: 10,
                                                weight: '600'
                                            },
                                            callback: val => {
                                                if (val >= 1000000) return (val /
                                                    1000000) + 'M';
                                                if (val >= 1000) return (val /
                                                    1000) + 'k';
                                                return val;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                }
            }));
        });
    </script>
@endsection
