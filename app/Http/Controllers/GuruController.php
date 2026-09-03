<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Lembaga;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <--- [TAMBAH INI UNTUK LOG AKTIVITAS]
use Maatwebsite\Excel\Facades\Excel; // <--- [BARU]
use App\Imports\GuruImport; // <--- [BARU]
use App\Exports\GuruExport;

class GuruController extends Controller
{
    // ==========================================
    // 1. HALAMAN UTAMA & FILTER
    // ==========================================
    public function index(Request $request) { return $this->getDataGuru($request, 'Semua Data Guru', 'ALL'); }
    public function indexMadin(Request $request) { return $this->getDataGuru($request, 'Data Guru Madin', 'MADIN'); }
    public function indexTpq(Request $request) { return $this->getDataGuru($request, 'Data Guru TPQ', 'TPQ'); }
    public function indexPonpes(Request $request) { return $this->getDataGuru($request, 'Data Guru Ponpes', 'PONPES'); }
    public function indexInsentif(Request $request) { return $this->getDataGuru($request, 'Data Insentif', 'INSENTIF'); }

    // LOGIKA PRIVATE UNTUK FILTER
    private function getDataGuru($request, $title, $filterType)
    {
        $user = Auth::user(); 
        $query = Guru::with(['lembaga.kecamatan', 'lembaga.desa']); 

        // [KEAMANAN] KUNCI WILAYAH KORCAM
        if ($user->role == 'korcam') {
            $query->whereHas('lembaga', function($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            });
        }

        // Filter Menu
        if ($filterType == 'INSENTIF') {
            // HANYA NON-ASN YANG BOLEH MASUK SINI! 
            // PNS dan PPPK dilarang keras tampil di Menu Insentif.
            $query->where('status_kepegawaian', 'NON-ASN');
        } elseif (in_array($filterType, ['MADIN', 'TPQ', 'PONPES'])) {
            // [FIXED FASE 2] Kunci data agar murni menampilkan jenis guru yang sesuai kamar menunya!
            $query->where('jenis_guru', $filterType);
        }


        // Filter Wilayah & Search
        if ($user->role != 'korcam' && $request->filled('filter_kecamatan')) {
            $query->whereHas('lembaga', function($q) use ($request) {
                $q->where('kecamatan_id', $request->filter_kecamatan);
            });
        }
        if ($request->filled('filter_desa')) {
            $query->whereHas('lembaga', function($q) use ($request) {
                $q->where('desa_id', $request->filter_desa);
            });
        }

        // [BARU POIN 3] Filter Spesifik Lembaga
        if ($request->filled('filter_lembaga')) {
            $query->where('lembaga_id', $request->filter_lembaga);
        }

        // [BARU POIN 3] Filter Spesifik Insentif (Diajukan / Tidak)
        if ($request->filled('filter_insentif')) {
            if ($request->filter_insentif == '1') {
                $query->where('penerima_insentif', 1);
            } elseif ($request->filter_insentif == '0') {
                $query->where('penerima_insentif', 0);
            }
        }




        // [REVISI] Filter Cerdas (Smart Sort) Dokumen Guru
        if ($request->filled('filter_berkas')) {
            $filterBerkas = $request->filter_berkas;

            if ($filterBerkas == 'kosong') {
                // Cari yang fisiknya benar-benar belum diupload sama sekali
                $query->where(function($q) {
                    $q->whereNull('file_ktp')
                      ->orWhereNull('file_kk')
                      ->orWhereNull('file_bukurekening');
                });
            } elseif ($filterBerkas == 'pending') {
                // Cari yang salah satu statusnya masih Pending
                $query->where(function($q) {
                    $q->where('status_ktp', 'Pending')
                      ->orWhere('status_kk', 'Pending')
                      ->orWhere('status_bukurekening', 'Pending');
                });
            } elseif ($filterBerkas == 'ditolak') {
                // Cari yang salah satu statusnya Ditolak
                $query->where(function($q) {
                    $q->where('status_ktp', 'Ditolak')
                      ->orWhere('status_kk', 'Ditolak')
                      ->orWhere('status_bukurekening', 'Ditolak');
                });
            } elseif ($filterBerkas == 'disetujui') {
                // Cari yang KETIGANYA sudah disetujui dan filenya ada
                $query->whereNotNull('file_ktp')
                      ->whereNotNull('file_kk')
                      ->whereNotNull('file_bukurekening')
                      ->where('status_ktp', 'Disetujui')
                      ->where('status_kk', 'Disetujui')
                      ->where('status_bukurekening', 'Disetujui');
            }
        }


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        // Data Dropdown Filter
        if ($user->role == 'korcam') {
            // Korcam cuma boleh lihat kecamatannya sendiri & desa di kecamatannya
            $data_kecamatan = Kecamatan::where('id', $user->kecamatan_id)->orderBy('nama_kecamatan')->get();
            $data_desa = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_desa')->get();
        } else {
            // Admin/Verifikator boleh lihat semua
            $data_kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();
            $data_desa = Desa::orderBy('nama_desa')->get();
        }

        // Ambil List Lembaga
        $lembagaQuery = Lembaga::orderBy('nama_lembaga');
        if ($filterType != 'ALL' && $filterType != 'INSENTIF') {
            $lembagaQuery->where('jenis_lembaga', $filterType);
        }
        if ($user->role == 'korcam') {
            $lembagaQuery->where('kecamatan_id', $user->kecamatan_id);
        } elseif ($request->filled('filter_kecamatan')) {
            $lembagaQuery->where('kecamatan_id', $request->filter_kecamatan);
        }
        $list_lembaga = $lembagaQuery->get();

        // ========================================================
        // 📊 [BARU - FASE 2] HITUNG KUOTA REAL-TIME UNTUK KORCAM
        // ========================================================
        $kuotaSistem = ['total' => 0, 'terpakai' => 0, 'sisa' => 0];
        
        if ($user->role == 'korcam') {
            $kuotaSistem['total'] = \App\Models\Kecamatan::where('id', $user->kecamatan_id)->value('kuota_insentif') ?? 0;
            
            $kuotaSistem['terpakai'] = Guru::whereHas('lembaga', function($q) use ($user) {
                $q->where('kecamatan_id', $user->kecamatan_id);
            })->where('penerima_insentif', 1)->count();
            
            $kuotaSistem['sisa'] = $kuotaSistem['total'] - $kuotaSistem['terpakai'];
        }

        // ========================================================
        // 🔍 [BARU] FILTER & SORTING SPESIFIK PER-KOLOM (ALA EXCEL)
        // ========================================================
        
        // A. Penangkap Pencarian Spesifik di Kolom Tertentu
        if ($request->filled('col_nama')) $query->where('nama_lengkap', 'like', '%' . $request->col_nama . '%');
        if ($request->filled('col_nik')) $query->where('nik', 'like', '%' . $request->col_nik . '%');
        if ($request->filled('col_status_pegawai')) $query->where('status_kepegawaian', 'like', '%' . $request->col_status_pegawai . '%');
        if ($request->filled('col_alamat')) $query->where('alamat_ktp', 'like', '%' . $request->col_alamat . '%');
        // (Kamu bisa tambahkan kolom lain di sini jika dibutuhkan nanti)

        // B. Penangkap Perintah Sorting (A-Z / Z-A)
        if ($request->filled('sort_col') && $request->filled('sort_dir')) {
            // Daftar kolom yang diizinkan untuk di-sort (Demi keamanan mencegah SQL Injection)
            $allowedSorts = ['nama_lengkap', 'nik', 'status_kepegawaian', 'jenis_guru'];
            
            if (in_array($request->sort_col, $allowedSorts)) {
                $query->orderBy($request->sort_col, $request->sort_dir);
            } else {
                $query->latest(); // Fallback keamanan
            }
        } else {
            $query->latest(); // Default urutan jika tidak ada yang di-klik
        }

        // [DIPERBAIKI] Panggil query paginasi (Hapus 'latest()' karena sudah diatur di atas)
        $gurus = $query->paginate(20)->withQueryString();

        // [DIPERBAIKI] Return view cukup 1x saja
        return view('admin.guru.index', compact('gurus', 'title', 'data_kecamatan', 'data_desa', 'filterType', 'list_lembaga', 'kuotaSistem'));
    }

