<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveMemberRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $members = $this->memberQuery()
            ->whereNull('members.deleted_at')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.$request->string('search').'%';
                $query->where(function ($query) use ($search): void {
                    $query->where('members.nama', 'like', $search)
                        ->orWhere('members.no_telp', 'like', $search)
                        ->orWhere('members.kode_member', 'like', $search);
                });
            })
            ->when($request->input('tipe') === 'vip', fn ($query) => $query->where('members.poin', '>=', 500))
            ->when($request->input('tipe') === 'reguler', fn ($query) => $query->where('members.poin', '<', 500))
            ->orderByDesc('members.created_at')
            ->paginate(10)
            ->withQueryString();

        $totalMembers = DB::table('members')->whereNull('deleted_at')->count();
        $vipMembers = DB::table('members')->whereNull('deleted_at')->where('poin', '>=', 500)->count();
        $totalBelanjaSemua = DB::table('transactions')
            ->whereNotNull('member_id')
            ->sum('total_belanja');

        return view('admin.member.index', compact(
            'members',
            'totalMembers',
            'vipMembers',
            'totalBelanjaSemua',
        ));
    }

    public function create(): View
    {
        return view('admin.member.create');
    }

    public function store(SaveMemberRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $now = now();

        DB::table('members')->insert([
            'kode_member' => $this->nextMemberCode(),
            'nama' => $validated['nama'],
            'no_telp' => $validated['no_telp'] ?? null,
            'email' => $validated['email'] ?? null,
            'poin' => $validated['poin'] ?? 0,
            'tanggal_bergabung' => $validated['tanggal_bergabung'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect()
            ->route('admin.member.index')
            ->with('success', 'Member berhasil ditambahkan.');
    }

    public function edit(int $member): View
    {
        return view('admin.member.edit', ['member' => $this->findMember($member)]);
    }

    public function update(SaveMemberRequest $request, int $member): RedirectResponse
    {
        $this->findMember($member);

        DB::table('members')
            ->where('id', $member)
            ->whereNull('deleted_at')
            ->update([
                ...$request->validated(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.member.index')
            ->with('success', 'Data member berhasil diperbarui.');
    }

    public function destroy(int $member): RedirectResponse
    {
        $this->findMember($member);

        DB::table('members')
            ->where('id', $member)
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.member.index')
            ->with('success', 'Member berhasil dihapus.');
    }

    private function memberQuery()
    {
        $transactionStats = DB::table('transactions')
            ->select('member_id')
            ->selectRaw('COUNT(*) as transactions_count')
            ->selectRaw('COALESCE(SUM(total_belanja), 0) as total_belanja')
            ->whereNotNull('member_id')
            ->groupBy('member_id');

        return DB::table('members')
            ->leftJoinSub($transactionStats, 'transaction_stats', function ($join): void {
                $join->on('transaction_stats.member_id', '=', 'members.id');
            })
            ->select(
                'members.*',
                DB::raw('COALESCE(transaction_stats.transactions_count, 0) as transactions_count'),
                DB::raw('COALESCE(transaction_stats.total_belanja, 0) as total_belanja'),
            );
    }

    private function findMember(int $id): object
    {
        return $this->memberQuery()
            ->where('members.id', $id)
            ->whereNull('members.deleted_at')
            ->firstOrFail();
    }

    private function nextMemberCode(): string
    {
        $sequence = (int) DB::table('members')->max('id') + 1;

        do {
            $code = 'MBR-'.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
            $sequence++;
        } while (DB::table('members')->where('kode_member', $code)->exists());

        return $code;
    }
}
