<div class="row">
    <!-- Total Penjualan Hari Ini -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Rp. {{ number_format($totalPenjualanHarian, 0, ',', '.') }}</h3>
                <p>Total Penjualan Hari Ini</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-cart"></i>
            </div>
        </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ count($produkTerlaris) }}</h3>
                <p>Produk Terlaris</p>
            </div>
            <div class="icon">
                <i class="ion ion-trophy"></i>
            </div>
        </div>
    </div>

    <!-- Produk Habis -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ count($produkHabis) }}</h3>
                <p>Produk Habis</p>
            </div>
            <div class="icon">
                <i class="ion ion-alert-circled"></i>
            </div>
        </div>
    </div>

    <!-- Total Pembelian Hari Ini -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Rp. {{ number_format($totalPembelianHarian, 0, ',', '.') }}</h3>
                <p>Total Pembelian Hari Ini</p>
            </div>
            <div class="icon">
                <i class="ion ion-ios-cart-outline"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Penjualan Pending -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $penjualanPending }}</h3>
                <p>Penjualan Pending</p>
            </div>
            <div class="icon">
                <i class="ion ion-clock"></i>
            </div>
        </div>
    </div>

    <!-- Penjualan Success -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $penjualanSuccess }}</h3>
                <p>Penjualan Sukses</p>
            </div>
            <div class="icon">
                <i class="ion ion-checkmark-circled"></i>
            </div>
        </div>
    </div>

    <!-- Jumlah Produk -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalProduk }}</h3>
                <p>Jumlah Produk</p>
            </div>
            <div class="icon">
                <i class="fas fa-cube"></i>
            </div>
        </div>
    </div>

    <!-- Total Customer -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalCustomer }}</h3>
                <p>Total Customer</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
</div>
