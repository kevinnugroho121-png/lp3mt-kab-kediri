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
use App\Exports\FormatSantriExport;
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
        // [BARU] Filter Ormas
        if ($request->filled('filter_ormas')) {
            if (strtoupper($request->filter_ormas) === 'LAINNYA') {
                $query->whereNotIn('ormas', ['NU', 'MUHAMMADIYAH', 'LDII']);
            } else {
                $query->where('ormas', strtoupper($request->filter_ormas));
            }
        }


        // [REVISI] Filter Cerdas Dokumen Lembaga (Sudah Mendukung Suket Domisili)
        if ($request->filled('filter_berkas')) {
            $filterBerkas = $request->filter_berkas;

            if ($filterBerkas == 'kosong') {
                $query->where(function($q) {
                    // Berkas dianggap kosong jika IJOP dan SKD dua-duanya tidak ada, atau SPTJM kosong, atau Excel kosong
                    $q->where(function($sub) {
                        $sub->whereNull('file_ijop')->whereNull('file_skd');
                    })
                    ->orWhereNull('file_super')
                    ->orWhereNull('file_skam');
                });
            } elseif ($filterBerkas == 'pending') {
                $query->where(function($q) {
                    $q->where('status_ijop', 'Pending')
                      ->orWhere('status_skd', 'Pending')
                      ->orWhere('status_super', 'Pending')
                      ->orWhere('status_skam', 'Pending');
                });
            } elseif ($filterBerkas == 'ditolak') {
                $query->where(function($q) {
                    $q->where('status_ijop', 'Ditolak')
                      ->orWhere('status_skd', 'Ditolak')
                      ->orWhere('status_super', 'Ditolak')
                      ->orWhere('status_skam', 'Ditolak');
                });
            } elseif ($filterBerkas == 'disetujui') {
                $query->where(function($sub) {
                        // Legalitas cukup salah satu yang disetujui (IJOP Asli atau Suket Domisili)
                        $sub->where(function($qIjop) {
                            $qIjop->whereNotNull('file_ijop')->where('status_ijop', 'Disetujui');
                        })->orWhere(function($qSkd) {
                            $qSkd->whereNotNull('file_skd')->where('status_skd', 'Disetujui');
                        });
                    })
                    ->whereNotNull('file_super')->where('status_super', 'Disetujui')
                    ->whereNotNull('file_skam')->where('status_skam', 'Disetujui');
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

        // 1. Sanitasi Nama & No HP Sebelum Validasi
        $cleanNama = strtoupper(preg_replace('/\s+/', ' ', trim($request->nama_lembaga)));
        
        $rawHp = (string)$request->no_telp;
        $cleanHp = str_ireplace('o', '0', $rawHp);
        $cleanHp = preg_replace('/[^0-9]/', '', $cleanHp);
        if (str_starts_with($cleanHp, '62')) {
            $cleanHp = '0' . substr($cleanHp, 2);
        } elseif (str_starts_with($cleanHp, '8')) {
            $cleanHp = '0' . $cleanHp;
        }

        $request->merge([
            'nama_lembaga' => $cleanNama,
            'no_telp'      => !empty($cleanHp) ? $cleanHp : null
        ]);

        // 2. VALIDASI DATA
        $request->validate([
            'nama_lembaga'       => 'required|string|max:255',
            'jenis_lembaga'      => 'required|in:MADIN,TPQ,PONPES',
            'kecamatan_id'       => 'required|exists:kecamatans,id',
            'desa_id'            => 'required|exists:desas,id',
            'alamat'             => 'nullable|string',
            'link_gmaps'         => 'nullable|string',
            'jumlah_santri'      => 'nullable|integer|min:0',
            'jumlah_santri_l'    => 'nullable|integer|min:0',
            'jumlah_santri_p'    => 'nullable|integer|min:0',
            'jumlah_guru'        => 'nullable|integer|min:0',

            // Validasi File Dokumen & Excel (IJOP atau Suket Domisili Wajib Salah Satu)
            'file_ijop'          => 'required_without:file_skd|nullable|mimes:pdf|max:2048', 
            'file_skd'           => 'required_without:file_ijop|nullable|mimes:pdf|max:2048',
            'file_super'         => 'required|mimes:pdf|max:2048', 
            'file_skam'          => 'required|mimes:xlsx,xls|max:5120',

            // Validasi Gambar Dokumentasi
            'foto_lembaga'       => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_nambor'        => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_bangunan'      => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_kbm'           => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
        ], [
            'file_ijop.required_without' => 'Wajib mengunggah minimal salah satu: Berkas IJOP Asli atau Suket Domisili (jika belum ada IJOP).',
            'file_skd.required_without'  => 'Wajib mengunggah Suket Domisili jika berkas IJOP Asli belum ada.',
            'file_ijop.mimes'            => 'File IJOP harus format PDF.',
            'file_ijop.max'              => 'Ukuran file IJOP maksimal 2MB.',
            'file_skd.mimes'             => 'File Suket Domisili harus format PDF.',
            'file_skd.max'               => 'Ukuran file Suket Domisili maksimal 2MB.',
            'file_super.required'=> 'File SPTJM dan SK Aktif Mengajar (PDF) wajib diunggah.',
            'file_super.mimes'   => 'File SPTJM dan SK Aktif Mengajar harus format PDF.',
            'file_skam.required' => 'File Data Santri/Murid (Excel) wajib diunggah.',
            'file_skam.mimes'    => 'File Data Santri harus berformat Excel (.xlsx atau .xls).',
            'file_skam.max'      => 'Ukuran file Excel Data Santri maksimal 5MB.',
            'foto_lembaga.image' => 'File profil lembaga wajib berupa format gambar (JPG/PNG) maksimal 1MB.',
            'foto_nambor.image'  => 'File papan nama wajib berupa format gambar (JPG/PNG) maksimal 1MB.',
            'foto_bangunan.image'=> 'File bangunan wajib berupa format gambar (JPG/PNG) maksimal 1MB.',
            'foto_kbm.image'     => 'File KBM wajib berupa format gambar (JPG/PNG) maksimal 1MB.',
        ]);

        // 3. SATPAM ANTI-DUPLIKASI LEMBAGA (Nama + Jenis + Desa)
        $isDuplicate = Lembaga::where('nama_lembaga', $cleanNama)
                              ->where('jenis_lembaga', $request->jenis_lembaga)
                              ->where('desa_id', $request->desa_id)
                              ->exists();
        if ($isDuplicate) {
            return back()->withInput()->withErrors([
                'nama_lembaga' => "Lembaga '{$cleanNama}' ({$request->jenis_lembaga}) sudah terdaftar di desa yang dipilih."
            ]);
        }

        // ====================================================================
        // 🛡️ SATPAM VALIDASI FILE EXCEL DATA SANTRI (SEBELUM DISIMPAN KE STORAGE)
        // ====================================================================
        $countL = 0;
        $countP = 0;

        if ($request->hasFile('file_skam')) {
            $fileSkamObj = $request->file('file_skam');

            try {
                $sheets = Excel::toArray(new class {}, $fileSkamObj);
                $rows = $sheets[0] ?? [];

                // 1. Satpam Cek File Kosong Total
                if (empty($rows) || count($rows) < 2) {
                    return back()->withInput()->withErrors([
                        'file_skam' => 'GAGAL: File Excel kosong atau tidak memiliki baris data santri!'
                    ]);
                }

                // 2. Satpam Header Resmi: Wajib format Blangko Santri & Blokir File Guru
                $headerIndex = -1;
                $colIndexNama = false;
                $colIndexLP = false;
                $hasCiriSantri = false;

                for ($r = 0; $r < min(5, count($rows)); $r++) {
                    $rowUpper = array_map(fn($h) => strtoupper(trim((string)$h)), $rows[$r]);
                    
                    // A. BLOKIR OTOMATIS JIKA INI FILE GURU
                    foreach ($rowUpper as $h) {
                        if (str_contains($h, 'NIK') || str_contains($h, 'REKENING') || str_contains($h, 'GURU') || str_contains($h, 'SERTIFIKASI') || str_contains($h, 'PEKERJAAN')) {
                            return back()->withInput()->withErrors([
                                'file_skam' => 'GAGAL: File yang Anda upload terdeteksi sebagai DATA GURU! Wajib mengunggah Blangko Excel Santri.'
                            ]);
                        }
                    }

                    // B. DETEKSI CIRI KHAS BLANGKO SANTRI (Wajib ada NAMA, L/P, dan KELAS/USIA/SANTRI)
                    foreach ($rowUpper as $idx => $headerVal) {
                        if (str_contains($headerVal, 'NAMA')) {
                            $colIndexNama = $idx;
                        }
                        if ($headerVal === 'L/P' || str_contains($headerVal, 'JENIS KELAMIN') || $headerVal === 'GENDER') {
                            $colIndexLP = $idx;
                        }
                        if (str_contains($headerVal, 'KELAS') || str_contains($headerVal, 'USIA') || str_contains($headerVal, 'SANTRI')) {
                            $hasCiriSantri = true;
                        }
                    }

                    if ($colIndexNama !== false && $colIndexLP !== false && $hasCiriSantri) {
                        $headerIndex = $r;
                        break;
                    }
                }

                // Jika bukan blangko santri resmi
                if ($headerIndex === -1 || !$hasCiriSantri) {
                    return back()->withInput()->withErrors([
                        'file_skam' => 'GAGAL: Format file Excel BUKAN blangko santri resmi! Pastikan menggunakan template resmi santri (memuat kolom Santri, Kelas/Usia, dan L/P).'
                    ]);
                }

                // 3. Satpam Pemeriksaan Baris Data Santri
                $daftarNamaSantri = [];
                $barisDataAda = 0;

                for ($i = $headerIndex + 1; $i < count($rows); $i++) {
                    $barisKe = $i + 1;
                    $rawNoUrut = trim((string)($rows[$i][0] ?? ''));
                    $noUrut = !empty($rawNoUrut) && is_numeric($rawNoUrut) ? $rawNoUrut : ($i - $headerIndex);

                    $namaSantri = trim((string)($rows[$i][$colIndexNama] ?? ''));
                    $gender = strtoupper(trim((string)($rows[$i][$colIndexLP] ?? '')));

                    // Lewati baris kosong total
                    if ($namaSantri === '' && $gender === '') {
                        continue;
                    }

                    $tagSantri = "No. Urut {$noUrut} (Baris Excel {$barisKe})";

                    // Satpam Nama Kosong tapi gender diisi
                    if ($namaSantri === '' && $gender !== '') {
                        return back()->withInput()->withErrors([
                            'file_skam' => "GAGAL pada {$tagSantri}: Kolom Nama Santri kosong padahal jenis kelamin terisi!"
                        ]);
                    }

                    // Satpam Validitas Kolom L/P (Hanya boleh L atau P)
                    if (!in_array($gender, ['L', 'P'])) {
                        return back()->withInput()->withErrors([
                            'file_skam' => "GAGAL pada {$tagSantri} - Santri: '{$namaSantri}': Kolom L/P wajib diisi 'L' atau 'P' (Terdeteksi: '{$gender}')."
                        ]);
                    }

                    // Satpam Anti-Duplikasi Santri di File yang Sama
                    $namaBersih = strtoupper(preg_replace('/\s+/', ' ', $namaSantri));
                    if (in_array($namaBersih, $daftarNamaSantri)) {
                        return back()->withInput()->withErrors([
                            'file_skam' => "GAGAL pada {$tagSantri}: Nama santri '{$namaSantri}' terdeteksi GANDA (duplikat) di dalam file ini."
                        ]);
                    }

                    $daftarNamaSantri[] = $namaBersih;
                    $barisDataAda++;

                    if ($gender === 'L') { $countL++; }
                    elseif ($gender === 'P') { $countP++; }
                }

                // Satpam Blangko Template Kosongan (Hanya ada header tanpa data santri)
                if ($barisDataAda === 0) {
                    return back()->withInput()->withErrors([
                        'file_skam' => 'GAGAL: File Excel ini masih template kosong! Belum ada santri yang diisikan.'
                    ]);
                }

                // Sinkronkan angka ke request
                $request->merge([
                    'jumlah_santri_l' => $countL,
                    'jumlah_santri_p' => $countP,
                    'jumlah_santri'   => $countL + $countP,
                ]);

            } catch (\Exception $e) {
                return back()->withInput()->withErrors([
                    'file_skam' => 'GAGAL: File Excel rusak atau tidak dapat dibaca. Pastikan format file .xlsx atau .xls valid.'
                ]);
            }
        }

        // ====================================================================
        // 4. PROSES UPLOAD FILE (HANYA BERJALAN JIKA SELURUH SATPAM LOLOS)
        // ====================================================================
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
        $data['status_skd'] = 'Pending';
        $data['status_super'] = 'Pending';
        $data['status_skam'] = 'Pending';

        // Otomatis tentukan status Fisik IJOP berdasarkan keberadaan file yang diunggah
        $data['ijop'] = $request->hasFile('file_ijop') ? 'ADA' : 'TIDAK ADA';

        // Hitung total santri otomatis dari L + P: HANYA jika TIDAK upload file Excel
        if (!$request->hasFile('file_skam')) {
            $data['jumlah_santri_l'] = (int)($request->jumlah_santri_l ?? 0);
            $data['jumlah_santri_p'] = (int)($request->jumlah_santri_p ?? 0);
            $totalInput = (int)($request->jumlah_santri ?? 0);

            if ($data['jumlah_santri_l'] > 0 || $data['jumlah_santri_p'] > 0) {
                $data['jumlah_santri'] = $data['jumlah_santri_l'] + $data['jumlah_santri_p'];
            } elseif ($totalInput > 0) {
                $data['jumlah_santri'] = $totalInput;
                $data['jumlah_santri_l'] = (int) ceil($totalInput / 2);
                $data['jumlah_santri_p'] = (int) floor($totalInput / 2);
            }
        }

        $lembagaBaru = Lembaga::create($data);

        // [CCTV LOG] Catat penambahan lembaga baru
        DB::table('activity_logs')->insert([
            'user_id'    => Auth::id(),
            'nama_user'  => Auth::user()->name,
            'aksi'       => 'Menambah Data Lembaga',
            'target'     => $lembagaBaru->nama_lembaga . ' (' . $lembagaBaru->jenis_lembaga . ')',
            'created_at' => now(),
        ]);

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

        // 1. Sanitasi Nama & No HP Sebelum Update
        $cleanNama = strtoupper(preg_replace('/\s+/', ' ', trim($request->nama_lembaga)));
        
        $rawHp = (string)$request->no_telp;
        $cleanHp = str_ireplace('o', '0', $rawHp);
        $cleanHp = preg_replace('/[^0-9]/', '', $cleanHp);
        if (str_starts_with($cleanHp, '62')) {
            $cleanHp = '0' . substr($cleanHp, 2);
        } elseif (str_starts_with($cleanHp, '8')) {
            $cleanHp = '0' . $cleanHp;
        }

        $request->merge([
            'nama_lembaga' => $cleanNama,
            'no_telp'      => !empty($cleanHp) ? $cleanHp : null
        ]);

        $request->validate([
            'nama_lembaga'       => 'required|string|max:255',
            'alamat'             => 'nullable|string',
            'link_gmaps'         => 'nullable|string',
            'jumlah_santri'      => 'nullable|integer|min:0',
            'jumlah_santri_l'    => 'nullable|integer|min:0',
            'jumlah_santri_p'    => 'nullable|integer|min:0',
            'file_ijop'          => 'nullable|mimes:pdf|max:2048',
            'file_skd'           => 'nullable|mimes:pdf|max:2048',
            'file_super'         => 'nullable|mimes:pdf|max:2048',
            'file_skam'          => 'nullable|mimes:xlsx,xls|max:5120',
            'foto_lembaga'       => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_nambor'        => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_bangunan'      => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
            'foto_kbm'           => 'nullable|image|mimes:jpeg,png,jpg,jfif|max:1024',
        ], [
            'file_skam.mimes'    => 'File Data Santri wajib berformat Excel (.xlsx atau .xls).',
            'file_skam.max'      => 'Ukuran file Excel Data Santri maksimal 5MB.',
        ]);

        // 2. Satpam Anti-Duplikasi Lembaga saat Edit (Kecuali Lembaga Ini Sendiri)
        $desaTarget = $request->desa_id ?? $lembaga->desa_id;
        $jenisTarget = $request->jenis_lembaga ?? $lembaga->jenis_lembaga;
        
        $isDuplicate = Lembaga::where('id', '!=', $lembaga->id)
                              ->where('nama_lembaga', $cleanNama)
                              ->where('jenis_lembaga', $jenisTarget)
                              ->where('desa_id', $desaTarget)
                              ->exists();
        if ($isDuplicate) {
            return back()->withInput()->withErrors([
                'nama_lembaga' => "Lembaga '{$cleanNama}' ({$jenisTarget}) sudah digunakan oleh data lembaga lain di desa ini."
            ]);
        }

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

        // [BARU] Cek Upload File Baru SKAM dengan Satpam Ketat
        if ($request->hasFile('file_skam')) {
            $fileSkamObj = $request->file('file_skam');

            // 1. SATPAM VALIDASI SEBELUM MENGHAPUS / MENIMPA FILE LAMA
            try {
                $sheets = Excel::toArray(new class {}, $fileSkamObj);
                $rows = $sheets[0] ?? [];

                // A. Satpam File Kosong Total
                if (empty($rows) || count($rows) < 2) {
                    return back()->withInput()->withErrors([
                        'file_skam' => 'GAGAL UPDATE: File Excel pengganti kosong atau tidak memiliki data santri!'
                    ]);
                }

                // B. Satpam Header Resmi Blangko Santri (Cari kolom NAMA, L/P, dan Ciri Santri)
                $headerIndex = -1;
                $colIndexNama = false;
                $colIndexLP = false;
                $hasCiriSantri = false;

                for ($r = 0; $r < min(5, count($rows)); $r++) {
                    $rowUpper = array_map(fn($h) => strtoupper(trim((string)$h)), $rows[$r]);
                    
                    // A. BLOKIR OTOMATIS JIKA INI FILE GURU
                    foreach ($rowUpper as $h) {
                        if (str_contains($h, 'NIK') || str_contains($h, 'REKENING') || str_contains($h, 'GURU') || str_contains($h, 'SERTIFIKASI') || str_contains($h, 'PEKERJAAN')) {
                            return back()->withInput()->withErrors([
                                'file_skam' => 'GAGAL UPDATE: File yang Anda upload terdeteksi sebagai DATA GURU! Wajib mengunggah Blangko Excel Santri.'
                            ]);
                        }
                    }

                    // B. DETEKSI CIRI KHAS BLANGKO SANTRI
                    foreach ($rowUpper as $idx => $headerVal) {
                        if (str_contains($headerVal, 'NAMA')) {
                            $colIndexNama = $idx;
                        }
                        if ($headerVal === 'L/P' || str_contains($headerVal, 'JENIS KELAMIN') || $headerVal === 'GENDER') {
                            $colIndexLP = $idx;
                        }
                        if (str_contains($headerVal, 'KELAS') || str_contains($headerVal, 'USIA') || str_contains($headerVal, 'SANTRI')) {
                            $hasCiriSantri = true;
                        }
                    }

                    if ($colIndexNama !== false && $colIndexLP !== false && $hasCiriSantri) {
                        $headerIndex = $r;
                        break;
                    }
                }

                if ($headerIndex === -1 || !$hasCiriSantri) {
                    return back()->withInput()->withErrors([
                        'file_skam' => 'GAGAL UPDATE: File Excel pengganti BUKAN blangko santri resmi! Kolom khas santri (Kelas/Usia) tidak ditemukan.'
                    ]);
                }

                // C. Satpam Baris Data Santri (Format Jelas: No Urut + Baris Excel + Nama)
                $daftarNamaSantri = [];
                $barisDataAda = 0;
                $countL = 0;
                $countP = 0;

                for ($i = $headerIndex + 1; $i < count($rows); $i++) {
                    $barisKe = $i + 1;
                    $rawNoUrut = trim((string)($rows[$i][0] ?? ''));
                    $noUrut = !empty($rawNoUrut) && is_numeric($rawNoUrut) ? $rawNoUrut : ($i - $headerIndex);

                    $namaSantri = trim((string)($rows[$i][$colIndexNama] ?? ''));
                    $gender = strtoupper(trim((string)($rows[$i][$colIndexLP] ?? '')));

                    // Lewati baris kosong total
                    if ($namaSantri === '' && $gender === '') {
                        continue;
                    }

                    $tagSantri = "No. Urut {$noUrut} (Baris Excel {$barisKe})";

                    if ($namaSantri === '' && $gender !== '') {
                        return back()->withInput()->withErrors([
                            'file_skam' => "GAGAL UPDATE pada {$tagSantri}: Kolom Nama Santri kosong padahal jenis kelamin terisi!"
                        ]);
                    }

                    if (!in_array($gender, ['L', 'P'])) {
                        return back()->withInput()->withErrors([
                            'file_skam' => "GAGAL UPDATE pada {$tagSantri} - Santri '{$namaSantri}': Kolom L/P wajib diisi 'L' atau 'P' (Terdeteksi: '{$gender}')."
                        ]);
                    }

                    $namaBersih = strtoupper(preg_replace('/\s+/', ' ', $namaSantri));
                    if (in_array($namaBersih, $daftarNamaSantri)) {
                        return back()->withInput()->withErrors([
                            'file_skam' => "GAGAL UPDATE pada {$tagSantri} - Santri '{$namaSantri}': Terdeteksi GANDA (duplikat) di dalam file pengganti ini."
                        ]);
                    }

                    $daftarNamaSantri[] = $namaBersih;
                    $barisDataAda++;

                    if ($gender === 'L') { $countL++; }
                    elseif ($gender === 'P') { $countP++; }
                }

                if ($barisDataAda === 0) {
                    return back()->withInput()->withErrors([
                        'file_skam' => 'GAGAL UPDATE: File Excel pengganti masih template kosong!'
                    ]);
                }

                // 2. EKSEKUSI PENGGANTIAN FILE (Hanya berjalan jika file baru 100% lolos satpam)
                if ($lembaga->file_skam && Storage::disk('public')->exists($lembaga->file_skam)) {
                    Storage::disk('public')->delete($lembaga->file_skam);
                }

                $data['file_skam'] = $fileSkamObj->store('dokumen_lembaga', 'public');
                $data['status_skam'] = 'Pending'; 

                $data['jumlah_santri_l'] = $countL;
                $data['jumlah_santri_p'] = $countP;
                $data['jumlah_santri']   = $countL + $countP;

                $request->merge([
                    'jumlah_santri_l' => $countL,
                    'jumlah_santri_p' => $countP,
                    'jumlah_santri'   => $countL + $countP,
                ]);

            } catch (\Exception $e) {
                return back()->withInput()->withErrors([
                    'file_skam' => 'GAGAL UPDATE: Berkas Excel rusak atau tidak dapat dibaca.'
                ]);
            }
        } else {
            unset($data['file_skam']);
        }

        // Otomatis sinkronkan status Fisik IJOP saat update data
        if ($request->hasFile('file_ijop')) {
            $data['ijop'] = 'ADA';
        } elseif (!$lembaga->file_ijop) {
            $data['ijop'] = 'TIDAK ADA';
        }

        // Sinkronisasi otomatis total santri: HANYA jalankan jika TIDAK upload file Excel baru
        if (!$request->hasFile('file_skam')) {
            if ($request->has('jumlah_santri_l') || $request->has('jumlah_santri_p') || $request->has('jumlah_santri')) {
                $data['jumlah_santri_l'] = (int)($request->jumlah_santri_l ?? $lembaga->jumlah_santri_l ?? 0);
                $data['jumlah_santri_p'] = (int)($request->jumlah_santri_p ?? $lembaga->jumlah_santri_p ?? 0);
                $totalInput = (int)($request->jumlah_santri ?? $lembaga->jumlah_santri ?? 0);

                if ($data['jumlah_santri_l'] > 0 || $data['jumlah_santri_p'] > 0) {
                    $data['jumlah_santri'] = $data['jumlah_santri_l'] + $data['jumlah_santri_p'];
                } elseif ($totalInput > 0) {
                    $data['jumlah_santri'] = $totalInput;
                    $data['jumlah_santri_l'] = (int) ceil($totalInput / 2);
                    $data['jumlah_santri_p'] = (int) floor($totalInput / 2);
                }
            }
        }

        $lembaga->update($data);

        // [CCTV LOG] Catat pengubahan data lembaga
        DB::table('activity_logs')->insert([
            'user_id'    => Auth::id(),
            'nama_user'  => Auth::user()->name,
            'aksi'       => 'Mengubah Data Lembaga',
            'target'     => $lembaga->nama_lembaga . ' (' . $lembaga->jenis_lembaga . ')',
            'created_at' => now(),
        ]);

        return redirect()->route('lembaga.index')->with('success', 'Data lembaga berhasil diperbarui');
    }

    /**
     * [BARU] Hapus file fisik PDF atau Foto Lembaga secara instan
     */
    public function deleteFile($id, $type)
    {
        $lembaga = Lembaga::findOrFail($id);
        $user = Auth::user();

        if ($user->role == 'korcam' && $lembaga->kecamatan_id != $user->kecamatan_id) {
            return back()->with('error', 'Akses Ditolak.');
        }

        $pdfTypes = [
            'ijop'  => ['file' => 'file_ijop',  'status' => 'status_ijop'],
            'skd'   => ['file' => 'file_skd',   'status' => 'status_skd'],
            'super' => ['file' => 'file_super', 'status' => 'status_super'],
            'skam'  => ['file' => 'file_skam',  'status' => 'status_skam'],
        ];

        $fotoTypes = [
            'foto_lembaga'  => 'foto_lembaga',
            'foto_nambor'   => 'foto_nambor',
            'foto_bangunan' => 'foto_bangunan',
            'foto_kbm'      => 'foto_kbm',
        ];

        // Hapus Dokumen PDF / Excel
        if (array_key_exists($type, $pdfTypes)) {
            $fileCol = $pdfTypes[$type]['file'];
            $statusCol = $pdfTypes[$type]['status'];

            if ($lembaga->$fileCol) {
                if (Storage::disk('public')->exists($lembaga->$fileCol)) {
                    Storage::disk('public')->delete($lembaga->$fileCol);
                }
                $lembaga->$fileCol = null;
                $lembaga->$statusCol = 'Pending';
                if ($type === 'ijop') {
                    $lembaga->ijop = 'TIDAK ADA';
                }
                // [BARU] Jika berkas Excel santri dihapus, reset seluruh hitungan santri ke NOL
                if ($type === 'skam') {
                    $lembaga->jumlah_santri_l = 0;
                    $lembaga->jumlah_santri_p = 0;
                    $lembaga->jumlah_santri   = 0;
                }
                $lembaga->save();

                // Pemetaan nama resmi agar notifikasi dan log terbaca rapi
                $namaResmiBerkas = match($type) {
                    'ijop'  => 'IJOP Asli',
                    'skd'   => 'Suket Domisili (SKD)',
                    'super' => 'SPTJM & SK Aktif Mengajar',
                    'skam'  => 'Data Santri/Murid (Excel)',
                    default => strtoupper($type)
                };

                DB::table('activity_logs')->insert([
                    'user_id'    => Auth::id(),
                    'nama_user'  => Auth::user()->name,
                    'aksi'       => 'Menghapus Berkas ' . $namaResmiBerkas,
                    'target'     => $lembaga->nama_lembaga,
                    'created_at' => now(),
                ]);

                return back()->with('success', 'Berkas ' . $namaResmiBerkas . ' berhasil dihapus.');
            }
        }

        // Hapus Foto Lapangan
        if (array_key_exists($type, $fotoTypes)) {
            $fotoCol = $fotoTypes[$type];

            if ($lembaga->$fotoCol) {
                if (Storage::disk('public')->exists($lembaga->$fotoCol)) {
                    Storage::disk('public')->delete($lembaga->$fotoCol);
                }
                $lembaga->$fotoCol = null;
                $lembaga->save();

                DB::table('activity_logs')->insert([
                    'user_id'    => Auth::id(),
                    'nama_user'  => Auth::user()->name,
                    'aksi'       => 'Menghapus Foto Lembaga',
                    'target'     => $lembaga->nama_lembaga,
                    'created_at' => now(),
                ]);

                return back()->with('success', 'Foto berhasil dihapus.');
            }
        }

        return back()->with('error', 'Berkas tidak ditemukan.');
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

        // [CCTV LOG] Catat penghapusan lembaga sebelum datanya dihapus permanen
        DB::table('activity_logs')->insert([
            'user_id'    => Auth::id(),
            'nama_user'  => Auth::user()->name,
            'aksi'       => 'Menghapus Data Lembaga',
            'target'     => $lembaga->nama_lembaga . ' (' . $lembaga->jenis_lembaga . ')',
            'created_at' => now(),
        ]);

        $lembaga->delete();

        return redirect()->route('lembaga.index')->with('success', 'Data lembaga berhasil dihapus');
    }

    /**
     * [BARU] Proses Import Data Lembaga via Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'Format file tidak didukung! Harus file Excel (.xlsx, .xls, atau .csv).',
            'file.max'      => 'Ukuran file Excel maksimal 5MB.',
        ]);



        $import = new LembagaImport(Auth::user());

        try {
            $namaFileAsli = $request->file('file')->getClientOriginalName();

            Excel::import($import, $request->file('file'));

            // [CCTV LOG] Catat Impor Lembaga Berhasil
            DB::table('activity_logs')->insert([
                'user_id'    => Auth::id(),
                'nama_user'  => Auth::user()->name,
                'aksi'       => 'Impor Data Lembaga Excel',
                'target'     => "Data Lembaga (File: {$namaFileAsli})",
                'created_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Alhamdulillah! Seluruh data Lembaga dari file Excel berhasil diproses tanpa ada yang cacat/ganda.');

        } catch (\Exception $e) {
            if ($e->getMessage() === 'excel_validation_failed') {
                $totalError = count($import->errors);

                // [CCTV LOG] Catat Percobaan Impor Lembaga yang Ditolak Satpam
                DB::table('activity_logs')->insert([
                    'user_id'    => Auth::id(),
                    'nama_user'  => Auth::user()->name,
                    'aksi'       => 'Gagal Impor Excel',
                    'target'     => "Ditolak ({$totalError} Temuan Salah) - File: " . ($request->file('file') ? $request->file('file')->getClientOriginalName() : 'Excel'),
                    'created_at' => now(),
                ]);

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

    /**
     * 1. Unduh Blangko Kosong Master Santri
     */
    public function downloadTemplateSantri(Request $request)
    {
        $namaFile = 'BLANGKO KOSONG SANTRI.xlsx';

        if ($request->filled('lembaga_id')) {
            $lembaga = Lembaga::find($request->lembaga_id);
            if ($lembaga) {
                $jenis = strtoupper($lembaga->jenis_lembaga ?? '');
                $cleanNama = preg_replace('/[^A-Za-z0-9\-\s_]/', '', $lembaga->nama_lembaga);
                $namaFile = 'BLANGKO KOSONG SANTRI - ' . trim("{$jenis} " . strtoupper($cleanNama)) . '.xlsx';
            }
        }

        return Excel::download(new FormatSantriExport, $namaFile);
    }

    /**
     * 2. Unduh Berkas Excel Santri Terisi Milik Lembaga (Nama Rapi & Jelas)
     */
    public function downloadSantriLembaga($id)
    {
        $lembaga = Lembaga::findOrFail($id);

        if (!$lembaga->file_skam || !Storage::disk('public')->exists($lembaga->file_skam)) {
            return back()->with('error', 'Berkas Excel Santri belum diunggah atau tidak ditemukan.');
        }

        // Ambil jenis lembaga, jika di database kosong deteksi dari nama lembaga atau default ke TPQ
        $cleanNama = strtoupper(trim(preg_replace('/[^A-Za-z0-9\-\s_]/', '', $lembaga->nama_lembaga)));
        $jenis = strtoupper(trim($lembaga->jenis_lembaga ?? ''));
        if (empty($jenis)) {
            if (str_contains($cleanNama, 'MADIN')) { $jenis = 'MADIN'; }
            elseif (str_contains($cleanNama, 'PONPES') || str_contains($cleanNama, 'PESANTREN')) { $jenis = 'PONPES'; }
            else { $jenis = 'TPQ'; }
        }

        // Cegah nama ganda seperti "TPQ TPQ AL-HIDAYAH"
        $namaTampil = str_starts_with($cleanNama, $jenis) ? $cleanNama : "{$jenis} {$cleanNama}";
        $namaFile = 'DATA SANTRI TERISI - ' . $namaTampil . '.xlsx';

        return response()->download(storage_path('app/public/' . $lembaga->file_skam), $namaFile);
    }

    /**
     * Sinkronkan otomatis angka santri di database dari file Excel yang tersimpan
     */
    public function syncSantriDariExcel()
    {
        // 1. Nol-kan semua lembaga yang TIDAK memiliki file Excel santri (Null, string kosong, atau strip)
        $resetCount = Lembaga::whereNull('file_skam')
            ->orWhere('file_skam', '')
            ->orWhere('file_skam', '-')
            ->update([
                'jumlah_santri_l' => 0,
                'jumlah_santri_p' => 0,
                'jumlah_santri'   => 0,
            ]);

        // 2. Hitung ulang hanya lembaga yang benar-benar memiliki file Excel di server
        $lembagas = Lembaga::whereNotNull('file_skam')->get();
        $updated = 0;

        foreach ($lembagas as $l) {
            $path = storage_path('app/public/' . $l->file_skam);
            if (file_exists($path)) {
                try {
                    $sheets = Excel::toArray(new class {}, $path);
                    $rows = $sheets[0] ?? [];
                    if (!empty($rows)) {
                        $header = array_map(fn($h) => strtoupper(trim((string)$h)), $rows[0]);
                        $colLP = array_search('L/P', $header);
                        if ($colLP === false) { $colLP = 2; }

                        $lCount = 0;
                        $pCount = 0;
                        for ($i = 1; $i < count($rows); $i++) {
                            $g = strtoupper(trim((string)($rows[$i][$colLP] ?? '')));
                            if ($g === 'L') { $lCount++; }
                            elseif ($g === 'P') { $pCount++; }
                        }

                        $l->update([
                            'jumlah_santri_l' => $lCount,
                            'jumlah_santri_p' => $pCount,
                            'jumlah_santri'   => $lCount + $pCount,
                        ]);
                        $updated++;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            } else {
                // Jika nama file tercatat di DB tapi fisiknya tidak ada di folder, nol-kan juga
                $l->update([
                    'file_skam'       => null,
                    'status_skam'     => 'Pending',
                    'jumlah_santri_l' => 0,
                    'jumlah_santri_p' => 0,
                    'jumlah_santri'   => 0,
                ]);
            }
        }

        return redirect()->route('lembaga.index')->with('success', "Pembersihan berhasil! {$resetCount} lembaga tanpa berkas dinolkan, dan {$updated} lembaga disinkronkan dari Excel.");
    }
}