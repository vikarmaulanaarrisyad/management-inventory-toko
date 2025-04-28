<div class="row">
    <!-- Total Penjualan Hari Ini -->
    <div class="col-lg-4 col-6">
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
    <!-- ./col -->

    <!-- Produk Terlaris -->
    <div class="col-lg-4 col-6">
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
    <!-- ./col -->

    <!-- Produk Habis -->
    <div class="col-lg-4 col-6">
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
    <!-- ./col -->
</div>

<div class="row">
    <!-- Penjualan Pending -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $penjualanPending }}</h3>
                <p>Penjualan Pending</p>
            </div>
            <div class="icon">
                <i class="ion ion-clock"></i> <!-- Ikon untuk pending -->
            </div>
        </div>
    </div>
    <!-- ./col -->

    <!-- Penjualan Success -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $penjualanSuccess }}</h3>
                <p>Penjualan Sukses</p>
            </div>
            <div class="icon">
                <i class="ion ion-checkmark-circled"></i> <!-- Ikon untuk sukses -->
            </div>
        </div>
    </div>
    <!-- ./col -->

    <!-- Jumlah Produk -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalProduk }}</h3>
                <p>Jumlah Produk</p>
            </div>
            <div class="icon">
                <i class="fas fa-cube"></i> <!-- Ikon untuk produk -->
            </div>
        </div>
    </div>
    <!-- Total Customer -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalCustomer }}</h3>
                <p>Total Customer</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i> <!-- Ikon untuk customer -->
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- ./col -->
</div>
