<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $branches = Branch::query()
            ->withCount(['users', 'transactions'])
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

        return response()->json(Branch::create($validated), 201);
    }

    public function show(Branch $branch): JsonResponse
    {
        return response()->json($branch->loadCount(['users', 'transactions']));
    }

    public function update(Request $request, Branch $branch): JsonResponse
    {
        $validated = $request->validate([
            'nama_cabang' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:30'],
        ]);

        $branch->update($validated);

        return response()->json($branch->fresh());
    }

    public function destroy(Branch $branch): JsonResponse
    {
        $branch->delete();

        return response()->json(status: 204);
    }
}
