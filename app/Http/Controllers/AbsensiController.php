<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Atlet;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    // 1. TAMPILKAN FORM ABSENSI (Daftar Siswa)
    public function create($jadwal_id)
    {
        // Ambil data jadwal yang dipilih
        $jadwal = Jadwal::with('pelatih')->findOrFail($jadwal_id);

        // Ambil SEMUA atlet yang aktif untuk diabsen
        // (Nanti bisa difilter berdasarkan Kategori Umur jika mau lebih canggih)
        $atlets = Atlet::where('status', 'Aktif')
                        ->orderBy('nama_lengkap', 'asc')
                        ->get();

        // Cek apakah sudah pernah absen sebelumnya di jadwal ini?
        // Jika sudah, kita ambil data lamanya biar bisa diedit
        $existingAbsensi = Absensi::where('jadwal_id', $jadwal_id)
                                  ->get()
                                  ->keyBy('atlet_id'); // Biar gampang dicocokkan

        return view('pelatih.absensi.create', compact('jadwal', 'atlets', 'existingAbsensi'));
    }

    // 2. SIMPAN DATA ABSENSI KE DATABASE
    public function store(Request $request, $jadwal_id)
    {
        $request->validate([
            'absensi' => 'required|array', // Wajib ada data yang dikirim
        ]);

        // Kita gunakan DB Transaction biar aman (Semua tersimpan atau Gagal semua)
        DB::transaction(function () use ($request, $jadwal_id) {
            
            foreach ($request->absensi as $atlet_id => $data) {
                // Gunakan updateOrCreate:
                // Jika data sudah ada -> Update
                // Jika belum ada -> Buat baru
                Absensi::updateOrCreate(
                    [
                        'jadwal_id' => $jadwal_id,
                        'atlet_id'  => $atlet_id
                    ],
                    [
                        'tanggal_latihan' => $request->tanggal_latihan, // Dari input hidden form
                        'status'          => $data['status'],           // Hadir/Sakit/Izin/Alpha
                        'catatan'         => $data['catatan'] ?? null,  // Catatan opsional
                    ]
                );
            }

            // Update status Jadwal jadi "Selesai" jika perlu
            // Jadwal::where('id', $jadwal_id)->update(['status' => 'Selesai']);
        });

        return redirect()->route('dashboard')->with('success', 'Data absensi berhasil disimpan!');
    }
}