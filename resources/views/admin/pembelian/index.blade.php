@extends('layouts.app')

@section('title', 'Pembelian')

@section('subtitle', 'Pembelian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                {{--  <x-slot name="header">
                    <button onclick="addForm(`{{ route('pembelian.create') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Transaksi Baru
                    </button>
                </x-slot>  --}}

                <x-table class="pembelian">
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Faktur</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Sales</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>

    @include('admin.pembelian.detail')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let penjualan, pembelianDetail;
        let modal = '#modal-form';
        let modalDetail = '.modal-detail';
        let button = '#submitBtn';

        $(function() {
            $('#spinner-border').hide();
        });
    </script>

    <script>
        pembelian = $('.pembelian').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('pembelian.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'invoice_number'
                },
                {
                    data: 'total_item'
                },
                {
                    data: 'total_harga'
                },
                {
                    data: 'status'
                },
                {
                    data: 'karyawan'
                },
                {
                    data: 'aksi',
                    searchable: false,
                    sortable: false
                },
            ],
        })

        pembelianDetail = $('.pembelian-detail').DataTable({
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
                    data: 'harga_lama',
                    searchable: false,
                    sortable: false
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
    </script>

    <script>
        function addForm(url) {
            window.location.href = url;
        }

        function addData(url, title = 'Tambah') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('POST');

            $('#spinner-border').hide();

            $(button).show();
            $(button).prop('disabled', false);

            resetForm(`${modal} form`);
        }

        function showDetail(url, title = "Detail Pembelian") {
            $(modalDetail).modal('show');
            $(`${modalDetail} .modal-title`).text(title);

            pembelianDetail.ajax.url(url);
            pembelianDetail.ajax.reload();
        }

        function deleteData(url, name) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
            })
            swalWithBootstrapButtons.fire({
                title: 'Apakah anda yakin?',
                text: 'Anda akan menghapus ' + name + ' ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya Hapus',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "delete",
                        url: url,
                        dataType: "json",
                        success: function(response) {
                            if (response.status = 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then(() => {
                                    window.location.reload();
                                    //pembelianDetail.ajax.reload();
                                    //penjualan.ajax.reload();
                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            // Menampilkan pesan error
                            Swal.fire({
                                icon: 'error',
                                title: 'Opps! Gagal',
                                text: xhr.responseJSON.message,
                                showConfirmButton: true,
                            }).then(() => {
                                // Refresh tabel atau lakukan operasi lain yang diperlukan
                                //pembelianDetail.ajax.reload();
                                // penjualan.ajax.reload();
                            });
                        }
                    });
                }
            });
        }

        // Fungsi untuk mencetak faktur
        function cetakFaktur(url) {
            window.open(url);
        }
    </script>
@endpush
