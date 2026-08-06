@extends('layouts.app')

@section('title', 'Tambah Stok Opname')

@section('subtitle', 'Tambah Stok Opname')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('stok-opname.index') }}">Stok Opname</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                <form action="{{ route('stok-opname.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="produk_id">Produk <span class="text-danger">*</span></label>
                            <select name="produk_id" id="produk_id" class="form-control select2" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach(\App\Models\Produk::orderBy('nama_produk')->get() as $produk)
                                    <option value="{{ $produk->id }}" data-stok="{{ $produk->stok }}">{{ $produk->nama_produk }} (Sistem: {{ $produk->stok }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stok_sistem">Stok Sistem</label>
                                    <input type="number" name="stok_sistem" id="stok_sistem" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="stok_fisik">Stok Fisik (Aktual) <span class="text-danger">*</span></label>
                                    <input type="number" name="stok_fisik" id="stok_fisik" class="form-control" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="selisih">Selisih</label>
                                    <input type="number" name="selisih" id="selisih" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan / Alasan Penyesuaian</label>
                            <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Misal: Barang rusak, hilang, dsb"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Penyesuaian</button>
                        <a href="{{ route('stok-opname.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 jika digunakan di template ini
            if ($.fn.select2) {
                $('.select2').select2({
                    theme: 'bootstrap4'
                });
            }

            $('#produk_id').on('change', function() {
                let stok_sistem = $(this).find(':selected').data('stok');
                if(stok_sistem !== undefined) {
                    $('#stok_sistem').val(stok_sistem);
                    calculateSelisih();
                } else {
                    $('#stok_sistem').val('');
                    $('#selisih').val('');
                }
            });

            $('#stok_fisik').on('input', function() {
                calculateSelisih();
            });

            function calculateSelisih() {
                let sistem = parseInt($('#stok_sistem').val()) || 0;
                let fisik = parseInt($('#stok_fisik').val()) || 0;
                
                if ($('#stok_sistem').val() !== '') {
                    $('#selisih').val(fisik - sistem);
                }
            }
        });
    </script>
@endpush
