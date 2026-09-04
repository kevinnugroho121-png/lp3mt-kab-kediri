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
        // [KEAMANAN] Cegah peran selain Admin mendownload seluruh isi database
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak! Hanya Admin yang berhak mengunduh cadangan database.');
        }

        try {
            // 1. Eksekusi backup khusus database
            Artisan::call('backup:run', ['--only-db' => true]);

            // 2. Mencari file zip backup yang baru saja dibuat di folder storage
            $folderName = config('backup.backup.name');
            $files = Storage::disk('local')->files($folderName);

            if (empty($files)) {
                return back()->with('error', 'File backup gagal ter-generate.');
            }

            // Ambil file yang paling terakhir dibuat (terbaru)
            $latestBackup = collect($files)->sortByDesc(function ($file) {
                return Storage::disk('local')->lastModified($file);
            })->first();

            $namaFileZip = basename($latestBackup);

            // 3. [CCTV LOG] Catat HANYA SETELAH file backup 100% sukses terbuat
            DB::table('activity_logs')->insert([
                'user_id'    => Auth::id(),
                'nama_user'  => Auth::user()->name,
                'aksi'       => 'Download Rekap Excel', // Atau gunakan 'Backup Database Sistem (.zip)'
                'target'     => "Cadangan Database ({$namaFileZip})",
                'created_at' => now(),
            ]);

            // 4. Paksa browser untuk men-download file tersebut
            return Storage::disk('local')->download($latestBackup);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses backup. Pastikan mysqldump tersedia. Error: ' . $e->getMessage());
        }
    }
}