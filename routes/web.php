<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/dashboard', function () {
    return view('admin.dashboard');
});
Route::get('admin/stock', function () {
    return view('admin.stock.index');
});

Route::get('admin/stock/create', function () {
    return view('admin.stock.create');
});

Route::get('admin/transaction', function () {
    return view('admin.transaction.index');
});

Route::get('admin/transaction/detail', function () {
    return view('admin.transaction.detail');
});

Route::get('admin/transaction/show', function () {
    return view('admin.transaction.show');
});

Route::get('admin/member', function () {
    return view('admin.member.index');
});
Route::get('kasir/pos', function () {
    return view('kasir.pos.index');
});

Route::get('owner/dashboard', function () {
    return view('owner.dashboard');
});

Route::get('owner/employee', function () {
    return view('owner.employee.index');
});
Route::get('owner/employee/create', function () {
    return view('owner.employee.create');
});
Route::get('owner/employee/edit', function () {
    return view('owner.employee.edit');
});

Route::get('owner/outlet', function () {
    return view('owner.outlet.index');
});
Route::get('owner/outlet/create', function () {
    return view('owner.outlet.create');
});
Route::get('owner/outlet/edit', function () {
    return view('owner.outlet.edit');
});
Route::get('owner/transaction', function () {
    return view('owner.transaction.index');
});
Route::get('owner/transaction/detail', function () {
    return view('owner.transaction.detail');
});

route::get('owner/finance', function () {
    return view('owner.finance.index');
});
