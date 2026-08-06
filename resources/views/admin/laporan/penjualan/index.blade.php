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
                    <button type="button" class="btn btn-danger btn-sm me-2" onclick="showPdfModal()">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                </div>

                <!-- Modal Export PDF -->
                <div class="modal fade" id="modalPdf" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <form action="{{ route('laporan.penjualan.exportPdf') }}" method="GET" target="_blank" class="modal-content rounded-3 shadow-lg">
                            <input type="hidden" name="tanggal" value="" id="pdf_tanggal">
                            <input type="hidden" name="bulan" value="" id="pdf_bulan">
                            <input type="hidden" name="tahun" value="" id="pdf_tahun">
                            
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="fas fa-file-pdf"></i> Export PDF Laporan</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label for="paper_size" class="form-label fw-bold">Ukuran Kertas</label>
                                    <select name="paper_size" id="paper_size" class="form-control">
                                        <option value="a4">A4</option>
                                        <option value="a5">A5 (Faktur / Setengah A4)</option>
                                        <option value="f4">F4 (Folio)</option>
                                        <option value="thermal">Thermal (Struk Kasir)</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="orientation" class="form-label fw-bold">Orientasi</label>
                                    <select name="orientation" id="orientation" class="form-control">
                                        <option value="portrait">Portrait</option>
                                        <option value="landscape" selected>Landscape</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-danger" onclick="$('#modalPdf').modal('hide');"><i class="fas fa-download"></i> Unduh PDF</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Export PDF Faktur Per Item -->
                <div class="modal fade" id="modalPdfFaktur" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <form action="" id="formPdfFaktur" method="GET" target="_blank" class="modal-content rounded-3 shadow-lg">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="fas fa-file-pdf"></i> Export PDF Faktur</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group mb-3">
                                    <label for="paper_size_faktur" class="form-label fw-bold">Ukuran Kertas</label>
                                    <select name="paper_size" id="paper_size_faktur" class="form-control">
                                        <option value="a5" selected>A5 (Faktur / Setengah A4)</option>
                                        <option value="a4">A4</option>
                                        <option value="f4">F4 (Folio)</option>
                                        <option value="thermal">Thermal (Struk Kasir)</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="orientation_faktur" class="form-label fw-bold">Orientasi</label>
                                    <select name="orientation" id="orientation_faktur" class="form-control">
                                        <option value="landscape">Landscape</option>
                                        <option value="portrait">Portrait</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-danger" onclick="$('#modalPdfFaktur').modal('hide');"><i class="fas fa-download"></i> Unduh PDF</button>
                            </div>
                        </form>
                    </div>
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

        function showPdfModal() {
            // Update hidden inputs with current filter values
            $('#pdf_tanggal').val($('#tanggal').val());
            $('#pdf_bulan').val($('#bulan').val());
            $('#pdf_tahun').val($('#tahun').val());
            $('#modalPdf').modal('show');
        }

        function showPdfModalPerItem(id) {
            let url = '{{ route("penjualan.export_pdf_faktur", ":id") }}';
            url = url.replace(':id', id);
            $('#formPdfFaktur').attr('action', url);
            $('#modalPdfFaktur').modal('show');
        }

        function showDetail(url, title = "Detail Penjualan") {
            $(modalDetail).modal('show');
            $(`${modalDetail} .modal-title`).text(title);

            penjualanDetail.ajax.url(url);
            penjualanDetail.ajax.reload();
        }
    </script>
@endpush
