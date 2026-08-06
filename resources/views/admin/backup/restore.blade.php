<!-- Modal -->
<div class="modal fade" id="restoreModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content rounded-3 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="restoreModalLabel">
                    <i class="fas fa-upload"></i> Restore Database
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>Pastikan file yang diupload adalah file <strong>.sql</strong> backup yang valid.</span>
                </div>

                <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoadingRestore()">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="sql_file" class="form-label fw-bold">Pilih File SQL</label>
                        <input type="file" class="form-control" id="sql_file" name="sql_file" accept=".sql" required>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-warning shadow">
                            <i class="fas fa-upload"></i> Restore
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
