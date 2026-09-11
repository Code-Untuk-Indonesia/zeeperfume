<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'month'         => ['nullable', 'integer', 'between:1,12'],
            'year'          => ['nullable', 'integer', 'between:2000,2100'],
            'start_date'    => ['nullable', 'date'],
            'end_date'      => [
                'nullable',
                'date',
                'required_with:start_date',
                'after_or_equal:start_date'
            ],
            'branch_id'      => ['nullable', 'integer', 'exists:branches,id'],
            'payment_method' => ['nullable', 'string', 'in:cash,qris,transfer,tempo,cash_tempo'],
        ]);

        $month = (int) ($validated['month'] ?? now()->month);
        $year = (int) ($validated['year'] ?? now()->year);

        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
            $monthName = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
        } else {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $monthName = $startDate->translatedFormat('F Y');
        }

        $branchId = $validated['branch_id'] ?? null;
        $paymentMethod = $validated['payment_method'] ?? null;

        $baseQuery = Transaction::query()
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->when($branchId, fn ($query) => $query->where('cabang_id', $branchId))
            ->when($paymentMethod, fn ($query) => $query->where('metode_bayar', $paymentMethod));

        // 1. Metrik Utama
        $totalIncome = (clone $baseQuery)->sum('total_belanja');
        $totalTrx = (clone $baseQuery)->count();
        $avgTransaction = $totalTrx > 0 ? $totalIncome / $totalTrx : 0;

        // 2. Grafik Pendapatan
        $diffDays = $startDate->diffInDays($endDate) + 1;
        if ($diffDays <= 31) {
            $chartResults = (clone $baseQuery)
                ->selectRaw('DATE(tanggal_waktu) as periode')
                ->selectRaw('SUM(total_belanja) as total')
                ->groupByRaw('DATE(tanggal_waktu)')
                ->orderBy('periode')
                ->pluck('total', 'periode');

            $labels = [];
            $dailyIncome = [];

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateKey = $date->format('Y-m-d');
                $labels[] = $date->format('d M');
                $dailyIncome[] = (float) ($chartResults[$dateKey] ?? 0);
            }
        } else {
            $chartResults = (clone $baseQuery)
                ->selectRaw("DATE_FORMAT(tanggal_waktu, '%Y-%m') as periode")
                ->selectRaw('SUM(total_belanja) as total')
                ->groupByRaw("DATE_FORMAT(tanggal_waktu, '%Y-%m')")
                ->orderBy('periode')
                ->get();

            $labels = $chartResults->map(fn ($item) => Carbon::createFromFormat('Y-m', $item->periode)->translatedFormat('M Y'))->values()->toArray();
            $dailyIncome = $chartResults->pluck('total')->map(fn ($value) => (float) $value)->toArray();
        }
        $chartData = ['labels' => $labels, 'income' => $dailyIncome];

        // 3. Distribusi Metode Pembayaran
        $paymentMethods = (clone $baseQuery)
            ->select('metode_bayar', DB::raw('COUNT(*) as total_transaksi'), DB::raw('SUM(total_belanja) as total_nominal'))
            ->groupBy('metode_bayar')
            ->orderByDesc('total_nominal')
            ->get();

        // 4. Pendapatan per Cabang
        $branchIncomes = Transaction::query()
            ->join('branches', 'branches.id', '=', 'transactions.cabang_id')
            ->whereBetween('transactions.tanggal_waktu', [$startDate, $endDate])
            ->when($branchId, fn ($query) => $query->where('transactions.cabang_id', $branchId))
            ->when($paymentMethod, fn ($query) => $query->where('transactions.metode_bayar', $paymentMethod))
            ->select('branches.id', 'branches.nama_cabang', DB::raw('COUNT(transactions.id) as total_trx'), DB::raw('COALESCE(SUM(transactions.total_belanja), 0) as total_income'))
            ->groupBy('branches.id', 'branches.nama_cabang')
            ->orderByDesc('total_income')
            ->get();

        // 5. Rekap Outlet
        $dailyOutletReports = DB::table('branches')
            ->leftJoin('transactions', function ($join) use ($startDate, $endDate, $paymentMethod) {
                $join->on('transactions.cabang_id', '=', 'branches.id')
                    ->whereBetween('transactions.tanggal_waktu', [$startDate, $endDate]);
                if ($paymentMethod) {
                    $join->where('transactions.metode_bayar', $paymentMethod);
                }
            })
            ->leftJoin('cash_tempo', 'cash_tempo.transaksi_id', '=', 'transactions.id')
            ->whereNull('branches.deleted_at')
            ->when($branchId, fn ($query) => $query->where('branches.id', $branchId))
            ->select([
                'branches.id as cabang_id',
                'branches.nama_cabang',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(transactions.total_belanja), 0) as total_pendapatan'),
                DB::raw("COALESCE(SUM(CASE WHEN transactions.metode_bayar = 'cash_tempo' THEN transactions.total_belanja - COALESCE(cash_tempo.sisa_piutang, transactions.total_belanja) ELSE transactions.total_belanja END), 0) as total_diterima"),
                DB::raw("COALESCE(SUM(CASE WHEN transactions.metode_bayar = 'cash_tempo' THEN COALESCE(cash_tempo.sisa_piutang, transactions.total_belanja) ELSE 0 END), 0) as total_piutang"),
            ])
            ->groupBy('branches.id', 'branches.nama_cabang')
            ->orderByDesc('total_pendapatan')
            ->orderBy('branches.nama_cabang')
            ->get();

        $dailySummary = [
            'total_transaksi'  => (int) $dailyOutletReports->sum('total_transaksi'),
            'total_pendapatan' => (float) $dailyOutletReports->sum('total_pendapatan'),
            'total_diterima'   => (float) $dailyOutletReports->sum('total_diterima'),
            'total_piutang'    => (float) $dailyOutletReports->sum('total_piutang'),
        ];

        // 6. Produk Terlaris
        $trxIds = (clone $baseQuery)->pluck('id');
        $topProducts = TransactionDetail::with('variant.product')
            ->whereIn('transaksi_id', $trxIds)
            ->select('varian_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('varian_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        $branches = Branch::query()->orderBy('nama_cabang')->get();

        // 7. Tabel Transaksi
        $incomeTransactions = (clone $baseQuery)
            ->with(['branch', 'cashier', 'member'])
            ->orderByDesc('tanggal_waktu')
            ->paginate(15)
            ->withQueryString();

        return view('owner.income.index', compact(
            'month', 'year', 'monthName', 'startDate', 'endDate',
            'branchId', 'paymentMethod', 'branches',
            'totalIncome', 'totalTrx', 'avgTransaction',
            'chartData', 'paymentMethods', 'branchIncomes', 'topProducts',
            'incomeTransactions', 'dailyOutletReports', 'dailySummary'
        ));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'month'          => ['nullable', 'integer', 'between:1,12'],
            'year'           => ['nullable', 'integer', 'between:2000,2100'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
            'branch_id'      => ['nullable', 'integer', 'exists:branches,id'],
            'payment_method' => ['nullable', 'string', 'in:cash,qris,transfer,tempo,cash_tempo'],
        ]);

        $month = (int) ($validated['month'] ?? now()->month);
        $year = (int) ($validated['year'] ?? now()->year);

        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
            $periodName = $startDate->format('d-m-Y') . '_sampai_' . $endDate->format('d-m-Y');
        } else {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $periodName = $startDate->format('F_Y');
        }

        $branchId = $validated['branch_id'] ?? null;
        $paymentMethod = $validated['payment_method'] ?? null;

        $query = Transaction::query()
            ->with(['branch', 'cashier', 'member'])
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->when($branchId, fn ($q) => $q->where('cabang_id', $branchId))
            ->when($paymentMethod, fn ($q) => $q->where('metode_bayar', $paymentMethod))
            ->orderBy('tanggal_waktu');

        $rows = [[
            'Tanggal & Waktu',
            'Nomor Invoice',
            'Pelanggan',
            'Cabang',
            'Kasir',
            'Metode Pembayaran',
            'Total Belanja (Rp)',
        ]];

        $totalIncome = 0;
        $totalTransaction = 0;

        foreach ($query->cursor() as $trx) {
            $amount = (float) $trx->total_belanja;

            $rows[] = [
                Carbon::parse($trx->tanggal_waktu)->format('d-m-Y H:i'),
                $trx->nomor_nota,
                $trx->member->nama ?? 'Umum',
                $trx->branch->nama_cabang ?? 'Pusat',
                $trx->cashier->nama_lengkap ?? 'Unknown',
                strtoupper(str_replace('_', ' ', $trx->metode_bayar)),
                $amount,
            ];

            $totalIncome += $amount;
            $totalTransaction++;
        }

        $rows[] = [];
        $rows[] = ['TOTAL TRANSAKSI', $totalTransaction];
        $rows[] = ['TOTAL PENDAPATAN', '', '', '', '', '', $totalIncome];

        return $this->downloadXlsx(
            $rows,
            'Laporan_Pendapatan_' . $periodName . '.xlsx'
        );
    }

    private function downloadXlsx(array $rows, string $fileName)
    {
        $temporaryFile = tempnam(storage_path('app'), 'income_export_');

        if ($temporaryFile === false) {
            abort(500, 'File sementara untuk export tidak dapat dibuat.');
        }

        $zip = new \ZipArchive();
        $opened = $zip->open($temporaryFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if ($opened !== true) {
            @unlink($temporaryFile);
            abort(500, 'File Excel tidak dapat dibuat.');
        }

        $zip->addFromString('[Content_Types].xml', $this->xlsxContentTypes());
        $zip->addFromString('_rels/.rels', $this->xlsxRootRelationships());
        $zip->addFromString('docProps/app.xml', $this->xlsxAppProperties());
        $zip->addFromString('docProps/core.xml', $this->xlsxCoreProperties());
        $zip->addFromString('xl/workbook.xml', $this->xlsxWorkbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->xlsxWorkbookRelationships());
        $zip->addFromString('xl/styles.xml', $this->xlsxStyles());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->xlsxWorksheet($rows));
        $zip->close();

        return response()
            ->download(
                $temporaryFile,
                $fileName,
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Cache-Control' => 'no-store, no-cache, must-revalidate',
                ]
            )
            ->deleteFileAfterSend(true);
    }

    private function xlsxWorksheet(array $rows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        $xml .= '<sheetViews><sheetView workbookViewId="0"/></sheetViews>';
        $xml .= '<cols>';
        $xml .= '<col min="1" max="1" width="20" customWidth="1"/>';
        $xml .= '<col min="2" max="2" width="22" customWidth="1"/>';
        $xml .= '<col min="3" max="6" width="24" customWidth="1"/>';
        $xml .= '<col min="7" max="7" width="20" customWidth="1"/>';
        $xml .= '</cols><sheetData>';

        foreach ($rows as $rowNumber => $row) {
            $excelRow = $rowNumber + 1;
            $xml .= '<row r="' . $excelRow . '">';

            foreach (array_values($row) as $columnNumber => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $cellReference = $this->xlsxColumnName($columnNumber + 1) . $excelRow;
                $isNumeric = is_int($value) || is_float($value);
                $style = $rowNumber === 0 ? ' s="1"' : ($columnNumber === 6 && $isNumeric ? ' s="2"' : '');

                if ($isNumeric) {
                    $xml .= '<c r="' . $cellReference . '"' . $style . '><v>' . $value . '</v></c>';
                    continue;
                }

                $xml .= '<c r="' . $cellReference . '" t="inlineStr"' . $style . '><is><t xml:space="preserve">'
                    . htmlspecialchars((string) $value, ENT_XML1 | ENT_COMPAT, 'UTF-8')
                    . '</t></is></c>';
            }

            $xml .= '</row>';
        }

        $xml .= '</sheetData></worksheet>';

        return $xml;
    }

    private function xlsxColumnName(int $columnNumber): string
    {
        $columnName = '';

        while ($columnNumber > 0) {
            $remainder = ($columnNumber - 1) % 26;
            $columnName = chr(65 + $remainder) . $columnName;
            $columnNumber = intdiv($columnNumber - 1, 26);
        }

        return $columnName;
    }

    private function xlsxContentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '<Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>'
            . '<Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>'
            . '</Types>';
    }

    private function xlsxRootRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>'
            . '</Relationships>';
    }

    private function xlsxAppProperties(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" '
            . 'xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">'
            . '<Application>Laravel</Application><AppVersion>1.0</AppVersion>'
            . '</Properties>';
    }

    private function xlsxCoreProperties(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" '
            . 'xmlns:dc="http://purl.org/dc/elements/1.1/" '
            . 'xmlns:dcterms="http://purl.org/dc/terms/" '
            . 'xmlns:dcmitype="http://purl.org/dc/dcmitype/" '
            . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
            . '<dc:title>Laporan Pendapatan</dc:title>'
            . '<dc:creator>ZeePerfume</dc:creator>'
            . '<dcterms:created xsi:type="dcterms:W3CDTF">' . now()->toIso8601String() . '</dcterms:created>'
            . '</cp:coreProperties>';
    }

    private function xlsxWorkbook(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Laporan Pendapatan" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private function xlsxWorkbookRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private function xlsxStyles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="164" formatCode="#,##0"/></numFmts>'
            . '<fonts count="2"><font><sz val="11"/><name val="Arial"/></font><font><b/><sz val="11"/><name val="Arial"/></font></fonts>'
            . '<fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FFEFEFEF"/><bgColor indexed="64"/></patternFill></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="3">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="1" borderId="0" applyFont="1" applyFill="1" xfId="0"/>'
            . '<xf numFmtId="164" fontId="0" fillId="0" borderId="0" applyNumberFormat="1" xfId="0"/>'
            . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    public function print(Request $request)
    {
        $validated = $request->validate([
            'month'          => ['nullable', 'integer', 'between:1,12'],
            'year'           => ['nullable', 'integer', 'between:2000,2100'],
            'start_date'     => ['nullable', 'date'],
            'end_date'       => ['nullable', 'date', 'required_with:start_date', 'after_or_equal:start_date'],
            'branch_id'      => ['nullable', 'integer', 'exists:branches,id'],
            'payment_method' => ['nullable', 'string', 'in:cash,qris,transfer,tempo,cash_tempo'],
        ]);

        $month = (int) ($validated['month'] ?? now()->month);
        $year = (int) ($validated['year'] ?? now()->year);

        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $startDate = Carbon::parse($validated['start_date'])->startOfDay();
            $endDate = Carbon::parse($validated['end_date'])->endOfDay();
            $periodLabel = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
        } else {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $periodLabel = $startDate->translatedFormat('F Y');
        }

        $branchId = $validated['branch_id'] ?? null;
        $paymentMethod = $validated['payment_method'] ?? null;

        $transactions = Transaction::query()
            ->with(['branch', 'cashier', 'member'])
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->when($branchId, fn ($query) => $query->where('cabang_id', $branchId))
            ->when($paymentMethod, fn ($query) => $query->where('metode_bayar', $paymentMethod))
            ->orderBy('tanggal_waktu')
            ->get();

        $totalIncome = (float) $transactions->sum('total_belanja');
        $totalTransaction = $transactions->count();
        $averageTransaction = $totalTransaction > 0 ? $totalIncome / $totalTransaction : 0;

        $outletReports = DB::table('branches')
            ->leftJoin('transactions', function ($join) use ($startDate, $endDate, $paymentMethod) {
                $join->on('transactions.cabang_id', '=', 'branches.id')
                    ->whereBetween('transactions.tanggal_waktu', [$startDate, $endDate]);

                if ($paymentMethod) {
                    $join->where('transactions.metode_bayar', $paymentMethod);
                }
            })
            ->leftJoin('cash_tempo', 'cash_tempo.transaksi_id', '=', 'transactions.id')
            ->whereNull('branches.deleted_at')
            ->when($branchId, fn ($query) => $query->where('branches.id', $branchId))
            ->select([
                'branches.nama_cabang',
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('COALESCE(SUM(transactions.total_belanja), 0) as total_pendapatan'),
                DB::raw("COALESCE(SUM(CASE WHEN transactions.metode_bayar = 'cash_tempo' THEN transactions.total_belanja - COALESCE(cash_tempo.sisa_piutang, transactions.total_belanja) ELSE transactions.total_belanja END), 0) as total_diterima"),
                DB::raw("COALESCE(SUM(CASE WHEN transactions.metode_bayar = 'cash_tempo' THEN COALESCE(cash_tempo.sisa_piutang, transactions.total_belanja) ELSE 0 END), 0) as total_piutang"),
            ])
            ->groupBy('branches.id', 'branches.nama_cabang')
            ->orderByDesc('total_pendapatan')
            ->orderBy('branches.nama_cabang')
            ->get();

        return view('owner.income.print', compact(
            'periodLabel',
            'startDate',
            'endDate',
            'branchId',
            'paymentMethod',
            'transactions',
            'totalIncome',
            'totalTransaction',
            'averageTransaction',
            'outletReports'
        ));
    }
}