    // ==========================================
    // 2. CREATE & STORE
    // ==========================================
    public function create(Request $request)
    {
        $user = Auth::user();
        
        // 1. Ambil Parameter 'type' dari URL (Default MADIN)
        $type = $request->query('type', 'MADIN'); 

        // 2. Filter Lembaga Sesuai Type
        $query = Lembaga::where('jenis_lembaga', $type)->orderBy('nama_lembaga');

        if ($user->role == 'korcam') {
            $query->where('kecamatan_id', $user->kecamatan_id);
        }
        $lembagas = $query->get();

        // 3. Data Wilayah
        $semua_kecamatan_kediri = Kecamatan::orderBy('nama_kecamatan')->get();
        $semua_desa_kediri = Desa::orderBy('nama_desa')->get();

        if ($user->role == 'korcam') {
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->orderBy('nama_kecamatan')->get();
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_desa')->get();
        } else {
            $kecamatans = $semua_kecamatan_kediri;
            $desas = $semua_desa_kediri;
        }

        return view('admin.guru.create', compact('lembagas', 'kecamatans', 'desas', 'semua_kecamatan_kediri', 'semua_desa_kediri', 'type'));
    }

    public function store(Request $request)
    {
        // [BARU] Normalisasi No HP otomatis berawalan 08 sebelum validasi & simpan
        $noHp = preg_replace('/[^0-9]/', '', (string)$request->no_hp);
        if (str_starts_with($noHp, '62')) {
            $noHp = '0' . substr($noHp, 2);
        } elseif (str_starts_with($noHp, '8')) {
            $noHp = '0' . $noHp;
        }
        $request->merge(['no_hp' => $noHp]);

        // 1. Validasi Input & Unik Kontak/Rekening
        $request->validate([
            'lembaga_id'        => 'required|exists:lembagas,id',
            'jenis_guru'        => 'required|in:MADIN,TPQ,PONPES',
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|numeric|digits:16|unique:gurus,nik',
            'tempat_lahir'      => 'required|string',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:L,P',
            
            'pekerjaan_utama'   => 'required|string',
            'status_kepegawaian'=> 'required|string',
            'status_sertifikasi'=> 'required|string',
            'penerima_insentif' => 'required|boolean',
            
            'nama_ibu_kandung'  => 'required|string',
            'agama'              => 'required|string',
            
            'alamat_ktp'        => 'required|string',
            'kecamatan_ktp'     => 'required|string', 
            'desa_ktp'          => 'required|string', 
            'kabupaten'         => 'required|string',

            'no_hp'             => 'required|numeric|unique:gurus,no_hp',
            'nomor_rekening'    => 'nullable|numeric|unique:gurus,nomor_rekening',
            
            'file_ktp'          => 'nullable|mimes:pdf|max:2048',
            'file_kk'           => 'nullable|mimes:pdf|max:2048',
            'file_bukurekening' => 'nullable|mimes:pdf|max:2048',
        ], [
            'nik.unique'            => 'NIK ini sudah terdaftar di database.',
            'no_hp.unique'          => 'Nomor HP sudah terdaftar atas nama guru lain.',
            'nomor_rekening.unique' => 'Nomor Rekening sudah digunakan oleh guru lain.',
        ]);

        // 1. Satpam Wilayah: Domisili KTP Wajib Kabupaten Kediri
        $kabKtp = strtoupper($request->kabupaten);
        if (!empty($kabKtp) && !str_contains($kabKtp, 'KEDIRI')) {
            return back()->withInput()->withErrors([
                'kabupaten' => "Domisili KTP Guru harus berada di Kabupaten Kediri (Terinput: {$request->kabupaten})."
            ]);
        }

        // 2. Satpam Validasi NIK Dukcapil vs Tanggal Lahir (+40 Perempuan)
        $nik = $request->nik;
        $tglLahir = \Carbon\Carbon::parse($request->tanggal_lahir);
        $tgl = (int)$tglLahir->format('d');
        $bln = $tglLahir->format('m');
        $thn = $tglLahir->format('y');
        $expectedTgl = ($request->jenis_kelamin === 'P') ? ($tgl + 40) : $tgl;
        $expectedNikPattern = str_pad($expectedTgl, 2, '0', STR_PAD_LEFT) . $bln . $thn;

        if (substr($nik, 6, 6) !== $expectedNikPattern) {
            return back()->withInput()->withErrors([
                'nik' => "ANOMALI NIK! NIK '{$nik}' tidak sinkron dengan Tanggal Lahir (" . $tglLahir->format('d-m-Y') . ") & Jenis Kelamin ({$request->jenis_kelamin}). Format NIK semestinya memuat kode '{$expectedNikPattern}'."
            ]);
        }

        // [BARU] Satpam Duplikasi Ganda: Kombinasi (Nama Lengkap + Nama Ibu Kandung)
        $cekGanda = Guru::where('nama_lengkap', strtoupper($request->nama_lengkap))
                        ->where('nama_ibu_kandung', strtoupper($request->nama_ibu_kandung))
                        ->first();
        if ($cekGanda) {
            return back()->withInput()->withErrors([
                'nama_lengkap' => "INDIKASI DATA GANDA! Guru bernama '{$request->nama_lengkap}' dengan Nama Ibu '{$request->nama_ibu_kandung}' sudah terdaftar di sistem (NIK: {$cekGanda->nik})."
            ]);
        }

        // 2. Proses Upload File


        $pathKtp = $request->file('file_ktp') ? $request->file('file_ktp')->store('berkas_guru', 'public') : null;
        $pathKk  = $request->file('file_kk') ? $request->file('file_kk')->store('berkas_guru', 'public') : null;
        $pathRek = $request->file('file_bukurekening') ? $request->file('file_bukurekening')->store('berkas_guru', 'public') : null;

        // 3. Simpan ke Database
        // 3. Simpan ke Database (DENGAN PEMAKSAAN HURUF KAPITAL)
        Guru::create([
            'lembaga_id'        => $request->lembaga_id,
            'jenis_guru'        => $request->jenis_guru,
            'nama_lengkap'      => strtoupper($request->nama_lengkap),
            'nik'               => $request->nik,
            'tempat_lahir'      => strtoupper($request->tempat_lahir),
            'tanggal_lahir'     => $request->tanggal_lahir,
            'jenis_kelamin'     => strtoupper($request->jenis_kelamin),
            'nama_ibu_kandung'  => strtoupper($request->nama_ibu_kandung),
            'agama'              => strtoupper($request->agama),
            'pekerjaan_utama'   => strtoupper($request->pekerjaan_utama),
            
            'status_kepegawaian'=> strtoupper($request->status_kepegawaian),
            'status_sertifikasi'=> strtoupper($request->status_sertifikasi),
            'penerima_insentif' => $request->penerima_insentif, 

            'alamat_ktp'        => strtoupper($request->alamat_ktp),
            'desa'              => strtoupper($request->desa_ktp), // Ambil dari input_ktp unik
            'kecamatan'         => strtoupper($request->kecamatan_ktp), // Ambil dari input_ktp unik
            'kabupaten'         => strtoupper($request->kabupaten),


            'no_hp'             => $noHp,
            'nomor_rekening'    => $request->nomor_rekening,
            'keterangan'        => $request->keterangan,
            
            'file_ktp'          => $pathKtp,
            'file_kk'           => $pathKk,
            'file_bukurekening' => $pathRek,
            'status_ktp'        => 'Pending',
            'status_kk'         => 'Pending',
            'status_bukurekening'=> 'Pending',
        ]);

        // [BARU] Cctv Log - Mencatat siapa yang menambah data
        DB::table('activity_logs')->insert([
            'user_id'    => Auth::id(),
            'nama_user'  => Auth::user()->name,
            'aksi'       => 'Menambah Data Guru',
            'target'     => strtoupper($request->nama_lengkap) . ' (' . $request->nik . ')',
            'created_at' => now(),
        ]);

        // Redirect Pintar
        $route = 'guru.index';
        if($request->jenis_guru == 'MADIN') $route = 'guru.madin';
        if($request->jenis_guru == 'TPQ')   $route = 'guru.tpq';
        if($request->jenis_guru == 'PONPES')$route = 'guru.ponpes';

        return redirect()->route($route)->with('success', 'Data Guru berhasil ditambahkan!');
    }

