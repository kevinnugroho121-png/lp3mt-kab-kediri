<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB; // <--- [BARU] Wajib dipanggil untuk akses tabel sessions
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. TAMPILKAN DAFTAR USER (+ FILTER & PEMANTAUAN SESI)
    public function index(Request $request)
    {
        $waktuBatasOnline = time() - 300; // 5 Menit terakhir dianggap online

        $query = User::with('kecamatan')
            // 1. Prioritaskan seluruh user yang SEDANG ONLINE (Aktif <= 5 menit) di paling atas
            ->orderByRaw("CASE WHEN (SELECT MAX(last_activity) FROM sessions WHERE sessions.user_id = users.id) >= ? THEN 1 ELSE 0 END DESC", [$waktuBatasOnline])
            // 2. Di antara yang online, urutkan dari yang paling baru bergerak/beraktivitas
            ->orderByRaw("(SELECT MAX(last_activity) FROM sessions WHERE sessions.user_id = users.id) DESC")
            // 3. Untuk user offline, urutkan dari yang paling baru logout/terakhir terlihat
            ->orderByDesc('last_seen_at')
            // 4. Logika bawaan tetap utuh: urutkan wilayah & jabatan untuk akun yang belum pernah login
            ->orderBy('kecamatan_id', 'asc')
            ->orderByRaw("FIELD(jabatan_korcam, 'Korcam', 'KORCAM', 'Ketua', 'KETUA', 'Anggota 1', 'ANGGOTA 1', 'Anggota 2', 'ANGGOTA 2') ASC, id ASC");

        // --- A. LOGIKA FILTER PINTAR ---
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('filter_role')) {
            $query->where('role', $request->filter_role);
        }

        if ($request->filled('filter_kecamatan')) {
            $query->where('kecamatan_id', $request->filter_kecamatan);
        }

        $users = $query->paginate(10)->withQueryString();

        // --- B. LOGIKA PENYADAPAN STATUS ONLINE & DEVICE ---
        $userIds = $users->pluck('id');
        
        $sessions = DB::table('sessions')
            ->whereIn('user_id', $userIds)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->groupBy('user_id');

        $waktuBatasOnline = time() - 300; // 5 Menit terakhir dianggap online

        foreach ($users as $user) {
            $sesiUser = $sessions->get($user->id)?->first(); 
            
            if ($sesiUser) {
                // Jika masih ada sisa sesi di tabel sessions
                $user->is_online = $sesiUser->last_activity >= $waktuBatasOnline;
                $user->last_seen = \Carbon\Carbon::createFromTimestamp($sesiUser->last_activity)->diffForHumans();
                $user->ip_address = $sesiUser->ip_address;
                $user->perangkat = $this->parseUserAgent($sesiUser->user_agent);
            } else {
                // Jika orangnya benar-benar sudah logout (tidak ada sesi)
                $user->is_online = false;
                
                // [FIX] Cek ingatan permanen dari tabel users
                if ($user->last_seen_at) {
                    $waktu = \Carbon\Carbon::parse($user->last_seen_at);
                    // Output akan seperti ini: "5 menit yang lalu (19:45)"
                    $user->last_seen = $waktu->diffForHumans() . ' (' . $waktu->format('H:i') . ')';
                } else {
                    $user->last_seen = 'Belum pernah login';
                }
                
                $user->ip_address = '-';
                $user->perangkat = '-';
            }
        }

        $data_kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();

        return view('admin.user.index', compact('users', 'data_kecamatan'));
    }

    // 2. FORM TAMBAH USER
    public function create()
    {
        // Mengambil semua kecamatan beserta relasi user yang ber-role 'korcam'
        $kecamatans = Kecamatan::with(['users' => function($query) {
            $query->where('role', 'korcam');
        }])->orderBy('nama_kecamatan', 'ASC')->get();

        return view('admin.user.create', compact('kecamatans'));
    }

    // 3. SIMPAN USER BARU
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required',
            
            // Validasi: Kecamatan & Jabatan WAJIB diisi JIKA role = korcam
            'kecamatan_id'   => 'required_if:role,korcam',
            'jabatan_korcam' => 'required_if:role,korcam', 
        ]);

        $kecamatan_id = null;
        $jabatan_korcam = null;

        // Jika Role yang dipilih adalah KORCAM, ambil datanya
        if ($request->role == 'korcam') {
            $kecamatan_id   = $request->kecamatan_id;
            $jabatan_korcam = $request->jabatan_korcam; // Isinya 'Korcam', 'Anggota 1', atau 'Anggota 2'
            
            // Double check: periksa jabatan 'Korcam' maupun sebutan lama 'Ketua'
            $targetJabatan = ($jabatan_korcam == 'Korcam' || $jabatan_korcam == 'Ketua') ? ['Korcam', 'Ketua'] : [$jabatan_korcam];

            $exists = User::where('role', 'korcam')
                          ->where('kecamatan_id', $kecamatan_id)
                          ->whereIn('jabatan_korcam', $targetJabatan)
                          ->exists();
            
            if ($exists) {
                return back()->withInput()->with('error', "Posisi $jabatan_korcam di kecamatan tersebut sudah terisi!");
            }
        }

        // Simpan ke Database dan tampung di variabel $user
        $user = User::create([
            'name'           => strtoupper($request->name),    // [FIX] Paksa Huruf Besar
            'email'          => strtolower($request->email),   // [FIX] Paksa Huruf Kecil
            'password'       => Hash::make($request->password),
            'role'           => $request->role,
            'kecamatan_id'   => $kecamatan_id,
            'jabatan_korcam' => $jabatan_korcam, 
        ]);

        // [TAMBAHAN BARU] Pancing sistem untuk ngirim email verifikasi!
        event(new Registered($user));

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan dan email verifikasi telah dikirim!');
    }

    // 4. HAPUS USER
    public function destroy(User $user)
    {
        // Cegah hapus akun sendiri
        if (Auth::id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        // [BARU] Hapus sesi aktif user ini di tabel sessions, agar jika dia masih online, dia langsung tertendang!
        DB::table('sessions')->where('user_id', $user->id)->delete();

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus dan diputus dari sistem.');
    }

    // ==========================================================
    // 5. CEK KETERSEDIAAN POSISI (AJAX)
    // ==========================================================
    public function checkKorcamAvailability(Request $request)
    {
        $kecamatanId = $request->kecamatan_id;
        $jabatan = $request->jabatan; 

        // Menyelaraskan pencarian jika jabatan bernilai 'Korcam' atau 'Ketua'
        $targetJabatan = ($jabatan == 'Korcam' || $jabatan == 'Ketua') ? ['Korcam', 'Ketua'] : [$jabatan];

        $exists = User::where('role', 'korcam')
                      ->where('kecamatan_id', $kecamatanId)
                      ->whereIn('jabatan_korcam', $targetJabatan)
                      ->exists();

        return response()->json(['exists' => $exists]);
    }

    // ==========================================================
    // 6. [BARU] FITUR SAPU JAGAT (RESET PERANGKAT)
    // ==========================================================
    public function resetDevice($id)
    {
        // Hapus paksa semua sidik jari perangkat milik user ini dari buku tamu
        DB::table('sessions')->where('user_id', $id)->delete();
        
        return back()->with('success', 'Berhasil! Seluruh perangkat untuk akun ini telah di-reset (di-logout paksa). Slot login kembali kosong.');
    }

    // ==========================================================
    // 7. [BARU] FUNGSI BANTUAN MEMBACA NAMA PERANGKAT & OS
    // ==========================================================
    private function parseUserAgent($userAgent) {
        if (!$userAgent) return 'Tidak Diketahui';
        $browser = 'Browser Lain';
        $os = 'OS Lain';

        if (preg_match('/Edge/i', $userAgent)) { $browser = 'Edge'; }
        elseif (preg_match('/Firefox/i', $userAgent)) { $browser = 'Firefox'; }
        elseif (preg_match('/Chrome/i', $userAgent)) { $browser = 'Chrome'; }
        elseif (preg_match('/Safari/i', $userAgent)) { $browser = 'Safari'; }
        
        if (preg_match('/Windows/i', $userAgent)) { $os = 'Windows'; }
        elseif (preg_match('/Mac/i', $userAgent)) { $os = 'Mac/iOS'; }
        elseif (preg_match('/Linux/i', $userAgent)) { $os = 'Linux'; }
        elseif (preg_match('/Android/i', $userAgent)) { $os = 'Android'; }

        return "$browser di $os";
    }

    // ==========================================================
    // 8. [BARU] FITUR RESET PASSWORD MANUAL OLEH SUPERADMIN
    // ==========================================================
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        
        // Mengembalikan password menjadi default: kediri2026
        $user->password = Hash::make('kediri2026');
        $user->save();

        return back()->with('success', "Berhasil! Password untuk akun {$user->name} telah direset menjadi: kediri2026");
    }
}