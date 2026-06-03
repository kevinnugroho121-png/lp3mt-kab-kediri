<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Pelatih;

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal dengan Filter Lengkap.
     */
    public function index(Request $request)
    {
        // 1. Siapkan query
        // 'withTrashed' agar nama pelatih yang sudah dihapus tetap muncul di histori jadwal
        $query = Jadwal::with(['pelatih' => function ($q) {
            $q->withTrashed(); 
        }]);

        // 2. Filter Kategori (Jika ada)
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 3. Filter Rentang Tanggal
        if ($request->filled('mulai_tanggal') && $request->filled('sampai_tanggal')) {
            $query->whereBetween('tanggal', [$request->mulai_tanggal, $request->sampai_tanggal]);
        } elseif ($request->filled('mulai_tanggal')) {
            $query->where('tanggal', '>=', $request->mulai_tanggal);
        } elseif ($request->filled('sampai_tanggal')) {
            $query->where('tanggal', '<=', $request->sampai_tanggal);
        }

        // 4. Ambil data
        $jadwals = $query->orderBy('tanggal', 'desc')
                         ->orderBy('jam_mulai', 'asc')
                         ->paginate(10)
                         ->withQueryString(); 

        return view('admin.jadwal.index', compact('jadwals'));
    }

    /**
     * Form tambah jadwal.
     */
    public function create()
    {
        $pelatihs = Pelatih::where('status', 'Aktif')->get();
        return view('admin.jadwal.create', compact('pelatihs'));
    }

    /**
     * Simpan jadwal baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (SUDAH DIPERBAIKI: Wajib Hari Ini atau Masa Depan)
        $request->validate([
            'tanggal'     => 'required|date|after_or_equal:today', 
            'kategori'    => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai', 
            'lokasi'      => 'required',
            'status'      => 'required',
            'pelatih_id'  => 'required|exists:pelatihs,id',
        ]);

        // 2. Cek Bentrok (Satpam Waktu)
        $bentrok = Jadwal::where('pelatih_id', $request->pelatih_id)
            ->where('tanggal', $request->tanggal)
            ->where(function ($query) use ($request) {
                // Rumus Irisan Waktu: (Start1 < End2) AND (End1 > Start2)
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->first();

        if ($bentrok) {
            $namaPelatih = Pelatih::find($request->pelatih_id)->nama_lengkap;
            return redirect()->back()
                ->withInput()
                ->withErrors(['pelatih_id' => "❌ GAGAL: Coach $namaPelatih sudah ada jadwal lain di jam tersebut ($bentrok->jam_mulai - $bentrok->jam_selesai)."]);
        }
        
        // 3. Simpan
        Jadwal::create($request->all());
        
        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal latihan berhasil ditambahkan!');
    }

    /**
     * Form edit jadwal.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $pelatihs = Pelatih::where('status', 'Aktif')->get();
        return view('admin.jadwal.edit', compact('jadwal', 'pelatihs'));
    }

    /**
     * Update jadwal.
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // 1. Validasi (SUDAH DIPERBAIKI: Wajib Hari Ini atau Masa Depan)
        $request->validate([
            'tanggal'     => 'required|date|after_or_equal:today',
            'kategori'    => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'lokasi'      => 'required',
            'status'      => 'required',
            'pelatih_id'  => 'required|exists:pelatihs,id',
        ]);

        // 2. Cek Bentrok (Kecuali jadwal ini sendiri)
        $bentrok = Jadwal::where('pelatih_id', $request->pelatih_id)
            ->where('tanggal', $request->tanggal)
            ->where('id', '!=', $id) 
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->first();

        if ($bentrok) {
            $namaPelatih = Pelatih::find($request->pelatih_id)->nama_lengkap;
            return redirect()->back()
                ->withInput()
                ->withErrors(['pelatih_id' => "❌ GAGAL: Coach $namaPelatih bentrok dengan jadwal lain ($bentrok->jam_mulai - $bentrok->jam_selesai)."]);
        }
        
        $jadwal->update($request->all());
        
        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal.
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();
        
        return redirect()->route('jadwal.index')
            ->with('success', 'Data jadwal berhasil dihapus.');
    }
}