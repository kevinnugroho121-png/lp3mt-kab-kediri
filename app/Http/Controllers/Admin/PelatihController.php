<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatih;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // <--- WAJIB: Untuk Upload/Hapus Foto

class PelatihController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelatih::query();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        $pelatihs = $query->latest()->paginate(10);
        return view('admin.pelatih.index', compact('pelatihs'));
    }

    public function create()
    {
        return view('admin.pelatih.create');
    }

    // === STORE: BUAT AKUN + DATA PELATIH + VALIDASI UMUR + FOTO ===
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            // Akun Login
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:8',
            
            // Data Pelatih
            'nama_lengkap'    => 'required|string|max:255',
            'tanggal_lahir'   => 'required|date|before:-18 years', // <--- [BARU] Minimal 18 Tahun
            'no_hp'           => 'required|numeric',
            'status'          => 'required',
            'foto_profil'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // <--- [BARU] Validasi Foto
        ], [
            // Pesan Error Custom (Bahasa Indonesia)
            'tanggal_lahir.before' => 'Maaf, Umur Coach minimal harus 18 tahun.',
            'foto_profil.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        // 2. Cek Ganda
        $cekGanda = Pelatih::where('nama_lengkap', $request->nama_lengkap)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->exists();
        
        if($cekGanda) {
            return redirect()->back()->withInput()
                ->withErrors(['ganda' => 'GAGAL: Coach dengan Nama dan Tanggal Lahir tersebut sudah terdaftar.']);
        }

        // 3. PROSES SIMPAN (TRANSAKSI)
        DB::beginTransaction();
        try {
            // A. Upload Foto (Jika Ada)
            $fotoPath = null;
            if ($request->hasFile('foto_profil')) {
                // Simpan ke folder 'storage/app/public/foto-pelatih'
                $fotoPath = $request->file('foto_profil')->store('foto-pelatih', 'public');
            }

            // B. Buat User Login
            $user = User::create([
                'name'     => $request->nama_lengkap,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'pelatih',
            ]);

            // C. Buat Data Pelatih (Link ke User)
            Pelatih::create([
                'user_id'       => $user->id,
                'nama_lengkap'  => $request->nama_lengkap,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp'         => $request->no_hp,
                'status'        => $request->status,
                'foto_profil'   => $fotoPath, // <--- [BARU] Simpan Path Foto
            ]);

            DB::commit();
            return redirect()->route('pelatih.index')->with('success', 'Berhasil! Akun dan Data Coach telah dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
            // Hapus foto jika gagal simpan DB (biar sampah gak numpuk)
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Pelatih $pelatih)
    {
        return view('admin.pelatih.edit', compact('pelatih'));
    }

    // === UPDATE: UPDATE DATA + FOTO + VALIDASI UMUR ===
    public function update(Request $request, Pelatih $pelatih)
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:-18 years', // <--- [BARU] Cek Umur
            'no_hp'         => 'required|numeric',
            'status'        => 'required',
            'foto_profil'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // <--- [BARU] Validasi Foto
        ], [
            'tanggal_lahir.before' => 'Maaf, Umur Coach minimal harus 18 tahun.',
        ]);

        // Cek Ganda (Kecuali diri sendiri)
        $cekGanda = Pelatih::where('nama_lengkap', $request->nama_lengkap)
                ->where('tanggal_lahir', $request->tanggal_lahir)
                ->where('id', '!=', $pelatih->id)
                ->exists();

        if($cekGanda) {
            return redirect()->back()->withInput()
                ->withErrors(['ganda' => 'GAGAL: Data bentrok dengan coach lain.']);
        }

        // Ambil semua data input kecuali foto
        $data = $request->except(['foto_profil']);

        // LOGIKA UPLOAD FOTO BARU
        if ($request->hasFile('foto_profil')) {
            // 1. Hapus foto lama jika ada
            if ($pelatih->foto_profil && Storage::disk('public')->exists($pelatih->foto_profil)) {
                Storage::disk('public')->delete($pelatih->foto_profil);
            }
            // 2. Simpan foto baru
            $path = $request->file('foto_profil')->store('foto-pelatih', 'public');
            $data['foto_profil'] = $path; // Masukkan path baru ke array data
        }

        // Update Database
        $pelatih->update($data);

        // Update Nama di tabel User juga (opsional, biar sinkron)
        if($pelatih->user) {
            $pelatih->user->update(['name' => $request->nama_lengkap]);
        }

        return redirect()->route('pelatih.index')->with('success', 'Data coach berhasil diperbarui.');
    }

    // === DESTROY: HAPUS PELATIH + HAPUS USER + HAPUS FOTO ===
    public function destroy(Pelatih $pelatih)
    {
        // 1. Hapus Foto Profil dari Storage (JIKA ADA)
        if ($pelatih->foto_profil && Storage::disk('public')->exists($pelatih->foto_profil)) {
            Storage::disk('public')->delete($pelatih->foto_profil);
        }

        // Cari user yang nyantol
        $user = $pelatih->user;

        // Hapus Pelatih
        $pelatih->delete();

        // Hapus User Login
        if ($user) {
            $user->delete();
        }
        
        return redirect()->route('pelatih.index')->with('success', 'Data coach dan akun login berhasil dihapus.');
    }
}