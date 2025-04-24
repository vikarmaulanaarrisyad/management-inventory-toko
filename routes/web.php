<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
        // Kategori
        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);
        Route::post('/kategori/import-excel', [KategoriController::class, 'importExcel'])->name('kategori.import_excel');
    });
});
