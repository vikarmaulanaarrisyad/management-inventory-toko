<x-modal data-backdrop="static" data-keyboard="false" size="modal-md">
    <x-slot name="title">
        Tambah
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                <input id="name" class="form-control" type="text" name="name" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="username">Username <span class="text-danger">*</span></label>
                <input id="username" class="form-control" type="text" name="username" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input id="email" class="form-control" type="text" name="email" autocomplete="off">
            </div>
        </div>
    </div>
    {{--  <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="form-group">
                <label for="password">Password <span class="text-danger">*</span></label>
                <input id="password" class="form-control" type="password" name="password" autocomplete="off">
            </div>
        </div>
    </div>  --}}

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-outline-primary" id="submitBtn">
            <span id="spinner-border" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <i class="fas fa-save mr-1"></i>
            Simpan</button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>
