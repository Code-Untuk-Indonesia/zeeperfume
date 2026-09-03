<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $branches = DB::table('branches')
            ->select('branches.*')
            ->selectSub(
                DB::table('users')
                    ->selectRaw('count(*)')
                    ->whereColumn('users.cabang_id', 'branches.id'),
                'users_count'
            )
            ->selectSub(
                DB::table('transactions')
                    ->selectRaw('count(*)')
                    ->whereColumn('transactions.cabang_id', 'branches.id'),
                'transactions_count'
            )
            ->whereNull('branches.deleted_at')
            ->when($request->filled('search'), fn ($query) => $query->where('nama_cabang', 'like', '%'.$request->string('search').'%'))
            ->orderBy('nama_cabang')
            ->paginate($request->integer('per_page', 15));

        return response()->json($branches);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_cabang' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:30'],
        ]);

        $now = now();
        $id = DB::table('branches')->insertGetId([
            ...$validated,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json($this->findBranch($id), 201);
    }

    public function show(Branch $branch): JsonResponse
    {
        return response()->json($this->findBranch($branch->getKey(), withCounts: true));
    }

    public function update(Request $request, Branch $branch): JsonResponse
    {
        $validated = $request->validate([
            'nama_cabang' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:30'],
        ]);

        DB::table('branches')
            ->where('id', $branch->getKey())
            ->whereNull('deleted_at')
            ->update([...$validated, 'updated_at' => now()]);

        return response()->json($this->findBranch($branch->getKey()));
    }

    public function destroy(Branch $branch): JsonResponse
    {
        DB::table('branches')
            ->where('id', $branch->getKey())
            ->whereNull('deleted_at')
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return response()->json(status: 204);
    }

    private function findBranch(int $id, bool $withCounts = false): object
    {
        $query = DB::table('branches')->where('id', $id)->whereNull('deleted_at');

        if ($withCounts) {
            $query
                ->select('branches.*')
                ->selectSub(
                    DB::table('users')
                        ->selectRaw('count(*)')
                        ->whereColumn('users.cabang_id', 'branches.id'),
                    'users_count'
                )
                ->selectSub(
                    DB::table('transactions')
                        ->selectRaw('count(*)')
                        ->whereColumn('transactions.cabang_id', 'branches.id'),
                    'transactions_count'
                );
        }

        return $query->firstOrFail();
    }
}