    // ==========================================
    // 3. SHOW (LIHAT DETAIL)
    // ==========================================
    public function show($id)
    {
        $guru = Guru::with('lembaga')->findOrFail($id);
        $user = Auth::user();

        if ($user->role == 'korcam' && $guru->lembaga->kecamatan_id != $user->kecamatan_id) {
            abort(403, 'Akses Ditolak.');
        }

        return view('admin.guru.show', compact('guru'));
    }

    // ==========================================
    // 4. EDIT & UPDATE
    // ==========================================
    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        $user = Auth::user();

        if ($user->role == 'korcam' && $guru->lembaga->kecamatan_id != $user->kecamatan_id) {
            abort(403, 'Akses Ditolak.');
        }
        
        // Data Wilayah Bebas untuk Alamat KTP
        $semua_kecamatan_kediri = Kecamatan::orderBy('nama_kecamatan')->get();
        $semua_desa_kediri = Desa::orderBy('nama_desa')->get();

        // Filter Dropdown Lembaga & Wilayah Kerja di Halaman Edit
        if ($user->role == 'korcam') {
            $lembagas = Lembaga::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_lembaga')->get();
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->orderBy('nama_kecamatan')->get();
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_desa')->get();
        } else {
            $lembagas = Lembaga::orderBy('nama_lembaga')->get();
            $kecamatans = $semua_kecamatan_kediri;
            $desas = $semua_desa_kediri;
        }

