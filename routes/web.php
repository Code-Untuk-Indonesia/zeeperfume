<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\OutletController;
use App\Support\RoleDashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Root Route & Authentication
|--------------------------------------------------------------------------
*/

Route::get('/', function (Request $request) {
    if ($request->user() === null) {
        return redirect()->route('login');
    }

    $dashboardRoute = RoleDashboard::routeName($request->user());
    abort_if($dashboardRoute === null, 403, 'Role akun belum memiliki akses ke aplikasi.');

    return redirect()->route($dashboardRoute);
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| ADMIN Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');

    Route::view('stock', 'admin.stock.index')->name('stock.index');
    Route::view('stock/create', 'admin.stock.create')->name('stock.create');
    Route::view('stock/edit', 'admin.stock.edit')->name('stock.edit');

    Route::view('transaction', 'admin.transaction.index')->name('transaction.index');
    Route::view('transaction/detail', 'admin.transaction.detail')->name('transaction.detail');
    Route::view('transaction/show', 'admin.transaction.show')->name('transaction.show');

    Route::view('member', 'admin.member.index')->name('member.index');
});


/*
|--------------------------------------------------------------------------
| KASIR Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::view('pos', 'kasir.pos.index')->name('pos');
    Route::view('pos/custom', 'kasir.pos.custom')->name('pos.custom');
    Route::view('pos/success', 'kasir.pos.success')->name('pos.success');

    // Asumsi file riwayat ada di kasir/pos/history.blade.php
    Route::view('transaction', 'kasir.pos.history')->name('transaction.index');

    Route::view('member/create', 'kasir.member.create')->name('member.create');
});


/*
|--------------------------------------------------------------------------
| OWNER Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {

    // Menggunakan Controller untuk Dashboard Owner
    Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // Menggunakan Controller untuk Index Pegawai (Menampilkan Data)
    Route::get('employee', [EmployeeController::class, 'index'])->name('employee.index');

    // Create & Edit masih berupa view statis, nanti bisa disesuaikan lagi jika controllernya sudah ada method create/edit
    Route::view('employee/create', 'owner.employee.create')->name('employee.create');
    Route::view('employee/edit', 'owner.employee.edit')->name('employee.edit');

    Route::get('outlet', [OutletController::class, 'index'])->name('outlet.index');
    Route::view('outlet/create', 'owner.outlet.create')->name('outlet.create');
    Route::view('outlet/edit', 'owner.outlet.edit')->name('outlet.edit');

    Route::view('transaction', 'owner.transaction.index')->name('transaction.index');
    Route::view('transaction/detail', 'owner.transaction.detail')->name('transaction.detail');

    Route::view('finance', 'owner.finance.index')->name('finance.index');
});
