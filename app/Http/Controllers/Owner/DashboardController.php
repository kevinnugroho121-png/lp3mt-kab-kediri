<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

// Import Model yang dibutuhkan
use App\Models\Atlet;
use App\Models\Tagihan;
use App\Models\Pembayaran; 

class DashboardController extends Controller
{
    public function index()
    {
        /**
         * ============================================
         * DASHBOARD OWNER (EXECUTIVE MODE)
         * Fokus: Monitoring, Grafik Tren, & Keuangan
         * ============================================
         */

        // ---------------------------------------------------------
        // 1. KARTU STATISTIK (ANGKA RINGKASAN)
        // ---------------------------------------------------------
        
        // Total Atlet Aktif
        $atlet_aktif = Atlet::where('status', 'Aktif')->count();
        // Fallback kalau kolom 'status' belum ada di database, pakai count() biasa:
        // $atlet_aktif = Atlet::count(); 

        // Total Pemasukan (Diambil dari tabel Tagihan yang Lunas agar akurat)
        $total_pemasukan = Tagihan::where('status', 'Lunas')->sum('nominal');

        // Total Piutang (Uang yang belum masuk)
        $tagihan_belum_lunas = Tagihan::whereIn('status', ['Belum Lunas', 'Menunggu Verifikasi'])
                                      ->sum('nominal');


        // ---------------------------------------------------------
        // 2. DATA UNTUK GRAFIK "TREN PEMASUKAN BULANAN" (Line Chart)
        // ---------------------------------------------------------
        
        $income_data = [];
        $bulan_label = [];
        
        // Kita looping dari Bulan 1 (Januari) sampai 12 (Desember)
        for ($i = 1; $i <= 12; $i++) {
            // Label Bulan (Januari, Februari...)
            $bulan_label[] = Carbon::create()->month($i)->translatedFormat('F');
            
            // Hitung pemasukan per bulan berdasarkan tanggal pembayaran
            // Menggunakan tabel Pembayaran agar grafik sesuai realisasi cashflow
            $total_bulan_ini = Pembayaran::whereYear('created_at', date('Y'))
                                ->whereMonth('created_at', $i)
                                ->sum('jumlah_dibayar');
            
            $income_data[] = $total_bulan_ini;
        }


        // ---------------------------------------------------------
        // 3. DATA UNTUK GRAFIK "STATUS SPP BULAN INI" (Donut Chart)
        // ---------------------------------------------------------
        
        $bulan_ini = date('m');
        $tahun_ini = date('Y');

        $spp_lunas = Tagihan::whereMonth('created_at', $bulan_ini)
                            ->whereYear('created_at', $tahun_ini)
                            ->where('status', 'Lunas')
                            ->count();

        $spp_belum = Tagihan::whereMonth('created_at', $bulan_ini)
                            ->whereYear('created_at', $tahun_ini)
                            ->where('status', '!=', 'Lunas')
                            ->count();
        
        // Pencegahan error jika data masih kosong (biar grafik tetap muncul meski kosong)
        if ($spp_lunas == 0 && $spp_belum == 0) {
            $spp_belum = 1; // Dummy value agar grafik tidak crash
        }


        // ---------------------------------------------------------
        // 4. RIWAYAT TRANSAKSI TERBARU (Tabel Bawah)
        // ---------------------------------------------------------
        
        // Mengambil 5 pembayaran terakhir yang masuk
        $riwayat_terbaru = Pembayaran::with(['atlet', 'tagihan']) // Eager load relasi
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();


        // ---------------------------------------------------------
        // 5. KIRIM SEMUA VARIABEL KE VIEW
        // ---------------------------------------------------------
        return view('owner.dashboard', compact(
            'atlet_aktif', 
            'total_pemasukan', 
            'tagihan_belum_lunas',
            'income_data',      // Wajib untuk Grafik Garis
            'bulan_label',      // Wajib untuk Grafik Garis
            'spp_lunas',        // Wajib untuk Grafik Donat
            'spp_belum',        // Wajib untuk Grafik Donat
            'riwayat_terbaru'   // Wajib untuk Tabel Riwayat
        ));
    }
}