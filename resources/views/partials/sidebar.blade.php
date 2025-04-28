<aside class="main-sidebar elevation-4 sidebar-light-primary">

    @php
        $setting = \App\Models\Setting::first();
    @endphp

    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link bg-primary">
        {{--  <img src="" alt="Logo" class="brand-image img-circle elevation-3 bg-light" style="opacity: .8">  --}}
        <span class="brand-text font-weight-light">{{ $setting->nama_toko }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                {{--
                @if (!empty(auth()->user()->avatar) && Storage::disk('public')->exists(auth()->user()->avatar))
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="logo" class="img-circle elevation-2"
                        style="width: 35px; height: 35px;">
                @else
                    <img src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}" alt="logo"
                        class="img-circle elevation-2" style="width: 35px; height: 35px;">
                @endif
                --}}

                @if (!empty(auth()->user()->foto) && Storage::disk('public')->exists(auth()->user()->foto))
                    <img src="{{ Storage::url(auth()->user()->foto) }}" alt="logo" class="img-circle elevation-2"
                        style="width: 35px; height: 35px;">
                @else
                    <img src="{{ asset('AdminLTE/dist/img/user1-128x128.jpg') }}" alt="logo"
                        class="img-circle elevation-2" style="width: 35px; height: 35px;">
                @endif
            </div>
            <div class="info">
                <a href="{{ route('profile.show') }}" class="d-block" data-toggle="tooltip" data-placement="top"
                    title="Edit Profil">
                    {{ auth()->user()->name }}
                    <i class="fas fa-pencil-alt ml-2 text-sm text-primary"></i>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-child-indent" data-widget="treeview"
                role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if (Auth::user()->hasRole('admin'))
                    <!-- Master Data -->
                    <li class="nav-header">MASTER DATA</li>
                    <li class="nav-item">
                        <a href="{{ route('kategori.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Kategori</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('produk.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Data Produk</p>
                        </a>
                    </li>
                    {{--  <li class="nav-item">
                    <a href="{{ route('supplier.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Data Supplier</p>
                    </a>
                </li>  --}}
                    <li class="nav-item">
                        <a href="{{ route('customer.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Customer</p>
                        </a>
                    </li>
                @endif

                <!-- Transaksi -->
                <li class="nav-header">TRANSAKSI</li>

                @if (Auth::user()->hasRole('admin'))
                    <li class="nav-item">
                        <a href="{{ route('pembelian.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-warehouse"></i> <!-- untuk "Data Pembelian" -->
                            <p>Pembelian</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('pembelian.create') }}" class="nav-link">
                            <i class="nav-icon fas fa-cart-plus"></i> <!-- untuk "Buat Transaksi Pembelian" -->
                            <p>Transaksi Pembelian</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ route('penjualan.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-store"></i> <!-- untuk "Data Penjualan" -->
                        <p>Penjualan</p>
                    </a>
                </li>

                @if (Auth::user()->hasRole('karyawan'))
                    <li class="nav-item">
                        <a href="{{ route('penjualan.create') }}" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i> <!-- untuk "Transaksi Penjualan" -->
                            <p>Transaksi Penjualan</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->hasRole('admin'))
                    <!-- Laporan -->
                    <li class="nav-item">
                        <a href="{{ route('laporan.stok.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Laporan Stok</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('laporan.penjualan') }}" class="nav-link">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan Penjualan</p>
                        </a>
                    </li>

                    <!-- Pengaturan -->
                    <li class="nav-item">
                        <a href="{{ route('user-management.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Manajemen Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('setting.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Setting Aplikasi</p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit" class="nav-link bg-danger mt-3 text-white text-left"
                            style="border: none; width: 100%;">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
