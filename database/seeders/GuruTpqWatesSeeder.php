<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\Lembaga;
use Carbon\Carbon;

class GuruTpqWatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataGuru = [
            ['nama' => "SIHATUL IRODAH", 'ttl' => "KEDIRI 02/06/1984", 'jk' => "P", 'nik' => "3506064206840002", 'lembaga' => "AL AMIN WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI ROHMAH", 'ttl' => "KEDIRI 07/02/1982", 'jk' => "P", 'nik' => "3506064702820004", 'lembaga' => "AL AZIZIYAH DAWUNG PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR SALI", 'ttl' => "KEDIRI 10/05/1969", 'jk' => "L", 'nik' => "3506061005690004", 'lembaga' => "AL AZIZIYAH DAWUNG PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "MARIYADI", 'ttl' => "KEDIRI 01/02/1971", 'jk' => "L", 'nik' => "3506060102710001", 'lembaga' => "AD DLIYA' SILIR WATES", 'jenis' => "TPQ"],
            ['nama' => "NURUL AINI", 'ttl' => "KEDIRI 14/09/1978", 'jk' => "P", 'nik' => "3506065409780001", 'lembaga' => "AD DLIYA' SILIR WATES", 'jenis' => "TPQ"],
            ['nama' => "RIMA AZMI MUAYADAH", 'ttl' => "KEDIRI 10/07/2001", 'jk' => "P", 'nik' => "3506061007010001", 'lembaga' => "AD DLIYA' SILIR WATES", 'jenis' => "TPQ"],
            ['nama' => "FAHIM MUBAROK", 'ttl' => "KEDIRI 26/06/1977", 'jk' => "L", 'nik' => "3506062606770001", 'lembaga' => "AL FALAH PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "MIFTAHUR ROHMAH", 'ttl' => "KEDIRI 30/05/1984", 'jk' => "P", 'nik' => "3506067005840001", 'lembaga' => "AL FALAH PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "SYAMSUDIN", 'ttl' => "KEDIRI 08/08/1973", 'jk' => "L", 'nik' => "3506064804730004", 'lembaga' => "AL FALAH TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "KHOLISHOTUL CHUSNA", 'ttl' => "KEDIRI 08/01/1984", 'jk' => "P", 'nik' => "3506064801840004", 'lembaga' => "AL FALAH TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "LUFFI SRIMANDANI", 'ttl' => "KEDIRI 29/07/1987", 'jk' => "P", 'nik' => "3506066907870003", 'lembaga' => "AL FALAH TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "SURYATI", 'ttl' => "KEDIRI 15/11/1974", 'jk' => "P", 'nik' => "3506065511740001", 'lembaga' => "AL FALAH TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "UMI MAIDAH", 'ttl' => "KEDIRI 26/02/1966", 'jk' => "P", 'nik' => "3506066602660003", 'lembaga' => "AL FARUQ WANOREJO WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "ANIS SYAHRUL HASANAH", 'ttl' => "KEDIRI 04/09/1978", 'jk' => "P", 'nik' => "3505034409780001", 'lembaga' => "AL FARUQ WANOREJO WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "BINTI MUKSODAH", 'ttl' => "KEDIRI 01/07/1963", 'jk' => "P", 'nik' => "3506064107630035", 'lembaga' => "AL FATTAH TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "NURJANAH", 'ttl' => "KEDIRI 12/07/1973", 'jk' => "P", 'nik' => "3506065207730005", 'lembaga' => "AL FATTAH TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "YULIATIN", 'ttl' => "KEDIRI 19/04/1978", 'jk' => "P", 'nik' => "3506065904780001", 'lembaga' => "AL FATTAH TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "FITRI ZAKIYATUR ROHMAH", 'ttl' => "KEDIRI 30/01/1998", 'jk' => "P", 'nik' => "3506037001980001", 'lembaga' => "AL FATTAH TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "MUTMAINAH", 'ttl' => "KEDIRI 21/05/1967", 'jk' => "P", 'nik' => "3506066105670007", 'lembaga' => "AL GHOFUR WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "QOMARIYAH", 'ttl' => "KEDIRI 21/08/1974", 'jk' => "P", 'nik' => "3506066108740003", 'lembaga' => "AL GHOFUR WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MASMU'ATIK", 'ttl' => "KEDIRI 02/07/1977", 'jk' => "P", 'nik' => "3506064207770001", 'lembaga' => "AL GHOZALI JANTI WATES", 'jenis' => "TPQ"],
            ['nama' => "AFID DZALIKA", 'ttl' => "KEDIRI 09/12/1985", 'jk' => "P", 'nik' => "3506064912850002", 'lembaga' => "AL GHOZALI JANTI WATES", 'jenis' => "TPQ"],
            ['nama' => "ZULFA MASRO'AH", 'ttl' => "KEDIRI 22/03/1985", 'jk' => "P", 'nik' => "3506066203850004", 'lembaga' => "AL GHOZALI JANTI WATES", 'jenis' => "TPQ"],
            ['nama' => "HANIF LATIFAH", 'ttl' => "KEDIRI 09/11/1979", 'jk' => "P", 'nik' => "3506064911790003", 'lembaga' => "AL GHOZALI JANTI WATES", 'jenis' => "TPQ"],
            ['nama' => "JAMI'ATUN NI'MAH", 'ttl' => "BANYUWANGI 21/01/1975", 'jk' => "P", 'nik' => "3506066101760001", 'lembaga' => "AL GHOZALI JANTI WATES", 'jenis' => "TPQ"],
            ['nama' => "ELOK FAIQOTUL HIMMAH", 'ttl' => "KEDIRI 05/03/1995", 'jk' => "P", 'nik' => "3506064503950005", 'lembaga' => "AL GHOZALI JANTI WATES", 'jenis' => "TPQ"],
            ['nama' => "AHMAD MUSLIM", 'ttl' => "KEDIRI 25/05/1977", 'jk' => "L", 'nik' => "3506062505770006", 'lembaga' => "AL GHOZALI DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "PURMIATI", 'ttl' => "KEDIRI 10/10/1962", 'jk' => "P", 'nik' => "3506065010620002", 'lembaga' => "AL GHOZALI DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "FITROTIN", 'ttl' => "KEDIRI 06/07/1980", 'jk' => "P", 'nik' => "3506064607800006", 'lembaga' => "AL GHOZALI DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "ISTIKAMAH", 'ttl' => "KEDIRI 03/12/1984", 'jk' => "P", 'nik' => "3506064312840002", 'lembaga' => "AL HAFIZH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "LUTFI MISTIANINGSIH", 'ttl' => "KEDIRI 14/09/1989", 'jk' => "P", 'nik' => "3506065409890002", 'lembaga' => "AL HAFIZH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "ANIS RINA RAHAYU", 'ttl' => "KEDIRI 01/02/1990", 'jk' => "P", 'nik' => "3506074102900003", 'lembaga' => "AL HAFIZH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "MING CHOLIFAH", 'ttl' => "KEDIRI 26/05/1992", 'jk' => "P", 'nik' => "3506066605920003", 'lembaga' => "AL HAFIZH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "DWI SRI KARTIKA", 'ttl' => "KEDIRI 21/04/1980", 'jk' => "P", 'nik' => "3506066104800004", 'lembaga' => "AL HIDAYAH WATES WATES", 'jenis' => "TPQ"],
            ['nama' => "BADRIYAH", 'ttl' => "KEDIRI 11/02/1979", 'jk' => "P", 'nik' => "3506065102790003", 'lembaga' => "AL HIDAYAH WATES WATES", 'jenis' => "TPQ"],
            ['nama' => "TRI HERNANI", 'ttl' => "KEDIRI 13/05/1983", 'jk' => "P", 'nik' => "3506065305830001", 'lembaga' => "AL HIDAYAH WATES WATES", 'jenis' => "TPQ"],
            ['nama' => "DHARATUL NAFI'AH", 'ttl' => "KEDIRI 09/05/1977", 'jk' => "P", 'nik' => "3506062511101584", 'lembaga' => "AL HIDAYAH TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "RIPANGI", 'ttl' => "KEDIRI 10/08/1976", 'jk' => "L", 'nik' => "3506064108760002", 'lembaga' => "AL HIDAYAH TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "DEWI MASRURIN", 'ttl' => "KEDIRI 19/10/1989", 'jk' => "P", 'nik' => "3506065910890004", 'lembaga' => "AL HIDAYAH JANTI WATES", 'jenis' => "TPQ"],
            ['nama' => "ALFIANA", 'ttl' => "KEDIRI 16/05/1980", 'jk' => "P", 'nik' => "3506065605800004", 'lembaga' => "AL HIKMAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "ITA ANDAYANI", 'ttl' => "KEDIRI 01/07/1993", 'jk' => "P", 'nik' => "3506064107930021", 'lembaga' => "AL HIKMAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "FARIDATUS SHOLIKHAH", 'ttl' => "KEDIRI 23/07/1985", 'jk' => "P", 'nik' => "3506066307850001", 'lembaga' => "AL IHSAN TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI ZULAIKAH", 'ttl' => "KEDIRI 27/11/1970", 'jk' => "P", 'nik' => "3506066711700001", 'lembaga' => "AL IHSAN POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "ASMANAH", 'ttl' => "BLITAR 07/07/1976", 'jk' => "P", 'nik' => "3506064707760005", 'lembaga' => "AL IHSAN POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "MIFTAHUR ROHMAH", 'ttl' => "KEDIRI 29/07/1989", 'jk' => "P", 'nik' => "3506076907890001", 'lembaga' => "AL IHSAN POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "MUTMAINATUL MUNAWAROH", 'ttl' => "KEDIRI 05/06/1973", 'jk' => "P", 'nik' => "3506064506730000", 'lembaga' => "AL IKHLAS JANTI WATES", 'jenis' => "TPQ"],
            ['nama' => "QOMARUDDIN", 'ttl' => "KEDIRI 07/08/1965", 'jk' => "L", 'nik' => "3506060708650002", 'lembaga' => "AL IKHLAS JANTI WATES", 'jenis' => "TPQ"],
            ['nama' => "MUH SHOLI", 'ttl' => "KEDIRI 12/07/1975", 'jk' => "L", 'nik' => "3506061207750001", 'lembaga' => "AL IKHLAS KARANGANYAR WATES", 'jenis' => "TPQ"],
            ['nama' => "ISMIYATIN", 'ttl' => "KEDIRI 12/04/1971", 'jk' => "P", 'nik' => "3506065204710004", 'lembaga' => "AL IKHLAS SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "KUSNUL KHOTIMAH", 'ttl' => "KEDIRI 26/10/1977", 'jk' => "L", 'nik' => "3596966610770002", 'lembaga' => "AL IRSYAD BEJI WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "TIKA SARI", 'ttl' => "KEDIRI 04/04/1984", 'jk' => "P", 'nik' => "3506064404840004", 'lembaga' => "AL ISTIQOMAH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "MARLIANA", 'ttl' => "KEDIRI 24/02/1977", 'jk' => "P", 'nik' => "3506066402770001", 'lembaga' => "AL ISTIQOMAH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "TRIYANINGSIH", 'ttl' => "KEDIRI 09/09/1989", 'jk' => "P", 'nik' => "3506064909890002", 'lembaga' => "AL ISTIQOMAH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "MARTINI", 'ttl' => "KEDIRI 02/10/1985", 'jk' => "P", 'nik' => "3506064210850004", 'lembaga' => "AL ISTIQOMAH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "IRMA YULI ROHMAWATI", 'ttl' => "KEDIRI 30/07/1987", 'jk' => "P", 'nik' => "3506067007870002", 'lembaga' => "AL ISTIQOMAH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "MUDJINAH", 'ttl' => "KEDIRI 06/05/1965", 'jk' => "P", 'nik' => "3506064505650001", 'lembaga' => "AL JIHAD TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI KADARIYAH", 'ttl' => "KEDIRI 27/03/1966", 'jk' => "P", 'nik' => "3506066703660001", 'lembaga' => "AL JIHAD TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "SELVINA ARUM DEWANTY", 'ttl' => "KEDIRI 05/01/1994", 'jk' => "P", 'nik' => "3506064501940002", 'lembaga' => "AL KHOIROT DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "MUNQONI'AH", 'ttl' => "KEDIRI 15/06/1979", 'jk' => "P", 'nik' => "3506065506790005", 'lembaga' => "AL MA'AARIF POJOK WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "ZAYYIN DHIYA ANA", 'ttl' => "KEDIRI 02/08/2000", 'jk' => "P", 'nik' => "3506064208000006", 'lembaga' => "AL MA'AARIF POJOK WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "SUMIDAH", 'ttl' => "KEDIRI 07/12/1958", 'jk' => "P", 'nik' => "3506064711580002", 'lembaga' => "AL MUBAROKAH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "LISTARI RAHAYUNINGTYAS", 'ttl' => "KEDIRI 03/04/1983", 'jk' => "P", 'nik' => "3506064304830003", 'lembaga' => "AL MUBAROKAH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "ANITA KRISMAYA", 'ttl' => "KEDIRI 24/08/1979", 'jk' => "P", 'nik' => "3506066408790002", 'lembaga' => "AL MUBAROKAH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "FARIDAH", 'ttl' => "SEMUNTAI 01/01/1981", 'jk' => "P", 'nik' => "3506064101810009", 'lembaga' => "AL MUBAROKAH GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "DEWI NURUL KAROMAH", 'ttl' => "KEDIRI 15/02/1983", 'jk' => "P", 'nik' => "3506065502830002", 'lembaga' => "AL MUKARROMAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "UMI NUR CHASANAH", 'ttl' => "KEDIRI 13/08/1969", 'jk' => "P", 'nik' => "3506065308690001", 'lembaga' => "AL MUKARROMAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "NINING NUR HANIFAH", 'ttl' => "KEDIRI 27/05/1991", 'jk' => "P", 'nik' => "3506066705910001", 'lembaga' => "AL MUKARROMAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "MARIROTUL AFIFAH", 'ttl' => "JEPARA 02/09/1988", 'jk' => "P", 'nik' => "3320074209880003", 'lembaga' => "AL MUKARROMAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "SUWANDI", 'ttl' => "KEDIRI 06/02/1978", 'jk' => "L", 'nik' => "3506060602780003", 'lembaga' => "AL MU'MINUN WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "FITROTIN", 'ttl' => "KEDIRI 28/04/1980", 'jk' => "P", 'nik' => "3506066804800001", 'lembaga' => "AL MU'MINUN WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "SUSIANI", 'ttl' => "KEDIRI 04/05/1991", 'jk' => "P", 'nik' => "3506064405910003", 'lembaga' => "AL MU'MINUN WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "MIFTAHUL AMIN WAHYUNI", 'ttl' => "KEDIRI 21/08/1995", 'jk' => "P", 'nik' => "3506226108950001", 'lembaga' => "AL HUSNA WATES WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI RUFI'AH", 'ttl' => "GROBOGAN 22/05/1989", 'jk' => "P", 'nik' => "3315146205890001", 'lembaga' => "AL HUSNA WATES WATES", 'jenis' => "TPQ"],
            ['nama' => "ATININGRUM", 'ttl' => "KEDIRI 03/10/1979", 'jk' => "P", 'nik' => "3506064310790001", 'lembaga' => "AL HUSNA WATES WATES", 'jenis' => "TPQ"],
            ['nama' => "IBNU FITROH ANA SHOFI", 'ttl' => "BLITAR 25/05/1989", 'jk' => "L", 'nik' => "3505022505890004", 'lembaga' => "AL HUDA DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "AHMAD FAUZI", 'ttl' => "KEDIRI 04/12/1993", 'jk' => "L", 'nik' => "3506060412930001", 'lembaga' => "AL HUDA DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "AGUNG ARIFIN", 'ttl' => "KEDIRI 23/11/1988", 'jk' => "L", 'nik' => "3506102311880001", 'lembaga' => "AL IHSAN JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "HJ SITI SYAMSIYAH", 'ttl' => "KEDIRI 26/05/1977", 'jk' => "P", 'nik' => "3506066605770001", 'lembaga' => "AL IHSAN JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI BAYAROH", 'ttl' => "KEDIRI 11/07/1979", 'jk' => "P", 'nik' => "3506065107790001", 'lembaga' => "AL IHSAN JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR AZIZAH", 'ttl' => "KEDIRI 10/11/1959", 'jk' => "P", 'nik' => "3506066011590001", 'lembaga' => "AL IHSAN JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "MUHAMMAD DOFIR ANSHORI", 'ttl' => "KEDIRI 16/12/1986", 'jk' => "L", 'nik' => "3506061612860003", 'lembaga' => "AL IKHLAS TIRTO JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "SRI SUBEKTI", 'ttl' => "KEDIRI 25/03/1968", 'jk' => "P", 'nik' => "3506066503680001", 'lembaga' => "AL IKHLAS TIRTO JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR INAYATUL AINI", 'ttl' => "KEDIRI 07/07/1992", 'jk' => "P", 'nik' => "3506104707920002", 'lembaga' => "AL IKHLAS TIRTO JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "UMI KHASANAH", 'ttl' => "KEDIRI 26/04/1987", 'jk' => "P", 'nik' => "3506066604870002", 'lembaga' => "AL IKHLAS PANDEAN JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "ENI MUNIFAH", 'ttl' => "KEDIRI 28/04/1974", 'jk' => "P", 'nik' => "3506066804740003", 'lembaga' => "AL WATHON SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "MU'ALIM", 'ttl' => "KEDIRI 03/01/1969", 'jk' => "L", 'nik' => "3506060103690001", 'lembaga' => "AL WATHON SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "SAYIDAH MABRUROH", 'ttl' => "KEDIRI 14/05/1972", 'jk' => "P", 'nik' => "3506065405720003", 'lembaga' => "AN NAJAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "ABD MU'IZ", 'ttl' => "KEDIRI 21/09/1966", 'jk' => "L", 'nik' => "3506062109660001", 'lembaga' => "AN NAJAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "ABDUL HASIB", 'ttl' => "KEDIRI 26/02/1969", 'jk' => "L", 'nik' => "3506062602690002", 'lembaga' => "AN NAJAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MAULIDIYAH", 'ttl' => "KEDIRI 04/08/1966", 'jk' => "P", 'nik' => "3506064408660004", 'lembaga' => "AN NAJAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI NURHASANAH", 'ttl' => "KEDIRI 12/04/1975", 'jk' => "P", 'nik' => "3506065204750001", 'lembaga' => "AN NUR PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "MUALIM BAHRUDIN", 'ttl' => "KEDIRI 12/12/1966", 'jk' => "L", 'nik' => "3506061212660002", 'lembaga' => "AN NUR PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "JAUHARATUL MAKNUN", 'ttl' => "KEDIRI 16/07/1975", 'jk' => "P", 'nik' => "3506065607750004", 'lembaga' => "AN NUUR TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "ENIK SUMARTI", 'ttl' => "KEDIRI 16/08/1972", 'jk' => "P", 'nik' => "3506065608720003", 'lembaga' => "AN NUR GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MUNAWAROH", 'ttl' => "KEDIRI 08/08/1968", 'jk' => "P", 'nik' => "3506064708680003", 'lembaga' => "AN NUR GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "DEWI AMALIATUL KHASANAH", 'ttl' => "KEDIRI 11/04/1989", 'jk' => "P", 'nik' => "3506065104890002", 'lembaga' => "AR ROHMAN WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI ZULAIKAH", 'ttl' => "KEDIRI 09/06/1973", 'jk' => "P", 'nik' => "3506064906730001", 'lembaga' => "AR ROHMAN WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "LUKMINATIN NURHIDAYAH", 'ttl' => "NGANJUK 11/08/1979", 'jk' => "P", 'nik' => "1810015108790003", 'lembaga' => "ARRI'AYATUL IJTIMA'IYAH POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "DEWI NUR ASIH", 'ttl' => "KEDIRI 22/06/2000", 'jk' => "P", 'nik' => "3506066206000004", 'lembaga' => "AS SALAM TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "LAILA MASROH", 'ttl' => "KEDIRI 30/11/1973", 'jk' => "P", 'nik' => "3506067011730001", 'lembaga' => "ASY SYUHADA' PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "RIFA'AH", 'ttl' => "KEDIRI 14/04/1966", 'jk' => "P", 'nik' => "3506065404660001", 'lembaga' => "ASY SYUHADA' PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "MUH DAMANHURI", 'ttl' => "KEDIRI 12/04/1972", 'jk' => "L", 'nik' => "3506061204720003", 'lembaga' => "ASY SYUHADA' PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "UMMARNO", 'ttl' => "KEDIRI 08/10/1962", 'jk' => "L", 'nik' => "3506060810620001", 'lembaga' => "ASY SYUHADA' PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "ANIS FARIDAH", 'ttl' => "KEDIRI 21/05/1985", 'jk' => "P", 'nik' => "6403055205850001", 'lembaga' => "ASY SYUHADA' PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "SUYADI", 'ttl' => "KEDIRI 09/07/1965", 'jk' => "L", 'nik' => "3506060907650002", 'lembaga' => "AS SYAFI'I DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "ZULISTIANI", 'ttl' => "KEDIRI 22/11/1977", 'jk' => "P", 'nik' => "3506066211770003", 'lembaga' => "AS SYAFI'I DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MUAWINAH", 'ttl' => "KEDIRI 09/05/1972", 'jk' => "P", 'nik' => "3506064905720001", 'lembaga' => "AT TAQWA WATES WATES", 'jenis' => "TPQ"],
            ['nama' => "BINTI MASLAKAH", 'ttl' => "KEDIRI 06/05/1967", 'jk' => "P", 'nik' => "3506064605670001", 'lembaga' => "AT TAQWA WATES WATES", 'jenis' => "TPQ"],
            ['nama' => "FAUZAN", 'ttl' => "KEDIRI 17/06/1960", 'jk' => "L", 'nik' => "3506061706600001", 'lembaga' => "BAITUL ABIDIN TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "ZUMROTUS SHOLIHAH", 'ttl' => "KEDIRI 05/05/1987", 'jk' => "P", 'nik' => "3506064505870002", 'lembaga' => "BAITUL ABIDIN TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "MISBAQUL MUNIR", 'ttl' => "KEDIRI 31/12/1996", 'jk' => "L", 'nik' => "3506063112970003", 'lembaga' => "BAITUL ABIDIN TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI DAWIDAH", 'ttl' => "KEDIRI 18/09/1972", 'jk' => "P", 'nik' => "3506065809720001", 'lembaga' => "BAITUN NIKMAH KARANGANYAR WATES", 'jenis' => "TPQ"],
            ['nama' => "MISLA KHUSNIKMA", 'ttl' => "KEDIRI 05/06/1999", 'jk' => "P", 'nik' => "3506064506990001", 'lembaga' => "BAITUN NIKMAH KARANGANYAR WATES", 'jenis' => "TPQ"],
            ['nama' => "RETNO ENDAH ERANITYAS", 'ttl' => "KEDIRI 11/06/1995", 'jk' => "P", 'nik' => "3506065106950003", 'lembaga' => "BAITURROHIM TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "MISTINA", 'ttl' => "KEDIRI 08/11/1990", 'jk' => "P", 'nik' => "3506064811900001", 'lembaga' => "BAITURROHIM TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "USWATUN HASANAH", 'ttl' => "KEDIRI 21/04/1975", 'jk' => "P", 'nik' => "3506066104750005", 'lembaga' => "BAITURROKHIM WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "TYAS ROSITA WATI", 'ttl' => "KEDIRI 27/04/1984", 'jk' => "P", 'nik' => "3506066704840004", 'lembaga' => "BAITURROKHIM WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "ENDARYANTI", 'ttl' => "KEDIRI 05/06/1984", 'jk' => "P", 'nik' => "3503024506840007", 'lembaga' => "BAITURROKHIM WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "RISTHON NAWAWI", 'ttl' => "KEDIRI 15/01/1975", 'jk' => "L", 'nik' => "3506061501750006", 'lembaga' => "BAITURROHIM DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "H ISA ANSORI", 'ttl' => "KEDIRI 25/05/1968", 'jk' => "L", 'nik' => "3506062505680001", 'lembaga' => "BAITURROHIM DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "DIAN MANDA SARI", 'ttl' => "KEDIRI 25/10/1999", 'jk' => "P", 'nik' => "3506066510990001", 'lembaga' => "BAITUL MUTTAQIN NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "MARIANA", 'ttl' => "KEDIRI 10/08/1982", 'jk' => "P", 'nik' => "3506065008820007", 'lembaga' => "BAITUL MUTTAQIN NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "NINDA LISTYA", 'ttl' => "KEDIRI 24/07/1998", 'jk' => "P", 'nik' => "3506096407980002", 'lembaga' => "BAITUL MUTTAQIN NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "YUDI PURWANTO", 'ttl' => "KEDIRI 03/09/1993", 'jk' => "L", 'nik' => "3506070309930001", 'lembaga' => "BAITUL MUTTAQIN NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "ISMONO", 'ttl' => "KEDIRI 20/09/1971", 'jk' => "L", 'nik' => "3506092009710002", 'lembaga' => "BAITUL MUTTAQIN NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "HADI WIYANTO", 'ttl' => "KEDIRI 18/06/1961", 'jk' => "L", 'nik' => "3506091806610002", 'lembaga' => "BAITUL MUTTAQIN NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "JUMIATI", 'ttl' => "KEDIRI 07/02/1969", 'jk' => "P", 'nik' => "3506064702690004", 'lembaga' => "BAITUS SYAKUR GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI ZAENAB", 'ttl' => "KEDIRI 16/06/1987", 'jk' => "P", 'nik' => "3506065606870001", 'lembaga' => "BAITUS SYAKUR GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MU'AWANAH", 'ttl' => "KEDIRI 29/05/1991", 'jk' => "P", 'nik' => "3506066905910003", 'lembaga' => "BINA UMAT SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "SUPARMI", 'ttl' => "KEDIRI 13/05/1975", 'jk' => "P", 'nik' => "3506065305750001", 'lembaga' => "BINA UMAT SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI AMINAH", 'ttl' => "KEDIRI 26/05/1969", 'jk' => "P", 'nik' => "3506066805690002", 'lembaga' => "DAARUZ ZAHRO TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "VIKI TITIK ANDRIANI", 'ttl' => "KEDIRI 10/08/1985", 'jk' => "P", 'nik' => "3506065008860006", 'lembaga' => "DAARUZ ZAHRO TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "ZUMROTUS SHOLIHAH", 'ttl' => "KEDIRI 19/06/1980", 'jk' => "P", 'nik' => "3506065906800001", 'lembaga' => "DAARUZ ZAHRO TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "SUSIANI", 'ttl' => "KEDIRI 25/04/1981", 'jk' => "P", 'nik' => "3506066504810003", 'lembaga' => "DAARUZ ZAHRO TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "FADIA RAHMADHANI", 'ttl' => "KEDIRI 12/02/2006", 'jk' => "P", 'nik' => "3506065202060003", 'lembaga' => "DAARUZ ZAHRO TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "ZAHROL KARIMAH", 'ttl' => "KEDIRI 22/01/1976", 'jk' => "P", 'nik' => "3506066201760002", 'lembaga' => "DARUL ATHFAL SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "ENDRIYATI", 'ttl' => "KEDIRI 09/10/1974", 'jk' => "P", 'nik' => "3506064910740003", 'lembaga' => "DARUL HIKMAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI KHOLISOH", 'ttl' => "KEDIRI 16/04/1977", 'jk' => "P", 'nik' => "3506065604770002", 'lembaga' => "DARUL HIKMAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "WINARSIH", 'ttl' => "KEDIRI 08/11/1975", 'jk' => "P", 'nik' => "3506064811750003", 'lembaga' => "DARUL HIKMAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "HENI FIDIANA", 'ttl' => "KEDIRI 09/09/1989", 'jk' => "P", 'nik' => "3506064909890004", 'lembaga' => "DARUL KHOIR JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "UFIA NIDA", 'ttl' => "KEDIRI 27/01/1992", 'jk' => "P", 'nik' => "3506046701920002", 'lembaga' => "DARUL KHOIR JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MASLAMAH", 'ttl' => "KEDIRI 13/09/1961", 'jk' => "P", 'nik' => "3506065309610001", 'lembaga' => "DARUL KHOIR JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "BINTI ISMU FARIDA", 'ttl' => "KEDIRI 15/06/1974", 'jk' => "P", 'nik' => "3506065506740009", 'lembaga' => "DARUS SALAM POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR ABIDAH HELMI", 'ttl' => "KEDIRI 30/05/2001", 'jk' => "P", 'nik' => "3506067005010005", 'lembaga' => "DARUS SALAM POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "NANING SULISAH", 'ttl' => "KEDIRI 12/10/1979", 'jk' => "P", 'nik' => "3506065210790004", 'lembaga' => "DARUS SALAM TAWANG WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "NINDA KURNIA L", 'ttl' => "KEDIRI 18/05/2004", 'jk' => "P", 'nik' => "3506065805040005", 'lembaga' => "DARUS SALAM TAWANG WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "KHOIRUL FARIDA", 'ttl' => "KEDIRI 01/03/1978", 'jk' => "P", 'nik' => "3506064103780001", 'lembaga' => "HIDAYATUL MUBTADIIN AL MUWAZANAH PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "SLAMET SUBUR MAKMUR", 'ttl' => "KEDIRI 03/07/1978", 'jk' => "L", 'nik' => "3506060307780003", 'lembaga' => "HIDAYATUL MUBTADIIN AL MUWAZANAH PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "MIFTAKHURROHMAH", 'ttl' => "KEDIRI 16/04/1999", 'jk' => "P", 'nik' => "3506065604990005", 'lembaga' => "HIDAYATUL MUBTADIIN AL MUWAZANAH PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "SYUHADA JAUHARI", 'ttl' => "KEDIRI 01/07/1971", 'jk' => "L", 'nik' => "3506060107710040", 'lembaga' => "HIDAYATUN NASYI'IN DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "NURUL HIDAYATI", 'ttl' => "KEDIRI 20/06/1990", 'jk' => "P", 'nik' => "3506046006900003", 'lembaga' => "HUDAYA JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "SUTIYAH", 'ttl' => "KEDIRI 06/10/1962", 'jk' => "P", 'nik' => "3506065003620003", 'lembaga' => "HUDAYA JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "SUPIN FARIDA", 'ttl' => "KEDIRI 02/07/1983", 'jk' => "P", 'nik' => "3506064702830001", 'lembaga' => "HUDAYA JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "SUNI FITRIYAH", 'ttl' => "KEDIRI 12/06/1988", 'jk' => "P", 'nik' => "3506065206880008", 'lembaga' => "IBADURROHMAN JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "FARIDAH HAYATI", 'ttl' => "KEDIRI 01/08/1964", 'jk' => "P", 'nik' => "3506064108640003", 'lembaga' => "IBADURROHMAN JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "MIFTAHUR ROHMAH", 'ttl' => "KEDIRI 11/02/1972", 'jk' => "P", 'nik' => "3506065102720002", 'lembaga' => "JALALABA KARANGANYAR WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR KHALIM", 'ttl' => "KEDIRI 09/07/1966", 'jk' => "L", 'nik' => "3506060907660001", 'lembaga' => "JALALABA KARANGANYAR WATES", 'jenis' => "TPQ"],
            ['nama' => "HARI SAFITRI", 'ttl' => "KEDIRI 03/05/1989", 'jk' => "P", 'nik' => "3506094107890050", 'lembaga' => "JALALABA KARANGANYAR WATES", 'jenis' => "TPQ"],
            ['nama' => "SOPIYAH", 'ttl' => "KEDIRI 10/08/1980", 'jk' => "P", 'nik' => "3506065008800005", 'lembaga' => "MA’ARIF WINONG SIDOMULYO WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "SOLIKATIN", 'ttl' => "KEDIRI 20/04/1986", 'jk' => "P", 'nik' => "3506266004860001", 'lembaga' => "MA’ARIF WINONG SIDOMULYO WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "MIR'ATUS SHOLEKAH", 'ttl' => "KEDIRI 24/06/1997", 'jk' => "P", 'nik' => "3506066306970001", 'lembaga' => "MA’ARIF WINONG SIDOMULYO WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "M ZUBAIDI", 'ttl' => "KEDIRI 03/12/1984", 'jk' => "L", 'nik' => "3506060312840005", 'lembaga' => "MAKANUL ULUM TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "AHMAD HABIB", 'ttl' => "KEDIRI 08/01/1985", 'jk' => "L", 'nik' => "3506060107810037", 'lembaga' => "MAKANUL ULUM TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "LINDA LINDIAWATI", 'ttl' => "KEDIRI 24/09/1986", 'jk' => "P", 'nik' => "3506066409860002", 'lembaga' => "MAKANUL ULUM TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "SRI RAHAYU", 'ttl' => "KEDIRI 19/02/1982", 'jk' => "P", 'nik' => "3506065902820009", 'lembaga' => "MAKANUL ULUM TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR RIYAMAH", 'ttl' => "KEDIRI 10/09/1981", 'jk' => "P", 'nik' => "3506065009810001", 'lembaga' => "MAKANUL ULUM TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "IMAM GHOZALI", 'ttl' => "KEDIRI 25/01/1985", 'jk' => "L", 'nik' => "3506122501850003", 'lembaga' => "MASJID AL IRSYAD POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "UMI TAMAMI", 'ttl' => "BLITAR 10/06/1980", 'jk' => "P", 'nik' => "3506065006800002", 'lembaga' => "MASJID AL IRSYAD POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "NURUL FITRIA", 'ttl' => "KEDIRI 04/04/1990", 'jk' => "P", 'nik' => "3506064404900001", 'lembaga' => "MASJID MBAH KAM DUWET WATES", 'jenis' => "TPQ"], // Penyesuaian nama, asumsikan masjid duwet sama dengan mbah kam
            ['nama' => "TSAMROTUL AZIZAH", 'ttl' => "KEDIRI 24/10/1991", 'jk' => "P", 'nik' => "3506066410910001", 'lembaga' => "MASJID MBAH KAM DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "ANITA KURNIAWATI", 'ttl' => "KEDIRI 13/04/1986", 'jk' => "P", 'nik' => "3506065304860002", 'lembaga' => "MASJID MBAH KAM DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "LAILATUL MAGHFIROH", 'ttl' => "LAMONGAN 17/01/1996", 'jk' => "P", 'nik' => "3524205701960001", 'lembaga' => "MASJID MBAH KAM DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "AHMAD BAIDOWI", 'ttl' => "KEDIRI 27/01/1961", 'jk' => "L", 'nik' => "3506062701610002", 'lembaga' => "MIFTAHUL HIDAYAH TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "MARIYATUL QIBTIYAH", 'ttl' => "KEDIRI 09/05/1971", 'jk' => "P", 'nik' => "3506064205710005", 'lembaga' => "MIFTAHUL HIDAYAH TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "ATIQOTUL MAULA AL FAJRIYAH", 'ttl' => "KEDIRI 18/07/2000", 'jk' => "P", 'nik' => "3506065807000002", 'lembaga' => "MIFTAHUL HIDAYAH TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "SUGIYANTO", 'ttl' => "SEMARANG 16/09/1975", 'jk' => "L", 'nik' => "3606061609750004", 'lembaga' => "MIFTAHUL ULUM TAWANG WATES", 'jenis' => "TPQ"], // Penyesuaian nama (' dihilangkan)
            ['nama' => "SOPIYATUN", 'ttl' => "KEDIRI 10/12/1978", 'jk' => "P", 'nik' => "3506065012780004", 'lembaga' => "MIFTAHUL ULUM TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "TITIS KHARISMA TITANIA", 'ttl' => "KEDIRI 08/01/2002", 'jk' => "P", 'nik' => "3506064801020002", 'lembaga' => "MIFTAHUL ULUM TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "ALVIANA", 'ttl' => "KEDIRI 29/12/1988", 'jk' => "P", 'nik' => "3506066912880004", 'lembaga' => "MUNAJATUL QUBRO WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "SUMIYATI", 'ttl' => "KEDIRI 14/12/1979", 'jk' => "P", 'nik' => "3506065412790002", 'lembaga' => "MUNAJATUL QUBRO WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "KHOLIFATUL IZUL LATIFI", 'ttl' => "PONOROGO 18/07/1991", 'jk' => "P", 'nik' => "3502085807910001", 'lembaga' => "NURUL HUDA DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "RISMAWATI", 'ttl' => "KEDIRI 07/01/1997", 'jk' => "P", 'nik' => "3506064701970003", 'lembaga' => "NURUL HUDA DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "NANIK TRIANINGSIH", 'ttl' => "KEDIRI 14/06/1975", 'jk' => "P", 'nik' => "3506065406750001", 'lembaga' => "NURUL IMAN TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "ZENI MUSLIMATIN", 'ttl' => "KEDIRI 08/09/1978", 'jk' => "P", 'nik' => "3506064908780005", 'lembaga' => "NURUL IMAN TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "LILIK MARJULIS", 'ttl' => "KEDIRI 16/07/1961", 'jk' => "P", 'nik' => "3506065607610002", 'lembaga' => "NURUL IMAN TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "MARIANI", 'ttl' => "KEDIRI 05/10/1977", 'jk' => "P", 'nik' => "3506074510770002", 'lembaga' => "NURUL IMAN TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "PUJI ASTUTI", 'ttl' => "KEDIRI 01/12/1981", 'jk' => "P", 'nik' => "3506064112810002", 'lembaga' => "NURUL IMAN TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "DEASY KUMALA DEWI", 'ttl' => "KEDIRI 15/12/1981", 'jk' => "P", 'nik' => "3506065512810002", 'lembaga' => "NURUL IMAN TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "AGHNA MAHIROTUL ILMI", 'ttl' => "KEDIRI 21/09/1999", 'jk' => "P", 'nik' => "3506066109990002", 'lembaga' => "NURUL ISLAM TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "AGUS SUGENG PRAYETNO", 'ttl' => "KEDIRI 28/08/1997", 'jk' => "L", 'nik' => "3506062808970002", 'lembaga' => "NURUL ISLAM TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "ANANDA RIZQA BEAUTY", 'ttl' => "KEDIRI 01/05/2000", 'jk' => "P", 'nik' => "3506064105000001", 'lembaga' => "NURUL ISLAM TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "MUHAMMAD RO'ES SYAFI'I", 'ttl' => "KEDIRI 29/11/1982", 'jk' => "L", 'nik' => "3506062911820002", 'lembaga' => "NURUL ISLAM TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR FAIZATUL HAMIDAH", 'ttl' => "KEDIRI 25/11/1997", 'jk' => "P", 'nik' => "3506066511970002", 'lembaga' => "NURUL ISLAM TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR ROHMATIKA", 'ttl' => "KEDIRI 22/05/2003", 'jk' => "P", 'nik' => "3506066205030003", 'lembaga' => "NURUL ISLAM TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "ULFA NUR FADHILAH", 'ttl' => "KEDIRI 25/04/1998", 'jk' => "P", 'nik' => "3506066503980002", 'lembaga' => "NURUL ISLAM TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "ABDUL RAHMAN", 'ttl' => "BLITAR 06/01/1976", 'jk' => "L", 'nik' => "3506060601760002", 'lembaga' => "NURUL ISHLAH DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR HIDAYAT", 'ttl' => "KEDIRI 30/07/1975", 'jk' => "L", 'nik' => "3506063007750003", 'lembaga' => "NURUL ISHLAH DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "UMI ANA MARATUS SHOLEHAH", 'ttl' => "KEDIRI 18/05/1985", 'jk' => "P", 'nik' => "3506065805850006", 'lembaga' => "NURUL ISHLAH DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "MARIYATIN", 'ttl' => "KEDIRI 26/12/1983", 'jk' => "P", 'nik' => "3506066612830003", 'lembaga' => "NURUL ISHLAH DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "SUSIANA", 'ttl' => "KEDIRI 13/03/1980", 'jk' => "P", 'nik' => "3506065303800003", 'lembaga' => "NURUL ISHLAH DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI AMIROTUS SOLEKAH", 'ttl' => "KEDIRI 12/04/1980", 'jk' => "P", 'nik' => "3506065204800002", 'lembaga' => "NURUL JANNAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "YUNI ISTIQOMAH", 'ttl' => "KEDIRI 31/12/1979", 'jk' => "P", 'nik' => "3506067112790001", 'lembaga' => "NURUL JANNAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "YULI ASTUTIK", 'ttl' => "KEDIRI 03/07/1977", 'jk' => "P", 'nik' => "3506064307770003", 'lembaga' => "NURUL MISBAH WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "TRISIYAH", 'ttl' => "SIDOARJO 12/05/1974", 'jk' => "P", 'nik' => "3515155205740002", 'lembaga' => "NURUL MISBAH WONOREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "L BADRIYAH", 'ttl' => "TULUNGAGUNG 06/05/1971", 'jk' => "P", 'nik' => "3506062505720001", 'lembaga' => "NURUL QUR'AN 1 PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "NI'MAH ILYANAH", 'ttl' => "KEDIRI 17/09/1975", 'jk' => "P", 'nik' => "3506065709750003", 'lembaga' => "NURUL QUR'AN 1 PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "TA'YIN", 'ttl' => "KEDIRI 18/09/1967", 'jk' => "L", 'nik' => "3506061809670003", 'lembaga' => "NURUL QUR'AN 1 PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MUYASAROH", 'ttl' => "KEDIRI 05/05/1974", 'jk' => "P", 'nik' => "3506064505740003", 'lembaga' => "NURUL QUR'AN 1 PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "UMI ROIDAH", 'ttl' => "KEDIRI 09/05/1979", 'jk' => "P", 'nik' => "3506064905790001", 'lembaga' => "NURUL QUR'AN 2 PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "FITRIA RIZQI DAMAYANTI", 'ttl' => "KEDIRI 10/02/1998", 'jk' => "P", 'nik' => "3506065002980002", 'lembaga' => "NURUL QUR'AN 2 PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI ALIFAH", 'ttl' => "KEDIRI 14/12/1985", 'jk' => "P", 'nik' => "3506234412850001", 'lembaga' => "NURUL QUR'AN 2 PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR SA'IDAH", 'ttl' => "KEDIRI 14/08/1971", 'jk' => "P", 'nik' => "3506065408730003", 'lembaga' => "NURUL QUR'AN 2 PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "A SYAHRU SF", 'ttl' => "MALANG 03/04/1984", 'jk' => "L", 'nik' => "3506060304840004", 'lembaga' => "NUSA SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "SHOLICHATUN INAROH", 'ttl' => "KEDIRI 03/08/1986", 'jk' => "P", 'nik' => "3506064308860002", 'lembaga' => "NUSA SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "NOVI RATNASARI", 'ttl' => "KEDIRI 30/03/2000", 'jk' => "P", 'nik' => "3506065303000002", 'lembaga' => "NUSA SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "MUSTAJI", 'ttl' => "KEDIRI 01/07/1954", 'jk' => "L", 'nik' => "3506060107540046", 'lembaga' => "NUSA SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "MOH HASIM", 'ttl' => "KEDIRI 08/08/1977", 'jk' => "L", 'nik' => "3506090808770005", 'lembaga' => "RAUDLATUL HUDA POJOK WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "SITI RUQOYYAH", 'ttl' => "KEDIRI 25/07/1984", 'jk' => "P", 'nik' => "3506096507840003", 'lembaga' => "RAUDLATUL HUDA POJOK WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "TURJI", 'ttl' => "KEDIRI 15/09/1969", 'jk' => "L", 'nik' => "3506061509690002", 'lembaga' => "ROUDHOTUL QUR'AN TUNGE WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "MASKUROTIN NAFISAH", 'ttl' => "KEDIRI 08/03/1999", 'jk' => "P", 'nik' => "3506064803990002", 'lembaga' => "ROUDHOTUL QUR'AN TUNGE WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "UMI FARIHATUN NISAK", 'ttl' => "KEDIRI 05/04/1999", 'jk' => "P", 'nik' => "3506064505990002", 'lembaga' => "ROUDHOTUL QUR'AN TUNGE WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "SUMMA DEWI ROHMAN", 'ttl' => "KEDIRI 07/11/2000", 'jk' => "P", 'nik' => "3506064711000005", 'lembaga' => "ROUDHOTUL QUR'AN TUNGE WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "MUH NUR IBNU QOWIM", 'ttl' => "KEDIRI 08/03/1998", 'jk' => "L", 'nik' => "3506060808980001", 'lembaga' => "ROUDLOTUL ANWAR JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MARYAM", 'ttl' => "KEDIRI 14/12/1967", 'jk' => "P", 'nik' => "3506065412670001", 'lembaga' => "ROUDLOTUL ANWAR JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "SANUSI ANWAR", 'ttl' => "KEDIRI 05/08/1959", 'jk' => "L", 'nik' => "3506060508590001", 'lembaga' => "ROUDLOTUL ANWAR JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "M MISBAH IQBAL DUTA", 'ttl' => "KEDIRI 09/05/1996", 'jk' => "L", 'nik' => "3506092905960002", 'lembaga' => "ROUDLOTUS SALAAM PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "BINTI ULWIYAH", 'ttl' => "KEDIRI 14/06/1976", 'jk' => "P", 'nik' => "3506065406760004", 'lembaga' => "ROUDLOTUS SALAAM PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "AKHMAD MASYFU'IN UDZMA", 'ttl' => "KEDIRI 10/01/1999", 'jk' => "L", 'nik' => "3506091001990008", 'lembaga' => "ROUDLOTUS SALAAM PLAOSAN WATES", 'jenis' => "TPQ"],
            ['nama' => "CHOMSATUN", 'ttl' => "KEDIRI 07/06/1963", 'jk' => "P", 'nik' => "3506064607630007", 'lembaga' => "ROUDLOTUTH THULLAB UMMU SALAMAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "RIDA SUKARMIATI", 'ttl' => "KEDIRI 26/11/1994", 'jk' => "P", 'nik' => "3506066611940001", 'lembaga' => "ROUDLOTUTH THULLAB UMMU SALAMAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MUTMAINAH", 'ttl' => "KEDIRI 29/01/1981", 'jk' => "P", 'nik' => "3506066901810001", 'lembaga' => "ROUDLOTUTH THULLAB UMMU SALAMAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "KEVIN NUGROHO", 'ttl' => "KEDIRI 12/03/2005", 'jk' => "L", 'nik' => "3506061203050002", 'lembaga' => "ROUDLOTUTH THULLAB UMMU SALAMAH SIDOMULYO WATES", 'jenis' => "TPQ"],
            ['nama' => "NAILAH AMIDAH", 'ttl' => "KEDIRI 10/07/1974", 'jk' => "P", 'nik' => "3506065007740003", 'lembaga' => "SABILILLAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "BACHTIAR ILHAM MAULANA", 'ttl' => "KEDIRI 25/08/2001", 'jk' => "L", 'nik' => "3506062508010001", 'lembaga' => "SABILILLAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "VINA DUWI AKTAVIYA", 'ttl' => "KEDIRI 04/10/2000", 'jk' => "P", 'nik' => "3506064410000001", 'lembaga' => "SABILILLAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "SUKARTI", 'ttl' => "KEDIRI 27/06/1976", 'jk' => "P", 'nik' => "3506066706760004", 'lembaga' => "SABILILLAH JOHO WATES", 'jenis' => "TPQ"],
            ['nama' => "SILFIA UMI BAIDHOK", 'ttl' => "KEDIRI 01/08/1994", 'jk' => "P", 'nik' => "3506104108940004", 'lembaga' => "SIROJUL ULUM POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "ISTIQOMAH", 'ttl' => "KEDIRI 05/06/1981", 'jk' => "P", 'nik' => "3506064506810005", 'lembaga' => "SIROJUL ULUM POJOK WATES", 'jenis' => "TPQ"],
            ['nama' => "SUSIANA", 'ttl' => "KEDIRI 10/04/1979", 'jk' => "P", 'nik' => "3506065004790001", 'lembaga' => "TARBIYATUL ATHFAL GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MARWIYAH", 'ttl' => "KEDIRI 06/06/1960", 'jk' => "P", 'nik' => "3506064606600008", 'lembaga' => "TARBIYATUL ATHFAL GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "KASIYEM", 'ttl' => "KEDIRI 05/08/1960", 'jk' => "P", 'nik' => "3506064508600002", 'lembaga' => "TARBIYATUL ATHFAL GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "FADJARIANI", 'ttl' => "KEDIRI 15/11/1961", 'jk' => "P", 'nik' => "3506065511610002", 'lembaga' => "TARBIYATUL ATHFAL GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "NURAINI", 'ttl' => "KEDIRI 21/07/1962", 'jk' => "P", 'nik' => "3506066107620004", 'lembaga' => "TARBIYATUL ATHFAL GADUNGAN WATES", 'jenis' => "TPQ"],
            ['nama' => "M MUZAMIL", 'ttl' => "KEDIRI 23/07/1982", 'jk' => "L", 'nik' => "3506062307820003", 'lembaga' => "TASWIRUL JUHALA PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "RIRIN NUR AZIZAH", 'ttl' => "KEDIRI 23/11/1985", 'jk' => "P", 'nik' => "3506066311850004", 'lembaga' => "TASWIRUL JUHALA PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "MOHAMMAD IN'AMUL AUFA", 'ttl' => "KEDIRI 10/10/1997", 'jk' => "L", 'nik' => "3506061010970002", 'lembaga' => "TASWIRUL JUHALA PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "MIFTAKHUL JANAH", 'ttl' => "KEDIRI 10/01/1993", 'jk' => "P", 'nik' => "3506065001930001", 'lembaga' => "AL JAMI' TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "AYINUN FATIMATUR ROHMAH", 'ttl' => "KEDIRI 20/01/1984", 'jk' => "L", 'nik' => "3506066001840005", 'lembaga' => "AL JAMI' TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "ANISIATUL MUSAMA", 'ttl' => "KEDIRI 15/06/1967", 'jk' => "P", 'nik' => "3506065506670001", 'lembaga' => "SABILLUL MUTTAQIN TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "ALIFATUL KHUSNA", 'ttl' => "KEDIRI 28/05/2000", 'jk' => "P", 'nik' => "3506066805000003", 'lembaga' => "SABILLUL MUTTAQIN TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "ISTIQOMATIN SAKDIYAH", 'ttl' => "KEDIRI 21/02/1974", 'jk' => "P", 'nik' => "3506066102740004", 'lembaga' => "AL HIKMAH TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "ASTITI", 'ttl' => "KEDIRI 27/10/1980", 'jk' => "P", 'nik' => "3506066710800001", 'lembaga' => "AL HIKMAH TEMPUREJO WATES", 'jenis' => "TPQ"],
            ['nama' => "ATIK NURUL ABIDAH", 'ttl' => "KEDIRI 30/03/1984", 'jk' => "P", 'nik' => "3506067003840001", 'lembaga' => "AL FALAH JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "LILIS FAUZIAH SOLICHAH", 'ttl' => "KEDIRI 14/06/1987", 'jk' => "P", 'nik' => "3506065406870001", 'lembaga' => "AL FALAH JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI QONI'AH", 'ttl' => "KEDIRI 30/09/1971", 'jk' => "P", 'nik' => "3506067009710002", 'lembaga' => "REMAJA AL MUBAROKAH PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "DAVIA LOVETTA", 'ttl' => "KEDIRI 10/11/2006", 'jk' => "P", 'nik' => "3506065011060001", 'lembaga' => "REMAJA AL MUBAROKAH PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI NURHAYATI", 'ttl' => "KEDIRI 13/03/1983", 'jk' => "P", 'nik' => "3506065303830004", 'lembaga' => "USTHULUL ULUM DUWET WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "SHINFANA BILA", 'ttl' => "KEDIRI 31/03/1998", 'jk' => "P", 'nik' => "3506267103980002", 'lembaga' => "USTHULUL ULUM DUWET WATES", 'jenis' => "TPQ"], // Penyesuaian nama
            ['nama' => "ERIZA DEVI MAHARANI", 'ttl' => "KEDIRI 11/07/1993", 'jk' => "P", 'nik' => "3506195107930002", 'lembaga' => "AL HIKMAH SILIR WATES", 'jenis' => "TPQ"],
            ['nama' => "MARIATUL KIPTIYAH", 'ttl' => "KEDIRI 21/06/1979", 'jk' => "P", 'nik' => "3506066106780003", 'lembaga' => "AL HIKMAH SILIR WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MUNAWAROH", 'ttl' => "KEDIRI 12/11/1982", 'jk' => "P", 'nik' => "3506065211820001", 'lembaga' => "AL HIKMAH SILIR WATES", 'jenis' => "TPQ"],
            ['nama' => "KHUSNUL KHOTIMAH", 'ttl' => "KEDIRI 21/04/1984", 'jk' => "P", 'nik' => "3506066104840002", 'lembaga' => "AL HIKMAH SILIR WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MUYASAROH", 'ttl' => "KEDIRI 06/01/1964", 'jk' => "P", 'nik' => "3506064601640001", 'lembaga' => "AL MUBAROK SILIR WATES", 'jenis' => "TPQ"],
            ['nama' => "KHUSNUL KHOTIMAH", 'ttl' => "KEDIRI 10/07/1965", 'jk' => "P", 'nik' => "3506065007650004", 'lembaga' => "BABUSSALAM NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI MUZAIYANAH", 'ttl' => "KEDIRI 26/04/1989", 'jk' => "P", 'nik' => "3506246604890002", 'lembaga' => "BABUSSALAM NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "ERVIN DWI ANGGUN TIA", 'ttl' => "KEDIRI 01/03/1996", 'jk' => "P", 'nik' => "3518064103960001", 'lembaga' => "BABUSSALAM NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "NASRULLOH", 'ttl' => "LAMONGAN 07/08/1987", 'jk' => "L", 'nik' => "3524140803870003", 'lembaga' => "BABUSSALAM NGIJO SUMBERAGUNG WATES", 'jenis' => "TPQ"],
            ['nama' => "UMI LAILATUL FITRIYAH", 'ttl' => "KEDIRI 11/07/1983", 'jk' => "P", 'nik' => "3506065107830006", 'lembaga' => "AR ROHMAN TUNGE WATES", 'jenis' => "TPQ"],
            ['nama' => "INSIYAH", 'ttl' => "KEDIRI 01/02/1976", 'jk' => "P", 'nik' => "3506064102760003", 'lembaga' => "DARUSSALAM DUWET WATES", 'jenis' => "TPQ"],
            ['nama' => "MIFTAHUL JANNAH", 'ttl' => "KEDIRI 04/09/1987", 'jk' => "L", 'nik' => "3506064409870003", 'lembaga' => "AL JIHAD JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "NUR ASIYAH", 'ttl' => "KEDIRI 27/05/1982", 'jk' => "L", 'nik' => "3506096705820001", 'lembaga' => "FAAZ JAJAR WATES", 'jenis' => "TPQ"],
            ['nama' => "SANIK", 'ttl' => "KEDIRI 16/10/1973", 'jk' => "P", 'nik' => "3506065610730002", 'lembaga' => "NURUL HUDA TAWANG WATES", 'jenis' => "TPQ"],
            ['nama' => "MUHAMAD ROFIQ", 'ttl' => "KEDIRI 13/03/1984", 'jk' => "L", 'nik' => "3506061303840002", 'lembaga' => "NURUL QUR'AN 3 PAGU WATES", 'jenis' => "TPQ"],
            ['nama' => "SITI KHOLIFAH", 'ttl' => "KEDIRI 14/06/1976", 'jk' => "P", 'nik' => "3506065406760003", 'lembaga' => "AL HUDA KARANGANYAR WATES", 'jenis' => "TPQ"],
        ];

        $insertedCount = 0;

        foreach ($dataGuru as $guru) {
            // 1. Cari Lembaga berdasarkan Nama (dan pastikan jenisnya TPQ)
            $namaLembaga = trim($guru['lembaga']);
            $lembaga = Lembaga::where('nama_lembaga', $namaLembaga)
                              ->where('jenis_lembaga', 'TPQ')
                              ->first();

            if (!$lembaga) {
                $this->command->warn("Lembaga TPQ '{$namaLembaga}' tidak ditemukan! Guru {$guru['nama']} dilewati.");
                continue; // Skip jika lembaga tidak ada
            }

            // 2. Format TTL (Tempat Tanggal Lahir)
            $ttlParts = explode(' ', trim($guru['ttl']));
            $tanggalStr = array_pop($ttlParts); // Ambil bagian paling belakang (tanggal)
            $tempatLahir = implode(' ', $ttlParts); // Sisanya adalah nama tempat (bisa >1 kata)
            
            // Ubah format DD/MM/YYYY menjadi YYYY-MM-DD untuk database
            try {
                $tanggalLahir = Carbon::createFromFormat('d/m/Y', $tanggalStr)->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalLahir = null; // Jika format salah, set null
            }

            // 3. Masukkan ke Database
            Guru::updateOrCreate(
                ['nik' => $guru['nik']], // Kunci pencarian berdasarkan NIK
                [
                    'nama_lengkap' => $guru['nama'],
                    'jenis_kelamin' => $guru['jk'],
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir,
                    // Karena tidak ada data alamat di TPQ, biarkan kosong/null
                    'alamat_ktp' => null, 
                    'lembaga_id' => $lembaga->id,
                    'jenis_guru' => 'TPQ',
                    
                    // Kolom Default (Kosong/Menunggu Korcam)
                    'status_kepegawaian' => 'Non-ASN', 
                    'status_sertifikasi' => 'Belum',
                    'penerima_insentif' => 0,
                    'agama' => 'Islam',
                    'kabupaten' => 'Kediri',
                    
                    // Status Dokumen dibiarkan Pending
                    'status_ktp' => 'Pending',
                    'status_kk' => 'Pending',
                    'status_bukurekening' => 'Pending',
                ]
            );

            $insertedCount++;
        }

        $this->command->info("Berhasil menambahkan {$insertedCount} data Guru TPQ ke dalam database.");
    }
}