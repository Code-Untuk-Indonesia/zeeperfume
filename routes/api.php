<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;

// Rute Publik (Tidak butuh login)
Route::post('login', [AuthController::class, 'login']);

// Rute Terproteksi (Wajib login / menyertakan Token)
Route::middleware('auth:sanctum')->group(function () {

    // Endpoint Auth
    Route::post('logout', [AuthController::class, 'logout']);

    // Endpoint Master Data Parfum
    Route::apiResource('products', ProductController::class);

    // Endpoint Transaksi / Kasir
    Route::post('checkout', [TransactionController::class, 'checkout']);

});
