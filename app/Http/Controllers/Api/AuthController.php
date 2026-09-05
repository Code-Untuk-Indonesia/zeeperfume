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

        $user = User::where('username', $request->username)->first();

        // Cek apakah user ada dan password benar
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah!'
            ], 401);
        }

        // Cek status aktif (mencegah akun yang sudah dinonaktifkan untuk login)
        if (!$user->status_aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sudah dinonaktifkan.'
            ], 403);
        }

        // Cek role_id (Asumsi role_id 3 adalah Kasir)
        // Silakan sesuaikan angka 3 jika ID role Kasir di database Anda berbeda
        if ($user->role_id !== 3) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak! Aplikasi ini hanya untuk Kasir.'
            ], 403); // 403 Forbidden
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
        // Hapus token yang sedang digunakan saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
