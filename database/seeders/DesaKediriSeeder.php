<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\Desa;

class DesaKediriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data master desa berdasarkan kecamatan dari klien
        $dataWilayah = [
            'BADAS' => ['BADAS', 'BLARU', 'BRINGIN', 'CANGGU', 'KRECEK', 'LAMONG', 'SEKOTO', 'TUNGLUR'],
            'BANYAKAN' => ['BANYAKAN', 'JABON', 'JATIREJO', 'MANYARAN', 'MARON', 'NGABLAK', 'PARANG', 'SENDANG', 'TIRON'],
            'GAMPENGREJO' => ['GAMPENG', 'JONGBIRU', 'KALIBELO', 'KEPUHREJO', 'NGEBRAK', 'PLOSOREJO', 'PUTIH', 'SAMBIREJO', 'SAMBIRESIK', 'TURUS', 'WANENGPATEN'],
            'GROGOL' => ['BAKALAN', 'CERME', 'DATENGAN', 'GAMBYOK', 'GROGOL', 'KALIPANG', 'SONOREJO', 'SUMBEREJO', 'WONOASRI'],
            'GURAH' => ['ADAN-ADAN', 'BANGKOK', 'BANYUANYAR', 'BESUK', 'BLIMBING', 'BOGEM', 'GABRU', 'GAYAM', 'GEMPOLAN', 'GURAH', 'KERKEP', 'KRANGGAN', 'NGASEM', 'NGLUMBANG', 'SUKOREJO', 'SUMBERCANGKRING', 'TAMBAKREJO', 'TIRU KIDUL', 'TIRU LOR', 'TURUS', 'WONOJOYO'],
            'KANDANGAN' => ['BANARAN', 'BUKUR', 'JERUKGULUNG', 'JERUKWANGI', 'JLUMBANG', 'KANDANGAN', 'KARANGTENGAH', 'KASREMAN', 'KEMIRI', 'KLAMPISAN', 'MEDOWO', 'MLANCU'],
            'KANDAT' => ['BLABAK', 'CENDONO', 'KANDAT', 'KARANGREJO', 'NGLETIH', 'NGRECO', 'PULE', 'PURWOREJO', 'RINGINSARI', 'SELOSARI', 'SUMBEREJO', 'TEGALAN'],
            'KAYEN KIDUL' => ['BANGSONGAN', 'BAYE', 'JAMBU', 'KAYEN KIDUL', 'MUKUH', 'NANGGUNGAN', 'PADANGAN', 'SAMBIROBYONG', 'SEKARAN', 'SEMAMBUNG', 'SENDEN', 'SUKOHARJO'],
            'KEPUNG' => ['BESOWO', 'BRUMBUNG', 'DAMARWULAN', 'KAMPUNGBARU', 'KEBONREJO', 'KELING', 'KENCONG', 'KEPUNG', 'KRENCENG', 'SIMAN'],
            'KRAS' => ['BANJARANYAR', 'BENDOSARI', 'BLEBER', 'BUTUH', 'JABANG', 'JAMBEAN', 'KANIGORO', 'KARANGTALUN', 'KRANDANG', 'KRAS', 'MOJOSARI', 'NYAWANGAN', 'PELAS', 'PURWODADI', 'REJOMULYO', 'SETONOREJO'],
            'KUNJANG' => ['BALONGJERUK', 'DUNGUS', 'JUWET', 'KAPAS', 'KAPI', 'KLEPEK', 'KUNJANG', 'KUWIK', 'PAKIS', 'PARELOR', 'TENGGER LOR', 'WONOREJO'],
            'MOJO' => ['BLIMBING', 'JUGO', 'KEDAWUNG', 'KENITEN', 'KRANDING', 'KRATON', 'MAESAN', 'MLATI', 'MOJO', 'MONDO', 'NGADI', 'NGETREP', 'PAMONGAN', 'PETOK', 'PETUNGROTO', 'PLOSO', 'PONGGOK', 'SUKOANYAR', 'SURAT', 'TAMBIBENDO'],
            'NGADILUWIH' => ['BADAL', 'BADAL PANDEAN', 'BANGGLE', 'BANJAREJO', 'BEDUG', 'BRANGGAHAN', 'DUKUH', 'MANGUNREJO', 'NGADILUWIH', 'PURWOKERTO', 'REMBANG', 'REMBANGKEPUH', 'SEKETI', 'SLUMBUNG', 'TALES', 'WONOREJO'],
            'NGANCAR' => ['BABADAN', 'BEDALI', 'JAGUL', 'KUNJANG', 'MANGGIS', 'MARGOURIP', 'NGANCAR', 'PANDANTOYO', 'SEMPU', 'SUGIHWARAS'],
            'NGASEM' => ['DOKO', 'GOGORANTE', 'KARANGREJO', 'KWADUNGAN', 'NAMBAAN', 'NGASEM', 'PARON', 'SUKOREJO', 'SUMBEREJO', 'TOYORESMI', 'TUGUREJO', 'WONOCATUR'],
            'PAGU' => ['BENDO', 'BULUPASAR', 'JAGUNG', 'KAMBINGAN', 'MENANG', 'PAGU', 'SEMANDING', 'SEMEN', 'SITIMERTO', 'TANJUNG', 'TENGGER KIDUL', 'WATES', 'WONOSARI'],
            'PAPAR' => ['DAWUHAN KIDUL', 'JAMBANGAN', 'JANTI', 'KEDUNGMALANG', 'KEPUH', 'KWARON', 'MADURETNO', 'MINGGIRAN', 'NGAMPEL', 'PAPAR', 'PEHKULON', 'PEHWETAN', 'PUHJAJAR', 'PURWOTENGAH', 'SRIKATON', 'SUKOMORO', 'TANON'],
            'PARE' => ['BENDO', 'DARUNGAN', 'GEDANGSEWU', 'PARE', 'PELEM', 'SAMBIREJO', 'SIDOREJO', 'SUMBERBENDO', 'TERTEK', 'TULUNGREJO'],
            'PLEMAHAN' => ['BANJAREJO', 'BOGOKIDUL', 'KAYEN LOR', 'LANGENHARJO', 'MEJONO', 'MOJOAYU', 'MOJOKEREP', 'NGINO', 'PAYAMAN', 'PLEMAHAN', 'PUHJARAK', 'RINGINPITU', 'SEBET', 'SIDOWAREK', 'SUKOHARJO', 'TEGOWANGI', 'WONOKERTO'],
            'PLOSOKLATEN' => ['BRENGGOLO', 'DONGANTI', 'GONDANG', 'JARAK', 'KAWEDUSAN', 'KAYUNAN', 'KLANDERAN', 'PANJER', 'PLOSO KIDUL', 'PLOSO LOR', 'PRANGGANG', 'PUNJUL', 'SEPAWON', 'SUMBERAGUNG', 'WONOREJO TRISULO'],
            'PUNCU' => ['ASMOROBANGUN', 'GADUNGAN', 'MANGGIS', 'PUNCU', 'SATAK', 'SIDOMULYO', 'WATUGEDE', 'WONOREJO'],
            'PURWOASRI' => ['BELOR', 'BLAWE', 'BULU', 'DAWUHAN', 'DAYU', 'JANTOK', 'KARANGPAKIS', 'KEMPLENG', 'KETAWANG', 'KLAMPITAN', 'MEKIKIS', 'MERJOYO', 'MRANGGEN', 'MUNENG', 'PANDANSARI', 'PESING', 'PURWOASRI', 'PURWODADI', 'SIDOMULYO', 'SUMBERJO', 'TUGU', 'WONOTENGAH', 'WOROMARTO'],
            'RINGINREJO' => ['BATUAJI', 'DAWUNG', 'DEYENG', 'JEMEKAN', 'NAMBAKAN', 'PURWODADI', 'RINGINREJO', 'SAMBI', 'SELODONO', 'SRIKATON', 'SUSUHBANGO'],
            'SEMEN' => ['BOBANG', 'BULU', 'JOHO', 'KANYORAN', 'KEDAK', 'PAGUNG', 'PUHRUBUH', 'PUHSARANG', 'SELOPANGGUNG', 'SEMEN', 'SIDOMULYO', 'TITIK'],
            'TAROKAN' => ['BLIMBING', 'BULUSARI', 'CENGKOK', 'JATI', 'KALIBOTO', 'KALIRONG', 'KEDUNGSARI', 'KEREP', 'SUMBERDUREN', 'TAROKAN']
        ];

        $insertedCount = 0;

        foreach ($dataWilayah as $namaKecamatan => $desas) {
            // Gunakan whereRaw untuk mengabaikan case-sensitive (Badas / BADAS)
            $kecamatan = Kecamatan::whereRaw('UPPER(nama_kecamatan) = ?', [strtoupper($namaKecamatan)])->first();

            if ($kecamatan) {
                foreach ($desas as $namaDesa) {
                    // Gunakan updateOrCreate agar tidak dobel (duplicate) jika dijalankan ulang
                    Desa::updateOrCreate(
                        [
                            'nama_desa' => $namaDesa,
                            'kecamatan_id' => $kecamatan->id
                        ]
                    );
                    $insertedCount++;
                }
            } else {
                $this->command->warn("Peringatan: Kecamatan '{$namaKecamatan}' belum ada di database, desa dilewati.");
            }
        }

        $this->command->info("Selesai! Berhasil mensinkronkan {$insertedCount} desa ke dalam database.");
    }
}