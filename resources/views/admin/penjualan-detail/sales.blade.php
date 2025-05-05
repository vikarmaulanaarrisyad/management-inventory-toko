@push('css')
    <style>
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }
    </style>
@endpush

<x-modal class="modal-sales" data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <x-table class="table-sales">
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Aksi</th>
                        <th>Nama Sales</th>
                        <th>Username</th>
                        <th>Email</th>
                    </x-slot>
                </x-table>
            </div>
        </div>
    </div>
</x-modal>
