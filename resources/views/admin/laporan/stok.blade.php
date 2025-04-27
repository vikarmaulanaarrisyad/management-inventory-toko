{{-- resources/views/laporan/stok.blade.php --}}
@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <!-- Header Card untuk Laporan Stok -->
                <x-slot name="header">
                    <h3 class="card-title">Laporan Stok Barang</h3>
                </x-slot>

                <!-- Konten Card untuk Filter Tanggal dan Tabel Stok -->
                <div class="card-body">
                    <!-- Filter Tanggal -->
                    <form method="GET" action="{{ route('laporan.stok') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="start_date">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary form-control">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3">
                        <!-- Export buttons (Excel, PDF) -->
                        {{--  <a href="#" class="btn btn-success btn-sm">Export Excel</a>
                        <a href="#" class="btn btn-danger btn-sm">Export PDF</a>  --}}
                    </div>

                    <div class="table-responsive mt-3">
                        <!-- Tabel Stok Barang -->
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Masuk</th>
                                    <th>Stok Keluar</th>
                                    <th>Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>{{ $item->stok_masuk }}</td>
                                        <td>{{ $item->stok_keluar }}</td>
                                        <td>{{ $item->stok_akhir }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection
