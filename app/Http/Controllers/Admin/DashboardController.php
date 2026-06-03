<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atlet;
use App\Models\Pelatih;
use App\Models\Jadwal;
use App\Models\Tagihan; // Pastikan model Tagihan sudah ada
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama
        $total_atlet = Atlet::where('status', 'Aktif')->count(); // Hanya hitung yang aktif
        $total_coach = Pelatih::where('status', 'Aktif')->count();
        $jadwal_aktif = Jadwal::whereDate('tanggal', '>=', now())->count();
        
        // Asumsi pendapatan dari tabel tagihan status 'Lunas' bulan ini
        // Jika belum ada tabel tagihan, ganti angka 0 saja.
        $total_pendapatan = 0; 
        if(class_exists(Tagihan::class)) {
             $total_pendapatan = Tagihan::where('status', 'Lunas')
                                ->whereMonth('created_at', now()->month)
                                ->sum('nominal');
        }

        // 2. Statistik Gender (Cowok vs Cewek)
        $atlet_laki = Atlet::where('jenis_kelamin', 'Laki-laki')->count();
        $atlet_perempuan = Atlet::where('jenis_kelamin', 'Perempuan')->count();
        
        // Hitung Persentase (Cegah error division by zero)
        $total_gender = $atlet_laki + $atlet_perempuan;
        $persen_laki = $total_gender > 0 ? round(($atlet_laki / $total_gender) * 100) : 0;
        $persen_perempuan = $total_gender > 0 ? round(($atlet_perempuan / $total_gender) * 100) : 0;

        // 3. Statistik Kelompok Umur (KU)
        // Kita hitung manual satu per satu agar bisa dikirim ke grafik
        $ku_stats = [
            'KU-10' => Atlet::where('kategori', 'KU-10')->count(),
            'KU-12' => Atlet::where('kategori', 'KU-12')->count(),
            'KU-14' => Atlet::where('kategori', 'KU-14')->count(),
            'KU-16' => Atlet::where('kategori', 'KU-16')->count(),
            'KU-18' => Atlet::where('kategori', 'KU-18')->count(),
        ];

        // 4. Jadwal Hari Ini
        $jadwal_hari_ini = Jadwal::with('pelatih')
                            ->whereDate('tanggal', Carbon::today())
                            ->orderBy('jam_mulai', 'asc')
                            ->get();

        // Kirim semua variabel ke View 'dashboard'
        return view('dashboard', compact(
            'total_atlet', 
            'total_coach', 
            'jadwal_aktif', 
            'total_pendapatan',
            'atlet_laki', 
            'atlet_perempuan', 
            'persen_laki', 
            'persen_perempuan',
            'ku_stats', 
            'jadwal_hari_ini'
        ));
    }
}