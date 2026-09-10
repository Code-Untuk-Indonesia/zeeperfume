<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Support\XlsxExporter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(private readonly XlsxExporter $xlsxExporter)
    {
    }

    public function index(Request $request)
    {
        // 1. Ambil data yang sedang menunggu persetujuan Owner
        $pendingApprovals = Transaction::with(['requester'])
            ->whereIn('approval_status', ['pending_edit', 'pending_delete'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $filters = $this->filters($request);

        // Eksekusi Query dengan Pagination (tambahkan withQueryString agar filter tidak hilang saat pindah page)
        $transactions = $this->transactionQuery($filters)
            ->orderBy('tanggal_waktu', 'desc')
            ->paginate(10)
            ->withQueryString();

        $branches = Branch::all();

        return view('owner.transaction.index', compact('pendingApprovals', 'transactions', 'branches'));
    }

    public function export(Request $request)
    {
        $filters = $this->filters($request);
        $transactions = $this->transactionQuery($filters)
            ->orderBy('tanggal_waktu', 'desc')
            ->get();

        $rows = [[
            'Tanggal & Waktu',
            'Nomor Invoice',
            'Kasir',
            'Cabang',
            'Pelanggan',
            'Metode Pembayaran',
            'Total Transaksi (Rp)',
            'Status Approval',
            'Alasan Approval',
        ]];

        foreach ($transactions as $transaction) {
            $rows[] = [
                Carbon::parse($transaction->tanggal_waktu)->format('d-m-Y H:i'),
                $transaction->nomor_nota,
                $transaction->cashier->nama_lengkap ?? 'Kasir',
                $transaction->branch->nama_cabang ?? 'Outlet Pusat',
                $transaction->member->nama ?? 'Pelanggan Umum',
                strtoupper(str_replace('_', ' ', $transaction->metode_bayar)),
                (float) $transaction->total_belanja,
                $this->approvalLabel($transaction->approval_status),
                $transaction->approval_reason ?? '',
            ];
        }

        return $this->xlsxExporter->download(
            $rows,
            'Riwayat_Transaksi_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function print(Request $request)
    {
        $filters = $this->filters($request);
        $transactions = $this->transactionQuery($filters)
            ->orderBy('tanggal_waktu', 'desc')
            ->get();
        $pendingApprovals = Transaction::with(['requester'])
            ->whereIn('approval_status', ['pending_edit', 'pending_delete'])
            ->orderBy('updated_at', 'desc')
            ->get();
        $filterLabel = $this->filterLabel($filters);

        return view('owner.transaction.print', compact(
            'transactions',
            'pendingApprovals',
            'filterLabel'
        ));
    }

    private function filters(Request $request): array
    {
        return $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'cabang' => ['nullable', 'string', 'max:20'],
            'metode' => ['nullable', 'in:all,cash,qris,transfer,tempo,cash_tempo'],
            'tanggal' => ['nullable', 'in:all,hari_ini,kemarin,7_hari,bulan_ini,custom'],
            'tanggal_spesifik' => ['nullable', 'date'],
        ]);
    }

    private function transactionQuery(array $filters): Builder
    {
        $query = Transaction::with([
            'cashier',
            'branch',
            'member',
            'details.variant',
            'cashTempo',
            'requester',
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $builder) use ($search) {
                $builder->where('nomor_nota', 'like', "%{$search}%")
                    ->orWhereHas('member', function (Builder $memberQuery) use ($search) {
                        $memberQuery->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if (!empty($filters['cabang']) && $filters['cabang'] !== 'all') {
            $query->where('cabang_id', $filters['cabang']);
        }

        if (!empty($filters['metode']) && $filters['metode'] !== 'all') {
            $query->where('metode_bayar', $filters['metode']);
        }

        if (!empty($filters['tanggal']) && $filters['tanggal'] !== 'all') {
            $now = Carbon::now();

            if ($filters['tanggal'] === 'custom' && !empty($filters['tanggal_spesifik'])) {
                $query->whereDate('tanggal_waktu', $filters['tanggal_spesifik']);
            } else {
                match ($filters['tanggal']) {
                    'hari_ini' => $query->whereDate('tanggal_waktu', $now->toDateString()),
                    'kemarin' => $query->whereDate('tanggal_waktu', $now->copy()->subDay()->toDateString()),
                    '7_hari' => $query->whereBetween('tanggal_waktu', [
                        $now->copy()->subDays(7)->startOfDay(),
                        $now->copy()->endOfDay(),
                    ]),
                    'bulan_ini' => $query
                        ->whereMonth('tanggal_waktu', $now->month)
                        ->whereYear('tanggal_waktu', $now->year),
                    default => $query,
                };
            }
        }

        return $query;
    }

    private function approvalLabel(?string $status): string
    {
        return match ($status) {
            'pending_edit' => 'Menunggu Persetujuan Edit',
            'pending_delete' => 'Menunggu Persetujuan Hapus',
            'approved_edit' => 'Edit Disetujui',
            default => 'Sah / Selesai',
        };
    }

    private function filterLabel(array $filters): string
    {
        $labels = [];

        if (!empty($filters['search'])) {
            $labels[] = 'Pencarian: ' . $filters['search'];
        }

        if (!empty($filters['cabang']) && $filters['cabang'] !== 'all') {
            $labels[] = 'Cabang ID: ' . $filters['cabang'];
        }

        if (!empty($filters['metode']) && $filters['metode'] !== 'all') {
            $labels[] = 'Metode: ' . strtoupper(str_replace('_', ' ', $filters['metode']));
        }

        if (($filters['tanggal'] ?? 'all') === 'custom' && !empty($filters['tanggal_spesifik'])) {
            $labels[] = 'Tanggal: ' . Carbon::parse($filters['tanggal_spesifik'])->format('d-m-Y');
        } elseif (!empty($filters['tanggal']) && $filters['tanggal'] !== 'all') {
            $labels[] = 'Periode: ' . ucwords(str_replace('_', ' ', $filters['tanggal']));
        }

        return $labels ? implode(' | ', $labels) : 'Semua transaksi';
    }

    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->approval_status === 'pending_delete') {
            $transaction->delete();
            return back()->with('success', 'Transaksi berhasil dihapus.');
        } elseif ($transaction->approval_status === 'pending_edit') {
            $transaction->update(['approval_status' => 'approved_edit']);
            return back()->with('success', 'Izin Edit berhasil diberikan kepada Admin.');
        }

        return back();
    }

    public function reject($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'approval_status' => 'none',
            'approval_reason' => null
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }
}
