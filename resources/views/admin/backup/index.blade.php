@extends('layouts.app')

@section('title', 'Backup & Restore Database')

@section('subtitle', 'Backup & Restore Database')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">@yield('subtitle')</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-12">
            


            <x-card>
                <x-slot name="header">
                    <div class="d-flex flex-wrap justify-content-start">
                        <form action="{{ route('backup.create') }}" method="POST" class="d-inline" onsubmit="showLoadingBackup()">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary mb-2 mr-2">
                                <i class="fas fa-download"></i> Buat Backup
                            </button>
                        </form>

                        <button onclick="confirmRestore()" type="button" class="btn btn-warning btn-sm mb-2 mr-2">
                            <i class="fas fa-upload"></i> Restore Database
                        </button>
                    </div>
                </x-slot>

                <x-table>
                    <x-slot name="thead">
                        <th width="5%" class="text-center">No</th>
                        <th>Nama File</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </x-slot>
                    
                    @forelse($files as $index => $file)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ basename($file) }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('backup.download', basename($file)) }}" class="btn btn-sm btn-success" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('backup.delete', basename($file)) }}" method="POST" class="d-inline" onsubmit="confirmDelete(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada file backup.</td>
                        </tr>
                    @endforelse
                </x-table>
            </x-card>
        </div>
    </div>

    @include('admin.backup.restore')
@endsection

@push('scripts')
    <script>
        function confirmRestore() {
            $('#restoreModal').modal('show');
        }

        function showLoadingBackup() {
            Swal.fire({
                title: 'Membuat Backup...',
                text: 'Mohon tunggu sebentar, sedang mencadangkan database.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function showLoadingRestore() {
            $('#restoreModal').modal('hide'); // Tutup modal saat proses dimulai
            Swal.fire({
                title: 'Merestore Database...',
                text: 'Mohon tunggu sebentar, proses ini mungkin membutuhkan waktu.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function confirmDelete(event, form) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "File backup yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: true
            });
        @endif
    </script>
@endpush
