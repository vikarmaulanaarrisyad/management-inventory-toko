<?php

use App\Http\Controllers\{
    BackupController,
    CustomerController,
    DashboardController,
    KategoriController,
    LaporanPenjualanController,
    LaporanStokController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    ProdukController,
    SettingController,
    SupplierController,
    UserManagementController,
    UserProfileInformationController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/manifest.json', function () {
    $env = env('APP_ENV_TYPE', 'production');

    // Tentukan warna berdasarkan environment
    $backgroundColor = $env === 'staging' ? '#ffeb3b' : '#6777ef';  // Contoh warna untuk staging (kuning) dan production (biru)
    $themeColor = $env === 'staging' ? '#ffeb3b' : '#6777ef';

    return response()->json([
        'name' => $env === 'staging' ? 'Multazam Staging' : 'Multazam',
        'short_name' => env('APP_SHORT_NAME', 'multazam'),
        'start_url' => '/index.php',
        'background_color' => $backgroundColor,
        'description' => env('APP_DESCRIPTION'),
        'display' => 'fullscreen',
        'theme_color' => $themeColor,
        'env_type' => $env,
        'icons' => [
            [
                'src' => asset('logo.png'),
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ]
        ],
    ])->header('Content-Type', 'application/manifest+json');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/profile', [UserProfileInformationController::class, 'show'])
        ->name('profile.show');

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
        Route::post('pembelian_detail/update_harga', [PembelianDetailController::class, 'updateHarga'])->name('pembelian_detail.update_harga');
        Route::resource('/pembeliandetail', PembelianDetailController::class);

        // // Penjualan
        // Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        // Route::get('/penjualan/{penjualan}/cetak-faktur', [PenjualanController::class, 'cetakFaktur'])->name('penjualan.cetak_faktur');
        // Route::resource('/penjualan', PenjualanController::class);

        // // Penjualan Detail
        // Route::get('/penjualandetail/produk/data', [PenjualanDetailController::class, 'produk'])->name('penjualandetail.produk');
        // Route::get('/penjualandetail/customer/data', [PenjualanDetailController::class, 'customer'])->name('penjualandetail.customer');
        // Route::get('/penjualandetail/{id}/data', [PenjualanDetailController::class, 'data'])->name('penjualandetail.data');
        // Route::get('/penjualandetail/{total}', [PenjualanDetailController::class, 'loadForm'])->name('penjualandetail.loadform');
        // Route::resource('/penjualandetail', PenjualanDetailController::class)->except('show');

        // Laporan Stok
        Route::get('/laporan/stok', [LaporanStokController::class, 'index'])->name('laporan.stok');

        // Laporan Penjualan
        Route::get('/laporan/penjualan/data', [LaporanPenjualanController::class, 'data'])->name('laporan.penjualan.data');
        Route::get('laporan/penjualan/export-pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('laporan.penjualan.exportPdf');
        Route::get('/laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');

        // User Management
        Route::get('/user-management/data', [UserManagementController::class, 'data'])->name('usermanagement.data');
        Route::resource('/user-management', UserManagementController::class);

        // Setting
        Route::controller(SettingController::class)->group(function () {
            Route::get('/setting', 'index')->name('setting.index');
            Route::put('/setting/{setting}', 'update')->name('setting.update');
        });

        Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/create', [BackupController::class, 'create'])->name('backup.create');
        Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
    });

    Route::group(['middleware' => ['role:admin|karyawan']], function () {
        // Penjualan
        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan/{penjualan}/cetak-faktur', [PenjualanController::class, 'cetakFaktur'])->name('penjualan.cetak_faktur');
        Route::resource('/penjualan', PenjualanController::class);

        // Penjualan Detail
        Route::get('/penjualandetail/produk/data', [PenjualanDetailController::class, 'produk'])->name('penjualandetail.produk');
        Route::get('/penjualandetail/customer/data', [PenjualanDetailController::class, 'customer'])->name('penjualandetail.customer');
        Route::get('/penjualandetail/sales/data', [PenjualanDetailController::class, 'sales'])->name('penjualandetail.sales');
        Route::get('/penjualandetail/{id}/data', [PenjualanDetailController::class, 'data'])->name('penjualandetail.data');
        Route::get('/penjualandetail/{total}', [PenjualanDetailController::class, 'loadForm'])->name('penjualandetail.loadform');
        Route::post('penjualandetail/update_harga', [PenjualanDetailController::class, 'updateHarga'])->name('penjualandetail.update_harga');
        Route::resource('/penjualandetail', PenjualanDetailController::class)->except('show');
        // Route::get('/laporan/stok/ajax', [LaporanStokController::class, 'data'])->name('laporan.stok.ajax');
        // Route untuk menampilkan halaman laporan stok
        Route::get('/laporan/stok', [LaporanStokController::class, 'index'])->name('laporan.stok.index');
        Route::get('/laporan/stok/data/{start}/{end}', [LaporanStokController::class, 'data'])->name('laporan.stok.data');
    });
});
