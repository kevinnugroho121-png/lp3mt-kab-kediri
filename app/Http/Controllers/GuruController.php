<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Lembaga;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
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
        if ($filterType != 'ALL' && $filterType != 'INSENTIF') {
            $query->where('jenis_guru', $filterType);
        } elseif ($filterType == 'INSENTIF') {
            $query->where('penerima_insentif', 1); // <--- KUNCI MENU INSENTIF
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

        $gurus = $query->latest()->paginate(20)->withQueryString();

        return view('admin.guru.index', compact('gurus', 'title', 'data_kecamatan', 'data_desa', 'filterType', 'list_lembaga'));
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

        $guru->delete();

        return back()->with('success', 'Data Guru berhasil dihapus.');
    }


    // ==========================================================
    // [BARU] FUNGSI IMPORT EXCEL SUPER KETAT (REJECT-ALL SYSTEM)
    // ==========================================================
    public function import(Request $request)
    {
        // 1. Validasi harus file Excel
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:5120', // Max 5MB
        ], [
            'file_excel.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_excel.mimes'    => 'Format file tidak didukung! Harus file Excel (.xlsx atau .xls), bukan CSV.',
            'file_excel.max'      => 'Ukuran file Excel maksimal 5MB.',
        ]);

        try {
            // 2. Jalankan Proses Import
            Excel::import(new GuruImport, $request->file('file_excel'));

            return back()->with('success', 'Alhamdulillah! Seluruh data di file Excel berhasil diproses dan disimpan ke database.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // 3. AMBIL ERROR JIKA ADA SEL YANG KOSONG ATAU SALAH (ALAMAT ERROR ALA PAK ARIF)
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                // $failure->row() = Baris ke berapa di Excel
                // $failure->attribute() = Nama kolom yang salah
                // $failure->errors() = Pesan errornya
                $errorMessages[] = "Baris Ke-" . $failure->row() . " (Kolom " . $failure->attribute() . "): " . implode(', ', $failure->errors());
            }

            // Batalkan semua data yang sempat masuk, kembalikan dengan error detail
            return back()->withErrors($errorMessages)->with('error', 'Gagal Import! Ditemukan data yang kosong atau salah format. Seluruh data di file Excel ini BATAL disimpan.');
            
        } catch (\Exception $e) {
            // Tangkap error sistem lainnya (misal format tanggal hancur)
            return back()->with('error', 'Terjadi kesalahan sistem saat membaca file Excel: ' . $e->getMessage());
        }
    }
    
}