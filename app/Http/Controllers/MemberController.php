<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $members = Member::query()
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

        return response()->json(Member::create($validated), 201);
    }

    public function show(Member $member): JsonResponse
    {
        return response()->json($member->loadCount('transactions'));
    }

    public function update(Request $request, Member $member): JsonResponse
    {
        $validated = $request->validate($this->rules($member));
        $member->update($validated);

        return response()->json($member->fresh());
    }

    public function destroy(Member $member): JsonResponse
    {
        $member->delete();

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
}
