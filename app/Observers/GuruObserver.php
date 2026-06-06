<?php

namespace App\Observers;

use App\Models\Guru;
use App\Models\Lembaga;

class GuruObserver
{
    /**
     * Fungsi pembantu untuk menghitung ulang (re-calculate) statistik guru di lembaga
     */
    private function sinkronisasiKuotaLembaga($lembagaId)
    {
        if (!$lembagaId) return;

        $lembaga = Lembaga::find($lembagaId);
        if ($lembaga) {
            // 1. Hitung total semua guru di lembaga ini
            $totalGuru = Guru::where('lembaga_id', $lembagaId)->count();

            // 2. Hitung jumlah PNS
            $pns = Guru::where('lembaga_id', $lembagaId)
                       ->where('status_kepegawaian', 'PNS')
                       ->count();

            // 3. Hitung jumlah PPPK (Gabungan PPPK + PPPK Paruh Waktu)
            $pppk = Guru::where('lembaga_id', $lembagaId)
                        ->where(function($q) {
                            $q->where('status_kepegawaian', 'PPPK')
                              ->orWhere('status_kepegawaian', 'LIKE', '%PPPK PARUH WAKTU%');
                        })->count();

            // 4. Hitung jumlah yang sudah Sertifikasi
            $sertifikasi = Guru::where('lembaga_id', $lembagaId)
                               ->where('status_sertifikasi', 'SERTIFIKASI')
                               ->count();

            // 5. Hitung Guru yang di sistem saat ini berstatus non-insentif (penerima_insentif = 0)
            $belumInsentif = Guru::where('lembaga_id', $lembagaId)
                                 ->where('penerima_insentif', 0)
                                 ->count();

            // SUNTIKKAN LANGSUNG KE TABEL LEMBAGA
            // [FIXED] Kolom 'penerima_insentif' DIBUANG dari update agar kuota asli dari Superadmin tidak hilang!
            $lembaga->update([
                'jumlah_guru'             => $totalGuru,
                'jumlah_pns'              => $pns,
                'jumlah_pppk'             => $pppk,
                'jumlah_sertifikasi'      => $sertifikasi,
                'belum_menerima_insentif' => $belumInsentif,
            ]);
        }
    }

    /**
     * Otomatis jalan SETELAH data Guru baru berhasil disimpan (Manual maupun Excel)
     */
    public function created(Guru $guru)
    {
        $this->sinkronisasiKuotaLembaga($guru->lembaga_id);
    }

    /**
     * Otomatis jalan SETELAH data Guru diedit/diperbarui
     */
    public function updated(Guru $guru)
    {
        $this->sinkronisasiKuotaLembaga($guru->lembaga_id);
        
        // Jika ternyata guru pindah lembaga, update juga statistik lembaga lamanya
        if ($guru->isDirty('lembaga_id')) {
            $this->sinkronisasiKuotaLembaga($guru->getOriginal('lembaga_id'));
        }
    }

    /**
     * Otomatis jalan SETELAH data Guru dihapus dari sistem
     */
    public function deleted(Guru $guru)
    {
        $this->sinkronisasiKuotaLembaga($guru->lembaga_id);
    }
}