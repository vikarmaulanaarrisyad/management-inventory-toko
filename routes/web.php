<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    ProdukController,
    SupplierController
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
        Route::get('/kategori/select2', [KategoriController::class, 'select2'])->name('kategori.select2');
        Route::resource('/kategori', KategoriController::class);
        Route::post('/kategori/import-excel', [KategoriController::class, 'importExcel'])->name('kategori.import_excel');

        // Barang
        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::get('/produk/export-excel', [ProdukController::class, 'exportExcel'])->name('produk.export_excel');
        Route::get('/produk/pdf', [ProdukController::class, 'exportPdf'])->name('produk.pdf');
        Route::post('/produk/import-excel', [ProdukController::class, 'importExcel'])->name('produk.import_excel');
        Route::resource('/produk', ProdukController::class)->except('create', 'edit');

        // Supplier
        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::post('/supplier/import-excel', [SupplierController::class, 'importExcel'])->name('supplier.import_excel');
        Route::resource('/supplier', SupplierController::class)->except('create', 'edit');
    });
});
