@extends('template.sidebar')
@section('title', 'Dashboard Owner')

@section('content')
    <main class="flex-1 bg-[#FAFAFA] overflow-y-auto px-4 lg:px-8 py-6 w-full relative min-h-screen">

        <!-- Loader Overlay -->
        <div id="ajax-loader"
            class="fixed inset-0 bg-white/60 backdrop-blur-sm z-[100] hidden items-center justify-center transition-all">
            <div class="flex flex-col items-center gap-3 bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <div class="w-10 h-10 border-4 border-[#CC9863]/30 border-t-[#CC9863] rounded-full animate-spin"></div>
                <p class="text-sm font-bold text-gray-700">Memuat Data...</p>
            </div>
        </div>

        <!-- Header Section & Smart Filter -->
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-5 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">
                    Selamat datang, {{ explode(' ', auth()->user()->nama_lengkap ?? 'Owner')[0] }}!
                </h1>
                <p class="text-gray-500 text-sm mt-1 font-medium">Ringkasan profitabilitas dan kesehatan operasional bisnis
                    parfum.</p>
            </div>

            <!-- Filter Form (AJAX) -->
            <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                <a href="{{ route('owner.finance.index') }}"
                    class="h-[42px] px-4 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-xs flex items-center gap-2 hover:border-[#CC9863] hover:text-[#CC9863] transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Full Analytics &rarr;
                </a>

                <div class="bg-white p-2.5 rounded-2xl border border-gray-200 shadow-sm flex-1 sm:flex-none"
                    x-data="{ period: '{{ $period }}' }">
                    <form id="dashboardFilter" onsubmit="event.preventDefault(); window.applyDashboardFilter();"
                        class="flex flex-col sm:flex-row items-center gap-2 w-full">
                        <select name="period" x-model="period"
                            @change="if(period !== 'custom') window.applyDashboardFilter();"
                            class="w-full sm:w-[160px] h-[38px] px-3 bg-gray-50 border border-transparent rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:border-[#CC9863] focus:bg-white transition-colors cursor-pointer">
                            <option value="today">Hari Ini</option>
                            <option value="this_week">Minggu Ini</option>
                            <option value="this_month">Bulan Ini</option>
                            <option value="this_year">Tahun Ini</option>
                            <option value="all">Keseluruhan</option>
                            <option value="custom">Pilih Tanggal...</option>
                        </select>
                        <div x-show="period === 'custom'" x-transition class="flex w-full sm:w-auto items-center gap-1.5"
                            style="display: none;">
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                class="h-[38px] px-2 w-[115px] bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700">
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                class="h-[38px] px-2 w-[115px] bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700">
                            <button type="submit"
                                class="h-[38px] px-3 bg-[#CC9863] text-white rounded-xl font-bold hover:bg-[#b58555] transition text-xs shrink-0">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Data Wrapper for AJAX -->
        <div id="dashboard-data-wrapper">

            <div class="mb-5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Periode Data:</span>
                    <span
                        class="bg-[#CC9863]/15 text-[#CC9863] font-black text-xs px-3 py-1 rounded-lg">{{ $periodLabel ?? 'Bulan Ini' }}</span>
                </div>
            </div>

            <!-- ================= PROFITABILITY & CASH CARDS ================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Laba Bersih -->
                <div
                    class="bg-[#1C1D21] text-white p-5 rounded-2xl shadow-md flex flex-col justify-between border border-gray-800">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Net Profit (Bersih)</h3>
                        <span
                            class="bg-[#CC9863]/20 text-[#CC9863] text-[10px] font-black px-2 py-0.5 rounded">{{ $marginPercentage ?? 0 }}%</span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white">Rp {{ number_format($labaBersih ?? 0, 0, ',', '.') }}
                        </h2>
                        <p class="text-[10px] text-gray-400 mt-1">Setelah dikurangi modal & operasional</p>
                    </div>
                </div>

                <!-- Kas Masuk -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-xs font-semibold text-gray-500">Kas Masuk (In Hand)</h3>
                        <div class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-black text-gray-900">Rp
                            {{ number_format($kasDiterima ?? 0, 0, ',', '.') }}</h2>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Tunai / Transfer cair</span>
                    </div>
                </div>

                <!-- Piutang Tempo -->
                <div
                    class="bg-white p-5 rounded-2xl border border-orange-100 shadow-sm flex flex-col justify-between border-b-4 border-b-orange-400">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-xs font-semibold text-orange-600">Piutang (Tempo)</h3>
                        <div class="w-7 h-7 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-black text-orange-600">Rp
                            {{ number_format($totalPiutang ?? 0, 0, ',', '.') }}</h2>
                        <span class="text-[10px] font-bold text-orange-700 bg-orange-100 px-2 py-0.5 rounded">Belum
                            lunas</span>
                    </div>
                </div>

                <!-- Beban Operasional / Expense -->
                <a href="{{ route('owner.expense.index') }}"
                    class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:border-[#CC9863] transition group">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-xs font-semibold text-gray-500 group-hover:text-[#CC9863]">Beban Operasional</h3>
                        <span class="text-gray-300 group-hover:text-[#CC9863]">&rarr;</span>
                    </div>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-black text-rose-600">Rp
                            {{ number_format($totalBebanOp ?? 0, 0, ',', '.') }}</h2>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Klik kelola beban</span>
                    </div>
                </a>
            </div>

            <!-- ================= SECONDARY METRICS (Aset & Stok Kritis) ================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">

                <!-- Card Aset Stok -->
                <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                    class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 relative overflow-hidden group hover:shadow-md hover:border-blue-200 transition-all flex flex-col justify-between min-h-[140px]">

                    <!-- Subtle Glow Background -->
                    <div
                        class="absolute -right-8 -top-8 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-70 group-hover:bg-blue-100 transition-colors duration-500">
                    </div>

                    <!-- Header Card -->
                    <div class="relative z-10 flex justify-between items-start mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/50 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Total Aset
                                    Stok</p>
                                <p class="text-[11px] font-bold text-blue-500 mt-0.5">Nilai Modal Riil</p>
                            </div>
                        </div>
                        <div
                            class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- Value -->
                    <div class="relative z-10">
                        <p class="text-2xl lg:text-3xl font-black text-gray-900 truncate">Rp
                            {{ number_format($totalAsetModal ?? 0, 0, ',', '.') }}</p>
                        <p class="text-[11px] text-gray-400 font-semibold mt-1">Nilai keseluruhan stok barang di gudang
                            pusat dan cabang.</p>
                    </div>
                </a>

                <!-- Card Stok Kritis -->
                <a href="{{ url(auth()->user()->role->nama_role . '/stock') }}"
                    class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 relative overflow-hidden group hover:shadow-md hover:border-red-200 transition-all flex flex-col justify-between min-h-[140px]">

                    <!-- Subtle Glow Background -->
                    <div
                        class="absolute -right-8 -top-8 w-32 h-32 bg-red-50 rounded-full blur-3xl opacity-70 group-hover:bg-red-100 transition-colors duration-500">
                    </div>

                    <!-- Header Card -->
                    <div class="relative z-10 flex justify-between items-start mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center border border-red-100/50 group-hover:bg-red-500 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-extrabold text-red-500 uppercase tracking-widest">Peringatan
                                    Stok</p>
                                <p class="text-[11px] font-bold text-gray-400 mt-0.5">Sisa &le; 10 Pcs/Ml</p>
                            </div>
                        </div>
                        <div
                            class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-600 transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <!-- Value -->
                    <div class="relative z-10">
                        <p class="text-2xl lg:text-3xl font-black text-red-600 truncate">
                            {{ isset($lowStocks) ? $lowStocks->count() : 0 }} <span
                                class="text-base text-red-400 font-bold ml-0.5">Varian</span>
                        </p>
                        <p class="text-[11px] text-gray-400 font-semibold mt-1">Varian produk yang hampir habis dan perlu
                            segera di-restok.</p>
                    </div>
                </a>

            </div>




            <!-- ================= MIDDLE SECTION (Charts) ================= -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Chart Area -->
                <div
                    class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-end gap-4 mb-8 relative z-10">
                        <div>
                            <p class="text-[10px] font-extrabold text-[#CC9863] uppercase tracking-widest mb-1">Revenue vs
                                Cost</p>
                            <h2 class="text-xl font-black text-gray-900">Performa Omzet & Modal</h2>
                            <p class="text-[11px] text-gray-500 font-medium mt-1">Grafik tren harian selama periode <span
                                    class="font-bold text-gray-700">{{ $periodLabel }}</span>.</p>
                        </div>
                        <div
                            class="flex items-center gap-3 text-[10px] font-extrabold uppercase tracking-wider bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-2"><span
                                    class="w-2.5 h-2.5 rounded-full bg-[#1C1D21]"></span> <span
                                    class="text-gray-600">Omzet</span></div>
                            <div class="flex items-center gap-2"><span
                                    class="w-2.5 h-2.5 rounded-full bg-[#CC9863]"></span> <span
                                    class="text-gray-600">Modal (HPP)</span></div>
                        </div>
                    </div>
                    <div class="relative w-full flex-1 min-h-[280px] z-10">
                        <canvas id="salesChart"></canvas>
                    </div>
                    <!-- Data untuk re-render chart AJAX -->
                    <div id="chart-data-source" class="hidden" data-chart='@json($chartData)'></div>
                </div>

                <!-- Payment Methods & Top Products -->
                <div
                    class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] flex flex-col justify-between">
                    <div>
                        <h2 class="text-lg font-black text-gray-900 mb-5">Metode Bayar</h2>
                        @if (isset($paymentMethods) && $paymentMethods->count() > 0)
                            <div class="w-full space-y-4">
                                @foreach ($paymentMethods as $pm)
                                    @php $pct = $totalTransactions > 0 ? round(($pm->total_transaksi / $totalTransactions) * 100) : 0; @endphp
                                    <div>
                                        <div class="flex justify-between text-xs font-bold mb-1.5">
                                            <span
                                                class="text-gray-700 uppercase tracking-wider">{{ str_replace('_', ' ', $pm->metode_bayar) }}</span>
                                            <span class="text-gray-900">{{ $pm->total_transaksi }} Trx <span
                                                    class="text-[#CC9863] ml-0.5">({{ $pct }}%)</span></span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                                            <div class="bg-[#1C1D21] h-1.5 rounded-full transition-all duration-700"
                                                style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <p class="text-xs text-gray-400 italic">Belum ada transaksi.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Top Produk Terlaris
                        </h3>
                        <div class="space-y-3">
                            @foreach ($topProducts->take(3) as $tp)
                                <div class="flex items-center justify-between text-xs group cursor-default">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-[#CC9863] transition-colors"></span>
                                        <span
                                            class="font-bold text-gray-700 group-hover:text-gray-900 transition-colors truncate max-w-[150px]">{{ $tp->variant->product->nama_produk ?? 'Produk' }}</span>
                                    </div>
                                    <span class="font-black text-[#CC9863]">Rp
                                        {{ number_format($tp->total_revenue / 1000, 0, ',', '.') }}k</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= RECENT TRANSACTIONS ================= -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <div>
                        <h2 class="text-base font-black text-gray-900">Transaksi Terakhir</h2>
                        <p class="text-[11px] font-medium text-gray-500 mt-1">Aktivitas real-time kasir di seluruh cabang.
                        </p>
                    </div>
                    <a href="{{ route('owner.transaction.index') }}"
                        class="text-[11px] font-extrabold text-[#CC9863] hover:underline bg-white border border-gray-200 px-3.5 py-1.5 rounded-xl shadow-sm">Semua
                        Riwayat &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-white border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Waktu & Nota</th>
                                <th class="px-6 py-4">Kasir / Cabang</th>
                                <th class="px-6 py-4 text-center">Metode</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4 text-right">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                            @forelse($recentTransactions as $trx)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-3.5">
                                        <p class="font-bold text-gray-900">
                                            {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->translatedFormat('d M Y') }}</p>
                                        <p class="text-[10px] text-gray-400 font-semibold mt-0.5">
                                            {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('H:i') }} • <span
                                                class="text-[#1C1D21] font-bold">{{ $trx->nomor_nota }}</span></p>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <p class="font-bold text-gray-900">{{ $trx->cashier->nama_lengkap ?? 'Kasir' }}
                                        </p>
                                        <p class="text-[10px] text-gray-500 font-semibold">
                                            {{ $trx->branch->nama_cabang ?? 'Pusat' }}</p>
                                    </td>
                                    <td class="px-6 py-3.5 text-center">
                                        <span
                                            class="inline-flex px-2.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-gray-100 text-gray-600">{{ str_replace('_', ' ', $trx->metode_bayar) }}</span>
                                    </td>
                                    <td class="px-6 py-3.5 text-xs font-bold text-gray-600">
                                        {{ $trx->member->nama ?? 'Umum' }}</td>
                                    <td class="px-6 py-3.5 text-right font-black text-gray-900">Rp
                                        {{ number_format($trx->total_belanja, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-sm text-gray-400 font-medium italic">
                                        Belum ada transaksi tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- End Dashboard Wrapper -->
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        let salesChartInstance = null;

        function initChart(chartData) {
            const canvas = document.getElementById('salesChart');
            if (!canvas || typeof Chart === 'undefined') return;

            if (salesChartInstance) salesChartInstance.destroy();

            const ctx = canvas.getContext('2d');

            // Gradasi Lembut untuk Omzet (Hitam)
            const gradientOmzet = ctx.createLinearGradient(0, 0, 0, 300);
            gradientOmzet.addColorStop(0, 'rgba(28, 29, 33, 0.15)'); // Hitam transparan
            gradientOmzet.addColorStop(1, 'rgba(28, 29, 33, 0)');

            // Gradasi Lembut untuk HPP (Emas)
            const gradientHpp = ctx.createLinearGradient(0, 0, 0, 300);
            gradientHpp.addColorStop(0, 'rgba(204, 152, 99, 0.15)'); // Emas transparan
            gradientHpp.addColorStop(1, 'rgba(204, 152, 99, 0)');

            salesChartInstance = new Chart(ctx, {
                type: 'line', // Mengubah Bar menjadi Line Chart
                data: {
                    labels: chartData.labels,
                    datasets: [{
                            label: 'Omzet',
                            data: chartData.omzet,
                            borderColor: '#1C1D21', // Hitam Elegan
                            backgroundColor: gradientOmzet,
                            borderWidth: 3,
                            tension: 0.4, // Membuat garis melengkung lembut (Smooth Area)
                            fill: true,
                            pointRadius: 0, // Titik disembunyikan sampai di-hover
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#ffffff',
                            pointHoverBorderColor: '#1C1D21',
                            pointHoverBorderWidth: 3,
                        },
                        {
                            label: 'Modal (HPP)',
                            data: chartData.hpp,
                            borderColor: '#CC9863', // Emas
                            backgroundColor: gradientHpp,
                            borderWidth: 2.5,
                            borderDash: [6, 4], // Garis putus-putus agar mudah dibedakan dengan Omzet
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#ffffff',
                            pointHoverBorderColor: '#CC9863',
                            pointHoverBorderWidth: 3,
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
                            padding: 12,
                            cornerRadius: 12,
                            titleFont: {
                                size: 13,
                                family: "'Plus Jakarta Sans', sans-serif"
                            },
                            bodyFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: ctx => ` ${ctx.dataset.label}: Rp ${Number(ctx.raw).toLocaleString('id-ID')}`
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
                                    weight: '700'
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
                                    weight: '700'
                                },
                                callback: val => val >= 1000000 ? (val / 1000000) + 'Jt' : (val >= 1000 ? (val /
                                    1000) + 'K' : val)
                            }
                        }
                    }
                }
            });
        }
        window.applyDashboardFilter = function() {
            const form = document.getElementById('dashboardFilter');
            const params = new URLSearchParams(new FormData(form)).toString();
            const loader = document.getElementById('ajax-loader');

            loader.classList.remove('hidden');
            loader.classList.add('flex');

            fetch(`{{ route('owner.dashboard') }}?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    window.history.pushState({}, '', `?${params}`);
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data.html, 'text/html');
                    document.getElementById('dashboard-data-wrapper').innerHTML = doc.getElementById(
                        'dashboard-data-wrapper').innerHTML;
                    initChart(data.chart);
                })
                .catch(err => console.error("Gagal memuat data", err))
                .finally(() => {
                    loader.classList.add('hidden');
                    loader.classList.remove('flex');
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const initialChartData = JSON.parse(document.getElementById('chart-data-source').dataset.chart);
            initChart(initialChartData);
        });
    </script>
@endsection
