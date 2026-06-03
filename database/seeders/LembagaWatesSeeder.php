<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lembaga;
use App\Models\Kecamatan;
use App\Models\Desa;

class LembagaWatesSeeder extends Seeder
{
    public function run()
    {
        $kecamatan = Kecamatan::firstOrCreate(['nama_kecamatan' => 'Wates']);

        // DATA LENGKAP 32 MADIN (PLUS DATA GURU DARI EXCEL)
        $data_madin = [
            [
                'nama' => "AL ASY'ARI POJOK WATES", 'desa' => "Pojok", 'ormas' => "NU", 'santri' => 27, 
                'guru' => 2, 'insentif' => 2, 'belum' => 0, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "RUDI PURNOMO", 'hp' => "08563680472", 'ket' => "SUKET DOMISILI 2025"
            ],
            [
                'nama' => "AL HUDA JOHO WATES", 'desa' => "Joho", 'ormas' => "NU", 'santri' => 50,
                'guru' => 6, 'insentif' => 2, 'belum' => 4, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "MUHAMMAD ROIHAN MD", 'hp' => "085649229189", 'ket' => "Masa Berlaku: 9 MEI 2018-2003"
            ],
            [
                'nama' => "AL WATHON SUMBERAGUNG WATES", 'desa' => "Sumberagung", 'ormas' => "NU", 'santri' => 15,
                'guru' => 1, 'insentif' => 1, 'belum' => 0, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "ENI MUNIFAH", 'hp' => "085736927381", 'ket' => "SUKET DOMISILI 2022"
            ],
            [
                'nama' => "AL BAROKAH POJOK WATES", 'desa' => "Pojok", 'ormas' => "NU", 'santri' => 25,
                'guru' => 5, 'insentif' => 3, 'belum' => 2, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => '2026-01-26', 'status' => "AKTIF", 'kepala' => "MOH. HASYIM", 'hp' => "085655775616", 'ket' => null
            ],
            [
                'nama' => "AL HIDAYAH JANTI WATES", 'desa' => "Janti", 'ormas' => "NU", 'santri' => 40,
                'guru' => 11, 'insentif' => 4, 'belum' => 7, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "MOHAMMAD KHAFID", 'hp' => "081249991361", 'ket' => "PROSES PERPANJANGAN"
            ],
            [
                'nama' => "AL HIKMAH JOHO WATES", 'desa' => "Joho", 'ormas' => "NU", 'santri' => 59,
                'guru' => 4, 'insentif' => 3, 'belum' => 1, 'pns' => 1, 'pppk' => 0, 'sertif' => 0, // Ada PNS 1
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "AKTIF", 'kepala' => "SITI SUHANIKMAH", 'hp' => "085736661914", 'ket' => "Masa Berlaku: 6 NOVEMBER 2025-2030"
            ],
            [
                'nama' => "AL IKHLAS PANDEAN JOHO WATES", 'desa' => "Joho", 'ormas' => "NU", 'santri' => 20,
                'guru' => 3, 'insentif' => 1, 'belum' => 2, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "FEERI KHUSNUL KHULUQ", 'hp' => "085730796222", 'ket' => "Masa Berlaku: 15 DESEMBER 2015-2020"
            ],
            [
                'nama' => "AL IRSYAD WONOREJO WATES", 'desa' => "Wonorejo", 'ormas' => "NU", 'santri' => 42,
                'guru' => 4, 'insentif' => 2, 'belum' => 2, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "AKTIF", 'kepala' => "HANIK YULAIKAH", 'hp' => "085843155911", 'ket' => "Masa Berlaku: 14 SEPTEMBER 2021 - 2026"
            ],
            [
                'nama' => "TARBIYATUN NASYI’IN AL MINHAJJ WATES WATES", 'desa' => "Wates", 'ormas' => "NU", 'santri' => 85,
                'guru' => 6, 'insentif' => 3, 'belum' => 3, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "ARIS JAMALUDDIN", 'hp' => "085702720009", 'ket' => "Masa Berlaku: 14 DESEMBER 2020 - 2025"
            ],
            [
                'nama' => "AL HUSNA WATES WATES", 'desa' => "Wates", 'ormas' => "NU", 'santri' => 51,
                'guru' => 4, 'insentif' => 1, 'belum' => 3, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "MIFTAHUL AMIN WAHYUNI, S.Si.", 'hp' => "085733320121", 'ket' => "SUKET DOMISILI 2025"
            ],
            [
                'nama' => "AL IHSAN JAJAR WATES", 'desa' => "Jajar", 'ormas' => "NU", 'santri' => 30,
                'guru' => 3, 'insentif' => 2, 'belum' => 1, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => '2026-02-09', 'status' => "AKTIF", 'kepala' => "DEWI MAISAROTUL MUFIDAH", 'hp' => "085735988812", 'ket' => null
            ],
            [
                'nama' => "AR ROHMAN TUNGE WATES", 'desa' => "Tunge", 'ormas' => "NU", 'santri' => 50,
                'guru' => 6, 'insentif' => 2, 'belum' => 4, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "ISTIKHOMAH, S.Pd.", 'hp' => "085646623857", 'ket' => "SUKET DOMISILI 2026. PROSES IJOP"
            ],
            [
                'nama' => "BAITUL MUTTAQIIN NGIJO SUMBERAGUNG WATES", 'desa' => "Sumberagung", 'ormas' => "NU", 'santri' => 89,
                'guru' => 7, 'insentif' => 3, 'belum' => 4, 'pns' => 0, 'pppk' => 0, 'sertif' => 1, // Ada Sertif 1
                'ijop' => "ADA", 'tgl_ijop' => '2026-09-13', 'status' => "AKTIF", 'kepala' => "IMAM KARYA BAKTI, M.Pd", 'hp' => "085668954821", 'ket' => null
            ],
            [
                'nama' => "BAITUL UMMAH SUMBERAGUNG WATES", 'desa' => "Sumberagung", 'ormas' => "NU", 'santri' => 30,
                'guru' => 5, 'insentif' => 3, 'belum' => 2, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => '2020-10-29', 'status' => "AKTIF", 'kepala' => "BAHRUL ZAENI", 'hp' => "085235882993", 'ket' => "IJOP TERBARU"
            ],
            [
                'nama' => "DARUL KHOIR JAJAR WATES", 'desa' => "Jajar", 'ormas' => "NU", 'santri' => 30,
                'guru' => 4, 'insentif' => 3, 'belum' => 1, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => '2026-10-06', 'status' => "AKTIF", 'kepala' => "HARIONO", 'hp' => "085755064945", 'ket' => null
            ],
            [
                'nama' => "DARUSSALAMAH SIDOMULYO WATES", 'desa' => "Sidomulyo", 'ormas' => "NU", 'santri' => 220,
                'guru' => 21, 'insentif' => 8, 'belum' => 13, 'pns' => 1, 'pppk' => 0, 'sertif' => 0, // Ada PNS 1
                'ijop' => "ADA", 'tgl_ijop' => '2020-12-03', 'status' => "AKTIF", 'kepala' => "IMAM KAMBALI", 'hp' => "085856519609", 'ket' => "ULA WUSTHO JADI 1"
            ],
            [
                'nama' => "HIDAYATUL MUBTADIIN AL MUWAZANAH PLAOSAN WATES", 'desa' => "Plaosan", 'ormas' => "NU", 'santri' => 30,
                'guru' => 2, 'insentif' => 1, 'belum' => 1, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => '2024-01-13', 'status' => "TIDAK AKTIF", 'kepala' => "KHOIRUL FARIDA", 'hp' => "085755759505", 'ket' => null
            ],
            [
                'nama' => "MADINATUL ULUM TEKENUWUNG SUMBERAGUNG WATES", 'desa' => "Sumberagung", 'ormas' => "NU", 'santri' => 50,
                'guru' => 6, 'insentif' => 3, 'belum' => 3, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => '2024-09-05', 'status' => "TIDAK AKTIF", 'kepala' => "WISUDA MAS'ULUDIN, S.Pd.I", 'hp' => "085746067709", 'ket' => null
            ],
            [
                'nama' => "MIFTAHUL HUDA SILIR WATES", 'desa' => "Silir", 'ormas' => "NU", 'santri' => 114,
                'guru' => 14, 'insentif' => 3, 'belum' => 11, 'pns' => 0, 'pppk' => 0, 'sertif' => 1, // Sertif 1
                'ijop' => "ADA", 'tgl_ijop' => '2023-10-05', 'status' => "TIDAK AKTIF", 'kepala' => "HANIF NURUL LAILI", 'hp' => "0895351438334", 'ket' => null
            ],
            [
                'nama' => "MIFTAKHUL MUBTADIIN SIDOMULYO WATES", 'desa' => "Sidomulyo", 'ormas' => "NU", 'santri' => 0,
                'guru' => 0, 'insentif' => 0, 'belum' => 0, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "LATIF", 'hp' => "081556748058", 'ket' => "Masa Berlaku: 24 JULI 2020 - 2025"
            ],
            [
                'nama' => "MMQ ROUDLOTUL BADI'AH PAGU WATES", 'desa' => "Pagu", 'ormas' => "NU", 'santri' => 114,
                'guru' => 12, 'insentif' => 6, 'belum' => 6, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "USMAN DAHLAN", 'hp' => "085730833453", 'ket' => "SUKET DOMISILI 2026"
            ],
            [
                'nama' => "NURUL QUR'AN 1 PAGU WATES", 'desa' => "Pagu", 'ormas' => "NU", 'santri' => 31,
                'guru' => 6, 'insentif' => 5, 'belum' => 1, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "H. MACHFUDZ AZIZ", 'hp' => "085848937567", 'ket' => "SUKET DOMISILI 2021"
            ],
            [
                'nama' => "ROUDLOTUL QUR'AN TUNGE WATES", 'desa' => "Tunge", 'ormas' => "NU", 'santri' => 25,
                'guru' => 5, 'insentif' => 2, 'belum' => 3, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => '2026-04-05', 'status' => "AKTIF", 'kepala' => "MOHAMMAD YASIR", 'hp' => "085735416816", 'ket' => null
            ],
            [
                'nama' => "SABILILLAH JOHO WATES", 'desa' => "Joho", 'ormas' => "NU", 'santri' => 37,
                'guru' => 5, 'insentif' => 3, 'belum' => 2, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "MOH.ALI FASIHUDDIN", 'hp' => "085708378001", 'ket' => "Masa Berlaku: 12 NOVEMBER 2020-2025"
            ],
            [
                'nama' => "NURUL ISHLAH DUWET WATES", 'desa' => "Duwet", 'ormas' => "NU", 'santri' => 35,
                'guru' => 2, 'insentif' => 1, 'belum' => 1, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "AKTIF", 'kepala' => "SITI ASIYAH", 'hp' => "085736485149", 'ket' => "SUKET DOMISILI 2026. tmbh 1 guru baru"
            ],
            [
                'nama' => "BAITURROHIM DUWET WATES", 'desa' => "Duwet", 'ormas' => "NU", 'santri' => 15,
                'guru' => 3, 'insentif' => 1, 'belum' => 2, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "AKTIF", 'kepala' => "RISTON NAWAWI", 'hp' => "085655826674", 'ket' => "Masa Berlaku: 27 APRIL 2021-2026. Ijop -3 bulan"
            ],
            [
                'nama' => "BABUSSALAM NGIJO SUMBERAGUNG WATES", 'desa' => "Sumberagung", 'ormas' => "NU", 'santri' => 27,
                'guru' => 3, 'insentif' => 1, 'belum' => 2, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "BINTI BADRIJAH", 'hp' => "089518447433", 'ket' => "SUKET DOMISILI 2026"
            ],
            [
                'nama' => "ROUDLOTUS SALAAM PLAOSAN WATES", 'desa' => "Plaosan", 'ormas' => "NU", 'santri' => 75,
                'guru' => 2, 'insentif' => 2, 'belum' => 0, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "MUH MIRFAQ", 'hp' => "085784164305", 'ket' => "SUKET DOMISILI 2024"
            ],
            [
                'nama' => "JABAL NUUR DUWET WATES", 'desa' => "Duwet", 'ormas' => "NU", 'santri' => 12,
                'guru' => 3, 'insentif' => 1, 'belum' => 2, 'pns' => 0, 'pppk' => 0, 'sertif' => 1, // Sertif 1
                'ijop' => "ADA", 'tgl_ijop' => '2027-03-11', 'status' => "AKTIF", 'kepala' => "KHAMIM ADAM HABIBI", 'hp' => "085649858003", 'ket' => null
            ],
            [
                'nama' => "AL JAMI' TUNGE WATES", 'desa' => "Tunge", 'ormas' => "NU", 'santri' => 15,
                'guru' => 2, 'insentif' => 2, 'belum' => 0, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "M. MUHSININ", 'hp' => "085848937262", 'ket' => "SUKET DOMISILI 2026"
            ],
            [
                'nama' => "NURUL QUR'AN 2 PAGU WATES", 'desa' => "Pagu", 'ormas' => "NU", 'santri' => 25,
                'guru' => 4, 'insentif' => 2, 'belum' => 2, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "TIDAK ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "NERI DWI RAHMAWATI", 'hp' => "085748394489", 'ket' => "SUKET DOMISILI 2026"
            ],
            [
                'nama' => "AL GHOZALI JANTI WATES", 'desa' => "Janti", 'ormas' => "NU", 'santri' => 51,
                'guru' => 6, 'insentif' => 0, 'belum' => 6, 'pns' => 0, 'pppk' => 0, 'sertif' => 0,
                'ijop' => "ADA", 'tgl_ijop' => null, 'status' => "TIDAK AKTIF", 'kepala' => "MUHAIMIN", 'hp' => "085784750446", 'ket' => "LEMBAGA BARU. Masa Berlaku: 18 JUNI 2020 - 2025"
            ],
        ];

        foreach ($data_madin as $item) {
            $desa = Desa::firstOrCreate([
                'nama_desa' => $item['desa'],
                'kecamatan_id' => $kecamatan->id
            ]);

            Lembaga::create([
                'kecamatan_id' => $kecamatan->id,
                'desa_id' => $desa->id,
                'jenis_lembaga' => 'MADIN',
                'nama_lembaga' => $item['nama'],
                'ormas' => $item['ormas'],
                'jumlah_santri' => $item['santri'],
                
                // MAPPING DATA GURU BARU
                'jumlah_guru' => $item['guru'],
                'penerima_insentif' => $item['insentif'],
                'belum_menerima_insentif' => $item['belum'],
                'jumlah_pns' => $item['pns'],
                'jumlah_pppk' => $item['pppk'],
                'jumlah_sertifikasi' => $item['sertif'],

                'ijop' => $item['ijop'],
                'masa_berlaku_ijop' => $item['tgl_ijop'],
                'status' => $item['status'],
                'kepala_lembaga' => $item['kepala'],
                'no_telp' => $item['hp'],
                'keterangan' => $item['ket'],
            ]);
        }
    }
}