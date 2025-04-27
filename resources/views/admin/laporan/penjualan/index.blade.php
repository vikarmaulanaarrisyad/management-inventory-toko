@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <form id="filter-form" class="row gy-3 gx-3 align-items-end mb-4">
                    <div class="col-md-3">
                        <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="bulan" class="form-label fw-bold">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select form-control">
                            <option value="">-- Semua Bulan --</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tahun" class="form-label fw-bold">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control"
                            value="{{ date('Y') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <x-table id="sales-table">
                        <x-slot name="thead">
                            <tr>
                                <th>#</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Total Harga</th>
                                <th>Tanggal</th>
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
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#sales-table').DataTable({
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
                        $('#total-omzet').text(json.total_omzet);
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
                        data: 'product_code',
                        name: 'product_code',
                        className: 'text-wrap'
                    },

                    {
                        data: 'product_name',
                        name: 'product_name',
                        className: 'text-wrap'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code',
                        className: 'text-wrap'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity',
                        className: 'text-center'
                    },
                    {
                        data: 'total_harga',
                        name: 'total_harga',
                        className: 'text-end'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        className: 'text-center'
                    },
                ]
            });

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });
        });
    </script>
@endpush
