<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
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
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <div class="logo-container">
                    <img src="{{ asset('images/logo_pemkab.png') }}" alt="Pemkab Kediri">
                    <img src="{{ asset('images/logo_lp3mt.png') }}" alt="LP3MT Kediri">
                    <img src="{{ asset('images/logo_masbup.png') }}" alt="Masbup">
                </div>
            </div>

            <div class="content">
                <h2>Verifikasi Akun Anda</h2>
                <p>Yth. <strong>{{ $name }}</strong>,</p>
                <p>Terima kasih telah bergabung di Portal LP3MT Kabupaten Kediri. Untuk menyelesaikan proses pendaftaran dan mengaktifkan akun Anda, mohon verifikasi alamat email ini dengan mengklik tombol di bawah ini:</p>
                
                <div class="btn-container">
                    <a href="{{ $url }}" class="btn">Verifikasi Email Saya</a>
                </div>

                <p>Jika Anda tidak merasa mendaftar di sistem kami, mohon abaikan email ini.</p>
                
                <br>
                <p>Hormat kami,<br><strong>Tim IT LP3MT Kabupaten Kediri</strong></p>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} LP3MT Kabupaten Kediri. Hak Cipta Dilindungi.<br>
                Pemerintah Kabupaten Kediri
            </div>
        </div>
    </div>
</body>
</html>