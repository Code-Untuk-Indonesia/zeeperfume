<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\OutletController;
use App\Http\Controllers\Owner\MemberController; // <-- Tambahan untuk Owner
use App\Http\Controllers\Admin\OutletController as AdminOutletController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\StockController as AdminStockController;
use App\Http\Controllers\Owner\StockController as OwnerStockController;
use App\Http\Controllers\Owner\FinanceController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Owner\TransactionController as OwnerTransactionController;
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

    // Stock CRUD
    Route::get('stock', [AdminStockController::class, 'index'])->name('stock.index');
    Route::get('stock/create', [AdminStockController::class, 'create'])->name('stock.create');
    Route::post('stock/store', [AdminStockController::class, 'store'])->name('stock.store');
    Route::get('stock/edit/{id}', [AdminStockController::class, 'edit'])->name('stock.edit');
    Route::post('stock/update/{id}', [AdminStockController::class, 'update'])->name('stock.update');
    Route::delete('stock/destroy/{id}', [AdminStockController::class, 'destroy'])->name('stock.destroy');

    Route::get('transaction', [AdminTransactionController::class, 'index'])->name('transaction.index');
    Route::post('transaction/request-approval', [AdminTransactionController::class, 'requestApproval'])->name('transaction.request_approval');
    Route::get('transaction/{id}/edit', [AdminTransactionController::class, 'edit'])->name('transaction.edit');
    Route::post('transaction/{id}/update', [AdminTransactionController::class, 'update'])->name('transaction.update');
    Route::get('transaction/online', [AdminTransactionController::class, 'createOnline'])->name('transaction.online');
    Route::get('transaction/search-product', [AdminTransactionController::class, 'searchProduct'])->name('transaction.search_product');
    Route::post('transaction/online/store', [AdminTransactionController::class, 'storeOnline'])->name('transaction.store_online');
    Route::get('transaction/{id}/detail', [AdminTransactionController::class, 'show'])->name('transaction.show');

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
    Route::get('pos', [PosController::class, 'index'])->name('pos');
    Route::view('pos/custom', 'kasir.pos.custom')->name('pos.custom');
    Route::get('pos/success', [PosController::class, 'success'])->name('pos.success');
    Route::get('transaction', [PosController::class, 'history'])->name('transaction.index');
    Route::post('pos/store', [PosController::class, 'store'])->name('pos.store');
    Route::get('pos/search-member', [PosController::class, 'searchMember'])->name('pos.searchMember');

    // --- PERBAIKAN ROUTE MEMBER ---
    Route::get('member/create', [PosController::class, 'createMember'])->name('member.create');
    Route::post('member/store', [PosController::class, 'storeMember'])->name('member.store');
});

/*
|--------------------------------------------------------------------------
| OWNER Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {

    Route::get('dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('finance', [FinanceController::class, 'index'])->name('finance.index');
    // -- PEGAWAI --
    Route::get('employee', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('employee/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('employee', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('employee/{employee}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('employee/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('employee/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy');
    Route::patch('employee/{employee}/restore', [EmployeeController::class, 'restore'])->name('employee.restore');

    // -- OUTLET --
    Route::get('outlet', [OutletController::class, 'index'])->name('outlet.index');
    Route::get('outlet/create', [OutletController::class, 'create'])->name('outlet.create');
    Route::post('outlet', [OutletController::class, 'store'])->name('outlet.store');
    Route::get('outlet/{outlet}/edit', [OutletController::class, 'edit'])->name('outlet.edit');
    Route::put('outlet/{outlet}', [OutletController::class, 'update'])->name('outlet.update');
    Route::delete('outlet/{outlet}', [OutletController::class, 'destroy'])->name('outlet.destroy');
    Route::patch('outlet/{outlet}/restore', [OutletController::class, 'restore'])->name('outlet.restore');


    // STOCK
    Route::get('stock', [OwnerStockController::class, 'index'])->name('stock.index');
    Route::get('stock/create', [OwnerStockController::class, 'create'])->name('stock.create');
    Route::post('stock/store', [OwnerStockController::class, 'store'])->name('stock.store');
    Route::get('stock/edit/{id}', [OwnerStockController::class, 'edit'])->name('stock.edit');
    Route::post('stock/update/{id}', [OwnerStockController::class, 'update'])->name('stock.update');
    Route::delete('stock/destroy/{id}', [OwnerStockController::class, 'destroy'])->name('stock.destroy');

    // -- MEMBER --
    Route::get('member', [MemberController::class, 'index'])->name('member.index');
    Route::get('member/create', [MemberController::class, 'create'])->name('member.create');
    Route::post('member', [MemberController::class, 'store'])->name('member.store');
    Route::get('member/{member}/edit', [MemberController::class, 'edit'])->name('member.edit');
    Route::put('member/{member}', [MemberController::class, 'update'])->name('member.update');
    Route::delete('member/{member}', [MemberController::class, 'destroy'])->name('member.destroy');

    // -- TRANSAKSI & LAPORAN --
    Route::get('transaction', [OwnerTransactionController::class, 'index'])->name('transaction.index');
    Route::post('transaction/approve/{id}', [OwnerTransactionController::class, 'approve'])->name('transaction.approve');
    Route::post('transaction/reject/{id}', [OwnerTransactionController::class, 'reject'])->name('transaction.reject');
});
