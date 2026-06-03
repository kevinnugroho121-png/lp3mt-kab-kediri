<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Atlet;
use App\Models\Pelatih;
use App\Models\ProgresAtlet;

class ProgresAtletController extends Controller
{
    // 1. HALAMAN DAFTAR ATLET (INDEX) - DENGAN FITUR CARI & FILTER
    public function index(Request $request)
    {
        // A. Mulai Query (Ambil data Atlet)
        // Kita hanya ambil atlet yang statusnya 'Aktif'
        $query = Atlet::where('status', 'Aktif');

        // B. Logika Pencarian (Jika ada input 'search')
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_panggilan', 'like', "%{$search}%");
            });
        }

        // C. Logika Filter Kategori (Jika ada input 'kategori')
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // D. Eksekusi Data dengan Pagination (10 per halaman)
        // withQueryString() berguna agar saat pindah ke halaman 2, filter pencarian tidak hilang
        $atlets = $query->orderBy('nama_lengkap', 'asc')
                        ->paginate(10)
                        ->withQueryString();

        return view('pelatih.progres.index', compact('atlets'));
    }

    // 2. HALAMAN FORMULIR INPUT NILAI (CREATE)
    public function create($id)
    {
        // Cari data atlet berdasarkan ID yang diklik
        $atlet = Atlet::findOrFail($id);
        $user = Auth::user();
        
        // Ambil data pelatih yang sedang login
        $pelatih = Pelatih::where('user_id', $user->id)->first();

        // Cek jika data pelatih belum lengkap
        if (!$pelatih) {
            return redirect()->back()->with('error', 'Profil Pelatih Anda belum lengkap. Hubungi Admin.');
        }

        return view('pelatih.progres.create', compact('atlet', 'pelatih'));
    }

    // 3. SIMPAN DATA KE DATABASE (STORE)
    public function store(Request $request)
    {
        $request->validate([
            'atlet_id'   => 'required',
            'pelatih_id' => 'required',
            'tanggal'    => 'required|date',
            // Validasi Nilai (0 - 100)
            'teknik'     => 'required|numeric|min:0|max:100',
            'fisik'      => 'required|numeric|min:0|max:100',
            'mental'     => 'required|numeric|min:0|max:100',
            'taktik'     => 'required|numeric|min:0|max:100',
        ]);

        ProgresAtlet::create([
            'pelatih_id' => $request->pelatih_id,
            'atlet_id'   => $request->atlet_id,
            'tanggal'    => $request->tanggal,
            'teknik'     => $request->teknik,
            'fisik'      => $request->fisik,
            'mental'     => $request->mental,
            'taktik'     => $request->taktik,
            'catatan'    => $request->catatan,
        ]);

        return redirect()->route('pelatih.progres.index')
                         ->with('success', 'Rapor nilai atlet berhasil disimpan! 🚀');
    }
}