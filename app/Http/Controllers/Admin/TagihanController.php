<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Atlet;
use App\Models\Notifikasi; // <--- WAJIB ADA: Biar bisa kirim notif
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // WAJIB ADA: Untuk upload foto

class TagihanController extends Controller
{
    // === 1. TAMPILKAN DAFTAR TAGIHAN ===
    public function index(Request $request)
    {
        $query = Tagihan::with('atlet');

        // Filter Pencarian Nama
        if ($request->filled('search')) {
            $query->whereHas('atlet', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Urutan: Yang Belum Lunas ditaruh paling atas, lalu urut berdasarkan Tahun & Bulan terbaru
        $tagihans = $query->orderBy('status', 'asc') // 'Belum Lunas' (B) lebih dulu dari 'Lunas' (L)
                          ->orderBy('tahun', 'desc')
                          ->orderBy('bulan', 'desc')
                          ->paginate(10)
                          ->withQueryString();

        return view('admin.tagihan.index', compact('tagihans'));
    }

    // === 2. FORM BUAT TAGIHAN BARU ===
    public function create()
    {
        $atlets = Atlet::where('status', 'Aktif')->orderBy('nama_lengkap', 'asc')->get();
        return view('admin.tagihan.create', compact('atlets'));
    }

    // === 3. SIMPAN TAGIHAN + KIRIM NOTIFIKASI ===
    public function store(Request $request)
    {
        $request->validate([
            'atlet_id' => 'required|exists:atlets,id',
            'bulan'    => 'required|integer|min:1|max:12',     // Input Angka 1-12
            'tahun'    => 'required|integer|min:2024|max:2030', // Input Tahun
            'nominal'  => 'required|numeric|min:0',
        ]);

        // CEK DUPLIKAT: Apakah atlet ini sudah punya tagihan di Bulan & Tahun tersebut?
        $cek = Tagihan::where('atlet_id', $request->atlet_id)
                      ->where('bulan', $request->bulan)
                      ->where('tahun', $request->tahun)
                      ->exists();

        if ($cek) {
            // Jika sudah ada, tolak dan kembalikan ke form
            return back()->withErrors(['duplikat' => 'GAGAL: Tagihan SPP untuk Bulan & Tahun tersebut sudah dibuat sebelumnya!']);
        }

        // Simpan Data Tagihan
        $tagihan = Tagihan::create([
            'atlet_id'        => $request->atlet_id,
            'jenis_tagihan'   => 'SPP', // Default otomatis SPP
            'bulan'           => $request->bulan,
            'tahun'           => $request->tahun,
            'nominal'         => $request->nominal,
            'tanggal_tagihan' => now(), // Tanggal hari ini
            'status'          => 'Belum Lunas',
        ]);

        // --- TRIGGER NOTIFIKASI: TAGIHAN BARU ---
        // Cari User ID milik Atlet ini (agar notif masuk ke akun yang benar)
        $atlet = Atlet::find($request->atlet_id);
        
        // Pastikan atlet ditemukan dan punya user_id (akun login)
        if ($atlet && $atlet->user_id) {
            Notifikasi::create([
                'user_id' => $atlet->user_id,
                'judul'   => 'Tagihan SPP Baru 🔔',
                'pesan'   => 'Admin telah menerbitkan Tagihan SPP Bulan ' . $request->bulan . '/' . $request->tahun . '. Mohon segera dicek.',
                'tipe'    => 'tagihan', // Warna Merah (Peringatan)
                'is_read' => false,     // Biar muncul Pop-up
                'link'    => route('dashboard'), // Arahkan ke dashboard atlet
            ]);
        }

        return redirect()->route('tagihan.index')->with('success', 'Tagihan SPP berhasil dibuat & Notifikasi dikirim ke Atlet.');
    }

    // === 4. FORM EDIT / BAYAR (KASIR) ===
    public function edit(Tagihan $tagihan)
    {
        return view('admin.tagihan.edit', compact('tagihan'));
    }

    // === 5. UPDATE (PROSES PEMBAYARAN + NOTIFIKASI LUNAS) ===
    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'status'            => 'required|in:Lunas,Belum Lunas',
            'metode_pembayaran' => 'nullable|string',
            'bukti_pembayaran'  => 'nullable|image|max:2048', // Maksimal 2MB (JPG, PNG)
            'keterangan'        => 'nullable|string',
        ]);

        $data = [
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ];

        // --- SKENARIO 1: ADMIN KLIK "LUNAS" ---
        if ($request->status == 'Lunas') {
            
            // Validasi: Wajib pilih metode pembayaran
            if (!$request->metode_pembayaran && !$tagihan->metode_pembayaran) {
                return back()->withErrors(['metode' => 'Metode pembayaran wajib dipilih jika status Lunas.']);
            }
            
            $data['metode_pembayaran'] = $request->metode_pembayaran;
            $data['tanggal_lunas'] = $tagihan->tanggal_lunas ?? now(); // Isi tanggal lunas hari ini

            // LOGIKA UPLOAD FOTO BUKTI
            if ($request->hasFile('bukti_pembayaran')) {
                // Hapus foto lama jika ada (biar memori server gak penuh)
                if ($tagihan->bukti_pembayaran) {
                    Storage::disk('public')->delete($tagihan->bukti_pembayaran);
                }
                // Simpan foto baru
                $path = $request->file('bukti_pembayaran')->store('bukti-bayar', 'public');
                $data['bukti_pembayaran'] = $path;
            }
            
            // Validasi Ketat: Kalau Lunas tapi gak ada bukti sama sekali (baik lama atau baru)
            if (empty($data['bukti_pembayaran']) && empty($tagihan->bukti_pembayaran)) {
                return back()->withErrors(['bukti' => 'Bukti Pembayaran (Foto Struk/Uang) WAJIB di-upload sebagai syarat sah Lunas.']);
            }

            // --- TRIGGER NOTIFIKASI: PEMBAYARAN SUKSES ---
            // Hanya kirim notif jika status sebelumnya "Belum Lunas" (Biar gak spam kalau edit ulang)
            if ($tagihan->status == 'Belum Lunas') {
                $atlet = $tagihan->atlet;
                if ($atlet && $atlet->user_id) {
                    Notifikasi::create([
                        'user_id' => $atlet->user_id,
                        'judul'   => 'Pembayaran Lunas ✅',
                        'pesan'   => 'Terima kasih! Pembayaran ' . $tagihan->judul_tagihan . ' telah kami terima dan diverifikasi.',
                        'tipe'    => 'sukses', // Warna Hijau
                        'is_read' => false,    // Biar muncul Pop-up
                        'link'    => route('dashboard'),
                    ]);
                }
            }

        } 
        // --- SKENARIO 2: ADMIN BATALKAN LUNAS (ROLLBACK) ---
        elseif ($request->status == 'Belum Lunas') {
            $data['tanggal_lunas'] = null;
            $data['metode_pembayaran'] = null;
            // Bukti pembayaran tidak dihapus otomatis demi keamanan arsip, tapi status kembali merah.
        }

        $tagihan->update($data);

        return redirect()->route('tagihan.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    // === 6. HAPUS TAGIHAN ===
    public function destroy(Tagihan $tagihan)
    {
        // Hapus file foto buktinya juga biar bersih
        if ($tagihan->bukti_pembayaran) {
            Storage::disk('public')->delete($tagihan->bukti_pembayaran);
        }
        
        $tagihan->delete();
        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil dihapus.');
    }
}