        return view('admin.guru.edit', compact('guru', 'lembagas', 'kecamatans', 'desas', 'semua_kecamatan_kediri', 'semua_desa_kediri'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        
        // [BARU] Normalisasi No HP otomatis berawalan 08 sebelum validasi & update
        $noHp = preg_replace('/[^0-9]/', '', (string)$request->no_hp);
        if (str_starts_with($noHp, '62')) {
            $noHp = '0' . substr($noHp, 2);
        } elseif (str_starts_with($noHp, '8')) {
            $noHp = '0' . $noHp;
        }
        $request->merge(['no_hp' => $noHp]);

        $request->validate([
            'lembaga_id'        => 'required|exists:lembagas,id',
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|numeric|digits:16|unique:gurus,nik,' . $id,
            'tempat_lahir'      => 'required|string',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:L,P',
            'nama_ibu_kandung'  => 'required|string',
            'agama'             => 'required|string',
            'no_hp'             => 'required|numeric|unique:gurus,no_hp,' . $id,
            'nomor_rekening'    => 'nullable|numeric|unique:gurus,nomor_rekening,' . $id,
            'pekerjaan_utama'   => 'required|string',
            'status_kepegawaian'=> 'required|string',
            'status_sertifikasi'=> 'required|string',
            'alamat_ktp'        => 'required|string',
            'kecamatan_ktp'     => 'required|string',
            'desa_ktp'          => 'required|string',
            'file_ktp'          => 'nullable|mimes:pdf|max:2048',
            'file_kk'           => 'nullable|mimes:pdf|max:2048',
            'file_bukurekening' => 'nullable|mimes:pdf|max:2048',
        ], [
            'nik.unique'            => 'NIK ini sudah digunakan oleh data guru lain.',
            'no_hp.unique'          => 'Nomor HP sudah terdaftar atas nama guru lain.',
            'nomor_rekening.unique' => 'Nomor Rekening sudah digunakan oleh guru lain.',
        ]);

        // 1. Satpam Wilayah saat Update: Domisili KTP Wajib Kabupaten Kediri
        $kabKtp = strtoupper($request->kabupaten);
        if (!empty($kabKtp) && !str_contains($kabKtp, 'KEDIRI')) {
            return back()->withInput()->withErrors([
                'kabupaten' => "Domisili KTP Guru harus berada di Kabupaten Kediri (Terinput: {$request->kabupaten})."
            ]);
        }

        // 2. Satpam Validasi NIK Dukcapil vs Tanggal Lahir saat Update
        $nik = $request->nik;
        $tglLahir = \Carbon\Carbon::parse($request->tanggal_lahir);
        $tgl = (int)$tglLahir->format('d');
        $bln = $tglLahir->format('m');
        $thn = $tglLahir->format('y');
        $expectedTgl = ($request->jenis_kelamin === 'P') ? ($tgl + 40) : $tgl;
        $expectedNikPattern = str_pad($expectedTgl, 2, '0', STR_PAD_LEFT) . $bln . $thn;

        if (substr($nik, 6, 6) !== $expectedNikPattern) {
            return back()->withInput()->withErrors([
                'nik' => "ANOMALI NIK! NIK '{$nik}' tidak sinkron dengan Tanggal Lahir (" . $tglLahir->format('d-m-Y') . ") & Jenis Kelamin ({$request->jenis_kelamin}). Format NIK semestinya memuat kode '{$expectedNikPattern}'."
            ]);
        }

        // [BARU] Satpam Duplikasi Ganda (Kecuali Data Guru Ini Sendiri)
        $cekGanda = Guru::where('id', '!=', $id)
                        ->where('nama_lengkap', strtoupper($request->nama_lengkap))
                        ->where('nama_ibu_kandung', strtoupper($request->nama_ibu_kandung))
                        ->first();
        if ($cekGanda) {
            return back()->withInput()->withErrors([
                'nama_lengkap' => "INDIKASI DATA GANDA! Guru bernama '{$request->nama_lengkap}' dengan Nama Ibu '{$request->nama_ibu_kandung}' sudah terdaftar pada data guru lain (NIK: {$cekGanda->nik})."
            ]);
        }

        // Ambil semua data input kecuali file, input wilayah khusus KTP, dan input trigger hapus
        $data = $request->except([
            'file_ktp', 'file_kk', 'file_bukurekening', 
            'kecamatan_ktp', 'desa_ktp',
            'hapus_file_ktp', 'hapus_file_kk', 'hapus_file_bukurekening',
            'penerima_insentif' // <-- KUNCI: Abaikan input insentif dari form edit
        ]);
        
        // Petakan manual hasil input unik ke kolom asli database
        $data['kecamatan'] = $request->kecamatan_ktp;
        $data['desa'] = $request->desa_ktp;
        $data['no_hp'] = $noHp;

        // PENGAMAN KUOTA KORCAM:
        // Jika status pegawai diubah jadi ASN (PNS/PPPK), jatah otomatis dicabut (0).
        // Jika tetap Non-ASN, pertahankan status asli database (tidak akan berubah jadi hijau sendiri).
        if (in_array(strtoupper($request->status_kepegawaian), ['PNS', 'PPPK'])) {
            $data['penerima_insentif'] = 0;
        } else {
            $data['penerima_insentif'] = $guru->penerima_insentif;
        }

        // --- MULAI SUNTIKAN KODE PEMAKSAAN KAPITAL ---
        $kolom_teks = [
            'nama_lengkap', 'tempat_lahir', 'nama_ibu_kandung', 'agama', 'pekerjaan_utama',
            'status_kepegawaian', 'status_sertifikasi', 
            'alamat_ktp', 'desa', 'kecamatan', 'kabupaten'
        ];
        
        foreach ($kolom_teks as $kolom) {
            if (isset($data[$kolom])) {
                $data[$kolom] = strtoupper($data[$kolom]);
            }
        }
        // --- AKHIR SUNTIKAN KODE ---

        // ========================================================
        // 📁 PROSES DOKUMEN: HAPUS FILE LAMA & UPLOAD FILE BARU
        // ========================================================

        // 1. FILE KTP
        if ($request->input('hapus_file_ktp') == '1') {
            if ($guru->file_ktp && Storage::disk('public')->exists($guru->file_ktp)) {
                Storage::disk('public')->delete($guru->file_ktp);
            }
            $data['file_ktp'] = null;
            $data['status_ktp'] = null;
        }
        if ($request->hasFile('file_ktp')) {
            if ($guru->file_ktp && Storage::disk('public')->exists($guru->file_ktp)) {
                Storage::disk('public')->delete($guru->file_ktp);
            }
            $data['file_ktp'] = $request->file('file_ktp')->store('berkas_guru', 'public');
            $data['status_ktp'] = 'Pending';
        }

        // 2. FILE KK
        if ($request->input('hapus_file_kk') == '1') {
            if ($guru->file_kk && Storage::disk('public')->exists($guru->file_kk)) {
                Storage::disk('public')->delete($guru->file_kk);
            }
            $data['file_kk'] = null;
            $data['status_kk'] = null;
        }
        if ($request->hasFile('file_kk')) {
            if ($guru->file_kk && Storage::disk('public')->exists($guru->file_kk)) {
                Storage::disk('public')->delete($guru->file_kk);
            }
            $data['file_kk'] = $request->file('file_kk')->store('berkas_guru', 'public');
            $data['status_kk'] = 'Pending';
        }

        // 3. FILE BUKU REKENING
        if ($request->input('hapus_file_bukurekening') == '1') {
            if ($guru->file_bukurekening && Storage::disk('public')->exists($guru->file_bukurekening)) {
                Storage::disk('public')->delete($guru->file_bukurekening);
            }
            $data['file_bukurekening'] = null;
            $data['status_bukurekening'] = null;
        }
        if ($request->hasFile('file_bukurekening')) {
            if ($guru->file_bukurekening && Storage::disk('public')->exists($guru->file_bukurekening)) {
                Storage::disk('public')->delete($guru->file_bukurekening);
            }
            $data['file_bukurekening'] = $request->file('file_bukurekening')->store('berkas_guru', 'public');
            $data['status_bukurekening'] = 'Pending';
        }

        // Update ke Database
        $guru->update($data);

        // [BARU] Cctv Log - Mencatat siapa yang edit data
        DB::table('activity_logs')->insert([
            'user_id'    => Auth::id(),
            'nama_user'  => Auth::user()->name,
            'aksi'       => 'Mengubah Profil Guru',
            'target'     => $guru->nama_lengkap . ' (' . $guru->nik . ')',
            'created_at' => now(),
        ]);

        // Redirect Pintar
        $route = 'guru.index';
        if($guru->jenis_guru == 'MADIN') $route = 'guru.madin';
        if($guru->jenis_guru == 'TPQ')   $route = 'guru.tpq';
        if($guru->jenis_guru == 'PONPES')$route = 'guru.ponpes';

        return redirect()->route($route)->with('success', 'Data Guru berhasil diperbarui!');
    }

    // ==========================================
    // 5. VERIFIKASI & HAPUS
    // ==========================================
    public function verifikasi($id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.verifikasi', compact('guru'));
    }

    public function prosesVerifikasi(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        
        $guru->update([
            'status_ktp' => $request->status_ktp,
            'status_kk'  => $request->status_kk,
            'status_bukurekening' => $request->status_bukurekening,
            'keterangan' => $request->catatan_verifikasi
        ]);

        // Redirect Pintar
        $route = 'guru.index';
        if($guru->jenis_guru == 'MADIN') $route = 'guru.madin';
        if($guru->jenis_guru == 'TPQ')   $route = 'guru.tpq';
        if($guru->jenis_guru == 'PONPES')$route = 'guru.ponpes';

        return redirect()->route($route)->with('success', 'Verifikasi dokumen berhasil disimpan.');
    }

    // ==========================================
    // [BARU] HAPUS FILE FISIK TUNGGAL SECARA INSTAN
    // ==========================================
    public function deleteFile($id, $type)
    {
        $guru = Guru::with('lembaga')->findOrFail($id);
        $user = Auth::user();

        // Keamanan: Cegah Korcam menghapus file guru kecamatan lain
        if ($user->role == 'korcam' && $guru->lembaga->kecamatan_id != $user->kecamatan_id) {
            return back()->with('error', 'Akses Ditolak.');
        }

        $columnFile = 'file_' . $type;
        $columnStatus = 'status_' . $type;

        if (in_array($type, ['ktp', 'kk', 'bukurekening']) && $guru->$columnFile) {
            // 1. Hapus file fisik dari storage disk public
            if (Storage::disk('public')->exists($guru->$columnFile)) {
                Storage::disk('public')->delete($guru->$columnFile);
            }

            // 2. Set file jadi null dan kembalikan status ke 'Pending' (Sesuai aturan database)
            $guru->$columnFile = null;
            $guru->$columnStatus = 'Pending';
            $guru->save();

            // 3. Catat di CCTV Activity Logs
            DB::table('activity_logs')->insert([
                'user_id'    => Auth::id(),
                'nama_user'  => Auth::user()->name,
                'aksi'       => 'Menghapus Berkas ' . strtoupper($type),
                'target'     => $guru->nama_lengkap . ' (' . $guru->nik . ')',
                'created_at' => now(),
            ]);

            return back()->with('success', 'Berkas ' . strtoupper($type) . ' berhasil dihapus permanen.');
        }

        return back()->with('error', 'Berkas tidak ditemukan.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $user = Auth::user();

        if ($user->role == 'korcam' && $guru->lembaga->kecamatan_id != $user->kecamatan_id) {
             return back()->with('error', 'Akses Ditolak.');
        }

        if ($guru->file_ktp) Storage::disk('public')->delete($guru->file_ktp);
        if ($guru->file_kk) Storage::disk('public')->delete($guru->file_kk);
        if ($guru->file_bukurekening) Storage::disk('public')->delete($guru->file_bukurekening);

        // [BARU] Cctv Log - Mencatat sebelum datanya hilang
        DB::table('activity_logs')->insert([
            'user_id'    => Auth::id(),
            'nama_user'  => Auth::user()->name,
            'aksi'       => 'Menghapus Data Guru',
            'target'     => $guru->nama_lengkap . ' (' . $guru->nik . ')',
            'created_at' => now(),
        ]);

        $guru->delete();

        return back()->with('success', 'Data Guru berhasil dihapus.');
    }


    // ==========================================================
    // [FIXED] FUNGSI IMPORT EXCEL SUPER KETAT DENGAN KUNCIAN MENU
    // ==========================================================
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:5120',
        ], [
            'file_excel.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_excel.mimes'    => 'Format file tidak didukung! Harus file Excel (.xlsx atau .xls).',
            'file_excel.max'      => 'Ukuran file Excel maksimal 5MB.',
        ]);

