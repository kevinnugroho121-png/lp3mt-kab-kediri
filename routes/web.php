<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Tambahan DB Facade
use Illuminate\Http\Request;

// Import Model
use App\Models\Lembaga;
use App\Models\Guru;
use App\Models\Kecamatan; // <-- Tambahan Model Kecamatan untuk Dashboard
use App\Models\Desa; // <-- Tambahan Model Desa

// Import Controller
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\LembagaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\UserController;

// ===============================
// 1. HALAMAN DEPAN (LANDING PAGE)
// ===============================
Route::get('/', function () {
    // 1. Hitung Total Lembaga (Real Data)
    $lembagaTPQ = Lembaga::where('jenis_lembaga', 'TPQ')->count();
    $lembagaMadin = Lembaga::where('jenis_lembaga', 'MADIN')->count();
    $lembagaPonpes = Lembaga::where('jenis_lembaga', 'PONPES')->count();

    // 2. Hitung Total Guru (Real Data)
    // Menggunakan kolom jenis_guru sesuai filter di GuruController
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
    
    // Default: Ambil SEMUA data (Untuk Admin Pusat & Verifikator)
    $queryLembaga = Lembaga::query();
    $queryGuru = Guru::query();
    $wilayahKerja = 'Seluruh Kabupaten Kediri';

    // Jika yang login Korcam, filter datanya HANYA untuk kecamatannya saja
    if ($user->role == 'korcam') {
        $queryLembaga->where('kecamatan_id', $user->kecamatan_id);
        
        // Filter guru berdasarkan relasi ke tabel lembaga -> kecamatan_id
        $queryGuru->whereHas('lembaga', function($q) use ($user) {
            $q->where('kecamatan_id', $user->kecamatan_id);
        });
        
        $kecamatan = Kecamatan::find($user->kecamatan_id);
        $wilayahKerja = 'Kecamatan ' . ($kecamatan ? $kecamatan->nama_kecamatan : 'Tidak Diketahui');
    }

    // --- 1. DATA LEMBAGA ---
    $lembagaTPQ = (clone $queryLembaga)->where('jenis_lembaga', 'TPQ')->count();
    $lembagaMadin = (clone $queryLembaga)->where('jenis_lembaga', 'MADIN')->count();
    $lembagaPonpes = (clone $queryLembaga)->where('jenis_lembaga', 'PONPES')->count();

    // --- 2. DATA SANTRI & GURU (TOTAL) ---
    $totalSantri = (clone $queryLembaga)->sum('jumlah_santri');
    $totalGuru = (clone $queryGuru)->count();

    // --- 3. DATA STATUS GURU ---
    $guruPNS = (clone $queryGuru)->where('status_kepegawaian', 'PNS')->count();
    $guruP3KFull = (clone $queryGuru)->where('status_kepegawaian', 'PPPK')->count();
    $guruP3KParuh = 0; // Sesuaikan jika ada status ini di database-mu
    $guruInpassing = (clone $queryGuru)->where('status_sertifikasi', 'Inpassing')->count();
    
    // Non-ASN = Total Guru dikurangi ASN & Inpassing
    $guruNonASN = $totalGuru - ($guruPNS + $guruP3KFull + $guruP3KParuh + $guruInpassing);
    if($guruNonASN < 0) $guruNonASN = 0;

    // --- 4. DATA INSENTIF ---
    // Target Insentif (Sebagai contoh: Korcam target 100, Admin target 1550)
    $targetInsentif = ($user->role == 'korcam') ? 100 : 1550; 
    
    $sudahTerimaInsentif = (clone $queryGuru)->where('penerima_insentif', 1)->count();
    $belumTerimaInsentif = $targetInsentif - $sudahTerimaInsentif;
    if($belumTerimaInsentif < 0) $belumTerimaInsentif = 0;
    
    $persenSudah = ($targetInsentif > 0) ? round(($sudahTerimaInsentif / $targetInsentif) * 100) : 0;
    $persenBelum = ($targetInsentif > 0) ? round(($belumTerimaInsentif / $targetInsentif) * 100) : 0;

    // --- 5. DATA CHART SEBARAN PER KECAMATAN ---
    $kecamatanLabels = [];
    $dataTpqSebaran = [];
    $dataMadinSebaran = [];
    $dataTotalSebaran = [];

    // Tentukan kecamatan yang akan dilooping (Semua atau Cuma 1 untuk Korcam)
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

    // --- 6. DATA CHART SEBARAN GURU PER DESA (BAWAH) ---
    $sebaranGuruPerKecamatan = [];
    foreach ($kecamatans as $kec) {
        $sebaranGuruPerKecamatan[$kec->id] = [
            'nama_kecamatan' => $kec->nama_kecamatan,
            'labels' => [], 'tpq' => [], 'madin' => [], 'total' => []
        ];
    }

    $desaList = Desa::whereIn('kecamatan_id', $kecamatans->pluck('id'))->orderBy('nama_desa')->get();
    
    // Perhitungan cepat menggunakan Join dan GroupBy
    $guruCounts = DB::table('gurus')
        ->join('lembagas', 'gurus.lembaga_id', '=', 'lembagas.id')
        ->select('lembagas.desa_id', 'gurus.jenis_guru', DB::raw('count(*) as total'))
        ->groupBy('lembagas.desa_id', 'gurus.jenis_guru')
        ->get();
        
    $mappedCounts = [];
    foreach ($guruCounts as $gc) {
        $mappedCounts[$gc->desa_id][$gc->jenis_guru] = $gc->total;
    }

    // Memasukkan hasil hitungan ke dalam format array untuk JavaScript
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
        'kecamatans', 'sebaranGuruPerKecamatan' // <-- Inject data Dropdown & Grafik Bawah
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// ===============================
// 3. GRUP ADMIN (DATA & SYSTEM)
// ===============================
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // ===== MASTER DATA WILAYAH =====
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('desa', DesaController::class);

    // ===== MASTER DATA LEMBAGA =====
    
    // [KHUSUS] Route Verifikasi Dokumen Lembaga
    Route::get('lembaga/{lembaga}/verifikasi', [LembagaController::class, 'verifikasi'])->name('lembaga.verifikasi');
    Route::post('lembaga/{lembaga}/verifikasi', [LembagaController::class, 'prosesVerifikasi'])->name('lembaga.proses_verifikasi');

    // Route Resource Standar Lembaga
    Route::post('/lembaga/import', [App\Http\Controllers\LembagaController::class, 'import'])->name('lembaga.import');
    Route::resource('lembaga', LembagaController::class);

    // ===== MASTER DATA GURU =====
    
    // 1. Route Menu Spesifik (Wajib DI ATAS resource 'guru')
    Route::post('/guru/import', [GuruController::class, 'import'])->name('guru.import');
    Route::get('guru/madin', [GuruController::class, 'indexMadin'])->name('guru.madin');
    Route::get('guru/tpq', [GuruController::class, 'indexTpq'])->name('guru.tpq');
    Route::get('guru/ponpes', [GuruController::class, 'indexPonpes'])->name('guru.ponpes');
    Route::get('guru/insentif', [GuruController::class, 'indexInsentif'])->name('guru.insentif');

    // 2. [TAMBAHAN PENTING] Route Verifikasi Guru
    Route::get('guru/{id}/verifikasi', [GuruController::class, 'verifikasi'])->name('guru.verifikasi');
    Route::post('guru/{id}/verifikasi', [GuruController::class, 'prosesVerifikasi'])->name('guru.proses_verifikasi');

    // 3. Route Resource Standar Guru (CRUD)
    Route::resource('guru', GuruController::class);

    // ===== MANAJEMEN USER =====
    
    // Route khusus Cek Posisi Korcam (AJAX) wajib DI ATAS resource 'user'
    Route::get('/user/check-korcam-availability', [UserController::class, 'checkKorcamAvailability'])
        ->name('user.check-korcam');

    // Route Resource Standar User
    Route::resource('user', UserController::class);

});

// ===============================
// 4. ROUTE KHUSUS LOGOUT
// ===============================
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/'); 
})->name('logout')->middleware('auth');

// ===============================
// 5. INCLUDE AUTH BAWAAN
// ===============================
require __DIR__.'/auth.php';