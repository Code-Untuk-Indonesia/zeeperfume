@extends('template.sidebar')
@section('title', 'Laporan Pendapatan')

@section('content')

    @php
        $activeFilter = request('filter_type', $filterType ?? 'month');

        $activeFilters = collect([
            $branchId ? $branches->firstWhere('id', $branchId)?->nama_cabang : null,
            $paymentMethod ? strtoupper(str_replace('_', ' ', $paymentMethod)) : null,
        ])->filter();
    @endphp

    <main class="flex-1 bg-[#F5F6F8] overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full min-h-screen">

        <!-- ================= HEADER ================= -->
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-5 mb-7">

            <div>
                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-2xl bg-[#CC9863]/10 text-[#CC9863] flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                            Income Report
                        </h1>

                        <p class="text-sm text-gray-500 font-medium mt-1">
                            Pantau pendapatan, transaksi dan performa setiap outlet.
                        </p>
                    </div>

                </div>
            </div>


            <div class="grid grid-cols-2 sm:flex gap-2 w-full xl:w-auto">

                <a href="{{ route('owner.income.print', request()->query()) }}" target="_blank"
                    class="h-11 px-4 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-bold flex items-center justify-center gap-2 shadow-sm hover:bg-gray-50 hover:border-gray-300 transition">

                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-2 0v4H8v-4m-2-9h12" />
                    </svg>

                    Print
                </a>


                <a href="{{ route('owner.income.export', request()->query()) }}"
                    class="h-11 px-4 rounded-xl bg-[#CC9863] text-white text-sm font-bold flex items-center justify-center gap-2 shadow-sm hover:bg-[#b78658] transition">

                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                    </svg>

                    Export Excel
                </a>

            </div>

        </div>



        <!-- ================= FILTER ================= -->
        <div class="bg-white rounded-[22px] border border-gray-100 shadow-sm mb-7 overflow-hidden">

            <form action="{{ route('owner.income.index') }}" method="GET" id="incomeFilter">

                <input type="hidden" name="filter_type" id="filterType" value="{{ $activeFilter }}">

                <!-- Filter Header -->
                <div
                    class="px-5 sm:px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">

                    <div>
                        <h2 class="text-sm font-black text-gray-900">
                            Filter Laporan
                        </h2>

                        <p class="text-[11px] text-gray-400 font-medium mt-0.5">
                            Atur periode, outlet dan metode pembayaran.
                        </p>
                    </div>


                    <div class="inline-flex bg-gray-100 rounded-xl p-1 w-full sm:w-auto">

                        <button type="button" id="btnMonthMode" onclick="changeFilterMode('month')"
                            class="filter-mode flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition">
                            Bulanan
                        </button>

                        <button type="button" id="btnCustomMode" onclick="changeFilterMode('custom')"
                            class="filter-mode flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition">
                            Rentang Tanggal
                        </button>

                    </div>

                </div>


                <!-- Filter Body -->
                <div class="p-5 sm:p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

                        <!-- MONTH -->
                        <div id="monthFilterGroup">

                            <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">
                                Bulan & Tahun
                            </label>

                            <div class="grid grid-cols-2 gap-2">

                                <select name="month" id="monthInput"
                                    class="w-full h-11 bg-gray-50 border border-gray-200 rounded-xl px-3 text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863]">

                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" @selected((int) $month === $i)>
                                            {{ \Carbon\Carbon::create(null, $i)->translatedFormat('F') }}
                                        </option>
                                    @endfor

                                </select>


                                <select name="year" id="yearInput"
                                    class="w-full h-11 bg-gray-50 border border-gray-200 rounded-xl px-3 text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863]">

                                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                                        <option value="{{ $y }}" @selected((int) $year === $y)>
                                            {{ $y }}
                                        </option>
                                    @endfor

                                </select>

                            </div>

                        </div>


                        <!-- CUSTOM DATE -->
                        <div id="customFilterGroup" class="hidden">

                            <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">
                                Rentang Tanggal
                            </label>

                            <div class="grid grid-cols-2 gap-2">

                                <input type="date" name="start_date" id="startDateInput"
                                    value="{{ request('start_date') }}"
                                    class="w-full h-11 bg-gray-50 border border-gray-200 rounded-xl px-3 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863]">


                                <input type="date" name="end_date" id="endDateInput" value="{{ request('end_date') }}"
                                    class="w-full h-11 bg-gray-50 border border-gray-200 rounded-xl px-3 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863]">

                            </div>

                        </div>


                        <!-- OUTLET -->
                        <div>

                            <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">
                                Outlet
                            </label>

                            <select name="branch_id"
                                class="w-full h-11 bg-gray-50 border border-gray-200 rounded-xl px-3 text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863]">

                                <option value="">Semua Outlet</option>

                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" @selected((string) $branchId === (string) $branch->id)>
                                        {{ $branch->nama_cabang }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                        <!-- PAYMENT -->
                        <div>

                            <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2">
                                Metode Pembayaran
                            </label>

                            <select name="payment_method"
                                class="w-full h-11 bg-gray-50 border border-gray-200 rounded-xl px-3 text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#CC9863]/20 focus:border-[#CC9863]">

                                <option value="">Semua Metode</option>
                                <option value="cash" @selected($paymentMethod === 'cash')>Cash</option>
                                <option value="qris" @selected($paymentMethod === 'qris')>QRIS</option>
                                <option value="transfer" @selected($paymentMethod === 'transfer')>Transfer</option>
                                <option value="cash_tempo" @selected($paymentMethod === 'cash_tempo')>Cash Tempo</option>
                                <option value="tempo" @selected($paymentMethod === 'tempo')>Tempo</option>

                            </select>

                        </div>


                        <!-- ACTION -->
                        <div class="md:col-span-2 xl:col-span-1 flex items-end gap-2">

                            <button type="submit"
                                class="flex-1 h-11 rounded-xl bg-gray-900 text-white text-sm font-bold flex items-center justify-center gap-2 hover:bg-black transition shadow-sm">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2l-7 7v6l-4 2v-8L3 6V4z" />
                                </svg>

                                Terapkan Filter

                            </button>


                            <a href="{{ route('owner.income.index') }}" title="Reset Filter"
                                class="w-11 h-11 shrink-0 rounded-xl bg-red-50 border border-red-100 text-red-500 flex items-center justify-center hover:bg-red-100 transition">

                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>



        <!-- ================= ACTIVE PERIOD ================= -->
        <div class="flex flex-wrap items-center gap-2 mb-5">

            <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">
                Periode Aktif
            </span>

            <span class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-xs font-bold">
                {{ $monthName }}
            </span>

            @foreach ($activeFilters as $filter)
                <span class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-gray-600 text-xs font-bold">
                    {{ $filter }}
                </span>
            @endforeach

        </div>



        <!-- ================= METRIC CARDS ================= -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-7">

            <!-- OMZET -->
            <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm p-6 relative overflow-hidden group">

                <div class="absolute -right-8 -top-8 w-32 h-32 bg-blue-100 rounded-full blur-3xl opacity-50"></div>

                <div class="relative">

                    <div class="flex justify-between items-start">

                        <div>

                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">
                                Total Omzet
                            </p>

                            <p class="text-2xl lg:text-3xl font-black text-gray-900 mt-2">
                                Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </p>

                        </div>


                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2" />
                            </svg>

                        </div>

                    </div>


                    <p class="text-[11px] text-gray-400 font-medium mt-4">
                        Total nilai seluruh transaksi pada periode aktif.
                    </p>

                </div>

            </div>


            <!-- TRANSACTION -->
            <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm p-6 relative overflow-hidden">

                <div class="absolute -right-8 -top-8 w-32 h-32 bg-purple-100 rounded-full blur-3xl opacity-50"></div>

                <div class="relative">

                    <div class="flex justify-between">

                        <div>

                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">
                                Total Transaksi
                            </p>

                            <p class="text-2xl lg:text-3xl font-black text-gray-900 mt-2">
                                {{ number_format($totalTrx, 0, ',', '.') }}
                                <span class="text-sm text-gray-400 font-bold">Trx</span>
                            </p>

                        </div>


                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                            </svg>

                        </div>

                    </div>


                    <p class="text-[11px] text-gray-400 font-medium mt-4">
                        Jumlah transaksi berhasil yang masuk ke laporan.
                    </p>

                </div>

            </div>


            <!-- AVERAGE -->
            <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm p-6 relative overflow-hidden">

                <div class="absolute -right-8 -top-8 w-32 h-32 bg-orange-100 rounded-full blur-3xl opacity-50"></div>

                <div class="relative">

                    <div class="flex justify-between">

                        <div>

                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">
                                Rata-Rata Order
                            </p>

                            <p class="text-2xl lg:text-3xl font-black text-gray-900 mt-2">
                                Rp {{ number_format($avgTransaction, 0, ',', '.') }}
                            </p>

                        </div>


                        <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 9h14l1 12H4L5 9z" />
                            </svg>

                        </div>

                    </div>


                    <p class="text-[11px] text-gray-400 font-medium mt-4">
                        Nilai rata-rata omzet pada setiap transaksi.
                    </p>

                </div>

            </div>

        </div>



        <!-- ================= CHART ================= -->
        <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm p-5 sm:p-6 mb-7">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">

                <div>

                    <p class="text-[10px] font-extrabold text-indigo-500 uppercase tracking-wider">
                        Revenue Performance
                    </p>

                    <h2 class="text-lg font-black text-gray-900 mt-1">
                        Tren Pendapatan
                    </h2>

                    <p class="text-[11px] text-gray-400 font-medium mt-1">
                        Pergerakan omzet selama {{ $monthName }}.
                    </p>

                </div>


                <div class="flex items-center gap-2 text-[10px] font-bold text-gray-500">

                    <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></span>

                    Total Omzet

                </div>

            </div>


            <div class="relative h-[280px] sm:h-[330px] w-full">

                <canvas id="incomeChart"></canvas>

            </div>

        </div>



        <!-- ================= OUTLET SUMMARY ================= -->
        <section class="mb-7">

            <div class="flex justify-between items-end mb-4">

                <div>
                    <h2 class="text-lg font-black text-gray-900">
                        Rekap Pendapatan Outlet
                    </h2>

                    <p class="text-[11px] text-gray-400 mt-1">
                        Omzet, kas diterima dan nilai piutang.
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-[#CC9863]">

                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">
                        Total Omzet
                    </p>

                    <p class="text-xl font-black text-gray-900 mt-1">
                        Rp {{ number_format($dailySummary['total_pendapatan'], 0, ',', '.') }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-emerald-500">

                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">
                        Kas Diterima
                    </p>

                    <p class="text-xl font-black text-emerald-600 mt-1">
                        Rp {{ number_format($dailySummary['total_diterima'], 0, ',', '.') }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 border-l-4 border-l-rose-500">

                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">
                        Piutang / Tempo
                    </p>

                    <p class="text-xl font-black text-rose-600 mt-1">
                        Rp {{ number_format($dailySummary['total_piutang'], 0, ',', '.') }}
                    </p>

                </div>

            </div>



            <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="w-full min-w-[720px] text-left">

                        <thead
                            class="bg-gray-50 border-b border-gray-100 text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">

                            <tr>

                                <th class="px-6 py-4">Outlet</th>
                                <th class="px-6 py-4 text-center">Transaksi</th>
                                <th class="px-6 py-4 text-right">Omzet</th>
                                <th class="px-6 py-4 text-right">Kas Diterima</th>
                                <th class="px-6 py-4 text-right">Piutang</th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-50 text-sm">

                            @forelse($dailyOutletReports as $report)
                                <tr class="hover:bg-gray-50/70 transition">

                                    <td class="px-6 py-4">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="w-9 h-9 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center font-black text-xs">
                                                {{ strtoupper(substr($report->nama_cabang, 0, 1)) }}
                                            </div>

                                            <span class="font-bold text-gray-900">
                                                {{ $report->nama_cabang }}
                                            </span>

                                        </div>

                                    </td>


                                    <td class="px-6 py-4 text-center">

                                        <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 font-bold text-xs">
                                            {{ number_format($report->total_transaksi, 0, ',', '.') }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4 text-right font-black text-gray-900">
                                        Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}
                                    </td>


                                    <td class="px-6 py-4 text-right font-black text-emerald-600">
                                        Rp {{ number_format($report->total_diterima, 0, ',', '.') }}
                                    </td>


                                    <td class="px-6 py-4 text-right font-black text-rose-600">
                                        Rp {{ number_format($report->total_piutang, 0, ',', '.') }}
                                    </td>

                                </tr>


                            @empty

                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium">
                                        Belum ada data outlet pada periode ini.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </section>



        <!-- ================= TRANSACTION TABLE ================= -->
        <div class="bg-white rounded-[20px] border border-gray-100 shadow-sm overflow-hidden mb-7">

            <div class="px-5 sm:px-6 py-5 border-b border-gray-100 flex justify-between items-center">

                <div>

                    <h2 class="text-lg font-black text-gray-900">
                        Rincian Transaksi
                    </h2>

                    <p class="text-[11px] text-gray-400 mt-1">
                        Transaksi berdasarkan filter laporan aktif.
                    </p>

                </div>


                <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold">
                    {{ number_format($totalTrx) }} Transaksi
                </span>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[850px] text-left">

                    <thead
                        class="bg-gray-50 border-b border-gray-100 text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">

                        <tr>

                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Invoice & Pelanggan</th>
                            <th class="px-6 py-4">Outlet & Kasir</th>
                            <th class="px-6 py-4 text-center">Metode</th>
                            <th class="px-6 py-4 text-right">Nominal</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-50 text-sm">

                        @forelse($incomeTransactions as $trx)
                            @php
                                $methodColors = [
                                    'cash' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'qris' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'transfer' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                    'tempo' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'cash_tempo' => 'bg-orange-50 text-orange-600 border-orange-100',
                                ];

                                $colorClass =
                                    $methodColors[strtolower($trx->metode_bayar)] ??
                                    'bg-gray-100 text-gray-600 border-gray-200';
                            @endphp


                            <tr class="hover:bg-gray-50/60 transition">

                                <td class="px-6 py-4">

                                    <p class="font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('d M Y') }}
                                    </p>

                                    <p class="text-[11px] text-gray-400 mt-1">
                                        {{ \Carbon\Carbon::parse($trx->tanggal_waktu)->format('H:i') }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="font-black text-[#CC9863]">
                                        {{ $trx->nomor_nota }}
                                    </p>

                                    <p class="text-[11px] text-gray-400 mt-1">
                                        {{ $trx->member->nama ?? 'Pelanggan Umum' }}
                                    </p>

                                </td>


                                <td class="px-6 py-4">

                                    <p class="font-bold text-gray-900">
                                        {{ $trx->branch->nama_cabang ?? 'Pusat' }}
                                    </p>

                                    <p class="text-[11px] text-gray-400 mt-1">
                                        {{ $trx->cashier->nama_lengkap ?? 'Kasir' }}
                                    </p>

                                </td>


                                <td class="px-6 py-4 text-center">

                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase border {{ $colorClass }}">
                                        {{ str_replace('_', ' ', $trx->metode_bayar) }}
                                    </span>

                                </td>


                                <td class="px-6 py-4 text-right text-base font-black text-gray-900">
                                    Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}
                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="5" class="px-6 py-12 text-center">

                                    <div
                                        class="w-12 h-12 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 mb-3">

                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>

                                    </div>

                                    <p class="font-bold text-gray-500">
                                        Tidak ada transaksi
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Coba ubah periode atau filter laporan.
                                    </p>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            @if ($incomeTransactions->hasPages())
                <div class="p-4 sm:px-6 border-t border-gray-100">
                    {{ $incomeTransactions->links('pagination::tailwind') }}
                </div>
            @endif

        </div>

    </main>



    <!-- ================= CHART JS ================= -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <script>
        (function() {

            const chartData = @json($chartData);

            function formatRupiah(value) {
                return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
            }

            function compactNumber(value) {
                value = Number(value || 0);

                if (value >= 1000000000) {
                    return 'Rp ' + (value / 1000000000).toLocaleString('id-ID', {
                        maximumFractionDigits: 1
                    }) + ' M';
                }

                if (value >= 1000000) {
                    return 'Rp ' + (value / 1000000).toLocaleString('id-ID', {
                        maximumFractionDigits: 1
                    }) + ' jt';
                }

                if (value >= 1000) {
                    return 'Rp ' + (value / 1000).toLocaleString('id-ID', {
                        maximumFractionDigits: 0
                    }) + ' rb';
                }

                return 'Rp ' + value.toLocaleString('id-ID');
            }


            function renderChart() {

                if (typeof Chart === 'undefined') {
                    console.error('Chart.js tidak berhasil dimuat.');
                    return;
                }

                const canvas = document.getElementById('incomeChart');

                if (!canvas) {
                    return;
                }

                const oldChart = Chart.getChart(canvas);

                if (oldChart) {
                    oldChart.destroy();
                }

                const ctx = canvas.getContext('2d');

                const gradient = ctx.createLinearGradient(0, 0, 0, 330);

                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.28)');
                gradient.addColorStop(0.55, 'rgba(99, 102, 241, 0.08)');
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

                new Chart(ctx, {
                    type: 'line',

                    data: {
                        labels: chartData.labels,

                        datasets: [{
                            label: 'Total Omzet',
                            data: chartData.income,
                            borderColor: '#6366F1',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            tension: 0.38,
                            fill: true,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBorderWidth: 3,
                            pointHoverBackgroundColor: '#ffffff',
                            pointHoverBorderColor: '#6366F1'
                        }]
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
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                padding: 12,
                                cornerRadius: 10,

                                callbacks: {
                                    label(context) {
                                        return ' Omzet: ' + formatRupiah(context.raw);
                                    }
                                }
                            }
                        },

                        scales: {
                            x: {
                                border: {
                                    display: false
                                },

                                grid: {
                                    display: false
                                },

                                ticks: {
                                    color: '#9CA3AF',
                                    font: {
                                        size: 10,
                                        weight: '600'
                                    },
                                    maxTicksLimit: 12,
                                    maxRotation: 0
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
                                    padding: 8,
                                    font: {
                                        size: 10,
                                        weight: '600'
                                    },

                                    callback(value) {
                                        return compactNumber(value);
                                    }
                                }
                            }
                        }
                    }
                });
            }


            window.changeFilterMode = function(mode) {

                const filterType = document.getElementById('filterType');
                const monthGroup = document.getElementById('monthFilterGroup');
                const customGroup = document.getElementById('customFilterGroup');

                const monthInput = document.getElementById('monthInput');
                const yearInput = document.getElementById('yearInput');
                const startDateInput = document.getElementById('startDateInput');
                const endDateInput = document.getElementById('endDateInput');

                const monthButton = document.getElementById('btnMonthMode');
                const customButton = document.getElementById('btnCustomMode');

                filterType.value = mode;

                const activeClass = [
                    'bg-white',
                    'text-gray-900',
                    'shadow-sm'
                ];

                const inactiveClass = [
                    'text-gray-500'
                ];

                monthButton.classList.remove(...activeClass, ...inactiveClass);
                customButton.classList.remove(...activeClass, ...inactiveClass);

                if (mode === 'custom') {

                    monthGroup.classList.add('hidden');
                    customGroup.classList.remove('hidden');

                    monthInput.disabled = true;
                    yearInput.disabled = true;

                    startDateInput.disabled = false;
                    endDateInput.disabled = false;

                    customButton.classList.add(...activeClass);
                    monthButton.classList.add(...inactiveClass);

                } else {

                    monthGroup.classList.remove('hidden');
                    customGroup.classList.add('hidden');

                    monthInput.disabled = false;
                    yearInput.disabled = false;

                    startDateInput.disabled = true;
                    endDateInput.disabled = true;

                    monthButton.classList.add(...activeClass);
                    customButton.classList.add(...inactiveClass);
                }

            };


            function init() {

                changeFilterMode(
                    document.getElementById('filterType').value || 'month'
                );

                renderChart();

            }


            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }

        })();
    </script>

@endsection
