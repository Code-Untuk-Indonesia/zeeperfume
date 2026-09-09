<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class MemberController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // 1. Validasi Input (menggantikan $this->rules())
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'no_telp' => 'required|string|max:20|unique:members,no_telp',
            'email'   => 'nullable|email|max:255',
            'poin'    => 'nullable|integer|min:0',
        ], [
            'no_telp.unique' => 'Nomor HP sudah terdaftar.'
        ]);

        // Generate Kode Member Otomatis (Format: MEM-YYYYMMDD-001)
        $lastMember = DB::table('members')->latest('id')->first();
        $nextId = $lastMember ? $lastMember->id + 1 : 1;
        $kodeMember = 'MEM-' . now()->format('Ymd') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $now = now();

        // 2. Insert ke database menggunakan Query Builder
        $id = DB::table('members')->insertGetId([
            'kode_member'       => $kodeMember,
            'nama'              => $validated['nama'],
            'no_telp'           => $validated['no_telp'],
            'email'             => $validated['email'] ?? null,
            'poin'              => $validated['poin'] ?? 0,
            'tanggal_bergabung' => $now->toDateString(),
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);

        // 3. Mengambil data member yang baru dibuat (menggantikan $this->findMember($id))
        $newMember = DB::table('members')->where('id', $id)->first();

        // 4. Kembalikan response JSON
        return response()->json([
            'success' => true,
            'message' => 'Member baru berhasil ditambahkan.',
            'data'    => $newMember
        ], 201);
    }
}
