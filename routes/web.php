<?php

use App\Http\Controllers\{
    CustomerController,
    DashboardController,
    KategoriController,
    PembelianController,
    PembelianDetailController,
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

        // Customer
        Route::get('/customer/data', [CustomerController::class, 'data'])->name('customer.data');
        Route::post('/customer/import-excel', [CustomerController::class, 'importExcel'])->name('customer.import_excel');
        Route::resource('/customer', CustomerController::class)->except('create', 'edit');

        // Pembelian
        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{pembelian}/cetak-faktur', [PembelianController::class, 'cetakFaktur'])->name('pembelian.cetak_faktur');
        Route::resource('/pembelian', PembelianController::class)->except('edit');

        // Pembelian Detail
        Route::get('/pembeliandetail/produk/data', [PembelianDetailController::class, 'produk'])->name('pembelian_detail.produk');
        Route::get('/pembeliandetail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembeliandetail/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.loadform');
        Route::resource('/pembeliandetail', PembelianDetailController::class);
    });
});