        // [KUNCIAN KRUSIAL] Ambil referer URL untuk mendeteksi asal halaman (madin / tpq / ponpes)
        $referer = $request->headers->get('referer');
        $menuAsal = 'MADIN'; // Default fallback

        if (str_contains(strtolower($referer), '/guru/tpq')) {
            $menuAsal = 'TPQ';
        } elseif (str_contains(strtolower($referer), '/guru/ponpes')) {
            $menuAsal = 'PONPES';
        }

        // Inisialisasi $import di luar try agar terbaca jelas oleh VS Code
        $import = new GuruImport(Auth::user(), $menuAsal);

        try {
            Excel::import($import, $request->file('file_excel'));

            return back()->with('success', "Alhamdulillah! Seluruh data Guru {$menuAsal} di file Excel berhasil diproses tanpa ada NIK/Rekening ganda.");

        } catch (\Exception $e) {
            if ($e->getMessage() === 'excel_validation_failed') {
                return redirect()->back()->with('custom_excel_errors', $import->errors);
            }
            
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * [BARU - FASE 2] SAKLAR INSENTIF KORCAM (Kunci & Lepas Jatah Kuota)
     */
    public function toggleInsentif(Request $request, $id)
    {
        $guru = Guru::with('lembaga.kecamatan')->findOrFail($id);
        $user = Auth::user();

        // 1. Hak Akses: Korcam hanya boleh utak-atik guru di kecamatannya sendiri
        if ($user->role == 'korcam' && $guru->lembaga->kecamatan_id != $user->kecamatan_id) {
            return back()->with('error', 'Akses Ditolak! Guru ini di luar wilayah kecamatan Anda.');
        }

        // 2. Logika jika Korcam mau MENGAKTIFKAN insentif (dari 0 ke 1)
        if ($guru->penerima_insentif == 0) {
            // Ambil batasan kuota kecamatan
            $maxKuota = $guru->lembaga->kecamatan->kuota_insentif ?? 0;

            // Hitung yang sudah terpakai di kecamatan tersebut saat ini
            $terpakai = Guru::whereHas('lembaga', function($q) use ($guru) {
                $q->where('kecamatan_id', $guru->lembaga->kecamatan_id);
            })->where('penerima_insentif', 1)->count();

            // Jika peluru habis, BLOKIR AKSI!
            if ($terpakai >= $maxKuota) {
                return back()->with('error', "Gagal Aktifkan! Jatah Kuota Insentif untuk Kecamatan {$guru->lembaga->kecamatan->nama_kecamatan} sudah HABIS ({$terpakai}/{$maxKuota}).");
            }

            // Jika aman, set jadi aktif
            $guru->update(['penerima_insentif' => 1]);

            // [BARU] Cctv Log Aktifkan Insentif
            DB::table('activity_logs')->insert([
                'user_id'    => Auth::id(),
                'nama_user'  => Auth::user()->name,
                'aksi'       => 'Mengaktifkan Status Insentif',
                'target'     => $guru->nama_lengkap,
                'created_at' => now(),
            ]);
            return back()->with('success', "Alhamdulillah! {$guru->nama_lengkap} resmi dialokasikan sebagai penerima insentif.");
        } 
        
        // 3. Logika jika Korcam mau MENCOPOT/STANDBY-KAN insentif (dari 1 ke 0)
        else {
            $guru->update(['penerima_insentif' => 0]);

            // [BARU] Cctv Log Copot Insentif
            DB::table('activity_logs')->insert([
                'user_id'    => Auth::id(),
                'nama_user'  => Auth::user()->name,
                'aksi'       => 'Mencopot Status Insentif',
                'target'     => $guru->nama_lengkap,
                'created_at' => now(),
            ]);
            return back()->with('success', "Status insentif {$guru->nama_lengkap} berhasil dicopot dan kembali Standby.");
        }
    }

    // ==========================================
    // 6. EXPORT EXCEL (UNDUH LAPORAN)
    // ==========================================
    public function exportExcel(Request $request)
    {
        $jenis = $request->query('type', 'ALL');
        $namaFile = 'Data_Guru_LP3MT_' . $jenis . '_' . date('d-M-Y') . '.xlsx';
        
        // [CCTV LOG] Catat diam-diam siapa yang mengunduh database
        DB::table('activity_logs')->insert([
            'user_id'    => Auth::id(),
            'nama_user'  => Auth::user()->name,
            'aksi'       => 'Download Rekap Excel',
            'target'     => 'Data ' . $jenis,
            'created_at' => now(),
        ]);

        return Excel::download(new GuruExport($request), $namaFile);
    }
    
}