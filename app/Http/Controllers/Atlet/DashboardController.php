<?php

namespace App\Http\Controllers\Atlet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Import Model
use App\Models\Atlet;
use App\Models\ProgresAtlet;
use App\Models\Absensi;
use App\Models\Tagihan;
use App\Models\Notifikasi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. CARI DATA ATLET (Berdasarkan User Login)
        $atlet = Atlet::where('user_id', $user->id)->first();

        // Jika data atlet belum dihubungkan, tampilkan error
        if (!$atlet) {
            return view('atlet.dashboard', [
                'error' => 'Data profil atlet Anda belum terhubung. Hubungi Admin.',
                'hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0,
                'chart_labels' => [], 'data_teknik' => [], 'data_fisik' => [], 'data_mental' => [], 'data_taktik' => [],
                'tagihan_pending' => 0,
                'notifikasis' => collect()
            ]);
        }

        // 2. STATISTIK ABSENSI (Untuk Kotak Atas)
        $hadir = Absensi::where('atlet_id', $atlet->id)->where('status', 'Hadir')->count();
        $sakit = Absensi::where('atlet_id', $atlet->id)->where('status', 'Sakit')->count();
        $izin  = Absensi::where('atlet_id', $atlet->id)->where('status', 'Izin')->count();
        $alpha = Absensi::where('atlet_id', $atlet->id)->where('status', 'Alpha')->count();

        // 3. DATA GRAFIK RAPOR (Untuk Chart.js)
        $riwayat_rapor = ProgresAtlet::where('atlet_id', $atlet->id)
                            ->orderBy('tanggal', 'asc') // Urutkan dari terlama ke terbaru biar grafik nyambung
                            ->take(10) // Ambil 10 data terakhir
                            ->get();

        // Siapkan Array Data Grafik
        $chart_labels = $riwayat_rapor->pluck('tanggal')->map(function($date){
            return Carbon::parse($date)->format('d M'); // Format tgl: 20 Jan
        });
        $data_teknik = $riwayat_rapor->pluck('teknik');
        $data_fisik  = $riwayat_rapor->pluck('fisik');
        $data_mental = $riwayat_rapor->pluck('mental');
        $data_taktik = $riwayat_rapor->pluck('taktik');

        // 4. CEK TAGIHAN (Keuangan)
        // Kita pakai 'try-catch' jaga-jaga kalau tabel tagihan belum dibuat
        $tagihan_pending = 0;
        try {
            $tagihan_pending = Tagihan::where('atlet_id', $atlet->id)
                                ->where('status', 'Belum Lunas')
                                ->count();
        } catch (\Exception $e) {
            $tagihan_pending = 0;
        }

        // 5. NOTIFIKASI / PENGUMUMAN
        $notifikasis = collect();
        try {
            // Ambil notif untuk 'all' atau khusus 'atlet'
            $notifikasis = Notifikasi::where('target_role', 'all')
                            ->orWhere('target_role', 'atlet')
                            ->orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();
        } catch (\Exception $e) {}

        // 6. KIRIM SEMUA DATA KE VIEW 'atlet.dashboard'
        return view('atlet.dashboard', compact(
            'atlet', 
            'hadir', 'sakit', 'izin', 'alpha',
            'chart_labels', 'data_teknik', 'data_fisik', 'data_mental', 'data_taktik',
            'tagihan_pending',
            'notifikasis'
        ));
    }
}