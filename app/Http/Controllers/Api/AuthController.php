<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Fungsi Login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tambahkan with('branch') agar data tabel branches ikut terpanggil
        $user = User::with('branch')->where('username', $request->username)->first();

        // Cek apakah user ada dan password benar
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah!'
            ], 401);
        }

        // Cek status aktif
        if (!$user->status_aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sudah dinonaktifkan.'
            ], 403);
        }

        // Cek role_id (Asumsi role_id 3 adalah Kasir)
        if ($user->role_id !== 3) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak! Aplikasi ini hanya untuk Kasir.'
            ], 403);
        }

        $token = $user->createToken('pos-kasir-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 200);
    }

    // Fungsi Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
