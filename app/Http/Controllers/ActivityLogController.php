<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data master kecamatan untuk dropdown
        $kecamatans = DB::table('kecamatans')->orderBy('nama_kecamatan')->get();

        $query = DB::table('activity_logs')->orderBy('created_at', 'desc');

        // Fitur Pencarian Log (Nama Operator, Aksi, Target)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_user', 'like', "%{$search}%")
                  ->orWhere('aksi', 'like', "%{$search}%")
                  ->orWhere('target', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Kecamatan (Menyaring log berdasarkan wilayah tugas operator)
        if ($request->filled('filter_kecamatan')) {
            $kecId = $request->filter_kecamatan;
            $query->whereIn('user_id', function($sub) use ($kecId) {
                $sub->select('id')->from('users')->where('kecamatan_id', $kecId);
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        // 2. Kirim $kecamatans ke view bersama $logs
        return view('admin.activity-log.index', compact('logs', 'kecamatans'));
    }

    public function clear()
    {
        // Fitur darurat untuk membersihkan log jika sudah terlalu penuh
        DB::table('activity_logs')->truncate();
        return back()->with('success', 'Seluruh riwayat log aktivitas berhasil dibersihkan.');
    }
}