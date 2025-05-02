@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Transaksi Penjualan</li>
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

        .table-penjualan tbody tr:last-child {
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
                    <div class="form-row">
                        <!-- Nama Produk -->
                        <div class="form-group col-md-6 d-flex align-items-center">
                            <label for="kode_produk" class="col-4 col-form-label">Nama Produk</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="hidden" name="penjualan_id" id="penjualan_id"
                                        value="{{ $penjualan->id }}">
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

                        <!-- Nama Toko -->
                        <div class="form-group col-md-6 d-flex align-items-center">
                            <label for="nama_toko" class="col-4 col-form-label">Nama Toko</label>
                            <div class="col-8">
                                <div class="input-group">
                                    <input id="nama_toko" class="form-control" type="text" onclick="tampilCustomer()">
                                    <div class="input-group-append">
                                        <button onclick="tampilCustomer()" class="btn btn-info btn-flat" type="button">
                                            <i class="fas fa-arrow-right"></i>
                                        </button>
                                        <button onclick="tambahCustomer('{{ route('customer.store') }}')"
                                            class="btn btn-info btn-flat" type="button">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button onclick="resetCustomer()" class="btn btn-info btn-flat" type="button">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

                {{-- TABEL PRODUK --}}
                <div class="table-responsive">
                    <x-table class="table-penjualan">
                        <x-slot name="thead">
                            <th width="10%">No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Stok</th>
                            <th width="11%">Harga Jual</th>
                            <th width="11%">Jumlah</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </x-slot>
                    </x-table>
                </div>


                <div class="row mt-4">
                    {{-- Total dan Terbilang --}}
                    <div class="col-12 col-md-6">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>

                    {{-- Form Pembayaran --}}
                    <div class="col-12 col-md-6">
                        <form action="{{ route('penjualan.store') }}" class="form-penjualan" method="post">
                            @csrf
                            <input type="hidden" name="penjualan_id" value="{{ $penjualan->id }}">
                            <input type="hidden" name="customer_id" id="customer_id">
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

    @include('admin.penjualan-detail.form_produk')
    @include('admin.penjualan-detail.form_customer')
    @include('admin.penjualan-detail.customer')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let table1, table2, table3;
        let modal = '#modal-form';
        let button = '#submitBtn';
        let modalCustomer = '.modal-customer';
        let modalTambahCustomer = '.modal-tambah-customer';

        $(function() {
            $('#spinner-border').hide();
            $('#nama_produk').focus();
            $('body').addClass('sidebar-collapse');
            $('#nama_toko').prop('disabled', true);
            $('.btn-simpan').prop('disabled', false);
        });

        $(document).on('input', '.quantity', function() {
            let id = $(this).data('id');
            let quantity = parseInt($(this).val());

            // Ambil harga dari elemen input yang sesuai
            let harga = parseInt($(this).closest('tr').find('.harga').val().replace(/\D/g, ''));

            // Validasi jumlah quantity
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

            // Update ke server
            let updateUrl = `{{ route('penjualandetail.update', ':id') }}`.replace(':id', id);

            $.post(updateUrl, {
                    _method: 'PUT',
                    _token: '{{ csrf_token() }}',
                    quantity,
                    harga, // Kirim harga bersama dengan quantity
                })
                .done(response => {
                    // Setelah sukses, reload DataTable
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
    </script>

    <script>
        $('.btn-simpan').on('click', function(e) {
            e.preventDefault(); // cegah submit biasa

            Swal.fire({
                title: 'Konfirmasi Transaksi',
                text: "Apakah Anda yakin ingin menyimpan transaksi ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form via AJAX
                    let form = $('.form-penjualan');

                    $.ajax({
                        url: form.attr('action'),
                        method: form.attr('method'),
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Transaksi berhasil disimpan!'
                            }).then(() => {
                                window.location.href =
                                    '{{ route('penjualan.index') }}'; // Redirect ke halaman index
                            });
                        },
                        error: function(xhr) {
                            $('.btn-simpan').prop('disabled', false);
                            let message = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: message
                            });
                        }
                    });
                }
            });
        });
    </script>

    <script>
        table1 = $('.table-penjualan').DataTable({
            processing: false,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            pageLength: 30,
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('penjualandetail.data', $penjualan->id) }}',
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
                    data: 'stok',
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
                url: '{{ route('penjualandetail.produk') }}',
            },
            columns: [{
                    data: 'aksi',
                    sortable: false,
                    searchable: false
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

            ],
        });

        table3 = $('.table-customer').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('penjualandetail.customer') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'aksi',
                    sortable: false,
                    searchable: false
                },
                {
                    data: 'nama_toko',
                },
                {
                    data: 'nama',
                },
                {
                    data: 'alamat',
                },
                {
                    data: 'nomorhp',
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
            $.post('{{ route('penjualandetail.store') }}', $('.form-produk').serialize())
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

            $.get(`{{ url('penjualandetail') }}/${total}`)
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

        function tampilCustomer(title = 'Pilih Customer') {
            $(modalCustomer).modal('show');
            $(`${modalCustomer} .modal-title`).text(title);
        }

        function tambahCustomer(url, title = 'Form Tambah Customer Baru') {
            $(modalTambahCustomer).modal('show');
            $(`${modalTambahCustomer} .modal-title`).text(title);
            $(`${modalTambahCustomer} form`).attr('action', url);
            $(`${modalTambahCustomer} [name=_method]`).val('POST');
            $('#spinner-border').hide();

            $(button).show();
            $(button).prop('disabled', false);

            resetForm(`${modal} form`);
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

        function resetCustomer() {
            $('#customer_id').val('');
            $('#nama_toko').val('');
            $('#diterima').val(0).focus().select();
            hideCustomer();
        }

        function pilihCustomer(id, kode) {
            $('#customer_id').val(id);
            $('#nama_toko').val(kode);
            $('#diterima').val(0).focus().select();
            hideCustomer();
        }

        function hideCustomer() {
            $(modalCustomer).modal('hide');
        }
    </script>

    <script>
        // Fungsi untuk mencetak faktur
        function cetakFaktur(url) {
            const penjualanId = $('#penjualan_id').val();
            // Anda bisa menggunakan window.print() atau mengarahkan ke URL faktur yang sudah di-generate
            window.open(`{{ url('admin/penjualan/faktur') }}/${penjualanId}`, '_blank');
        }

        $(document).on('input', '.harga', function() {
            updateHarga(this);
            table1.ajax.reload();
        });
    </script>

    <script>
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        const debouncedUpdateHarga = debounce(function(el) {
            let id = $(el).data('id');
            let harga = $(el).val().replace(/\D/g, '');

            $.ajax({
                url: '{{ route('penjualandetail.update_harga') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    harga: harga
                },
                success: function(res, textStatus, xhr) {
                    if (xhr.status == 201) {
                        table1.ajax.reload();
                        table2.ajax.reload();
                        table3.ajax.reload();
                    }
                },
                error: function(xhr) {
                    table1.ajax.reload();
                    table2.ajax.reload();
                    table3.ajax.reload();
                }
            });
        }, 500); // hanya eksekusi setelah 500ms user berhenti mengetik

        function updateHarga(el) {
            debouncedUpdateHarga(el);
        }
    </script>
@endpush
