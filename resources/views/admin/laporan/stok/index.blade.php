@extends('layouts.app')

@section('title', 'Laporan Stok ' . tanggal_indonesia($start) . ' s/d ' . tanggal_indonesia($end))
@section('subtitle', 'Laporan Stok')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    {{--  <h3 class="card-title">Laporan Stok Barang</h3>  --}}
                    <button data-toggle="modal" data-target="#modal-form" class="btn btn-primary"><i
                            class="fas fa-pencil-alt"></i> Ubah Periode</button>
                </x-slot>

                <div class="card-body">
                    <!-- Filter Tanggal -->
                    {{--  <form id="filter-form" method="GET" action="{{ route('laporan.stok') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="start">Tanggal Mulai</label>
                                <input type="date" name="start" id="start" class="form-control"
                                    value="{{ $start }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end">Tanggal Akhir</label>
                                <input type="date" name="end" id="end" class="form-control"
                                    value="{{ $end }}">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary form-control">Filter</button>
                            </div>
                        </div>
                    </form>  --}}



                    <div class="mt-3">
                        {{-- Tombol Export Excel nanti di sini --}}
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-striped" id="stok-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Masuk</th>
                                    <th>Stok Keluar</th>
                                    <th>Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data diisi lewat AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
    @include('admin.laporan.stok.form')
@endsection

@include('includes.datatables')
@include('includes.datepicker')

@push('scripts')
    <script>
        let modal = '#modal-form';
        // Initialize DataTable
        let table = $('#stok-table').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('laporan.stok.data', compact('start', 'end')) }}', // tidak perlu pakai compact()
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'nama_produk'
                },
                {
                    data: 'stok_masuk'
                },
                {
                    data: 'stok_keluar'
                },
                {
                    data: 'sisa_stok'
                }
            ],
            paginate: false,
            searching: false,
            bInfo: false,
            order: []
        });


        // Reload data saat filter di-submit
        $('#filter-form').submit(function(e) {
            e.preventDefault();
            table.ajax.reload();
        });
    </script>
@endpush
