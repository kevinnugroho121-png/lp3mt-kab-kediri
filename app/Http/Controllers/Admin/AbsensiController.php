<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Atlet;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    /**
     * Menampilkan Form Absensi & Penilaian.
     */
    public function create($jadwal_id)
    {
        // 1. Ambil data Jadwal
        $jadwal = Jadwal::findOrFail($jadwal_id);

        // 2. Ambil semua Atlet yang COCOK dengan kategori jadwal ini
        $atlets = Atlet::where('kategori', $jadwal->kategori)
            ->where('status', 'Aktif')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        // 3. Kirim ke View
        return view('admin.absensi.create', compact('jadwal', 'atlets'));
    }

    /**
     * Menyimpan Data Absensi & Nilai Rapor Mini.
     */
    public function store(Request $request, $jadwal_id)
    {
        // 1. Validasi
        $request->validate([
            'absensi' => 'required|array', 
        ]);

        $jadwal = Jadwal::findOrFail($jadwal_id);

        // 2. LOOPING DATA (Proses satu per satu atlet)
        foreach ($request->absensi as $atlet_id => $data) {
            
            // === LOGIKA UPDATE OR CREATE ===
            Absensi::updateOrCreate(
                // SYARAT PENCARIAN (Kunci Unik):
                [
                    'jadwal_id' => $jadwal->id,
                    'atlet_id'  => $atlet_id,
                ],
                // DATA YANG DISIMPAN/DIUPDATE:
                [
                    'status'          => $data['status'],
                    
                    // === TAMBAHAN BARU: NILAI SKILL ===
                    // Menggunakan '?? null' artinya: Jika input kosong, simpan sebagai NULL
                    'nilai_dribbling' => $data['nilai_dribbling'] ?? null,
                    'nilai_passing'   => $data['nilai_passing'] ?? null,
                    'nilai_shooting'  => $data['nilai_shooting'] ?? null,
                    'nilai_perilaku'  => $data['nilai_perilaku'] ?? null,
                    
                    'catatan'         => $data['catatan'] ?? null, 
                ]
            );
        }

        return redirect()->route('jadwal.index')
            ->with('success', 'Data Absensi & Penilaian berhasil disimpan!');
    }
}