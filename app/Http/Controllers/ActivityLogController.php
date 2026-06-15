<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('activity_logs')->orderBy('created_at', 'desc');

        // Fitur Pencarian Log
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_user', 'like', "%{$search}%")
                  ->orWhere('aksi', 'like', "%{$search}%")
                  ->orWhere('target', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.activity-log.index', compact('logs'));
    }

    public function clear()
    {
        // Fitur darurat untuk membersihkan log jika sudah terlalu penuh
        DB::table('activity_logs')->truncate();
        return back()->with('success', 'Seluruh riwayat log aktivitas berhasil dibersihkan.');
    }
}