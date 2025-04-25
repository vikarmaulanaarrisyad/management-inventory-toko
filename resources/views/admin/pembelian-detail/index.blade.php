@extends('layouts.app')

@section('title', 'Transaksi Pembelian')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Transaksi Pembelian</li>
@endsection

@push('css')
    <style>
        .tampil-bayar {
            font-size: 2.8em;
            font-weight: bold;
            text-align: center;
            height: 80px;
            line-height: 80px;
            color: #fff;
            border-radius: 10px;
        }

        .tampil-terbilang {
            margin-top: 10px;
            padding: 15px;
            font-style: italic;
            background: #e2e8f0;
            border-radius: 8px;
            font-size: 1.1em;
        }

        @media(max-width: 768px) {
            .tampil-bayar {
                font-size: 2em;
                height: 60px;
                line-height: 60px;
            }
        }

        @media (max-width: 576px) {
            .input-group .input-group-append {
                margin-top: 0px;
            }

            .tampil-bayar {
                font-size: 1.6em;
                height: auto;
                padding: 10px;
            }

            .tampil-terbilang {
                font-size: 1em;
                padding: 10px;
            }

            .form-group label {
                padding-top: 0;
            }
        }


        .form-group>label {
            font-weight: 500;
            padding-top: 8px;
        }

        .input-group .btn {
            border-radius: 0 5px 5px 0 !important;
        }

        .input-group .btn+.btn {
            margin-left: -1px;
        }

        .table-pembelian tbody tr:last-child {
            display: none;
        }

        .btn-flat {
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }

        .btn-flat:hover {
            transform: scale(1.05);
        }
    </style>
@endpush


