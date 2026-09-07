@extends('template.sidebar')
@section('title', 'Analytics Finance')

@section('content')

<main class="flex-1 bg-[#F3F4F6] overflow-y-auto px-4 lg:px-8 py-6 lg:py-8 w-full relative">

    <!-- ================= HEADER SECTION ================= -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">

        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                Analytics
            </h1>

            <p class="text-gray-500 text-sm mt-1 font-medium">
                Detailed overview of your financial situation
            </p>
        </div>


        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">

            <form action="{{ route('owner.finance.index') }}" method="GET" class="flex gap-2">

                <div class="flex items-center bg-white border border-gray-200 rounded-2xl px-3 shadow-sm">

                    <svg class="w-4 h-4 text-gray-400 mr-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>

                    </svg>


                    <select
                        name="month"
                        onchange="this.form.submit()"
                        class="bg-transparent border-none text-gray-700 py-2.5 font-bold text-sm focus:outline-none cursor-pointer appearance-none">

                        @for($i = 1; $i <= 12; $i++)

                            <option
                                value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                {{ $month == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>

                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}

                            </option>

                        @endfor

                    </select>

                </div>


                <div class="flex items-center bg-white border border-gray-200 rounded-2xl px-3 shadow-sm">

                    <select
                        name="year"
                        onchange="this.form.submit()"
                        class="bg-transparent border-none text-gray-700 py-2.5 font-bold text-sm focus:outline-none cursor-pointer appearance-none">

                        <option value="2026" {{ $year == '2026' ? 'selected' : '' }}>
                            2026
                        </option>

                        <option value="2025" {{ $year == '2025' ? 'selected' : '' }}>
                            2025
                        </option>

                    </select>

                </div>

            </form>


            <button
                onclick="window.print()"
                class="bg-indigo-500 text-white px-5 py-2.5 rounded-2xl font-bold shadow-sm hover:bg-indigo-600 flex items-center gap-2 text-sm transition-colors print:hidden">

                <svg class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M12 4v16m8-8H4">
                    </path>

                </svg>

                Export Report

            </button>

        </div>

    </div>



    <!-- ================= TOP METRICS CARDS ================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- 1. LABA BERSIH -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6">

            <div class="flex justify-between items-center mb-3">

                <h3 class="text-sm font-bold text-gray-900">
                    Total balance (Net Profit)
                </h3>

                <span class="text-xs font-semibold bg-gray-50 text-gray-500 px-2 py-1 rounded-lg border border-gray-200">
                    IDR
                </span>

            </div>


            <h2 class="text-3xl font-black text-gray-900 mb-4">
                Rp {{ number_format($labaBersih, 0, ',', '.') }}
            </h2>


            <div class="flex items-center justify-between">

                <span class="
                    {{ $marginPercentage >= 0
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700'
                    }}
                    text-[10px]
                    font-bold
                    px-2
                    py-0.5
                    rounded-md
                    flex
                    items-center
                    gap-1
                ">

                    <svg class="w-3 h-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="3"
                            d="M5 10l7-7m0 0l7 7m-7-7v18">
                        </path>

                    </svg>

                    {{ $marginPercentage }}% Margin

                </span>


                <span class="text-[10px] font-bold text-gray-400">
                    Laba Kotor - Operasional
                </span>

            </div>

        </div>



        <!-- 2. LABA KOTOR -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6">

            <div class="flex justify-between items-center mb-3">

                <h3 class="text-sm font-bold text-gray-900">
                    Gross Profit (Laba Kotor)
                </h3>

                <span class="text-xs font-semibold bg-gray-50 text-gray-500 px-2 py-1 rounded-lg border border-gray-200">
                    IDR
                </span>

            </div>


            <h2 class="text-3xl font-black text-gray-900 mb-4">
                Rp {{ number_format($labaKotor, 0, ',', '.') }}
            </h2>


            <div class="flex items-center justify-between">

                <span class="text-[10px] font-semibold text-gray-400">
                    Omzet - Modal HPP Barang
                </span>


                <div class="flex items-center gap-2 bg-blue-50 px-2 py-1 rounded-lg">

                    <span class="text-[10px] font-bold text-blue-700">
                        Gross
                    </span>

                </div>

            </div>

        </div>



        <!-- 3. TOTAL EXPENSE -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-6">

            <div class="flex justify-between items-center mb-3">

                <h3 class="text-sm font-bold text-gray-900">
                    Total Expenses
                </h3>

                <span class="text-xs font-semibold bg-gray-50 text-gray-500 px-2 py-1 rounded-lg border border-gray-200">
                    IDR
                </span>

            </div>


            <h2 class="text-3xl font-black text-gray-900 mb-4">
                Rp {{ number_format($totalBeban, 0, ',', '.') }}
            </h2>


            <div class="flex items-center justify-between">

                <span class="text-[10px] font-semibold text-gray-400">
                    Total semua modal & biaya.
                </span>


                <div class="flex items-center gap-2 bg-red-50 px-2 py-1 rounded-lg">

                    <span class="text-[10px] font-bold text-red-700">
                        Cost
                    </span>

                </div>

            </div>

        </div>

    </div>



    <!-- ================= CHART SECTION ================= -->

    @php

        $hppPercentage = $totalBeban > 0
            ? round(($totalHpp / $totalBeban) * 100)
            : 0;

        $opsPercentage = $totalBeban > 0
            ? round(($totalPengeluaran / $totalBeban) * 100)
            : 0;

    @endphp


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- ================= LINE CHART ================= -->
        <div class="lg:col-span-2 bg-white rounded-[24px] border border-gray-100 shadow-sm p-5 sm:p-6 flex flex-col">

            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6">

                <div>

                    <p class="text-[10px] font-extrabold text-indigo-500 uppercase tracking-wider mb-1">
                        Financial Performance
                    </p>

                    <h2 class="text-base sm:text-lg font-extrabold text-gray-900">
                        Income & Expense Overview
                    </h2>

                    <p class="text-[11px] sm:text-xs text-gray-400 font-medium mt-1">
                        Pergerakan omzet dan biaya {{ $monthName }}.
                    </p>

                </div>


                <div class="flex items-center gap-4 text-[10px] font-bold">

                    <div class="flex items-center gap-2">

                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>

                        <span class="text-gray-500">
                            Omzet
                        </span>

                    </div>


                    <div class="flex items-center gap-2">

                        <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>

                        <span class="text-gray-500">
                            Total Biaya
                        </span>

                    </div>

                </div>

            </div>


            <!-- IMPORTANT: menggunakan ID, bukan Alpine x-ref -->
            <div class="relative w-full h-[280px] sm:h-[320px]">

                <canvas id="balanceChart"></canvas>

            </div>


            <div class="grid grid-cols-2 gap-3 mt-5 pt-5 border-t border-gray-100">

                <div class="bg-indigo-50/70 rounded-2xl px-4 py-3">

                    <p class="text-[9px] uppercase tracking-wide font-extrabold text-indigo-400">
                        Total Omzet
                    </p>

                    <p class="text-sm sm:text-base font-black text-indigo-700 mt-1">

                        Rp {{ number_format($totalOmzet, 0, ',', '.') }}

                    </p>

                </div>


                <div class="bg-gray-50 rounded-2xl px-4 py-3">

                    <p class="text-[9px] uppercase tracking-wide font-extrabold text-gray-400">
                        Total Beban
                    </p>

                    <p class="text-sm sm:text-base font-black text-gray-700 mt-1">

                        Rp {{ number_format($totalBeban, 0, ',', '.') }}

                    </p>

                </div>

            </div>

        </div>



        <!-- ================= DONUT CHART ================= -->
        <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm p-5 sm:p-6 flex flex-col">

            <div class="mb-5">

                <p class="text-[10px] font-extrabold text-[#CC9863] uppercase tracking-wider mb-1">
                    Cost Structure
                </p>

                <h2 class="text-base font-extrabold text-gray-900">
                    Expense Breakdown
                </h2>

                <p class="text-[11px] text-gray-400 font-medium mt-1">
                    Komposisi HPP dan biaya operasional.
                </p>

            </div>


            <div class="flex-1 flex items-center justify-center py-2">

                <div class="relative w-[190px] h-[190px] sm:w-[210px] sm:h-[210px]">

                    <canvas id="expenseChart"></canvas>


                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">

                        <span class="text-[9px] uppercase tracking-wider font-bold text-gray-400">
                            Total Beban
                        </span>


                        <span class="text-base font-black text-gray-900 mt-1">

                            @if($totalBeban >= 1000000)

                                Rp {{ number_format($totalBeban / 1000000, 1, ',', '.') }} jt

                            @elseif($totalBeban >= 1000)

                                Rp {{ number_format($totalBeban / 1000, 0, ',', '.') }} rb

                            @else

                                Rp {{ number_format($totalBeban, 0, ',', '.') }}

                            @endif

                        </span>

                    </div>

                </div>

            </div>


            <!-- Breakdown -->
            <div class="space-y-3 mt-4">

                <!-- HPP -->
                <div class="p-3.5 bg-gray-50 rounded-2xl">

                    <div class="flex items-center justify-between gap-3">

                        <div class="flex items-center gap-2">

                            <span class="w-2.5 h-2.5 bg-gray-800 rounded-full shrink-0"></span>

                            <span class="text-xs font-bold text-gray-600">
                                Modal / HPP
                            </span>

                        </div>


                        <span class="text-xs font-black text-gray-900">

                            {{ $hppPercentage }}%

                        </span>

                    </div>


                    <div class="mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">

                        <div
                            class="h-full bg-gray-800 rounded-full transition-all duration-700"
                            style="width: {{ $hppPercentage }}%">
                        </div>

                    </div>


                    <p class="text-[10px] text-gray-400 font-semibold mt-2">

                        Rp {{ number_format($totalHpp, 0, ',', '.') }}

                    </p>

                </div>



                <!-- OPERASIONAL -->
                <div class="p-3.5 bg-indigo-50/60 rounded-2xl">

                    <div class="flex items-center justify-between gap-3">

                        <div class="flex items-center gap-2">

                            <span class="w-2.5 h-2.5 bg-indigo-500 rounded-full shrink-0"></span>

                            <span class="text-xs font-bold text-gray-600">
                                Operasional
                            </span>

                        </div>


                        <span class="text-xs font-black text-indigo-700">

                            {{ $opsPercentage }}%

                        </span>

                    </div>


                    <div class="mt-2 h-1.5 bg-indigo-100 rounded-full overflow-hidden">

                        <div
                            class="h-full bg-indigo-500 rounded-full transition-all duration-700"
                            style="width: {{ $opsPercentage }}%">
                        </div>

                    </div>


                    <p class="text-[10px] text-gray-400 font-semibold mt-2">

                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}

                    </p>

                </div>

            </div>

        </div>

    </div>



    <!-- ================= TABEL PENGELUARAN ================= -->
    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden mb-6">

        <div class="p-6 border-b border-gray-50 flex justify-between items-center">

            <div>

                <h2 class="text-base font-bold text-gray-900">
                    Daftar Pengeluaran Operasional
                </h2>

                <p class="text-[11px] text-gray-500 font-semibold mt-1">
                    Catatan pengeluaran selama {{ $monthName }}.
                </p>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-left whitespace-nowrap">

                <thead class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-gray-50/50 border-b border-gray-100">

                    <tr>

                        <th class="px-6 py-4">
                            Tanggal
                        </th>

                        <th class="px-6 py-4">
                            Kategori & Nama
                        </th>

                        <th class="px-6 py-4">
                            Lokasi (Cabang)
                        </th>

                        <th class="px-6 py-4">
                            Diinput Oleh
                        </th>

                        <th class="px-6 py-4 text-right">
                            Nominal
                        </th>

                    </tr>

                </thead>


                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">

                    @forelse($expenses as $exp)

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 font-bold text-gray-900">

                                {{ \Carbon\Carbon::parse($exp->tanggal_pengeluaran)->format('d M Y') }}

                            </td>


                            <td class="px-6 py-4">

                                <p class="font-bold text-gray-900">

                                    {{ $exp->nama_pengeluaran }}

                                </p>


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


                                    <p class="font-bold text-gray-800 text-xs">

                                        {{ explode(' ', $exp->user->nama_lengkap ?? 'Unknown')[0] }}

                                    </p>

                                </div>

                            </td>


                            <td class="px-6 py-4 text-right font-black text-red-500">

                                Rp {{ number_format($exp->nominal, 0, ',', '.') }}

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-8 text-center text-gray-400 font-medium italic">

                                Tidak ada catatan pengeluaran operasional bulan ini.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>



    <!-- ================= TABLE CABANG ================= -->
    <div class="bg-white rounded-[24px] border border-gray-100 shadow-sm overflow-hidden mb-6">

        <div class="p-6 border-b border-gray-50 flex justify-between items-center">

            <div>

                <h2 class="text-base font-bold text-gray-900">
                    Rincian Keuangan per Cabang
                </h2>

                <p class="text-[11px] text-gray-500 font-semibold mt-1">
                    Sistem Perhitungan: Laba Bersih = Omzet - (HPP + Ops) per cabang.
                </p>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-left whitespace-nowrap">

                <thead class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider bg-gray-50/50 border-b border-gray-100">

                    <tr>

                        <th class="px-6 py-4">
                            Nama Outlet
                        </th>

                        <th class="px-6 py-4 text-right text-indigo-500">
                            Omzet (Kotor)
                        </th>

                        <th class="px-6 py-4 text-right">
                            Modal / HPP
                        </th>

                        <th class="px-6 py-4 text-right text-blue-500">
                            Laba Kotor
                        </th>

                        <th class="px-6 py-4 text-right">
                            Pengeluaran Ops
                        </th>

                        <th class="px-6 py-4 text-right text-green-600">
                            Laba Bersih
                        </th>

                        <th class="px-6 py-4 text-center">
                            Margin Bersih
                        </th>

                    </tr>

                </thead>


                <tbody class="text-sm text-gray-700 divide-y divide-gray-50">

                    @foreach($branchReports as $report)

                        <tr class="hover:bg-gray-50 transition cursor-pointer">

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-[10px] uppercase">

                                        {{ substr($report->nama_cabang, 0, 2) }}

                                    </div>


                                    <span class="font-bold text-gray-900">

                                        {{ $report->nama_cabang }}

                                    </span>

                                </div>

                            </td>


                            <td class="px-6 py-4 text-right font-bold text-gray-900">

                                Rp {{ number_format($report->omzet, 0, ',', '.') }}

                            </td>


                            <td class="px-6 py-4 text-right text-gray-500 font-semibold">

                                Rp {{ number_format($report->hpp, 0, ',', '.') }}

                            </td>


                            <td class="px-6 py-4 text-right font-black text-gray-900">

                                Rp {{ number_format($report->laba_kotor, 0, ',', '.') }}

                            </td>


                            <td class="px-6 py-4 text-right text-red-500 font-semibold">

                                Rp {{ number_format($report->pengeluaran, 0, ',', '.') }}

                            </td>


                            <td class="
                                px-6
                                py-4
                                text-right
                                font-black
                                {{ $report->laba_bersih >= 0
                                    ? 'text-green-600'
                                    : 'text-red-600'
                                }}
                            ">

                                Rp {{ number_format($report->laba_bersih, 0, ',', '.') }}

                            </td>


                            <td class="px-6 py-4 text-center">

                                <span class="
                                    {{
                                        $report->margin >= 0
                                            ? 'bg-indigo-50 text-indigo-700'
                                            : 'bg-red-50 text-red-700'
                                    }}
                                    text-[10px]
                                    font-extrabold
                                    px-2.5
                                    py-1
                                    rounded-md
                                ">

                                    {{ $report->margin }}%

                                </span>

                            </td>

                        </tr>

                    @endforeach

                </tbody>


                <tfoot class="bg-gray-50 text-gray-900 border-t border-gray-200">

                    <tr>

                        <td class="px-6 py-5 font-black tracking-widest text-xs uppercase">

                            Total {{ $monthName }}

                        </td>


                        <td class="px-6 py-5 text-right font-black">

                            Rp {{ number_format($totalOmzet, 0, ',', '.') }}

                        </td>


                        <td class="px-6 py-5 text-right font-bold text-gray-500">

                            Rp {{ number_format($totalHpp, 0, ',', '.') }}

                        </td>


                        <td class="px-6 py-5 text-right font-black">

                            Rp {{ number_format($labaKotor, 0, ',', '.') }}

                        </td>


                        <td class="px-6 py-5 text-right font-bold text-red-500">

                            Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}

                        </td>


                        <td class="
                            px-6
                            py-5
                            text-right
                            font-black
                            {{
                                $labaBersih >= 0
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            }}
                        ">

                            Rp {{ number_format($labaBersih, 0, ',', '.') }}

                        </td>


                        <td class="px-6 py-5 text-center font-black">

                            {{ $marginPercentage }}%

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</main>



