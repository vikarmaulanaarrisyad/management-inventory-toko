<x-modal class="modal-detail" data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-md-12">
            <x-table class="penjualan-detail">
                <x-slot name="thead">
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </x-slot>
            </x-table>
        </div>
    </div>
</x-modal>
