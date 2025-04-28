@extends('layouts.app')

@section('title', 'Laporan Penjualan Produk')
@section('subtitle', 'Laporan Penjualan Produk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <form id="filter-form" action="{{ route('laporan.penjualan') }}" method="get"
                    class="row gy-3 gx-3 align-items-end mb-4">
                    <div class="col-md-3">
                        <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                            value="{{ request('tanggal') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="bulan" class="form-label fw-bold">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select form-control">
                            <option value="">-- Semua Bulan --</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tahun" class="form-label fw-bold">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control"
                            value="{{ request('tahun') ?? date('Y') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </form>

                <div class="mb-3">

                    {{--  <a href="{{ route('laporan.penjualan.exportPdf') }}?tanggal={{ request('tanggal') }}&bulan={{ request('bulan') }}&tahun={{ request('tahun') }}"
                        id="export-pdf-btn" class="btn btn-danger btn-sm me-2">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>  --}}

                </div>

                <div class="table-responsive">
                    <x-table id="sales-table">
                        <x-slot name="thead">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Invoice</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </x-slot>
                        <tbody></tbody>
                    </x-table>
                </div>

                <div class="mt-4 text-end">
                    <h4>Total Omzet: <span id="total-omzet" class="badge bg-success fs-5">Rp 0</span></h4>
                </div>
            </x-card>
        </div>
    </div>
    @include('admin.laporan.penjualan.form')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let modal = '#modal-form';
        let modalDetail = '.modal-detail';

        // Initialize DataTable with AJAX request
        let table = $('#sales-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('laporan.penjualan.data') }}',
                data: function(d) {
                    d.tanggal = $('#tanggal').val();
                    d.bulan = $('#bulan').val();
                    d.tahun = $('#tahun').val();
                },
                dataSrc: function(json) {
                    $('#total-omzet').text('Rp ' + json.total_omzet); // Update total omzet
                    return json.data;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'tanggal',
                    name: 'tanggal',
                },
                {
                    data: 'invoice_number',
                    name: 'invoice_number',
                },
                {
                    data: 'total_item',
                    name: 'total_item',
                    className: 'text-center'
                },
                {
                    data: 'total_harga',
                    name: 'total_harga',
                    className: 'text-end',
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                },
            ]
        });

        let penjualanDetail = $('.penjualan-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'kode_produk',
                },
                {
                    data: 'nama_produk',
                },
                {
                    data: 'harga',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'jumlah',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'total_harga',
                    sortable: false,
                    searchable: false
                },
            ]
        });

        // Reload table data when filter form is submitted
        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // Handle Print button functionality
        $('#btnPrint').on('click', function() {
            window.print();
        });

        // Handle Export Excel functionality
        $('#btnExport').on('click', function() {
            // You can implement Export Excel functionality here
            alert("Export Excel functionality is not yet implemented.");
        });

        // Fungsi untuk mencetak faktur
        function cetakFaktur(url) {
            window.open(url);
        }

        function showDetail(url, title = "Detail Penjualan") {
            $(modalDetail).modal('show');
            $(`${modalDetail} .modal-title`).text(title);

            penjualanDetail.ajax.url(url);
            penjualanDetail.ajax.reload();
        }
    </script>
@endpush
