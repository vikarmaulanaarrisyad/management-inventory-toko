@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Daftar Penjualan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card>
                <x-slot name="header">
                    <button onclick="addForm(`{{ route('penjualan.create') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Transaksi Baru
                    </button>
                </x-slot>

                <x-table class="penjualan">
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @include('admin.penjualan.form')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let penjualanDetail, penjualan;
        let modal = '#modal-form';
        let modalDetail = '.modal-detail';
        let button = '#submitBtn';

        $(function() {
            $('#spinner-border').hide();
        });
    </script>

    <script>
        penjualan = $('.penjualan').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('penjualan.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'tanggal',
                },
                {
                    data: 'invoice_number',
                },
                {
                    data: 'customer',
                },
                {
                    data: 'total_item',
                },
                {
                    data: 'total_harga',
                },
                {
                    data: 'user',
                },
                {
                    data: 'status',
                },
                {
                    data: 'aksi',
                    searchable: false,
                    sortable: false
                },
            ],
        })

        penjualanDetail = $('.penjualan-detail').DataTable({
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

        function showDetail(url, title = "Detail Penjualan") {
            $(modalDetail).modal('show');
            $(`${modalDetail} .modal-title`).text(title);

            penjualanDetail.ajax.url(url);
            penjualanDetail.ajax.reload();
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
                                    //penjualanDetail.ajax.reload();
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
                                //penjualanDetail.ajax.reload();
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

        function lanjutkanTransaksi(url) {
            window.location.href = url;
        }
    </script>
@endpush
