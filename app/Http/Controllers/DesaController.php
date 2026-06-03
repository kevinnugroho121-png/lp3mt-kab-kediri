<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // [PENTING] Wajib ada

class DesaController extends Controller
{
    /**
     * Tampilkan data desa (Lengkap dengan Filter & Pagination & Proteksi)
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil user yang login

        // 1. Mulai Query
        $query = Desa::with('kecamatan');

        // 2. LOGIKA KORCAM (KACAMATA KUDA)
        // Jika Korcam, paksa filter hanya kecamatannya sendiri
        if ($user->role == 'korcam') {
            $query->where('kecamatan_id', $user->kecamatan_id);
        } 
        // Jika Admin, boleh pakai filter dropdown
        elseif ($request->has('kecamatan_id') && $request->kecamatan_id != '') {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        // 3. Pencarian
        if ($request->filled('search')) {
            $query->where('nama_desa', 'like', '%' . $request->search . '%');
        }

        // 4. Ambil Data
        $desas = $query->orderBy('nama_desa', 'asc')->paginate(20)->withQueryString();

        // 5. Data Dropdown Kecamatan (Untuk Filter di Index)
        if ($user->role == 'korcam') {
            // Korcam cuma butuh datanya sendiri
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->get();
        } else {
            // Admin butuh semua
            $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();
        }

        return view('admin.desa.index', compact('desas', 'kecamatans'));
    }

    /**
     * Form tambah desa
     */
    public function create()
    {
        $user = Auth::user();

        // [PROTEKSI] Dropdown Create
        // Jika Korcam, kirim cuma 1 kecamatan (miliknya)
        if ($user->role == 'korcam') {
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->get();
        } else {
            $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();
        }

        return view('admin.desa.create', compact('kecamatans'));
    }

    /**
     * Simpan data desa
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // [PROTEKSI BACKEND]
        // Jika Korcam, PAKSA kecamatan_id pakai punya dia.
        // Abaikan input dari form (untuk mencegah manipulasi inspect element)
        if ($user->role == 'korcam') {
            $request->merge(['kecamatan_id' => $user->kecamatan_id]);
        }

        $request->validate([
            'nama_desa'    => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        // Contoh di fungsi store / update Desa
        Desa::create([
            'kecamatan_id' => $request->kecamatan_id,
            'nama_desa' => strtoupper($request->nama_desa)
        ]);

        return redirect()->route('desa.index')->with('success', 'Data desa berhasil ditambahkan');
    }

    /**
     * Form edit desa (Tambahan agar tombol pensil berfungsi)
     */
    public function edit(Desa $desa)
    {
        $user = Auth::user();

        // [PROTEKSI URL] Korcam gaboleh edit desa kecamatan lain
        if ($user->role == 'korcam' && $desa->kecamatan_id != $user->kecamatan_id) {
            abort(403, 'Akses Ditolak: Ini bukan wilayah Anda.');
        }

        if ($user->role == 'korcam') {
            $kecamatans = Kecamatan::where('id', $user->kecamatan_id)->get();
        } else {
            $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        }

        return view('admin.desa.edit', compact('desa', 'kecamatans'));
    }

    /**
     * Update data desa (Tambahan)
     */
    public function update(Request $request, Desa $desa)
    {
        $user = Auth::user();

        // [PROTEKSI UPDATE]
        if ($user->role == 'korcam') {
            // Cek otorisasi
            if ($desa->kecamatan_id != $user->kecamatan_id) { abort(403); }
            // Paksa ID tetap punya dia
            $request->merge(['kecamatan_id' => $user->kecamatan_id]);
        }

        $request->validate([
            'nama_desa'    => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        // [FIX] Pasang strtoupper juga saat update data
        $desa->update([
            'nama_desa'    => strtoupper($request->nama_desa),
            'kecamatan_id' => $request->kecamatan_id,
        ]);

        return redirect()->route('desa.index')->with('success', 'Data desa berhasil diperbarui');
    }

    /**
     * Hapus data desa (Tambahan)
     */
    public function destroy(Desa $desa)
    {
        // [PROTEKSI HAPUS]
        // Verifikator gaboleh hapus
        if (Auth::user()->role == 'verifikator') {
            return back()->with('error', 'Verifikator tidak boleh menghapus data.');
        }
        
        // Korcam gaboleh hapus desa orang lain
        if (Auth::user()->role == 'korcam' && $desa->kecamatan_id != Auth::user()->kecamatan_id) {
            abort(403);
        }

        $desa->delete();
        return redirect()->route('desa.index')->with('success', 'Data desa berhasil dihapus');
    }
}