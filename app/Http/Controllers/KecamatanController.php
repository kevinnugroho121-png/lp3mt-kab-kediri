<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB; // [BARU] Untuk hitung real-time insentif terpakai

class KecamatanController extends Controller
{
    /**
     * Tampilkan data kecamatan
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil user yang login

        // 1. Siapkan Query + HITUNG DESA
        $query = Kecamatan::withCount('desa');

        // 2. LOGIKA KHUSUS KORCAM (Kacamata Kuda)
        // Jika Korcam, paksa hanya tampilkan kecamatannya sendiri
        if ($user->role == 'korcam') {
            $query->where('id', $user->kecamatan_id);
        }

        // 3. Logika Pencarian (Hanya aktif jika BUKAN Korcam)
        // Korcam tidak perlu cari, karena datanya cuma satu
        if ($user->role != 'korcam' && $request->filled('search')) {
            $query->where('nama_kecamatan', 'like', '%' . $request->search . '%');
        }

        // 4. Ambil semua data sekaligus (tanpa pagination)
        $kecamatans = $query->orderBy('nama_kecamatan', 'asc')->get();

        // [BARU] Ambil Real-Time Jumlah Guru Diajukan Insentif per Kecamatan
        $terpakaiMap = DB::table('gurus')
            ->join('lembagas', 'gurus.lembaga_id', '=', 'lembagas.id')
            ->where('gurus.penerima_insentif', 1)
            ->groupBy('lembagas.kecamatan_id')
            ->select('lembagas.kecamatan_id', DB::raw('count(*) as total'))
            ->pluck('total', 'kecamatan_id');

        // [BARU] Hitung Kontrol Kuota Induk Kabupaten (Murni dari input petugas)
        $totalPagu = (int) Cache::get('pagu_induk_kabupaten', 0);
        $kuotaTerdistribusi = (int) Kecamatan::sum('kuota_insentif');
        $sisaKuota = $totalPagu - $kuotaTerdistribusi;

        return view('admin.kecamatan.index', compact('kecamatans', 'totalPagu', 'kuotaTerdistribusi', 'sisaKuota', 'terpakaiMap'));
    }

    /**
     * Form tambah kecamatan
     */
    public function create()
    {
        // [PROTEKSI] Korcam DILARANG akses halaman tambah
        if (Auth::user()->role == 'korcam') {
            abort(403, 'Akses Ditolak: Korcam tidak berhak menambah kecamatan.');
        }

        return view('admin.kecamatan.create');
    }

    /**
     * Simpan kecamatan baru
     */
    public function store(Request $request)
    {
        // [PROTEKSI] Korcam DILARANG simpan data
        if (Auth::user()->role == 'korcam') {
            abort(403);
        }

        $request->validate([
            'nama_kecamatan' => 'required|string|max:255|unique:kecamatans,nama_kecamatan'
        ], [
            'nama_kecamatan.unique' => 'Nama kecamatan ini sudah ada di database, tidak boleh ganda.'
        ]);

        // Contoh di fungsi store / update Kecamatan
        Kecamatan::create([
            'nama_kecamatan' => strtoupper($request->nama_kecamatan)
        ]);

        return redirect()->route('kecamatan.index')
            ->with('success', 'Data kecamatan berhasil ditambahkan');
    }

    /**
     * Form edit kecamatan
     */
    public function edit(Kecamatan $kecamatan)
    {
        // [PROTEKSI] Korcam hanya boleh edit kecamatannya sendiri
        $user = Auth::user();
        if ($user->role == 'korcam' && $kecamatan->id != $user->kecamatan_id) {
            abort(403, 'Anda tidak memiliki akses ke kecamatan ini.');
        }

        return view('admin.kecamatan.edit', compact('kecamatan'));
    }

    /**
     * Update data kecamatan
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        // [PROTEKSI] Korcam hanya boleh update kecamatannya sendiri
        $user = Auth::user();
        if ($user->role == 'korcam' && $kecamatan->id != $user->kecamatan_id) {
            abort(403);
        }

        $request->validate([
            'nama_kecamatan' => 'required|max:255|unique:kecamatans,nama_kecamatan,' . $kecamatan->id,
        ]);

        // [FIX] Pasang strtoupper juga saat update data
        $kecamatan->update([
            'nama_kecamatan' => strtoupper($request->nama_kecamatan)
        ]);

        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil diperbarui');
    }

    /**
     * Hapus kecamatan
     */
    public function destroy(Kecamatan $kecamatan)
    {
        // [PROTEKSI] HANYA ADMIN yang boleh hapus kecamatan
        // Korcam & Verifikator DILARANG
        if (Auth::user()->role != 'admin') {
            return back()->with('error', 'Hanya Admin Pusat yang boleh menghapus kecamatan.');
        }

        $kecamatan->delete();

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil dihapus');
    }

    /**
     * [BARU - FASE 2] Update jatah angka kuota kecamatan oleh Superadmin / Verifikator
     */
    public function updateKuota(Request $request, $id)
    {
        // 1. Amankan hak akses! Korcam dilarang keras menembak fungsi ini
        if (Auth::user()->role == 'korcam') {
            abort(403, 'Akses Ditolak! Korcam tidak berhak mengubah kuota.');
        }

        // 2. Validasi inputan harus berupa angka bulat positif
        $request->validate([
            'kuota_insentif' => 'required|integer|min:0'
        ], [
            'kuota_insentif.required' => 'Angka kuota wajib diisi.',
            'kuota_insentif.integer'  => 'Kuota harus berupa angka, bukan teks.',
            'kuota_insentif.min'      => 'Kuota minimal adalah angka 0.'
        ]);

        // 3. Eksekusi simpan perubahan
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->update([
            'kuota_insentif' => $request->kuota_insentif
        ]);

        // Cek apakah ada request 'current_page_url' dari form
        if ($request->filled('current_page_url')) {
            return redirect($request->current_page_url)->with('success', "Alhamdulillah! Kuota untuk Kecamatan {$kecamatan->nama_kecamatan} berhasil diatur menjadi {$request->kuota_insentif} jatah.");
        }

        return redirect()->route('kecamatan.index')->with('success', "Alhamdulillah! Kuota untuk Kecamatan {$kecamatan->nama_kecamatan} berhasil diatur menjadi {$request->kuota_insentif} jatah.");
    }

    /**
     * [BARU] Simpan Angka Master Pagu Kuota Kabupaten
     */
    public function updatePaguInduk(Request $request)
    {
        if (Auth::user()->role == 'korcam') {
            abort(403, 'Akses Ditolak! Korcam tidak berhak mengubah pagu induk.');
        }

        $request->validate([
            'pagu_induk' => 'required|integer|min:0'
        ], [
            'pagu_induk.required' => 'Total kuota kabupaten wajib diisi.',
            'pagu_induk.integer'  => 'Total kuota harus berupa angka.',
            'pagu_induk.min'      => 'Total kuota minimal 0.'
        ]);

        Cache::forever('pagu_induk_kabupaten', $request->pagu_induk);

        return redirect()->route('kecamatan.index')->with('success', "Alhamdulillah! Total Pagu Kuota Kabupaten berhasil diatur menjadi " . number_format($request->pagu_induk) . " jatah.");
    }
}