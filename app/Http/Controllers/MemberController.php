<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $members = DB::table('members')
            ->whereNull('deleted_at')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%'.$request->string('search').'%';
                $query->where(function ($query) use ($search) {
                    $query->where('kode_member', 'like', $search)
                        ->orWhere('nama', 'like', $search)
                        ->orWhere('no_telp', 'like', $search);
                });
            })
            ->orderBy('nama')
            ->paginate($request->integer('per_page', 15));

        return response()->json($members);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $now = now();
        $id = DB::table('members')->insertGetId([
            ...$validated,
            'poin' => $validated['poin'] ?? 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json($this->findMember($id), 201);
    }

    public function show(Member $member): JsonResponse
    {
        $memberData = DB::table('members')
            ->select('members.*')
            ->selectSub(
                DB::table('transactions')
                    ->selectRaw('count(*)')
                    ->whereColumn('transactions.member_id', 'members.id'),
                'transactions_count'
            )
            ->where('members.id', $member->getKey())
            ->whereNull('members.deleted_at')
            ->firstOrFail();

        return response()->json($memberData);
    }

    public function update(Request $request, Member $member): JsonResponse
    {
        $validated = $request->validate($this->rules($member));
        DB::table('members')
            ->where('id', $member->getKey())
            ->whereNull('deleted_at')
            ->update([...$validated, 'updated_at' => now()]);

        return response()->json($this->findMember($member->getKey()));
    }

    public function destroy(Member $member): JsonResponse
    {
        DB::table('members')
            ->where('id', $member->getKey())
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json(status: 204);
    }

    private function rules(?Member $member = null): array
    {
        return [
            'kode_member' => [
                'required',
                'string',
                'max:30',
                Rule::unique('members', 'kode_member')->ignore($member),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'no_telp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'poin' => ['sometimes', 'integer', 'min:0'],
            'tanggal_bergabung' => ['required', 'date'],
        ];
    }

    private function findMember(int $id): object
    {
        return DB::table('members')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();
    }
}
