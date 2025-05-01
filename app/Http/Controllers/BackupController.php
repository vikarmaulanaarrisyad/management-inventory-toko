<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index()
    {
        $files = Storage::files('backup');
        return view('admin.backup.index', compact('files'));
    }

    public function create()
    {
        $fileName = 'backup-' . now()->format('Ymd_His') . '.sql';
        $path = storage_path("app/backup/{$fileName}");

        $db = config('database.connections.mysql');
        $command = "mysqldump --user={$db['username']} --password={$db['password']} --host={$db['host']} {$db['database']} > {$path}";

        exec($command, $output, $result);

        return back()->with($result === 0 ? 'success' : 'error', $result === 0 ? 'Backup berhasil' : 'Backup gagal');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimes:sql',
        ]);

        $path = $request->file('sql_file')->storeAs('backup', 'restore-temp.sql');
        $fullPath = storage_path("app/{$path}");

        $db = config('database.connections.mysql');
        $command = "mysql --user={$db['username']} --password={$db['password']} --host={$db['host']} {$db['database']} < {$fullPath}";

        exec($command, $output, $result);

        return back()->with($result === 0 ? 'success' : 'error', $result === 0 ? 'Restore berhasil' : 'Restore gagal');
    }
}
