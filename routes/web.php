<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Import Model
use App\Models\Lembaga;
use App\Models\Guru;
use App\Models\Kecamatan;
use App\Models\Desa;

// Import Controller
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\LembagaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DokumentasiController;

// ===============================
// 1. HALAMAN DEPAN (LANDING PAGE)
// ===============================
Route::get('/', function () {
    $lembagaTPQ = Lembaga::where('jenis_lembaga', 'TPQ')->count();
    $lembagaMadin = Lembaga::where('jenis_lembaga', 'MADIN')->count();
    $lembagaPonpes = Lembaga::where('jenis_lembaga', 'PONPES')->count();

    $guruTPQ = Guru::where('jenis_guru', 'TPQ')->count();
    $guruMadin = Guru::where('jenis_guru', 'MADIN')->count();
    $guruPonpes = Guru::where('jenis_guru', 'PONPES')->count();

    return view('welcome', compact(
        'lembagaTPQ', 'lembagaMadin', 'lembagaPonpes',
        'guruTPQ', 'guruMadin', 'guruPonpes'
    ));
});

// ===============================
// 2. DASHBOARD UTAMA (ROLE BASED)
// ===============================
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    $queryLembaga = Lembaga::query();
    $queryGuru = Guru::query();
    $wilayahKerja = 'Seluruh Kabupaten Kediri';

    if ($user->role == 'korcam') {
        $queryLembaga->where('kecamatan_id', $user->kecamatan_id);
        $queryGuru->whereHas('lembaga', function($q) use ($user) {
            $q->where('kecamatan_id', $user->kecamatan_id);
        });
        
        $kecamatan = Kecamatan::find($user->kecamatan_id);
        $wilayahKerja = 'Kecamatan ' . ($kecamatan ? $kecamatan->nama_kecamatan : 'Tidak Diketahui');
    }

    $lembagaTPQ = (clone $queryLembaga)->where('jenis_lembaga', 'TPQ')->count();
    $lembagaMadin = (clone $queryLembaga)->where('jenis_lembaga', 'MADIN')->count();
    $lembagaPonpes = (clone $queryLembaga)->where('jenis_lembaga', 'PONPES')->count();

    $totalSantri = (clone $queryLembaga)->sum('jumlah_santri');
    $totalGuru = (clone $queryGuru)->count();

    $guruPNS = (clone $queryGuru)->where('status_kepegawaian', 'PNS')->count();
    $guruP3KFull = (clone $queryGuru)->where('status_kepegawaian', 'PPPK')->count();
    $guruP3KParuh = 0; 
    $guruInpassing = (clone $queryGuru)->where('status_sertifikasi', 'Inpassing')->count();
    
    $guruNonASN = $totalGuru - ($guruPNS + $guruP3KFull + $guruP3KParuh + $guruInpassing);
    if($guruNonASN < 0) $guruNonASN = 0;

    // ========================================================
    // [BARU] HITUNG PROGRESS PEMBERKASAN DOKUMEN (LEMBAGA & GURU)
    // ========================================================
    // 1. Progress Lembaga
    $totalLembagaBerkas = (clone $queryLembaga)->count();
    $lembagaLengkap = (clone $queryLembaga)
        ->whereNotNull('file_ijop')->whereNotNull('file_super')->whereNotNull('file_skam')
        ->where('status_ijop', 'Disetujui')->where('status_super', 'Disetujui')->where('status_skam', 'Disetujui')
        ->count();
    $persenLembaga = $totalLembagaBerkas > 0 ? round(($lembagaLengkap / $totalLembagaBerkas) * 100) : 0;

    // 2. Progress Guru
    $totalGuruBerkas = (clone $queryGuru)->count();
    $guruLengkap = (clone $queryGuru)
        ->whereNotNull('file_ktp')->whereNotNull('file_kk')->whereNotNull('file_bukurekening')
        ->where('status_ktp', 'Disetujui')->where('status_kk', 'Disetujui')->where('status_bukurekening', 'Disetujui')
        ->count();
    $persenGuru = $totalGuruBerkas > 0 ? round(($guruLengkap / $totalGuruBerkas) * 100) : 0;

    // Target Insentif Real Database
    if ($user->role == 'korcam') {
        $targetInsentif = \App\Models\Kecamatan::where('id', $user->kecamatan_id)->value('kuota_insentif') ?? 0;
    } else {
        $targetInsentif = \App\Models\Kecamatan::sum('kuota_insentif');
    }
    
    $sudahTerimaInsentif = (clone $queryGuru)->where('penerima_insentif', 1)->count();
    $belumTerimaInsentif = $targetInsentif - $sudahTerimaInsentif;
    if($belumTerimaInsentif < 0) $belumTerimaInsentif = 0;
    
    $persenSudah = ($targetInsentif > 0) ? round(($sudahTerimaInsentif / $targetInsentif) * 100) : 0;
    $persenBelum = ($targetInsentif > 0) ? round(($belumTerimaInsentif / $targetInsentif) * 100) : 0;

    $kecamatanLabels = [];
    $dataTpqSebaran = [];
    $dataMadinSebaran = [];
    $dataTotalSebaran = [];

    $kecamatans = ($user->role == 'korcam') 
                  ? Kecamatan::where('id', $user->kecamatan_id)->get() 
                  : Kecamatan::orderBy('nama_kecamatan')->get();

    foreach ($kecamatans as $kec) {
        $kecamatanLabels[] = $kec->nama_kecamatan;
        
        $jmlTpq = Lembaga::where('kecamatan_id', $kec->id)->where('jenis_lembaga', 'TPQ')->count();
        $jmlMadin = Lembaga::where('kecamatan_id', $kec->id)->where('jenis_lembaga', 'MADIN')->count();
        
        $dataTpqSebaran[] = $jmlTpq;
        $dataMadinSebaran[] = $jmlMadin;
        $dataTotalSebaran[] = $jmlTpq + $jmlMadin;
    }

    $sebaranGuruPerKecamatan = [];
    foreach ($kecamatans as $kec) {
        $sebaranGuruPerKecamatan[$kec->id] = [
            'nama_kecamatan' => $kec->nama_kecamatan,
            'labels' => [], 'tpq' => [], 'madin' => [], 'total' => []
        ];
    }

    $desaList = Desa::whereIn('kecamatan_id', $kecamatans->pluck('id'))->orderBy('nama_desa')->get();
    
    $guruCounts = DB::table('gurus')
        ->join('lembagas', 'gurus.lembaga_id', '=', 'lembagas.id')
        ->select('lembagas.desa_id', 'gurus.jenis_guru', DB::raw('count(*) as total'))
        ->groupBy('lembagas.desa_id', 'gurus.jenis_guru')
        ->get();
        
    $mappedCounts = [];
    foreach ($guruCounts as $gc) {
        $mappedCounts[$gc->desa_id][$gc->jenis_guru] = $gc->total;
    }

    foreach ($desaList as $desa) {
        $tpq = $mappedCounts[$desa->id]['TPQ'] ?? 0;
        $madin = $mappedCounts[$desa->id]['MADIN'] ?? 0;
        
        $sebaranGuruPerKecamatan[$desa->kecamatan_id]['labels'][] = $desa->nama_desa;
        $sebaranGuruPerKecamatan[$desa->kecamatan_id]['tpq'][] = $tpq;
        $sebaranGuruPerKecamatan[$desa->kecamatan_id]['madin'][] = $madin;
        $sebaranGuruPerKecamatan[$desa->kecamatan_id]['total'][] = $tpq + $madin;
    }

    return view('dashboard', compact(
        'wilayahKerja', 'lembagaTPQ', 'lembagaMadin', 'lembagaPonpes',
        'totalSantri', 'totalGuru', 
        'guruPNS', 'guruP3KFull', 'guruP3KParuh', 'guruInpassing', 'guruNonASN',
        'targetInsentif', 'sudahTerimaInsentif', 'belumTerimaInsentif', 'persenSudah', 'persenBelum',
        'kecamatanLabels', 'dataTpqSebaran', 'dataMadinSebaran', 'dataTotalSebaran',
        'kecamatans', 'sebaranGuruPerKecamatan',
        'totalLembagaBerkas', 'lembagaLengkap', 'persenLembaga', // [BARU]
        'totalGuruBerkas', 'guruLengkap', 'persenGuru' // [BARU]
    ));


})->middleware(['auth', 'verified'])->name('dashboard');

