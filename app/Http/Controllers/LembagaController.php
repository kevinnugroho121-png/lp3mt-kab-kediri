<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lembaga;
use App\Models\Kecamatan;
use App\Models\Desa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// Import Library Excel (Export & Import)
use App\Imports\LembagaImport;
use App\Exports\LembagaExport;
use Maatwebsite\Excel\Facades\Excel;

class LembagaController extends Controller
{
    /**
     * Tampilkan daftar lembaga
     */
    public function index(Request $request)
    {
        $user = Auth::user(); 

        $query = Lembaga::with(['kecamatan', 'desa']);

        // [LOGIKA KORCAM]
        if ($user->role == 'korcam') {
            $query->where('kecamatan_id', $user->kecamatan_id);
        }

        // [FILTER ADMIN]
        if ($user->role != 'korcam' && $request->filled('filter_kecamatan')) {
            $query->where('kecamatan_id', $request->filter_kecamatan);
        }

        // [FILTER LAINNYA]
        if ($request->filled('filter_desa')) {
            $query->where('desa_id', $request->filter_desa);
        }
        if ($request->filled('filter_jenis')) {
            $query->where('jenis_lembaga', $request->filter_jenis);
        }


        // [REVISI] Filter Cerdas (Smart Sort) Dokumen Lembaga
        if ($request->filled('filter_berkas')) {
            $filterBerkas = $request->filter_berkas;

            if ($filterBerkas == 'kosong') {
                $query->where(function($q) {
                    $q->whereNull('file_ijop')
                      ->orWhereNull('file_super')
                      ->orWhereNull('file_skam');
                });
            } elseif ($filterBerkas == 'pending') {
                $query->where(function($q) {
                    $q->where('status_ijop', 'Pending')
                      ->orWhere('status_super', 'Pending')
                      ->orWhere('status_skam', 'Pending');
                });
            } elseif ($filterBerkas == 'ditolak') {
                $query->where(function($q) {
                    $q->where('status_ijop', 'Ditolak')
                      ->orWhere('status_super', 'Ditolak')
                      ->orWhere('status_skam', 'Ditolak');
                });
            } elseif ($filterBerkas == 'disetujui') {
                $query->whereNotNull('file_ijop')
                      ->whereNotNull('file_super')
                      ->whereNotNull('file_skam')
                      ->where('status_ijop', 'Disetujui')
                      ->where('status_super', 'Disetujui')
                      ->where('status_skam', 'Disetujui');
            }
        }

        // [PENCARIAN]
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_lembaga', 'like', '%' . $request->search . '%')
                  ->orWhere('kepala_lembaga', 'like', '%' . $request->search . '%');
            });
        }

        $lembagas = $query->orderBy('nama_lembaga')->paginate(20)->withQueryString();

        // [DATA DROPDOWN]
        if ($user->role == 'korcam') {
            $data_kecamatan = Kecamatan::where('id', $user->kecamatan_id)->get();
            $data_desa = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_desa')->get();
        } else {
            $data_kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();
            // [FIX] Selalu panggil semua desa agar bisa difilter oleh Javascript
            $data_desa = Desa::orderBy('nama_desa')->get(); 
        }

        return view('admin.lembaga.index', compact('lembagas', 'data_kecamatan', 'data_desa'));
    }

    /**
     * Form tambah lembaga
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role == 'korcam') {
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->get();
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_desa')->get();
        } else {
            $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
            $desas = Desa::orderBy('nama_desa')->get();
        }

        return view('admin.lembaga.create', compact('kecamatans', 'desas'));
    }

    /**
     * Simpan data lembaga & Upload Dokumen
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // [PROTEKSI KORCAM]
        if ($user->role == 'korcam') {
            $request->merge(['kecamatan_id' => $user->kecamatan_id]);
        }

        // 1. VALIDASI DATA
        $request->validate([
            'nama_lembaga'       => 'required|string|max:255',
            'jenis_lembaga'      => 'required',
            'kecamatan_id'       => 'required|exists:kecamatans,id',
            'desa_id'            => 'required|exists:desas,id',
            'alamat'             => 'nullable|string',
            'link_gmaps'         => 'nullable|string', // <-- Tambahkan baris ini
            'jumlah_santri'      => 'required|integer|min:0',
            'jumlah_guru'        => 'required|integer|min:0',


            // Validasi File PDF
            'file_ijop'          => 'nullable|mimes:pdf|max:2048', 
            'file_skd'           => 'nullable|mimes:pdf|max:2048', // [BARU Poin 1] Validasi SKD
            'file_super'         => 'nullable|mimes:pdf|max:2048', 
            'file_skam'          => 'nullable|mimes:pdf|max:2048',

            // [BARU - FASE 3] Validasi Gambar Dokumentasi (HANYA GAMBAR, MAKSIMAL 1 MB)
            'foto_lembaga'       => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_nambor'        => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_bangunan'      => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_kbm'           => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
        ], [
            'file_ijop.mimes'    => 'File IJOP harus format PDF.',
            'file_ijop.max'      => 'Ukuran file IJOP maksimal 2MB.',
            'file_super.mimes'   => 'File Surat Pernyataan harus format PDF.',
            'file_skam.mimes'    => 'File Surat Ket. Aktif Mengajar harus format PDF.',
            
            // Pesan validasi gambar kustom
            'foto_lembaga.image' => 'File profil lembaga wajib berupa format gambar (JPG/PNG) maksimal 1MB.',
            'foto_nambor.image'  => 'File papan nama wajib berupa format gambar (JPG/PNG) maksimal 1MB.',
            'foto_bangunan.image'=> 'File bangunan wajib berupa format gambar (JPG/PNG) maksimal 1MB.',
            'foto_kbm.image'     => 'File KBM wajib berupa format gambar (JPG/PNG) maksimal 1MB.',
        ]);

        // 2. PROSES UPLOAD FILE
        $pathIjop = null;
        if ($request->hasFile('file_ijop')) {
            $pathIjop = $request->file('file_ijop')->store('dokumen_lembaga', 'public');
        }

        $pathSuper = null;
        if ($request->hasFile('file_super')) {
            $pathSuper = $request->file('file_super')->store('dokumen_lembaga', 'public');
        }

        $pathSkam = null;
        if ($request->hasFile('file_skam')) {
            $pathSkam = $request->file('file_skam')->store('dokumen_lembaga', 'public');
        }

        // 3. SIMPAN KE DATABASE
        $data = $request->all();

        // [BARU - FASE 3] PROSES UPLOAD 4 GAMBAR DOKUMENTASI LAPANGAN
        $fotoFields = ['foto_lembaga', 'foto_nambor', 'foto_bangunan', 'foto_kbm'];
        foreach ($fotoFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('dokumentasi_lembaga', 'public');
            }
        }

        // --- MULAI SUNTIKAN KODE PEMAKSAAN KAPITAL ---

        // --- MULAI SUNTIKAN KODE PEMAKSAAN KAPITAL ---
        $kolom_teks = ['nama_lembaga', 'nsbq', 'ormas', 'alamat', 'kepala_lembaga', 'keterangan'];
        foreach ($kolom_teks as $kolom) {
            if (isset($data[$kolom])) {
                $data[$kolom] = strtoupper($data[$kolom]);
            }
        }
        // --- AKHIR SUNTIKAN KODE ---

        // [BARU Poin 1] PROSES UPLOAD FILE SKD
        $pathSkd = null;
        if ($request->hasFile('file_skd')) {
            $pathSkd = $request->file('file_skd')->store('dokumen_lembaga', 'public');
        }

        $data['file_ijop'] = $pathIjop;
        $data['file_skd']  = $pathSkd; // [BARU]
        $data['file_super'] = $pathSuper;
        $data['file_skam'] = $pathSkam; 
        
        // Set default status dokumen jika belum ada
        $data['status_ijop'] = 'Pending';
        $data['status_skd'] = 'Pending'; // [BARU]
        $data['status_super'] = 'Pending';
        $data['status_skam'] = 'Pending';



        Lembaga::create($data);

        return redirect()->route('lembaga.index')->with('success', 'Data lembaga dan dokumen berhasil disimpan.');
    }

    /**
     * Tampilkan Detail Lembaga
     */
    public function show(Lembaga $lembaga)
    {
        if (Auth::user()->role == 'korcam' && $lembaga->kecamatan_id != Auth::user()->kecamatan_id) {
            abort(403, 'Akses Ditolak.');
        }
        
        return view('admin.lembaga.show', compact('lembaga'));
    }

    /**
     * Halaman Verifikasi Dokumen
     */
    public function verifikasi(Lembaga $lembaga)
    {
        if (Auth::user()->role == 'korcam' && $lembaga->kecamatan_id != Auth::user()->kecamatan_id) {
            abort(403, 'Akses Ditolak.');
        }

        return view('admin.lembaga.verifikasi', compact('lembaga'));
    }

    /**
     * Proses Simpan Status Verifikasi
     */
    public function prosesVerifikasi(Request $request, Lembaga $lembaga)
    {
        $request->validate([
            'status_ijop' => 'required',
            'status_skd' => 'required', // [BARU] Wajib validasi status SKD
            'status_super' => 'required',
            'status_skam' => 'required', 
            'catatan_verifikasi' => 'nullable|string'
        ]);

        $lembaga->update([
            'status_ijop' => $request->status_ijop,
            'status_skd' => $request->status_skd, // [BARU] Simpan status SKD sesuai pilihan Admin
            'status_super' => $request->status_super,
            'status_skam' => $request->status_skam, 
            'keterangan' => $request->catatan_verifikasi
        ]);

        return redirect()->route('lembaga.index')->with('success', 'Status verifikasi dokumen diperbarui.');
    }

    /**
     * Form edit lembaga
     */
    public function edit(Lembaga $lembaga)
    {
        $user = Auth::user();

        if ($user->role == 'korcam' && $lembaga->kecamatan_id != $user->kecamatan_id) {
            abort(403, 'Akses Ditolak.');
        }

        if ($user->role == 'korcam') {
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->get();
            $desas = Desa::where('kecamatan_id', $user->kecamatan_id)->orderBy('nama_desa')->get();
        } else {
            $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
            $desas = Desa::where('kecamatan_id', $lembaga->kecamatan_id)->get();
        }

        return view('admin.lembaga.edit', compact('lembaga', 'kecamatans', 'desas'));
    }

    /**
     * Update data lembaga (Termasuk jika ada update file)
     */
    public function update(Request $request, Lembaga $lembaga)
    {
        $user = Auth::user();

        if ($user->role == 'korcam') {
            if ($lembaga->kecamatan_id != $user->kecamatan_id) { abort(403); }
            $request->merge(['kecamatan_id' => $user->kecamatan_id]);
        }

        $request->validate([
            'nama_lembaga'       => 'required|string|max:255',
            'alamat'             => 'nullable|string',
            'link_gmaps'         => 'nullable|string', // <-- Tambahkan baris ini
            'file_ijop'          => 'nullable|mimes:pdf|max:2048',
            'file_skd'           => 'nullable|mimes:pdf|max:2048', // [BARU] Validasi SKD
            'file_super'         => 'nullable|mimes:pdf|max:2048',
            'file_skam'          => 'nullable|mimes:pdf|max:2048',


            
            // [BARU - FASE 3] Validasi Gambar saat Update Data
            'foto_lembaga'       => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_nambor'        => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_bangunan'      => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_kbm'           => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
        ]);

        $data = $request->all();

        // [BARU - FASE 3] LOGIKA AMAN PENIMPAAN FOTO LAMA (CEGAH SAMPAH STORAGE)
        $fotoFields = ['foto_lembaga', 'foto_nambor', 'foto_bangunan', 'foto_kbm'];
        foreach ($fotoFields as $field) {
            if ($request->hasFile($field)) {
                // Hapus file fisik foto lama di local storage jika terdeteksi ada
                if ($lembaga->$field && Storage::disk('public')->exists($lembaga->$field)) {
                    Storage::disk('public')->delete($lembaga->$field);
                }
                // Simpan file foto baru
                $data[$field] = $request->file($field)->store('dokumentasi_lembaga', 'public');
            } else {
                // Jika tidak upload baru, hapus field dari array data agar alamat lama tidak tertimpa NULL
                unset($data[$field]);
            }
        }

        // --- MULAI SUNTIKAN KODE PEMAKSAAN KAPITAL ---

        // --- MULAI SUNTIKAN KODE PEMAKSAAN KAPITAL ---
        $kolom_teks = ['nama_lembaga', 'nsbq', 'ormas', 'alamat', 'kepala_lembaga', 'keterangan'];
        foreach ($kolom_teks as $kolom) {
            if (isset($data[$kolom])) {
                $data[$kolom] = strtoupper($data[$kolom]);
            }
        }
        // --- AKHIR SUNTIKAN KODE ---
        
        // [PERBAIKAN] Cek Upload File Baru IJOP
        // [BARU Poin 1] Cek Upload File Baru SKD
        if ($request->hasFile('file_skd')) {
            if ($lembaga->file_skd && Storage::disk('public')->exists($lembaga->file_skd)) {
                Storage::disk('public')->delete($lembaga->file_skd);
            }
            $data['file_skd'] = $request->file('file_skd')->store('dokumen_lembaga', 'public');
            $data['status_skd'] = 'Pending'; 
        } else {
            unset($data['file_skd']);
        }



        if ($request->hasFile('file_ijop')) {
            // Hapus file fisik yang lama jika ada
            if ($lembaga->file_ijop && Storage::disk('public')->exists($lembaga->file_ijop)) {
                Storage::disk('public')->delete($lembaga->file_ijop);
            }
            // Simpan file ke folder dan ubah isi data menjadi path yang benar
            $data['file_ijop'] = $request->file('file_ijop')->store('dokumen_lembaga', 'public');
            $data['status_ijop'] = 'Pending';
        } else {
            // Jika tidak upload file baru, hapus dari array agar path lama di DB tidak tertimpa alamat temp Windows
            unset($data['file_ijop']); 
        }

        // Cek Upload File Baru SUPER
        if ($request->hasFile('file_super')) {
            if ($lembaga->file_super && Storage::disk('public')->exists($lembaga->file_super)) {
                Storage::disk('public')->delete($lembaga->file_super);
            }
            $data['file_super'] = $request->file('file_super')->store('dokumen_lembaga', 'public');
            $data['status_super'] = 'Pending'; 
        } else {
            unset($data['file_super']);
        }

        // [BARU] Cek Upload File Baru SKAM
        if ($request->hasFile('file_skam')) {
            if ($lembaga->file_skam && Storage::disk('public')->exists($lembaga->file_skam)) {
                Storage::disk('public')->delete($lembaga->file_skam);
            }
            $data['file_skam'] = $request->file('file_skam')->store('dokumen_lembaga', 'public');
            $data['status_skam'] = 'Pending'; 
        } else {
            unset($data['file_skam']);
        }

        $lembaga->update($data);

        return redirect()->route('lembaga.index')->with('success', 'Data lembaga berhasil diperbarui');
    }

    /**
     * Hapus lembaga
     */
    public function destroy(Lembaga $lembaga)
    {
        if (Auth::user()->role == 'verifikator') {
            return back()->with('error', 'Akses Ditolak: Verifikator tidak boleh menghapus data.');
        }
        
        if (Auth::user()->role == 'korcam' && $lembaga->kecamatan_id != Auth::user()->kecamatan_id) {
            return back()->with('error', 'Anda tidak berhak menghapus data ini.');
        }

        // Hapus File Fisik Dulu (Bersih-bersih)
        if ($lembaga->file_ijop && Storage::disk('public')->exists($lembaga->file_ijop)) {
            Storage::disk('public')->delete($lembaga->file_ijop);
        }
        
        // [BARU] Hapus file fisik SKD
        if ($lembaga->file_skd && Storage::disk('public')->exists($lembaga->file_skd)) {
            Storage::disk('public')->delete($lembaga->file_skd);
        }

        if ($lembaga->file_super && Storage::disk('public')->exists($lembaga->file_super)) {
            Storage::disk('public')->delete($lembaga->file_super);
        }

        if ($lembaga->file_skam && Storage::disk('public')->exists($lembaga->file_skam)) {
            Storage::disk('public')->delete($lembaga->file_skam);
        }




        // [BARU - FASE 3] BERSIHKAN 4 FILE FOTO FISIK DARI STORAGE SAAT LEMBAGA DIHAPUS
        $fotoFields = ['foto_lembaga', 'foto_nambor', 'foto_bangunan', 'foto_kbm'];
        foreach ($fotoFields as $field) {
            if ($lembaga->$field && Storage::disk('public')->exists($lembaga->$field)) {
                Storage::disk('public')->delete($lembaga->$field);
            }
        }

        $lembaga->delete();

        return redirect()->route('lembaga.index')->with('success', 'Data lembaga berhasil dihapus');
    }

    /**
     * [BARU] Proses Import Data Lembaga via Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);



        $import = new LembagaImport(Auth::user());

        try {
            Excel::import($import, $request->file('file'));

            return redirect()->back()->with('success', 'Alhamdulillah! Seluruh data Lembaga dari file Excel berhasil diproses tanpa ada yang cacat/ganda.');

        } catch (\Exception $e) {
            if ($e->getMessage() === 'excel_validation_failed') {
                // Lempar data array string error buatan kita ke session khusus
                return redirect()->back()->with('custom_excel_errors', $import->errors);
            }

            // Tangkap error tidak terduga lainnya
            return redirect()->back()->with('error', $e->getMessage());
        }


    }

    public function exportExcel(Request $request)
    {
        $namaFile = 'Data_Lembaga_LP3MT_' . date('d-M-Y') . '.xlsx';
        
        // Catat di log aktivitas
        \Illuminate\Support\Facades\DB::table('activity_logs')->insert([
            'user_id'    => \Illuminate\Support\Facades\Auth::id(),
            'nama_user'  => \Illuminate\Support\Facades\Auth::user()->name,
            'aksi'       => 'Download Rekap Excel',
            'target'     => 'Data Lembaga',
            'created_at' => now(),
        ]);

        return Excel::download(new LembagaExport($request), $namaFile);
    }
}