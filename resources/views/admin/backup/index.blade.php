@extends('layouts.app')

@section('title', 'Backup & Restore Database')

@section('content')
    <div class="container">
        <h4 class="mb-4">Backup & Restore Database</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('backup.create') }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-download"></i> Buat Backup
            </button>
        </form>

        <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="form-group">
                <label for="sql_file">Upload File SQL:</label>
                <input type="file" name="sql_file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning mt-2">
                <i class="fas fa-upload"></i> Restore Database
            </button>
        </form>

        <h5>Daftar File Backup:</h5>
        <ul class="list-group">
            @forelse($files as $file)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ basename($file) }}
                    <div class="btn-group">
                        <a href="{{ route('backup.download', basename($file)) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-download"></i> Download
                        </a>
                        <form action="{{ route('backup.delete', basename($file)) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-muted">Belum ada file backup.</li>
            @endforelse

        </ul>
    </div>
@endsection
