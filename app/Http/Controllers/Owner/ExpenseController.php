<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $expenses = DB::table('pengeluaran')
            ->join('branches', 'branches.id', '=', 'pengeluaran.cabang_id')
            ->join('users', 'users.id', '=', 'pengeluaran.user_id')
            ->whereNull('branches.deleted_at')
            ->when($request->filled('cabang_id'), fn ($query) => $query->where('pengeluaran.cabang_id', $request->integer('cabang_id')))
            ->when($request->filled('kategori_pengeluaran'), fn ($query) => $query->where('pengeluaran.kategori_pengeluaran', (string) $request->string('kategori_pengeluaran')))
            ->when($request->filled('tanggal_mulai'), fn ($query) => $query->whereDate('pengeluaran.tanggal_pengeluaran', '>=', $request->date('tanggal_mulai')))
            ->when($request->filled('tanggal_selesai'), fn ($query) => $query->whereDate('pengeluaran.tanggal_pengeluaran', '<=', $request->date('tanggal_selesai')))
            ->orderByDesc('pengeluaran.tanggal_pengeluaran')
            ->orderByDesc('pengeluaran.id')
            ->select([
                'pengeluaran.*',
                'branches.nama_cabang',
                'users.nama_lengkap as dicatat_oleh',
            ])
            ->paginate($request->integer('per_page', 15));

        return response()->json($expenses);
    }

    public function store(Request $request): JsonResponse
    {
        abort_if($request->user() === null, 401);

        $validated = $request->validate($this->rules());
        $now = now();

        $id = DB::table('pengeluaran')->insertGetId([
            ...$validated,
            'user_id' => $request->user()->getKey(),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return response()->json($this->findExpense($id), 201);
    }

    public function show(Expense $expense): JsonResponse
    {
        return response()->json($this->findExpense($expense->getKey()));
    }

    public function update(Request $request, Expense $expense): JsonResponse
    {
        $validated = $request->validate($this->rules());

        DB::table('pengeluaran')
            ->where('id', $expense->getKey())
            ->update([
                ...$validated,
                'updated_at' => now(),
            ]);

        return response()->json($this->findExpense($expense->getKey()));
    }

    public function destroy(Expense $expense): JsonResponse
    {
        DB::table('pengeluaran')->where('id', $expense->getKey())->delete();

        return response()->json(['message' => 'Pengeluaran berhasil dihapus.']);
    }

    private function rules(): array
    {
        return [
            'cabang_id' => [
                'required',
                'integer',
                Rule::exists('branches', 'id')->whereNull('deleted_at'),
            ],
            'kategori_pengeluaran' => ['required', 'string', 'max:100'],
            'nama_pengeluaran' => ['required', 'string', 'max:150'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'tanggal_pengeluaran' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    private function findExpense(int $id): object
    {
        return DB::table('pengeluaran')
            ->join('branches', 'branches.id', '=', 'pengeluaran.cabang_id')
            ->join('users', 'users.id', '=', 'pengeluaran.user_id')
            ->where('pengeluaran.id', $id)
            ->whereNull('branches.deleted_at')
            ->select([
                'pengeluaran.*',
                'branches.nama_cabang',
                'users.nama_lengkap as dicatat_oleh',
            ])
            ->firstOrFail();
    }
}
