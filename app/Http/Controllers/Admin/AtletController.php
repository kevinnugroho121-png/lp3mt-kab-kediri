<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Atlet;
use App\Models\User; // <--- PENTING: Import Model User
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB; // <--- PENTING: Import DB
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AtletController extends Controller
{
    // === 1. INDEX ===
    public function index(Request $request)
    {
        $query = Atlet::query();

        if ($request->filled('search')) {
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $atlets = $query->latest()->paginate(10)->withQueryString();

        return view('admin.atlet.index', compact('atlets'));
    }

    public function create()
    {
        return view('admin.atlet.create');
    }

    // === 2. STORE (TAMBAH DATA + AKUN LOGIN) ===
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:8',
            'nama_lengkap'    => 'required|string|max:255',
            'jenjang_sekolah' => 'required|string',
            'nama_sekolah'    => 'required|string',
            'kategori'        => 'required|string',
            'tanggal_lahir'   => 'required|date',
            'status'          => 'required|string',
            'foto_profil'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Logika Umur
        $umur = Carbon::parse($request->tanggal_lahir)->age;
        $kategori = $request->kategori;
        $jenjang = $request->jenjang_sekolah;

        $rentangKU = [
            'KU-10' => ['min' => 5,  'max' => 10],
            'KU-12' => ['min' => 11, 'max' => 12],
            'KU-14' => ['min' => 13, 'max' => 14],
            'KU-16' => ['min' => 15, 'max' => 16],
            'KU-18' => ['min' => 17, 'max' => 18],
        ];

        if (isset($rentangKU[$kategori])) {
            if ($umur < $rentangKU[$kategori]['min'] || $umur > $rentangKU[$kategori]['max']) {
                return redirect()->back()->withInput()->withErrors([
                    'umur_salah' => "GAGAL: Umur $umur tahun tidak sesuai untuk $kategori."
                ]);
            }
        }

        if ($jenjang == 'SMP' && $umur < 11) {
            return redirect()->back()->withInput()->withErrors(['sekolah_salah' => 'Tidak Logis: Atlet SMP minimal 11 tahun.']);
        }
        if ($jenjang == 'SMA' && $umur < 14) {
            return redirect()->back()->withInput()->withErrors(['sekolah_salah' => 'Tidak Logis: Atlet SMA minimal 14 tahun.']);
        }
        if ($umur < 5) {
            return redirect()->back()->withInput()->withErrors(['sekolah_salah' => 'Atlet terlalu muda (Minimal 5 tahun).']);
        }

        // Cek Ganda
        $cekGanda = Atlet::where('nama_lengkap', $request->nama_lengkap)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->exists();

        if ($cekGanda) {
            return redirect()->back()->withInput()->withErrors(['ganda' => 'GAGAL: Atlet tersebut sudah terdaftar.']);
        }

        // PROSES TRANSAKSI (User + Atlet)
        DB::beginTransaction();

        try {
            $fotoPath = null;
            if ($request->hasFile('foto_profil')) {
                $fotoPath = $request->file('foto_profil')->store('foto-atlet', 'public');
            }

            // 1. Buat User
            $user = User::create([
                'name'     => $request->nama_lengkap,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'atlet',
            ]);

            // 2. Buat Atlet
            Atlet::create([
                'user_id'         => $user->id, 
                'nama_lengkap'    => $request->nama_lengkap,
                'nama_panggilan'  => $request->nama_panggilan,
                'tempat_lahir'    => $request->tempat_lahir,
                'tanggal_lahir'   => $request->tanggal_lahir,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'alamat'          => $request->alamat,
                'no_hp_atlet'     => $request->no_hp_atlet,
                'jenjang_sekolah' => $request->jenjang_sekolah,
                'nama_sekolah'    => $request->nama_sekolah,
                'kategori'        => $request->kategori,
                'posisi'          => $request->posisi,
                'status'          => $request->status,
                'nama_orang_tua'  => $request->nama_orang_tua,
                'no_hp_orang_tua' => $request->no_hp_orang_tua,
                'foto_profil'     => $fotoPath,
            ]);

            DB::commit();
            return redirect()->route('atlet.index')->with('success', 'Berhasil! Akun User dan Data Atlet telah dibuat.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // === 3. SHOW ===
    public function show(Atlet $atlet)
    {
        return view('admin.atlet.show', compact('atlet'));
    }

    public function edit(Atlet $atlet)
    {
        return view('admin.atlet.edit', compact('atlet'));
    }

    // === 4. UPDATE (EDIT DATA) ===
    public function update(Request $request, Atlet $atlet)
    {
        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'jenjang_sekolah' => 'required|string',
            'nama_sekolah'    => 'required|string',
            'kategori'        => 'required|string',
            'tanggal_lahir'   => 'required|date',
            'status'          => 'required|string',
            'foto_profil'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $umur = Carbon::parse($request->tanggal_lahir)->age;
        $kategori = $request->kategori;
        $jenjang = $request->jenjang_sekolah;

        // Validasi Logic (VERSI LENGKAP - BIAR ERRORNYA JELAS)
        $rentangKU = [
            'KU-10' => ['min' => 5,  'max' => 10],
            'KU-12' => ['min' => 11, 'max' => 12],
            'KU-14' => ['min' => 13, 'max' => 14],
            'KU-16' => ['min' => 15, 'max' => 16],
            'KU-18' => ['min' => 17, 'max' => 18],
        ];

        if (isset($rentangKU[$kategori])) {
            if ($umur < $rentangKU[$kategori]['min'] || $umur > $rentangKU[$kategori]['max']) {
                return redirect()->back()->withInput()->withErrors([
                    'umur_salah' => "Update Gagal: Umur $umur tahun tidak sesuai untuk $kategori."
                ]);
            }
        }

        if ($jenjang == 'SMP' && $umur < 11) {
            return redirect()->back()->withInput()->withErrors(['sekolah_salah' => 'Update Gagal: Atlet SMP minimal 11 tahun.']);
        }
        if ($jenjang == 'SMA' && $umur < 14) {
            return redirect()->back()->withInput()->withErrors(['sekolah_salah' => 'Update Gagal: Atlet SMA minimal 14 tahun.']);
        }

        // Cek Ganda
        $cekGanda = Atlet::where('nama_lengkap', $request->nama_lengkap)
            ->where('tanggal_lahir', $request->tanggal_lahir)
            ->where('id', '!=', $atlet->id)
            ->exists();

        if ($cekGanda) {
            return redirect()->back()->withInput()->withErrors(['ganda' => 'Data bentrok dengan atlet lain.']);
        }
        
        $data = $request->all();

        if ($request->hasFile('foto_profil')) {
            if ($atlet->foto_profil && Storage::disk('public')->exists($atlet->foto_profil)) {
                Storage::disk('public')->delete($atlet->foto_profil);
            }
            $path = $request->file('foto_profil')->store('foto-atlet', 'public');
            $data['foto_profil'] = $path;
        }

        $atlet->update($data);
        return redirect()->route('atlet.index')->with('success', 'Data atlet berhasil diperbarui.');
    }

    // === 5. DESTROY (HAPUS DATA + AKUN) ===
    public function destroy(Atlet $atlet)
    {
        // 1. Cari user yang nyantol
        $user = $atlet->user; 

        // 2. Hapus Data Atlet (Soft Delete)
        $atlet->delete();

        // 3. Hapus Akun Login (Jika ada)
        if ($user) {
            $user->delete();
        }

        return redirect()->route('atlet.index')->with('success', 'Data atlet dan akun login berhasil dihapus.');
    }

    // === 6. DOWNLOAD PDF ===
    public function downloadPDF($id)
    {
        $atlet = Atlet::findOrFail($id);
        $pdf = Pdf::loadView('admin.atlet.pdf_view', compact('atlet'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('Biodata-' . $atlet->nama_lengkap . '.pdf');
    }
}