<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - ZeePerfume</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: #1f2937;
            background: #f3f4f6;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        .toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 16px max(16px, calc((100% - 1200px) / 2));
            background: #1c1d21;
        }
        .toolbar button {
            border: 0;
            border-radius: 6px;
            padding: 9px 14px;
            color: #fff;
            background: #cc9863;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }
        .toolbar button:last-child { background: #4b5563; }
        .report {
            max-width: 1200px;
            margin: 24px auto;
            padding: 28px;
            background: #fff;
        }
        h1, h2, p { margin: 0; }
        h1 { color: #111827; font-size: 24px; }
        h2 { margin: 24px 0 10px; color: #111827; font-size: 15px; }
        .muted { margin-top: 5px; color: #6b7280; }
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
        .summary-card strong { color: #111827; font-size: 16px; }
        .approval-list { margin: 0; padding: 0; list-style: none; }
        .approval-list li {
            padding: 8px 10px;
            border: 1px solid #fed7aa;
            border-radius: 5px;
            background: #fff7ed;
        }
        .approval-list li + li { margin-top: 6px; }
        table { width: 100%; border-collapse: collapse; }
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
        .center { text-align: center; }
        @media print {
            @page { size: A4 landscape; margin: 12mm; }
            body { background: #fff; font-size: 10px; }
            .no-print { display: none !important; }
            .report { max-width: none; margin: 0; padding: 0; }
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
            <h1>Riwayat Transaksi &amp; Approval</h1>
            <p class="muted">Filter: {{ $filterLabel }}</p>
        </header>

        <section class="summary">
            <div class="summary-card">
                <span>Total transaksi</span>
                <strong>{{ number_format($transactions->count(), 0, ',', '.') }}</strong>
            </div>
            <div class="summary-card">
                <span>Total nominal</span>
                <strong>Rp {{ number_format($transactions->sum('total_belanja'), 0, ',', '.') }}</strong>
            </div>
            <div class="summary-card">
                <span>Menunggu approval</span>
                <strong>{{ number_format($pendingApprovals->count(), 0, ',', '.') }}</strong>
            </div>
        </section>

        @if ($pendingApprovals->isNotEmpty())
            <h2>Pengajuan Approval</h2>
            <ul class="approval-list">
                @foreach ($pendingApprovals as $pending)
                    <li>
                        <strong>{{ $pending->nomor_nota }}</strong>
                        :
                        {{ $pending->approval_status === 'pending_delete' ? 'Pengajuan hapus' : 'Pengajuan edit' }}
                        oleh {{ $pending->requester->nama_lengkap ?? 'Admin' }}.
                        <span>{{ $pending->approval_reason ?: 'Tidak ada alasan.' }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <h2>Daftar Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal &amp; Waktu</th>
                    <th>Nomor Invoice</th>
                    <th>Kasir</th>
                    <th>Cabang</th>
                    <th>Pelanggan</th>
                    <th>Metode</th>
                    <th class="number">Total</th>
                    <th>Status Approval</th>
                    <th>Alasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->tanggal_waktu)->format('d-m-Y H:i') }}</td>
                        <td>{{ $transaction->nomor_nota }}</td>
                        <td>{{ $transaction->cashier->nama_lengkap ?? 'Kasir' }}</td>
                        <td>{{ $transaction->branch->nama_cabang ?? 'Outlet Pusat' }}</td>
                        <td>{{ $transaction->member->nama ?? 'Pelanggan Umum' }}</td>
                        <td>{{ strtoupper(str_replace('_', ' ', $transaction->metode_bayar)) }}</td>
                        <td class="number">Rp {{ number_format($transaction->total_belanja, 0, ',', '.') }}</td>
                        <td>
                            @if ($transaction->approval_status === 'pending_edit')
                                Menunggu Persetujuan Edit
                            @elseif ($transaction->approval_status === 'pending_delete')
                                Menunggu Persetujuan Hapus
                            @elseif ($transaction->approval_status === 'approved_edit')
                                Edit Disetujui
                            @else
                                Sah / Selesai
                            @endif
                        </td>
                        <td>{{ $transaction->approval_reason ?? '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9">Tidak ada transaksi yang sesuai filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>

    <script>
        window.addEventListener('load', () => window.setTimeout(() => window.print(), 300));
    </script>
</body>

</html>
