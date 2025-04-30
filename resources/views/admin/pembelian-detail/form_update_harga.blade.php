<!-- Modal Edit Harga -->
<div class="modal fade" id="modal-edit-harga" tabindex="-1" role="dialog" aria-labelledby="modalLabelHarga"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-edit-harga">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Harga Produk</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="harga-id">
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="text" onkeyup="format_uang(this)" class="form-control" name="harga"
                            id="harga-value" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