@section('content')
    <div class="row">
        <div class="col-12">
            <x-card>
                {{-- FORM PRODUK --}}
                <form class="form-produk">
                    @csrf
                    <div class="form-group row align-items-center">
                        <label for="kode_produk" class="col-12 col-md-3 col-lg-2">Nama Produk</label>
                        <div class="col-12 col-md-9 col-lg-4">
                            <div class="input-group">
                                <input type="hidden" name="pembelian_id" id="pembelian_id" value="{{ $pembelian->id }}">
                                <input type="hidden" name="produk_id" id="produk_id">

                                <input id="kode_produk" class="form-control" type="text" name="kode_produk">

                                <div class="input-group-append">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- TABEL PRODUK --}}
                <x-table class="table-pembelian">
                    <x-slot name="thead">
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="14%">Jumlah</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>

                <div class="row mt-4">
                    {{-- Total dan Terbilang --}}
                    <div class="col-12 col-md-6">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>

                    {{-- Form Pembayaran --}}
                    <div class="col-12 col-md-6">
                        <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" name="pembelian_id" value="{{ $pembelian->id }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">

                            <button type="submit" class="btn btn-primary btn-block btn-simpan mt-3">
                                <i class="fas fa-save"></i> Simpan Transaksi
                            </button>

                            <!-- Tombol Cetak Faktur -->
                            {{--  <button type="button" class="btn btn-success btn-block mt-3" onclick="cetakFaktur()">
                                <i class="fas fa-print"></i> Cetak Transaksi Faktur
                            </button>  --}}
                        </form>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    @include('admin.pembelian-detail.form_produk')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let table1, table2, table3;
        let modal = '#modal-form';
        let button = '#submitBtn';

        $(function() {
            $('#spinner-border').hide();
            $('#nama_produk').focus();
            $('body').addClass('sidebar-collapse');
            $('.btn-simpan').prop('disabled', false);
        });

        $(document).on('input', '.quantity', function() {
            let id = $(this).data('id');
            let quantity = parseInt($(this).val());

            if (quantity < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Opps! Gagal',
                    text: 'Jumlah tidak boleh kurang dari 1',
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    $(this).val(1);
                });
                return;
            }

            if (quantity > 10000) {
                Swal.fire({
                    icon: 'error',
                    title: 'Opps! Gagal',
                    text: 'Jumlah tidak boleh melebihi 10000',
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    $(this).val(10000);
                });
                return;
            }

            // Menggunakan route() dengan menggantikan :id dengan id dari produk
            let updateUrl = `{{ route('pembeliandetail.update', ':id') }}`.replace(':id', id);

            $.post(updateUrl, {
                    _method: 'PUT',
                    _token: '{{ csrf_token() }}',
                    quantity,
                })
                .done(response => {
                    table1.ajax.reload();
                    table2.ajax.reload();
                    table3.ajax.reload();
                })
                .fail(errors => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errors.responseJSON.message ?? 'Tidak dapat menyimpan data ke server',
                        showConfirmButton: false,
                        timer: 3000,
                    }).then(() => {
                        $(this).val(1);
                    });
                });
        });

        $('.btn-simpan').on('click', function() {
            let form = $('.form-pembelian');
            form.submit();
        })
    </script>

    <script>
        table1 = $('.table-pembelian').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('pembelian_detail.data', $pembelian->id) }}',
            },
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
                },
                {
                    data: 'quantity',
                },
                {
                    data: 'total_harga',
                },
                {
                    data: 'aksi',
                    sortable: false,
                    searchable: false
                },
            ],
            dom: 'Brt',
            bSort: false,
        }).on('draw.dt', function() {
            loadForm();
        });

        table2 = $('.table-produk').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('pembelian_detail.produk') }}',
            },
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
                    data: 'stok',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'aksi',
                    sortable: false,
                    searchable: false
                },
            ],
        });
    </script>


    <script>
        function tampilProduk(title = 'Pilih Produk') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
        }

        function pilihProduk(id, nama) {
            $('#produk_id').val(id);
            $('#nama_produk').val(nama);
            hideProduk();
            tambahProduk(nama);
            $('.btn-simpan').prop('disabled', false);
        }

        function tambahProduk(nama) {
            $.post('{{ route('pembeliandetail.store') }}', $('.form-produk').serialize())
                .done(response => {
                    if (response.status = 200) {
                        table1.ajax.reload();
                        table2.ajax.reload();
                        table3.ajax.reload();
                        $('#nama_produk').focus();
                    }
                })
                .fail(errors => {
                    table1.ajax.reload();
                    table2.ajax.reload();
                    table3.ajax.reload();
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errors.responseJSON.message,
                        showConfirmButton: true,
                    });
                    if (errors.status == 422) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Opps! Gagal',
                            text: errors.responseJSON.message,
                            showConfirmButton: true,
                        });
                        loopErrors(errors.responseJSON.errors);
                        return;
                    }

                    $('#nama_produk').focus();
                    $('#nama_produk').val(nama);
                });

        }

        function hideProduk() {
            $(modal).modal('hide');
        }

        function submitForm(originalForm) {
            $(button).prop('disabled', true);
            $('#spinner-border').show();

            $.post({
                    url: $(originalForm).attr('action'),
                    data: new FormData(originalForm),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false
                })
                .done(response => {
                    $(modalTambahCustomer).modal('hide');
                    if (response.status = 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            $(button).prop('disabled', false);
                            $('#spinner-border').hide();

                            table3.ajax.reload();
                        })
                    }
                })
                .fail(errors => {
                    $('#spinner-border').hide();
                    $(button).prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errors.responseJSON.message,
                        showConfirmButton: true,
                    });
                    if (errors.status == 422) {
                        $('#spinner-border').hide()
                        $(button).prop('disabled', false);
                        loopErrors(errors.responseJSON.errors);
                        return;
                    }
                });
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
                                    table1.ajax.reload();
                                    table2.ajax.reload();
                                    table3.ajax.reload();
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
                                table1.ajax.reload();
                                table2.ajax.reload();
                                table3.ajax.reload();
                            });
                        }
                    });
                }
            });
        }
    </script>

    <script>
        function loadForm() {
            let totalText = $('.total').text().replace(/[^\d]/g, ''); // Hapus karakter non-angka
            let total = parseInt(totalText) || 0; // fallback ke 0 jika kosong

            $('#total').val(total);
            $('#total_item').val($('.total_item').text());

            $.get(`{{ url('admin/pembeliandetail') }}/${total}`)
                .done(response => {
                    $('#totalrp').val('Rp. ' + response.totalrp);
                    $('#bayarrp').val('Rp. ' + response.bayarrp);
                    $('#bayar').val(response.bayar);
                    $('.tampil-bayar').text('Rp. ' + response.bayarrp);
                    if (total != 0) {

                        $('.tampil-terbilang').text(response.terbilang);
                    }
                })
                .fail(errors => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: 'Tidak dapat menampilkan data',
                        showConfirmButton: false,
                        timer: 3000
                    });
                });
        }
    </script>

    <script>
        // Fungsi untuk mencetak faktur
        function cetakFaktur(url) {
            const pembelianId = $('#pembelian_id').val();
            // Anda bisa menggunakan window.print() atau mengarahkan ke URL faktur yang sudah di-generate
            window.open(`{{ url('admin/pembelian/faktur') }}/${pembelianId}`, '_blank');
        }
    </script>
@endpush
