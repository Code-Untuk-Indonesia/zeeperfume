<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\MemberController;

// Rute Publik (Tidak butuh login)
Route::post('login', [AuthController::class, 'login']);

// Rute Terproteksi (Wajib login / menyertakan Token)
Route::middleware('auth:sanctum')->group(function () {

    // Endpoint Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('profile/update', [AuthController::class, 'updateProfile']);

    // Endpoint Produk
    // apiResource otomatis mengarahkan method GET /products ke fungsi index()
    Route::apiResource('products', ProductController::class)->only(['index']);

    // Endpoint Transaksi / Kasir
    Route::post('checkout', [TransactionController::class, 'store']); // Ganti method ke store() sesuai controller baru
    Route::get('transactions/history', [TransactionController::class, 'history']); // Menampilkan riwayat hari ini
    Route::get('transactions/{id}', [TransactionController::class, 'show']); // Menampilkan detail transaksi spesifik
    Route::get('transactions/{id}/receipt', [TransactionController::class, 'receipt']);

    // Endpoint Member
    Route::post('members/search', [TransactionController::class, 'searchMember']); // Mencari member berdasarkan no HP
    Route::post('members', [MemberController::class, 'store']);

});
