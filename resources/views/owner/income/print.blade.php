<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - {{ $periodLabel }}</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Arial, Helvetica, sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: #1f2937;
            background: #f3f4f6;
            font-size: 12px;
        }

        .toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 16px max(16px, calc((100% - 1100px) / 2));
            background: #1c1d21;
        }

        .toolbar button,
        .toolbar a {
            border: 0;
            border-radius: 6px;
            padding: 9px 14px;
            color: #fff;
            background: #cc9863;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .toolbar a { background: #4b5563; }

        .report {
            max-width: 1100px;
            margin: 24px auto;
            padding: 28px;
            background: #fff;
        }

        h1, h2, p { margin: 0; }
        h1 { font-size: 24px; color: #111827; }
        h2 { margin: 24px 0 10px; font-size: 15px; color: #111827; }
        .period { margin-top: 5px; color: #6b7280; }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 22px 0;
        }

        .summary-card {
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        .summary-card span {
            display: block;
            margin-bottom: 5px;
            color: #6b7280;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .summary-card strong { font-size: 16px; color: #111827; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px 9px;
            border: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
            color: #4b5563;
            font-size: 10px;
            text-transform: uppercase;
        }

        .number { text-align: right; }
        .muted { color: #6b7280; }

        @media print {
            @page { size: A4 landscape; margin: 12mm; }

            body { background: #fff; font-size: 10px; }
            .no-print { display: none !important; }
            .report { max-width: none; margin: 0; padding: 0; }
            .summary { margin: 14px 0; }
            h2 { margin-top: 16px; }
            thead { display: table-header-group; }
            tr { break-inside: avoid; page-break-inside: avoid; }
        }

        @media (max-width: 700px) {
            .report { margin: 0; padding: 16px; }
            .summary { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
    <div class="toolbar no-print">
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
        <button type="button" onclick="window.close()">Tutup</button>
    </div>

    <main class="report">
        <header>
            <h1>Laporan Pendapatan</h1>
            <p class="period">Periode: {{ $periodLabel }}</p>
            @if ($paymentMethod)
                <p class="period">Metode pembayaran: {{ strtoupper(str_replace('_', ' ', $paymentMethod)) }}</p>
            @endif
        </header>

        <section class="summary">
            <div class="summary-card">
                <span>Total pendapatan</span>
                <strong>Rp {{ number_format($totalIncome, 0, ',', '.') }}</strong>
            </div>
            <div class="summary-card">
                <span>Total transaksi</span>
                <strong>{{ number_format($totalTransaction, 0, ',', '.') }}</strong>
            </div>
            <div class="summary-card">
                <span>Rata-rata transaksi</span>
                <strong>Rp {{ number_format($averageTransaction, 0, ',', '.') }}</strong>
            </div>
        </section>

        <h2>Rekap Pendapatan per Outlet</h2>
        <table>
            <thead>
                <tr>
                    <th>Outlet</th>
                    <th class="number">Transaksi</th>
                    <th class="number">Pendapatan</th>
                    <th class="number">Diterima</th>
                    <th class="number">Piutang</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($outletReports as $report)
                    <tr>
                        <td>{{ $report->nama_cabang }}</td>
                        <td class="number">{{ number_format($report->total_transaksi, 0, ',', '.') }}</td>
                        <td class="number">Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</td>
                        <td class="number">Rp {{ number_format($report->total_diterima, 0, ',', '.') }}</td>
                        <td class="number">Rp {{ number_format($report->total_piutang, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted">Tidak ada data outlet pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        <h2>Detail Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Nomor Nota</th>
                    <th>Pelanggan</th>
                    <th>Cabang</th>
                    <th>Kasir</th>
                    <th>Metode</th>
                    <th class="number">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->tanggal_waktu)->format('d-m-Y H:i') }}</td>
                        <td>{{ $transaction->nomor_nota }}</td>
                        <td>{{ $transaction->member->nama ?? 'Umum' }}</td>
                        <td>{{ $transaction->branch->nama_cabang ?? 'Pusat' }}</td>
                        <td>{{ $transaction->cashier->nama_lengkap ?? 'Unknown' }}</td>
                        <td>{{ strtoupper(str_replace('_', ' ', $transaction->metode_bayar)) }}</td>
                        <td class="number">Rp {{ number_format($transaction->total_belanja, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">Tidak ada transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>

    <script>
        window.addEventListener('load', () => {
            window.setTimeout(() => window.print(), 300);
        });
    </script>
</body>

</html>
