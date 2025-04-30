<x-modal data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <x-table class="table-produk">
                    <x-slot name="thead">
                        {{--  <th>No</th>  --}}
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </div>
        </div>
    </div>
</x-modal>
