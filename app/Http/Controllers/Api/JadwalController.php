<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal; // Pastikan Model ini ada

class JadwalController extends Controller
{
    public function index()
    {
        // 1. Ambil data jadwal
        // Kita gunakan 'orderBy' supaya urut berdasarkan TANGGAL latihan, bukan waktu input.
        // 'desc' = Tanggal paling baru di atas.
        // 'asc'  = Tanggal lama di atas (kalau mau urut dari hari ini ke depan).
        
        $jadwals = Jadwal::orderBy('tanggal', 'desc')->get();

        // 2. Kirim sebagai JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Jadwal',
            'data'    => $jadwals
        ], 200);
    }
}