<!-- ========================================================= -->
<!-- CHART.JS -->
<!-- ========================================================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>


<script>
(function () {

    /*
    |--------------------------------------------------------------------------
    | DATA FROM CONTROLLER
    |--------------------------------------------------------------------------
    */

    const financeData = {
        labels: @json($chartData['labels']),
        income: @json($chartData['income']),
        expense: @json($chartData['expense']),
        totalHpp: {{ (float) $totalHpp }},
        totalPengeluaran: {{ (float) $totalPengeluaran }}
    };


    /*
    |--------------------------------------------------------------------------
    | FORMAT RUPIAH
    |--------------------------------------------------------------------------
    */

    const rupiah = value => {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    };


    /*
    |--------------------------------------------------------------------------
    | FORMAT AXIS
    |--------------------------------------------------------------------------
    */

    const compact = value => {

        value = Number(value || 0);

        if (Math.abs(value) >= 1000000000) {
            return 'Rp ' + (value / 1000000000).toLocaleString('id-ID', {
                maximumFractionDigits: 1
            }) + ' M';
        }

        if (Math.abs(value) >= 1000000) {
            return 'Rp ' + (value / 1000000).toLocaleString('id-ID', {
                maximumFractionDigits: 1
            }) + ' jt';
        }

        if (Math.abs(value) >= 1000) {
            return 'Rp ' + (value / 1000).toLocaleString('id-ID', {
                maximumFractionDigits: 0
            }) + ' rb';
        }

        return rupiah(value);
    };


    /*
    |--------------------------------------------------------------------------
    | RENDER CHARTS
    |--------------------------------------------------------------------------
    */

    function renderFinanceCharts() {

        /*
        |--------------------------------------------------------------------------
        | CHECK CHART.JS
        |--------------------------------------------------------------------------
        */

        if (typeof Chart === 'undefined') {

            console.error('Chart.js tidak berhasil dimuat.');

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | LINE CHART
        |--------------------------------------------------------------------------
        */

        const balanceCanvas = document.getElementById('balanceChart');

        if (balanceCanvas) {

            /*
             * Hapus instance lama jika ada.
             */

            const existingChart = Chart.getChart(balanceCanvas);

            if (existingChart) {
                existingChart.destroy();
            }


            const ctx = balanceCanvas.getContext('2d');


            const gradient = ctx.createLinearGradient(
                0,
                0,
                0,
                320
            );


            gradient.addColorStop(
                0,
                'rgba(99, 102, 241, 0.28)'
            );

            gradient.addColorStop(
                0.55,
                'rgba(99, 102, 241, 0.08)'
            );

            gradient.addColorStop(
                1,
                'rgba(99, 102, 241, 0)'
            );


            new Chart(ctx, {

                type: 'line',

                data: {

                    labels: financeData.labels,

                    datasets: [

                        {
                            label: 'Omzet',
                            data: financeData.income,
                            borderColor: '#6366F1',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHoverBorderWidth: 3,
                            pointHoverBackgroundColor: '#ffffff',
                            pointHoverBorderColor: '#6366F1'
                        },

                        {
                            label: 'Total Biaya',
                            data: financeData.expense,
                            borderColor: '#9CA3AF',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [6, 5],
                            fill: false,
                            tension: 0.35,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHoverBorderWidth: 3,
                            pointHoverBackgroundColor: '#ffffff',
                            pointHoverBorderColor: '#6B7280'
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


                    animation: {
                        duration: 650,
                        easing: 'easeOutQuart'
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

                                title(items) {
                                    return items[0]?.label ?? '';
                                },

                                label(context) {
                                    return ' ' +
                                        context.dataset.label +
                                        ': ' +
                                        rupiah(context.raw);
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

                                autoSkip: true,

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
                                    return compact(value);
                                }

                            }

                        }

                    }

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | DONUT CHART
        |--------------------------------------------------------------------------
        */

        const expenseCanvas = document.getElementById('expenseChart');

        if (expenseCanvas) {

            const existingChart = Chart.getChart(expenseCanvas);

            if (existingChart) {
                existingChart.destroy();
            }


            const totalHpp = Number(financeData.totalHpp || 0);

            const totalPengeluaran = Number(
                financeData.totalPengeluaran || 0
            );


            const hasExpense =
                totalHpp > 0 ||
                totalPengeluaran > 0;


            const expenseData = hasExpense
                ? [
                    totalHpp,
                    totalPengeluaran
                ]
                : [
                    1,
                    0
                ];


            new Chart(expenseCanvas, {

                type: 'doughnut',

                data: {

                    labels: [
                        'Modal / HPP',
                        'Biaya Operasional'
                    ],

                    datasets: [

                        {
                            data: expenseData,

                            backgroundColor: hasExpense
                                ? [
                                    '#1F2937',
                                    '#6366F1'
                                ]
                                : [
                                    '#E5E7EB',
                                    '#F3F4F6'
                                ],

                            borderWidth: 0,

                            spacing: hasExpense ? 2 : 0,

                            hoverOffset: 4
                        }

                    ]

                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    cutout: '78%',


                    animation: {
                        duration: 700,
                        animateRotate: true
                    },


                    plugins: {

                        legend: {
                            display: false
                        },


                        tooltip: {

                            enabled: hasExpense,

                            backgroundColor: '#111827',

                            padding: 10,

                            cornerRadius: 10,

                            callbacks: {

                                label(context) {

                                    const value = Number(
                                        context.raw || 0
                                    );


                                    const total =
                                        totalHpp +
                                        totalPengeluaran;


                                    const percentage = total > 0
                                        ? Math.round(
                                            (value / total) * 100
                                        )
                                        : 0;


                                    return ' ' +
                                        context.label +
                                        ': ' +
                                        rupiah(value) +
                                        ' (' +
                                        percentage +
                                        '%)';
                                }

                            }

                        }

                    }

                }

            });

        }

    }


    /*
    |--------------------------------------------------------------------------
    | RUN AFTER DOM READY
    |--------------------------------------------------------------------------
    */

    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            renderFinanceCharts
        );

    } else {

        renderFinanceCharts();

    }

})();
</script>

@endsection

