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
        if ($request->filled('filter_lembaga')) {
            $query->where('lembaga_id', $request->filter_lembaga);
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

        // [DIPERBAIKI] Panggil query paginasi cukup 1x saja di bawah
        $gurus = $query->latest()->paginate(20)->withQueryString();

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
        if ($user->role == 'korcam') {
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->orderBy('nama_kecamatan')->get();
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_desa')->get();
        } else {
            $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
            $desas = Desa::orderBy('nama_desa')->get();
        }

        return view('admin.guru.create', compact('lembagas', 'kecamatans', 'desas', 'type'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'lembaga_id'        => 'required|exists:lembagas,id',
            'jenis_guru'        => 'required|in:MADIN,TPQ,PONPES',
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|numeric|digits:16|unique:gurus,nik',
            'tempat_lahir'      => 'required|string',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:L,P',
            
            'status_kepegawaian'=> 'required|string',
            'status_sertifikasi'=> 'required|string',
            'penerima_insentif' => 'required|boolean', // [BARU] Wajib diisi (0/1)
            
            'nama_ibu_kandung'  => 'required|string',
            'agama'             => 'required|string',
            'alamat_ktp'        => 'required|string',
            'kecamatan'         => 'required|string', 
            'desa'              => 'required|string', 
            'kabupaten'         => 'required|string',
            'no_hp'             => 'required|numeric',
            'nomor_rekening'    => 'nullable|numeric',
            
            'file_ktp'          => 'nullable|mimes:pdf|max:2048',
            'file_kk'           => 'nullable|mimes:pdf|max:2048',
            'file_bukurekening' => 'nullable|mimes:pdf|max:2048',
        ]);

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
            'agama'             => strtoupper($request->agama),
            
            'status_kepegawaian'=> strtoupper($request->status_kepegawaian),
            'status_sertifikasi'=> strtoupper($request->status_sertifikasi),
            'penerima_insentif' => $request->penerima_insentif, 

            'alamat_ktp'        => strtoupper($request->alamat_ktp),
            'desa'              => strtoupper($request->desa),
            'kecamatan'         => strtoupper($request->kecamatan),
            'kabupaten'         => strtoupper($request->kabupaten),


            'no_hp'             => $request->no_hp,
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
        
        // Filter Dropdown di Halaman Edit
        if ($user->role == 'korcam') {
            $lembagas = Lembaga::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_lembaga')->get();
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->orderBy('nama_kecamatan')->get();
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_desa')->get();
        } else {
            $lembagas = Lembaga::orderBy('nama_lembaga')->get();
            $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
            $desas = Desa::orderBy('nama_desa')->get();
        }

        return view('admin.guru.edit', compact('guru', 'lembagas', 'kecamatans', 'desas'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        
        $request->validate([
            'lembaga_id'        => 'required|exists:lembagas,id',
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|numeric|digits:16|unique:gurus,nik,' . $id,
            'status_kepegawaian'=> 'required|string',
            'status_sertifikasi'=> 'required|string',
            'penerima_insentif' => 'required|boolean', // [BARU] Validasi Update
            'file_ktp'          => 'nullable|mimes:pdf|max:2048',
            'file_kk'           => 'nullable|mimes:pdf|max:2048',
            'file_bukurekening' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Ambil semua data input kecuali file
        $data = $request->except(['file_ktp', 'file_kk', 'file_bukurekening']);
        
        // [PENTING] Pastikan penerima_insentif masuk ke array $data
        $data['penerima_insentif'] = $request->penerima_insentif;

        // --- MULAI SUNTIKAN KODE PEMAKSAAN KAPITAL ---
        $kolom_teks = [
            'nama_lengkap', 'tempat_lahir', 'nama_ibu_kandung', 'agama', 
            'status_kepegawaian', 'status_sertifikasi', 
            'alamat_ktp', 'desa', 'kecamatan', 'kabupaten'
        ];
        
        foreach ($kolom_teks as $kolom) {
            if (isset($data[$kolom])) {
                $data[$kolom] = strtoupper($data[$kolom]);
            }
        }
        // --- AKHIR SUNTIKAN KODE ---

        // Proses File

        // Proses File
        if ($request->hasFile('file_ktp')) {
            if ($guru->file_ktp) Storage::disk('public')->delete($guru->file_ktp);
            $data['file_ktp'] = $request->file('file_ktp')->store('berkas_guru', 'public');
            $data['status_ktp'] = 'Pending';
        }
        if ($request->hasFile('file_kk')) {
            if ($guru->file_kk) Storage::disk('public')->delete($guru->file_kk);
            $data['file_kk'] = $request->file('file_kk')->store('berkas_guru', 'public');
            $data['status_kk'] = 'Pending';
        }
        if ($request->hasFile('file_bukurekening')) {
            if ($guru->file_bukurekening) Storage::disk('public')->delete($guru->file_bukurekening);
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

        try {
            // Suntikkan $menuAsal ke dalam class Import agar satpam mendeteksi dengan akurat
            $import = new GuruImport(Auth::user(), $menuAsal);
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
    
}