<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\Lembaga;
use Carbon\Carbon;

class GuruMadinWatesSeeder extends Seeder
{
    public function run(): void
    {
        $dataGuru = [
            ['nama' => "ERIK YULIATI", 'ttl' => "KEDIRI 29/09/1992", 'jk' => "P", 'nik' => "3506066909920003", 'lembaga' => "AL ASY'ARI POJOK WATES", 'alamat' => "DUSUN SELODONO RT/RW 018/005"],
            ['nama' => "MOH. SYAMSODIN", 'ttl' => "KEDIRI 25/07/1986", 'jk' => "L", 'nik' => "3506062507860002", 'lembaga' => "AL ASY'ARI POJOK WATES", 'alamat' => "DUSUN SELODONO RT/RW 014/004"],
            ['nama' => "BINTI SOLEKAH ARTI BINGRUM", 'ttl' => "KEDIRI 02/07/1998", 'jk' => "P", 'nik' => "3506104207980001", 'lembaga' => "AL ASY'ARI POJOK WATES", 'alamat' => "DSN TLOGOWONO RT 007 RW 003"],
            ['nama' => "SITI RUMANAH", 'ttl' => "KEDIRI 21/04/1970", 'jk' => "P", 'nik' => "3506106104700004", 'lembaga' => "AL ASY'ARI POJOK WATES", 'alamat' => "DSN TLOGOWONO RT 007 RW 003"],
            ['nama' => "ISROWIYAH", 'ttl' => "KEDIRI 23/10/1972", 'jk' => "P", 'nik' => "3506096310720002", 'lembaga' => "AL ASY'ARI POJOK WATES", 'alamat' => "DSN GONDANG RT 012 RW 003"],
            ['nama' => "MUHAMMAD ROIHAN", 'ttl' => "KEDIRI 09/08/1970", 'jk' => "L", 'nik' => "3506060404670001", 'lembaga' => "AL HUDA JOHO WATES", 'alamat' => "DUSUN SUMBERDLINGO RT 21 RW 05"],
            ['nama' => "BINTI MUKARROMAH", 'ttl' => "KEDIRI 17/07/1980", 'jk' => "P", 'nik' => "3506065707800002", 'lembaga' => "AL HUDA JOHO WATES", 'alamat' => "JL. PROTOKOL RT 14 RW 03"],
            ['nama' => "MOCHAMMAD FARRIJ KARBANA", 'ttl' => "KEDIRI 30/09/1998", 'jk' => "L", 'nik' => "3506063009980001", 'lembaga' => "AL WATHON SUMBERAGUNG WATES", 'alamat' => "DUSUN SEMINANG RT/RW 014/004"],
            ['nama' => "IMAM MUSLIM", 'ttl' => "KEDIRI 24/04/1974", 'jk' => "L", 'nik' => "3506062404740002", 'lembaga' => "AL BAROKAH POJOK WATES", 'alamat' => "DSN POJOK 009/003"],
            ['nama' => "ASMAUL HUSNA", 'ttl' => "KEDIRI 01/01/1974", 'jk' => "P", 'nik' => "3506064401740002", 'lembaga' => "AL BAROKAH POJOK WATES", 'alamat' => "DSN POJOK 005/002"],
            ['nama' => "SUHARTI", 'ttl' => "KEDIRI 28/02/1981", 'jk' => "P", 'nik' => "3506066802810002", 'lembaga' => "AL BAROKAH POJOK WATES", 'alamat' => "DSN POJOK 007/002"],
            ['nama' => "SITI ROFI'AH", 'ttl' => "KEDIRI 12/07/1975", 'jk' => "P", 'nik' => "3506065207750004", 'lembaga' => "AL HIDAYAH JANTI WATES", 'alamat' => "Gemenggeng Janti Wates Kediri"],
            ['nama' => "DEWI MAHMUDAH", 'ttl' => "KEDIRI 08/05/1978", 'jk' => "P", 'nik' => "3506064805780001", 'lembaga' => "AL HIDAYAH JANTI WATES", 'alamat' => "Gemenggeng Janti Wates Kediri"],
            ['nama' => "NURUL HIDAYAH", 'ttl' => "KEDIRI 20/03/1984", 'jk' => "P", 'nik' => "3506066003840001", 'lembaga' => "AL HIDAYAH JANTI WATES", 'alamat' => "Gemenggeng Janti Wates Kediri"],
            ['nama' => "MUHAMMAD KHABIB", 'ttl' => "KEDIRI 04/02/2000", 'jk' => "L", 'nik' => "3506264402000000", 'lembaga' => "AL HIDAYAH JANTI WATES", 'alamat' => "Gemenggeng Janti Wates Kediri"],
            ['nama' => "SITI SUHANIKMAH", 'ttl' => "KEDIRI 14/06/1991", 'jk' => "P", 'nik' => "3506065406910001", 'lembaga' => "AL HIKMAH JOHO WATES", 'alamat' => "JOHO-WATES-KEDIRI"],
            ['nama' => "TRISTIANA", 'ttl' => "KEDIRI 14/02/1976", 'jk' => "P", 'nik' => "3506065402760002", 'lembaga' => "AL HIKMAH JOHO WATES", 'alamat' => "JOHO-WATES-KEDIRI"],
            ['nama' => "HUSNUL AINI", 'ttl' => "KEDIRI 02/03/1964", 'jk' => "P", 'nik' => "3506064302640001", 'lembaga' => "AL IKHLAS JOHO WATES", 'alamat' => "DSN TIRTOMULYO RT 05 RW 01"],
            ['nama' => "IKHSAN EFENDI", 'ttl' => "KEDIRI 24/11/1977", 'jk' => "L", 'nik' => "3506062411770000", 'lembaga' => "AL IRSYAD WONOREJO WATES", 'alamat' => "Dsn Beji RT 004 RW 001"],
            ['nama' => "MUHAMMAD HAMDANY ALI", 'ttl' => "KEDIRI 08/02/2000", 'jk' => "L", 'nik' => "3506060802000000", 'lembaga' => "AL IRSYAD WONOREJO WATES", 'alamat' => "Dsn Jaten RT 022 RW 005"],
            ['nama' => "AVIN ROHMATI", 'ttl' => "KEDIRI 20/10/1982", 'jk' => "P", 'nik' => "3506046010820004", 'lembaga' => "AL MINHAJJ WATES WATES", 'alamat' => "DSN NGADILUWIH RT 2 RW 4"],
            ['nama' => "AIDA FITRIA HASNA", 'ttl' => "KEDIRI 13/03/1994", 'jk' => "P", 'nik' => "3506065303940001", 'lembaga' => "AL MINHAJJ WATES WATES", 'alamat' => "DSN BONDO RT 23 RW 08"],
            ['nama' => "ARIS JAMALUDIN", 'ttl' => "LAMONGAN 26/07/1989", 'jk' => "L", 'nik' => "3524092607890002", 'lembaga' => "AL MINHAJJ WATES WATES", 'alamat' => "DSN KREBET RT 5 RW 1"],
            ['nama' => "FAJAR EKA KURNIAWAN", 'ttl' => "KEDIRI 25/04/1981", 'jk' => "L", 'nik' => "3506060803020002", 'lembaga' => "AL HUSNA WATES WATES", 'alamat' => "DSN PESANGGRAHAN RT 20 RW 07"],
            ['nama' => "WAHYU AHMAD BIRRUL WALIDAIN", 'ttl' => "KEDIRI 25/12/2002", 'jk' => "L", 'nik' => "3506062512020003", 'lembaga' => "AL HUSNA WATES WATES", 'alamat' => "DSN PESANGGRAHAN RT 20 RW 07"],
            ['nama' => "MUHAMMAD SYAUQIL ULAA", 'ttl' => "KEDIRI 05/04/1991", 'jk' => "L", 'nik' => "3506060504910003", 'lembaga' => "AL IHSAN JAJAR WATES", 'alamat' => "KALIKAJAR 28/05"],
            ['nama' => "DEWI MAISAROTUL MUFIDAH", 'ttl' => "KEDIRI 27/06/1991", 'jk' => "P", 'nik' => "3506176706910003", 'lembaga' => "AL IHSAN JAJAR WATES", 'alamat' => "KALIKAJAR 28/05"],
            ['nama' => "ISTIKHOMAH", 'ttl' => "KEDIRI 23/11/1980", 'jk' => "P", 'nik' => "3506066311800001", 'lembaga' => "AR ROHMAN TUNGE WATES", 'alamat' => "JL TEMPURSARI RT 018 RW 03"],
            ['nama' => "SULISTIYANI", 'ttl' => "KEDIRI 06/06/1974", 'jk' => "P", 'nik' => "3506064605740003", 'lembaga' => "AR ROHMAN TUNGE WATES", 'alamat' => "JL TEMPURSARI RT 016 RW 03"],
            ['nama' => "CIKA DWI CAHYANI", 'ttl' => "NGANJUK 23/09/1984", 'jk' => "P", 'nik' => "3506066309840003", 'lembaga' => "AR ROHMAN TUNGE WATES", 'alamat' => "JL TEMPURSARI RT 017 RW 05"],
            ['nama' => "NINA NURROFI'", 'ttl' => "KEDIRI 16/07/1984", 'jk' => "P", 'nik' => "3506065607840001", 'lembaga' => "BAITUL MUTTAQIIN NGIJO SUMBERAGUNG WATES", 'alamat' => "RT 022 RW 005 DSN JATEN"],
            ['nama' => "ANISA NUR FADILA", 'ttl' => "KEDIRI 28/07/2002", 'jk' => "P", 'nik' => "3506066807020001", 'lembaga' => "BAITUL MUTTAQIIN NGIJO SUMBERAGUNG WATES", 'alamat' => "RT 025 RW 006 DSN SUMBERAGUNG"],
            ['nama' => "MIFTAKUL JANAH", 'ttl' => "KEDIRI 18/10/1991", 'jk' => "P", 'nik' => "3506065810910001", 'lembaga' => "BAITUL MUTTAQIIN NGIJO SUMBERAGUNG WATES", 'alamat' => "RT 041 RW 010 DSN DAWUNG"],
            ['nama' => "RUBANGI", 'ttl' => "KEDIRI 05/08/1963", 'jk' => "L", 'nik' => "3506060508630003", 'lembaga' => "BAITUL UMMAH SUMBERAGUNG WATES", 'alamat' => "RT 31 RW 08 DSN SUMBERASIH"],
            ['nama' => "M FAHRU ROZI", 'ttl' => "KEDIRI 12/10/1986", 'jk' => "L", 'nik' => "3506061210860000", 'lembaga' => "DARUL KHOIR JAJAR WATES", 'alamat' => "KALIKAJAR RT 22 RW 04"],
            ['nama' => "VINURIKA HIMATUS SA'ADAH", 'ttl' => "KEDIRI 21/08/1986", 'jk' => "P", 'nik' => "3506066108860000", 'lembaga' => "DARUL KHOIR JAJAR WATES", 'alamat' => "KALIKAJAR RT 21 RW 04"],
            ['nama' => "SITI MUNTAMAH", 'ttl' => "KEDIRI 28/05/1970", 'jk' => "P", 'nik' => "3506066805700001", 'lembaga' => "DARUL KHOIR JAJAR WATES", 'alamat' => "JL ABU BAKAR RT 025 RW 005"],
            ['nama' => "SULIS HARTINI", 'ttl' => "KEDIRI 08/05/1985", 'jk' => "P", 'nik' => "3506064805860003", 'lembaga' => "DARUL KHOIR JAJAR WATES", 'alamat' => "DSN TUNGE 026/004"],
            ['nama' => "NIKEN PERTIWI", 'ttl' => "KEDIRI 01/04/2000", 'jk' => "P", 'nik' => "3506094104000004", 'lembaga' => "DARUL KHOIR JAJAR WATES", 'alamat' => "DSN KWARASAN RT 001 RW 001 DS KAWEDUSAN"],
            ['nama' => "AINUL YAQIN RAMADHANI", 'ttl' => "KEDIRI 07/02/1996", 'jk' => "L", 'nik' => "3506050702960005", 'lembaga' => "DARUL KHOIR JAJAR WATES", 'alamat' => "DSN TLOGOWONO 007/003"],
            ['nama' => "NUR LAILI HIDAYAH", 'ttl' => "KEDIRI 21/01/1979", 'jk' => "P", 'nik' => "3506066101790001", 'lembaga' => "DARUSSALAMAH SIDOMULYO WATES", 'alamat' => "DSN SIDOMULYO RT/RW 003/001"],
            ['nama' => "IDA BINTI SA'ADAH", 'ttl' => "KEDIRI 13/02/1977", 'jk' => "P", 'nik' => "3506065302770002", 'lembaga' => "DARUSSALAMAH SIDOMULYO WATES", 'alamat' => "DSN KALEN RT/RW 005/002"],
            ['nama' => "SITI ROFIATUL KHOLIFAH", 'ttl' => "KEDIRI 17/09/1998", 'jk' => "P", 'nik' => "3506065709980004", 'lembaga' => "DARUSSALAMAH SIDOMULYO WATES", 'alamat' => "DSN WINONG RT/RW 002/004"],
            ['nama' => "MAHBUBATUL MAIMUNAH", 'ttl' => "KEDIRI 14/10/1983", 'jk' => "P", 'nik' => "3506065410830001", 'lembaga' => "DARUSSALAMAH SIDOMULYO WATES", 'alamat' => "DSN SIDOMULYO RT/RW 002/001"],
            ['nama' => "UMI SHOLEKAH", 'ttl' => "KEDIRI 13/07/1976", 'jk' => "P", 'nik' => "3506065307760002", 'lembaga' => "DARUSSALAMAH SIDOMULYO WATES", 'alamat' => "DSN SIDOMULYO RT/RW 003/001"],
            ['nama' => "NAFIK ATUR ROHMAH", 'ttl' => "KEDIRI 14/12/1995", 'jk' => "P", 'nik' => "3506065412950002", 'lembaga' => "DARUSSALAMAH SIDOMULYO WATES", 'alamat' => "DSN SUMBERBENING RT/RW 050/014"],
            ['nama' => "IMAM KAMBALI", 'ttl' => "KEDIRI 09/03/1968", 'jk' => "L", 'nik' => "3506061109690001", 'lembaga' => "DARUSSALAMAH SIDOMULYO WATES", 'alamat' => "DSN SIDOMULYO RT/RW 003/001"],
            ['nama' => "JUMALI", 'ttl' => "KEDIRI 09/12/1968", 'jk' => "L", 'nik' => "3506060912680003", 'lembaga' => "DARUSSALAMAH SIDOMULYO WATES", 'alamat' => "DSN WINONG RT/RW 003/004"],
            ['nama' => "ISTIQOMAH", 'ttl' => "KEDIRI 27/07/1957", 'jk' => "P", 'nik' => "3506066707570001", 'lembaga' => "HIDAYATUL MUBTADIIN AL MUWAZANAH PLAOSAN WATES", 'alamat' => "DSN PLAOSAN"],
            ['nama' => "DEWI MARYAM", 'ttl' => "BLITAR 06/01/1986", 'jk' => "P", 'nik' => "3506064501860004", 'lembaga' => "MADINATUL ULUM TEKENUWUNG SUMBERAGUNG WATES", 'alamat' => "DSN SUMBERBENING DS SUMBERAGUNG 047/013"],
            ['nama' => "ALFIAH", 'ttl' => "MADIUN 16/03/1968", 'jk' => "P", 'nik' => "3506065603680002", 'lembaga' => "MADINATUL ULUM TEKENUWUNG SUMBERAGUNG WATES", 'alamat' => "DSN SUMBERBENING DS SUMBERAGUNG 050/014"],
            ['nama' => "ST CHOTIMUL ASFIYAH", 'ttl' => "TULUNGAGUNG 19/10/1962", 'jk' => "P", 'nik' => "3506065910620001", 'lembaga' => "MADINATUL ULUM TEKENUWUNG SUMBERAGUNG WATES", 'alamat' => "DSN SUMBERBENING DS SUMBERAGUNG 049/014"],
            ['nama' => "HANIF NURUL LAILI", 'ttl' => "KEDIRI 09/04/1971", 'jk' => "P", 'nik' => "3506064904710001", 'lembaga' => "MIFTAKHUL HUDA SILIR WATES", 'alamat' => "DESA SILIR KEC WATES KAB KEDIRI"],
            ['nama' => "UMI HANIK", 'ttl' => "KEDIRI 10/08/1969", 'jk' => "P", 'nik' => "3506065008690001", 'lembaga' => "MIFTAKHUL HUDA SILIR WATES", 'alamat' => "DESA SILIR KEC WATES KAB KEDIRI"],
            ['nama' => "ASMIRATUN NAIMAH", 'ttl' => "KEDIRI 25/03/1996", 'jk' => "P", 'nik' => "3506016503960002", 'lembaga' => "MIFTAKHUL HUDA SILIR WATES", 'alamat' => "DESA SILIR KEC WATES KAB KEDIRI"],
            ['nama' => "KHOIRUL HUDA", 'ttl' => "KEDIRI 06/05/1984", 'jk' => "L", 'nik' => "3506030805840003", 'lembaga' => "MIFTAKHUL MUBTADIIN SIDOMULYO WATES", 'alamat' => "TEMPURAN"],
            ['nama' => "SITI NUR NAFI'AH", 'ttl' => "KEDIRI 15/02/1992", 'jk' => "P", 'nik' => "3506245502920002", 'lembaga' => "MIFTAKHUL MUBTADIIN SIDOMULYO WATES", 'alamat' => "TEMPURAN"],
            ['nama' => "MOCHAMAD WIDI FATURRAHMAN", 'ttl' => "KEDIRI 23/08/2000", 'jk' => "L", 'nik' => "3506062308000002", 'lembaga' => "MIFTAKHUL MUBTADIIN SIDOMULYO WATES", 'alamat' => "DSN TEMBORO RT 008 RW 002"],
            ['nama' => "INNESI WIDYASARI", 'ttl' => "MALANG 17/02/1989", 'jk' => "P", 'nik' => "3573055702890007", 'lembaga' => "MIFTAKHUL MUBTADIIN SIDOMULYO WATES", 'alamat' => "JL BRANJANGAN RT 017 RW 006"],
            ['nama' => "USMAN DAHLAN", 'ttl' => "MALANG 01/07/1963", 'jk' => "L", 'nik' => "3506060107630067", 'lembaga' => "MMQ ROUDLOTUL BADI'AH PAGU WATES", 'alamat' => "DSN SUMBERURIP 019/004"],
            ['nama' => "BADI'ATUL AZMINA", 'ttl' => "KEDIRI 06/04/1967", 'jk' => "P", 'nik' => "3506064604670001", 'lembaga' => "MMQ ROUDLOTUL BADI'AH PAGU WATES", 'alamat' => "DSN SUMBERURIP 019/004"],
            ['nama' => "SITI KALIMAH", 'ttl' => "KEDIRI 17/10/1976", 'jk' => "P", 'nik' => "3506065710760002", 'lembaga' => "MMQ ROUDLOTUL BADI'AH PAGU WATES", 'alamat' => "DSN SUMBERURIP 018/004"],
            ['nama' => "KOMARIAH", 'ttl' => "JOMBANG 14/09/1978", 'jk' => "P", 'nik' => "3506065409780004", 'lembaga' => "MMQ ROUDLOTUL BADI'AH PAGU WATES", 'alamat' => "DSN JATEN 030/007"],
            ['nama' => "SITI MUNAWAROH", 'ttl' => "KEDIRI 06/05/1985", 'jk' => "P", 'nik' => "3506064605850001", 'lembaga' => "MMQ ROUDLOTUL BADI'AH PAGU WATES", 'alamat' => "DSN JATEN 024/005"],
            ['nama' => "SITI KHOIRIYAH", 'ttl' => "KEDIRI 15/06/1973", 'jk' => "P", 'nik' => "3506065506730006", 'lembaga' => "MMQ ROUDLOTUL BADI'AH PAGU WATES", 'alamat' => "DSN SUMBERURIP 013/003"],
            ['nama' => "SUTAJI", 'ttl' => "KEDIRI 18/12/1970", 'jk' => "L", 'nik' => "3506061812700003", 'lembaga' => "NURUL QUR'AN PAGU WATES", 'alamat' => "KREBET RT 08 RW 02"],
            ['nama' => "ASMAUL CHASANAH", 'ttl' => "KEDIRI 25/10/2002", 'jk' => "P", 'nik' => "3506066510020001", 'lembaga' => "NURUL QUR'AN PAGU WATES", 'alamat' => "KREBET RT 10 RW 02"],
            ['nama' => "NADHIROTUL BADI'AH", 'ttl' => "KEDIRI 11/05/2002", 'jk' => "P", 'nik' => "3506065105020002", 'lembaga' => "NURUL QUR'AN PAGU WATES", 'alamat' => "SUMBERURIP RT 16 RW 03"],
            ['nama' => "H M MACHFUDZ AZIZ", 'ttl' => "KEDIRI 18/07/1981", 'jk' => "L", 'nik' => "3506061807810001", 'lembaga' => "NURUL QUR'AN PAGU WATES", 'alamat' => "SUMBERURIP RT 16 RW 03"],
            ['nama' => "UMI FAIZAH", 'ttl' => "KEDIRI 01/12/1985", 'jk' => "P", 'nik' => "3506064112850001", 'lembaga' => "NURUL QUR'AN PAGU WATES", 'alamat' => "KREBET RT 08 RW 02"],
            ['nama' => "TUTIK MASRUROH", 'ttl' => "KEDIRI 01/09/1969", 'jk' => "P", 'nik' => "3506064109690001", 'lembaga' => "ROUDLOTUL QUR'AN TUNGE WATES", 'alamat' => "DSN JAMBU RT 013 RW 002"],
            ['nama' => "M JUWAINI", 'ttl' => "KEDIRI 22/02/1974", 'jk' => "L", 'nik' => "3506062202740002", 'lembaga' => "ROUDLOTUL QUR'AN TUNGE WATES", 'alamat' => "DSN JAMBU RT 005 RW 001"],
            ['nama' => "MOH ALI FASHIHUDDIN", 'ttl' => "KEDIRI 14/01/1972", 'jk' => "L", 'nik' => "3506061401720001", 'lembaga' => "SABILILLAH JOHO WATES", 'alamat' => "SUMBERDLINGO RT 028 RW 006"],
            ['nama' => "MOH ALI MANSUR", 'ttl' => "KEDIRI 15/11/1964", 'jk' => "L", 'nik' => "3506061511640002", 'lembaga' => "SABILILLAH JOHO WATES", 'alamat' => "SUMBERDLINGO RT 024 RW 005"],
            ['nama' => "MOHAMMAD DAMANHURI", 'ttl' => "KEDIRI 04/12/1987", 'jk' => "L", 'nik' => "3506060412870001", 'lembaga' => "SABILILLAH JOHO WATES", 'alamat' => "SUMBERDLINGO RT 024 RW 005"],
            ['nama' => "SITI ASIYAH", 'ttl' => "BLITAR 29/07/1979", 'jk' => "P", 'nik' => "3506066907790003", 'lembaga' => "NURUL ISHLAH DUWET WATES", 'alamat' => "DSN PUCANGANOM"],
            ['nama' => "AMIN DOROINI", 'ttl' => "KEDIRI 05/12/1984", 'jk' => "L", 'nik' => "3506060512840003", 'lembaga' => "BAITURROHIM DUWET WATES", 'alamat' => "DSN DUWET RT 033 RW 008"],
            ['nama' => "BINTI BADRIJAH", 'ttl' => "KEDIRI 03/07/1967", 'jk' => "P", 'nik' => "3506064307670002", 'lembaga' => "BABUSSALAM NGIJO SUMBERAGUNG WATES", 'alamat' => "NGIJO RT 38 RW 10"],
            ['nama' => "ATIK", 'ttl' => "KEDIRI 21/04/1967", 'jk' => "P", 'nik' => "3506066104670004", 'lembaga' => "BABUSSALAM NGIJO SUMBERAGUNG WATES", 'alamat' => "NGIJO RT 35 RW 09"],
            ['nama' => "MUH MIRFAQ", 'ttl' => "KEDIRI 01/08/1989", 'jk' => "L", 'nik' => "3506090108890001", 'lembaga' => "ROUDLOTUS SALAAM PLAOSAN WATES", 'alamat' => "RT 015 RW 003 DS GONDANG"],
            ['nama' => "MUHAMMAD FACHRUROZI", 'ttl' => "KEDIRI 11/02/2003", 'jk' => "L", 'nik' => "3506091102030001", 'lembaga' => "ROUDLOTUS SALAAM PLAOSAN WATES", 'alamat' => "RT 007 RW 002"],
            ['nama' => "KHAMIM ADAM HABIBI", 'ttl' => "KEDIRI 29/04/1994", 'jk' => "L", 'nik' => "3506062904940004", 'lembaga' => "JABAL NUUR DUWET WATES", 'alamat' => "DSN BABADAN RT 20 RW 006 DUWET"],
            ['nama' => "NIKMATUL KHOIRIYAH", 'ttl' => "BLITAR 30/09/1971", 'jk' => "P", 'nik' => "3506067009710004", 'lembaga' => "JABAL NUUR DUWET WATES", 'alamat' => "DSN DUWET RT 34 RW 007"],
            ['nama' => "IMAM ZAENUDIN", 'ttl' => "KEDIRI 01/08/1970", 'jk' => "L", 'nik' => "3506060108700011", 'lembaga' => "AL JAMI' TUNGE WATES", 'alamat' => "DSN TUNGE RT 021 RW 004"],
            ['nama' => "KHOIRIYAH", 'ttl' => "KEDIRI 07/10/1970", 'jk' => "P", 'nik' => "3506064710700006", 'lembaga' => "AL JAMI' TUNGE WATES", 'alamat' => "DSN TUNGE RT 021 RW 004"],
            ['nama' => "M LUTFI ZAKARIA", 'ttl' => "KEDIRI 27/01/2002", 'jk' => "L", 'nik' => "3506062701020002", 'lembaga' => "NURUL QUR'AN 2 PAGU WATES", 'alamat' => "DSN SUMBERDLINGO JOHO WATES"],
        ];

        $insertedCount = 0;

        foreach ($dataGuru as $guru) {
            // 1. Cari Lembaga berdasarkan Nama
            $namaLembaga = trim($guru['lembaga']);
            $lembaga = Lembaga::where('nama_lembaga', $namaLembaga)->first();

            if (!$lembaga) {
                $this->command->warn("Lembaga '{$namaLembaga}' tidak ditemukan! Guru {$guru['nama']} dilewati.");
                continue; // Skip jika lembaga tidak ada
            }

            // 2. Format TTL (Tempat Tanggal Lahir)
            // Contoh input: "KEDIRI 29/09/1992"
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
            // Menggunakan updateOrCreate berdasarkan NIK agar jika dijalankan ulang tidak dobel
            Guru::updateOrCreate(
                ['nik' => $guru['nik']], // Kunci pencarian
                [
                    'nama_lengkap' => $guru['nama'],
                    'jenis_kelamin' => $guru['jk'],
                    'tempat_lahir' => $tempatLahir,
                    'tanggal_lahir' => $tanggalLahir,
                    'alamat_ktp' => $guru['alamat'],
                    'lembaga_id' => $lembaga->id,
                    'jenis_guru' => 'MADIN',
                    
                    // Kolom Default (Kosong/Menunggu Korcam)
                    'status_kepegawaian' => 'Non-ASN', // Sesuai form default
                    'status_sertifikasi' => 'Belum',
                    'penerima_insentif' => 0,
                    'agama' => 'Islam',
                    'kabupaten' => 'Kediri',
                    
                    // Status Dokumen dibiarkan Pending/Null
                    'status_ktp' => 'Pending',
                    'status_kk' => 'Pending',
                    'status_bukurekening' => 'Pending',
                ]
            );

            $insertedCount++;
        }

        $this->command->info("Berhasil menambahkan {$insertedCount} data Guru MADIN ke dalam database.");
    }
}