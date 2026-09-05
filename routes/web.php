<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\OutletController;
use App\Http\Controllers\Owner\MemberController; // <-- Tambahan untuk Owner
use App\Http\Controllers\Admin\OutletController as AdminOutletController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
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

    // Menggunakan AdminOutletController
    Route::get('outlet', [AdminOutletController::class, 'index'])->name('outlet.index');
    Route::view('outlet/create', 'admin.outlet.create')->name('outlet.create');
    Route::view('outlet/edit', 'admin.outlet.edit')->name('outlet.edit');

    Route::view('stock', 'admin.stock.index')->name('stock.index');
    Route::view('stock/create', 'admin.stock.create')->name('stock.create');
    Route::view('stock/edit', 'admin.stock.edit')->name('stock.edit');

    Route::view('transaction', 'admin.transaction.index')->name('transaction.index');
    Route::view('transaction/detail', 'admin.transaction.detail')->name('transaction.detail');
    Route::view('transaction/show', 'admin.transaction.show')->name('transaction.show');

    // Menggunakan AdminMemberController
    Route::get('member', [AdminMemberController::class, 'index'])->name('member.index');
    Route::get('member/create', [AdminMemberController::class, 'create'])->name('member.create');
    Route::post('member', [AdminMemberController::class, 'store'])->name('member.store');
    Route::get('member/{member}/edit', [AdminMemberController::class, 'edit'])->name('member.edit');
    Route::put('member/{member}', [AdminMemberController::class, 'update'])->name('member.update');
    Route::delete('member/{member}', [AdminMemberController::class, 'destroy'])->name('member.destroy');
});


/*
|--------------------------------------------------------------------------
| KASIR Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    // Route POS utama menggunakan Controller
    Route::get('pos', [PosController::class, 'index'])->name('pos');

    Route::view('pos/custom', 'kasir.pos.custom')->name('pos.custom');
    Route::get('pos/success', [PosController::class, 'success'])->name('pos.success');
    Route::get('transaction', [PosController::class, 'history'])->name('transaction.index');
    Route::post('pos/store', [PosController::class, 'store'])->name('pos.store');

    Route::view('member/create', 'kasir.member.create')->name('member.create');
});


/*
|--------------------------------------------------------------------------
| OWNER Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {

    Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // -- PEGAWAI --
    Route::get('employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::view('employee/create', 'owner.employee.create')->name('employee.create');
    Route::view('employee/edit', 'owner.employee.edit')->name('employee.edit');

    // -- OUTLET --
    Route::get('outlet', [OutletController::class, 'index'])->name('outlet.index');
    Route::get('outlet/create', [OutletController::class, 'create'])->name('outlet.create');
    Route::post('outlet', [OutletController::class, 'store'])->name('outlet.store');
    Route::get('outlet/{outlet}/edit', [OutletController::class, 'edit'])->name('outlet.edit');
    Route::put('outlet/{outlet}', [OutletController::class, 'update'])->name('outlet.update');
    Route::delete('outlet/{outlet}', [OutletController::class, 'destroy'])->name('outlet.destroy');
    Route::patch('outlet/{outlet}/restore', [OutletController::class, 'restore'])->name('outlet.restore');

    // -- MEMBER --
    Route::get('member', [MemberController::class, 'index'])->name('member.index');
    Route::get('member/create', [MemberController::class, 'create'])->name('member.create');
    Route::post('member', [MemberController::class, 'store'])->name('member.store');
    Route::get('member/{member}/edit', [MemberController::class, 'edit'])->name('member.edit');
    Route::put('member/{member}', [MemberController::class, 'update'])->name('member.update');
    Route::delete('member/{member}', [MemberController::class, 'destroy'])->name('member.destroy');

    // -- TRANSAKSI & LAPORAN --
    Route::view('transaction', 'owner.transaction.index')->name('transaction.index');
    Route::view('transaction/detail', 'owner.transaction.detail')->name('transaction.detail');
    Route::view('finance', 'owner.finance.index')->name('finance.index');
});
