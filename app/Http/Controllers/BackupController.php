<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $files = Storage::disk('public')->files('backup');

        return view('admin.backup.index', compact('files'));
    }

    public function create()
    {
        $fileName = 'backup-' . now()->format('Ymd_His') . '.sql';
        $backupDir = storage_path('app/public/backup');

        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $path = "{$backupDir}/{$fileName}";
        $db = config('database.connections.mysql');
        $mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe'; // ← sesuaikan lokasi

        $command = "\"{$mysqldump}\" --user={$db['username']} --password=\"{$db['password']}\" --host={$db['host']} {$db['database']} > \"{$path}\"";

        exec($command, $output, $result);

        if ($result !== 0) {
            logger()->error('Backup gagal', ['command' => $command, 'output' => $output, 'result' => $result]);
        }

        return back()->with($result === 0 ? 'success' : 'error', $result === 0 ? "Backup berhasil: {$fileName}" : 'Backup gagal, cek log.');
    }


    public function restore(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimes:sql',
        ]);

        // Simpan file SQL ke folder public/backup dengan nama sementara
        $file = $request->file('sql_file');
        $filename = 'restore-temp.sql';
        $path = $file->storeAs('public/backup', $filename);

        $fullPath = storage_path('app/' . $path);

        // Ambil konfigurasi database dari config
        $db = config('database.connections.mysql');

        // Perintah restore MySQL
        $command = "mysql --user={$db['username']} --password=\"{$db['password']}\" --host={$db['host']} {$db['database']} < \"{$fullPath}\"";

        exec($command, $output, $result);

        // Hapus file sementara setelah restore
        Storage::delete($path);

        // Beri notifikasi ke user
        return back()->with(
            $result === 0 ? 'success' : 'error',
            $result === 0 ? 'Restore berhasil dilakukan.' : 'Restore gagal. Silakan cek file SQL.'
        );
    }

    public function download($filename)
    {
        $path = "backup/{$filename}";

        if (!Storage::disk('public')->exists($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($path);
    }


    public function delete($filename)
    {
        $path = "backup/{$filename}";

        if (!Storage::disk('public')->exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        Storage::disk('public')->delete($path);
        return back()->with('success', 'File backup berhasil dihapus.');
    }
}
