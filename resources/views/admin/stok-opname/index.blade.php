@extends('layouts.app')

@section('title', 'Data Stok Opname')

@section('subtitle', 'Data Stok Opname')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <x-slot name="header">
                    <a href="{{ route('stok-opname.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Tambah Data Opname
                    </a>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Selisih</th>
                        <th>Keterangan</th>
                        <th>Admin</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let table;

        table = $('.table').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('stok-opname.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'produk'
                },
                {
                    data: 'stok_sistem'
                },
                {
                    data: 'stok_fisik'
                },
                {
                    data: 'selisih_badge',
                    name: 'selisih_badge',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'user'
                },
            ]
        })
    </script>
@endpush
