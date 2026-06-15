<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BackupController extends Controller
{
    public function download()
    {
        try {
            // 1. Catat ke CCTV Log Aktivitas (Biar elegan pas didemokan)
            DB::table('activity_logs')->insert([
                'user_id'    => Auth::id(),
                'nama_user'  => Auth::user()->name,
                'aksi'       => 'Backup Database Sistem (.zip)',
                'target'     => 'Database Keseluruhan',
                'created_at' => now(),
            ]);

            // 2. Perintah sakti untuk mengeksekusi backup khusus database
            Artisan::call('backup:run', ['--only-db' => true]);

            // 3. Mencari file zip backup yang baru saja dibuat di folder storage
            $folderName = config('backup.backup.name'); // Biasanya nama folder sama dengan APP_NAME di .env
            $files = Storage::disk('local')->files($folderName);

            if (empty($files)) {
                return back()->with('error', 'File backup gagal ter-generate.');
            }

            // Ambil file yang paling terakhir dibuat (terbaru)
            $latestBackup = collect($files)->sortByDesc(function ($file) {
                return Storage::disk('local')->lastModified($file);
            })->first();

            // 4. Paksa browser untuk men-download file tersebut
            return Storage::disk('local')->download($latestBackup);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses backup. Pastikan mysqldump tersedia. Error: ' . $e->getMessage());
        }
    }
}