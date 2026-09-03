<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Support\RoleDashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('stock', 'admin.stock.index')->name('stock.index');
    Route::view('stock/create', 'admin.stock.create')->name('stock.create');
    Route::view('transaction', 'admin.transaction.index')->name('transaction.index');
    Route::view('transaction/detail', 'admin.transaction.detail')->name('transaction.detail');
    Route::view('transaction/show', 'admin.transaction.show')->name('transaction.show');
    Route::view('member', 'admin.member.index')->name('member.index');
});

Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::view('pos', 'kasir.pos.index')->name('pos');
});

Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::view('dashboard', 'owner.dashboard')->name('dashboard');
    Route::view('employee', 'owner.employee.index')->name('employee.index');
    Route::view('employee/create', 'owner.employee.create')->name('employee.create');
    Route::view('employee/edit', 'owner.employee.edit')->name('employee.edit');
    Route::view('outlet', 'owner.outlet.index')->name('outlet.index');
    Route::view('outlet/create', 'owner.outlet.create')->name('outlet.create');
    Route::view('outlet/edit', 'owner.outlet.edit')->name('outlet.edit');
    Route::view('transaction', 'owner.transaction.index')->name('transaction.index');
    Route::view('transaction/detail', 'owner.transaction.detail')->name('transaction.detail');
    Route::view('finance', 'owner.finance.index')->name('finance.index');
});
