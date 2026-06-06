<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Sandi</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f3f4f6; padding-bottom: 40px; padding-top: 40px; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); overflow: hidden; }
        .header { padding: 25px 20px; text-align: center; border-bottom: 2px solid #f1f5f9; background-color: #ffffff; }
        .logo-container img { height: 65px; margin: 0 10px; display: inline-block; }
        .content { padding: 35px 30px; color: #334155; line-height: 1.6; font-size: 15px; }
        .content h2 { color: #0f172a; margin-top: 0; font-size: 20px; }
        .btn-container { text-align: center; margin: 30px 0; }
        .btn { background-color: #dc2626; color: #ffffff !important; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; display: inline-block; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; font-size: 13px; color: #64748b; border-top: 1px solid #e2e8f0; }
        .muted { color: #94a3b8; font-size: 13px; margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 20px; word-break: break-all; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <!-- BAGIAN KOP SURAT / LOGO -->
            <div class="header">
                <div class="logo-container">
                    <!-- Pastikan nama file gambarnya sesuai dengan yang ada di folder public/images sampeyan -->
                    <img src="{{ asset('images/logo_pemkab.png') }}" alt="logo_kabupaten.png">
                    <img src="{{ asset('images/logo_lp3mt.png') }}" alt="logo_lp3mt.png">
                    <img src="{{ asset('images/logo_masbup.png') }}" alt="logo_masbup.png">
                </div>
            </div>

            <!-- BAGIAN ISI EMAIL -->
            <div class="content">
                <h2>Pengaturan Ulang Sandi</h2>
                <p>Yth. Pengguna <strong>{{ $email }}</strong>,</p>
                <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda di Portal LP3MT Kabupaten Kediri. Silakan klik tombol di bawah ini untuk membuat sandi baru:</p>
                
                <div class="btn-container">
                    <a href="{{ $url }}" class="btn">Atur Ulang Sandi</a>
                </div>

                <p>Tautan ini hanya berlaku selama <strong>60 menit</strong>.</p>
                <p>Jika Anda tidak pernah merasa meminta pengaturan ulang sandi, mohon abaikan email ini. Akun Anda akan tetap aman.</p>
                
                <br>
                <p>Hormat kami,<br><strong>Tim IT LP3MT Kabupaten Kediri</strong></p>

                <!-- Menampilkan URL mentah berjaga-jaga jika tombol gagal diklik -->
                <div class="muted">
                    Jika Anda kesulitan mengklik tombol "Atur Ulang Sandi", salin dan tempel URL berikut ke browser web Anda:<br>
                    <a href="{{ $url }}" style="color: #2563eb;">{{ $url }}</a>
                </div>
            </div>

            <!-- BAGIAN FOOTER -->
            <div class="footer">
                &copy; {{ date('Y') }} LP3MT Kabupaten Kediri. Hak Cipta Dilindungi.<br>
                Pemerintah Kabupaten Kediri
            </div>
        </div>
    </div>
</body>
</html>