// ==============================================================
// 3A. GRUP KHUSUS SUPERADMIN & VERIFIKATOR (KORCAM DILARANG MASUK)
// ==============================================================
Route::middleware(['auth', 'role:admin,verifikator'])->prefix('admin')->group(function () {
    
    // ===== MASTER DATA WILAYAH =====
    Route::resource('kecamatan', KecamatanController::class);
    Route::put('/kecamatan/{id}/update-kuota', [KecamatanController::class, 'updateKuota'])->name('kecamatan.update_kuota');
    Route::resource('desa', DesaController::class);

    // ===== MANAJEMEN USER =====
    Route::get('/user/check-korcam-availability', [UserController::class, 'checkKorcamAvailability'])->name('user.check-korcam');
    Route::post('/user/{id}/reset-device', [UserController::class, 'resetDevice'])->name('user.reset-device');
    Route::post('/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
    Route::resource('user', UserController::class);

    // ===== LOG AKTIVITAS (CCTV SISTEM) =====
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity.log');
    Route::post('/activity-logs/clear', [ActivityLogController::class, 'clear'])->name('activity.log.clear');

    // ===== [BARU] BACKUP DATABASE =====
    Route::post('/backup-database', [\App\Http\Controllers\BackupController::class, 'download'])->name('backup.database');

    // ===== MANAJEMEN LANDING PAGE =====
    Route::resource('dokumentasi', DokumentasiController::class)->only(['index', 'store', 'destroy', 'edit', 'update']);

});

// ==============================================================
// 3B. GRUP UMUM (ADMIN, VERIFIKATOR, & KORCAM BOLEH MASUK)
// ==============================================================
Route::middleware(['auth', 'role:admin,verifikator,korcam'])->prefix('admin')->group(function () {
    
    // ===== MASTER DATA LEMBAGA =====
    Route::get('lembaga/{lembaga}/verifikasi', [LembagaController::class, 'verifikasi'])->name('lembaga.verifikasi');
    Route::post('lembaga/{lembaga}/verifikasi', [LembagaController::class, 'prosesVerifikasi'])->name('lembaga.proses_verifikasi');
    Route::post('/lembaga/import', [LembagaController::class, 'import'])->name('lembaga.import');
    Route::get('/lembaga/export-excel', [LembagaController::class, 'exportExcel'])->name('lembaga.export'); // <--- [TAMBAH BARIS INI]
    Route::resource('lembaga', LembagaController::class);

    // ===== MASTER DATA GURU =====
    Route::post('/guru/import', [GuruController::class, 'import'])->name('guru.import');
    Route::get('guru/madin', [GuruController::class, 'indexMadin'])->name('guru.madin');
    Route::get('guru/tpq', [GuruController::class, 'indexTpq'])->name('guru.tpq');
    Route::get('guru/ponpes', [GuruController::class, 'indexPonpes'])->name('guru.ponpes');
    Route::get('guru/insentif', [GuruController::class, 'indexInsentif'])->name('guru.insentif');
    
    Route::get('guru/{id}/verifikasi', [GuruController::class, 'verifikasi'])->name('guru.verifikasi');
    Route::post('guru/{id}/verifikasi', [GuruController::class, 'prosesVerifikasi'])->name('guru.proses_verifikasi');
    Route::post('/guru/{id}/toggle-insentif', [GuruController::class, 'toggleInsentif'])->name('guru.toggle_insentif');
    // Selipkan rute ini di dalam Grup 3B
    Route::get('/guru/export-excel', [GuruController::class, 'exportExcel'])->name('guru.export');
    Route::resource('guru', GuruController::class);

});

// Rute untuk Halaman Profil & Ganti Password
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===============================
// 5. INCLUDE AUTH BAWAAN
// ===============================
require __DIR__.'/auth.php';