# Sistem Informasi Pendataan LP3MT Kabupaten Kediri

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Lighthouse-99%20Score-success?style=for-the-badge&logo=googlechrome&logoColor=white" alt="Lighthouse Score">
</p>

---

## 📌 Ringkasan Sistem

**Sistem Informasi Pendataan LP3MT (Lembaga Pengembangan Pesantren, Madin, dan TPQ)** adalah platform enterprise berbasis web yang dirancang untuk mengelola, memverifikasi, dan mengaudit data kelembagaan keagamaan serta tenaga pendidik di seluruh wilayah Kabupaten Kediri.

Sistem ini memfasilitasi sinkronisasi data berjenjang dari tingkat desa, kecamatan, hingga kabupaten untuk memastikan keabsahan alokasi insentif dan legalitas operasional lembaga secara transparan, akuntabel, dan bebas duplikasi.

---

## ✨ Fitur Utama & Logika Sistem

### 1. Compact Grid UI Architecture
* **Edge-to-Edge Data Density:** Desain antarmuka yang memanfaatkan lebar layar penuh (`max-w-full`, `py-2`) untuk meminimalkan *scrolling* vertikal.
* **Zero Layout Shift (CLS):** Setiap penampung media (*image container* & *PDF iframe*) memiliki dimensi statis untuk mencegah pergeseran tata letak saat proses pemuatan data.
* **Audit Performa Tinggi:** Skor Lighthouse desktop mencapai **Performance 99**, **Accessibility 98**, **Best Practices 100**, dan **SEO 100**.

### 2. Validasi & Hierarki Multi-Role (Korcam Engine)
* **Pemisahan Hak Akses:** Mendukung peran **Super Admin (Pusat)**, **Verifikator Kabupaten**, dan **Koordinator Kecamatan (Korcam)**.
* **Enforced Territorial Quota:** Pembatasan ketat kapasitas pengurus Korcam maksimal 3 personel per kecamatan (1 Ketua, 1 Anggota 1, 1 Anggota 2).
* **Real-Time AJAX State Check:** Terintegrasi dengan Select2 custom template untuk memvalidasi ketersediaan formasi kepengurusan secara langsung sebelum data dikirim ke server.

### 3. Pipeline Import Excel Transaksional
* **Hardened Import Pipeline (`GuruImport` & `LembagaImport`):** Mencegah korupsi data dengan menyaring dan memvalidasi duplikasi NIK, Nomor Rekening Bank, dan NSBQ di memori sebelum dieksekusi ke basis data.
* **Flash Session Error Handling:** Melempar rincian baris dan kolom yang cacat secara spesifik ke *session memory* untuk memudahkan perbaikan data massal oleh operator.

### 4. Sandbox Verifikasi Dokumen & Media 2x2
* **Dual Preview Legalitas:** Menampilkan dokumen PDF (IJOP, SKD, SPTJM, SKAM) dan foto fisik lapangan (Profil, Nambor, Bangunan, KBM) dalam format Grid 2x2 responsif.
* **Client-Side Image Guard:** Validasi format ketat (JPG/PNG) dan pembatasan ukuran berkas (maksimal 1 MB) secara *real-time* sebelum proses *upload*.
* **Status Action Bar:** Pengambilan keputusan verifikasi (*Pending, Disetujui, Ditolak*) yang disematkan langsung pada *header* dokumen terkait.

### 5. Context-Aware Pagination Retention
* Retensi parameter halaman (`redirect()->back()`) yang menjaga posisi halaman tabel (*page offset*) operator saat melakukan manipulasi data, mencegah sistem kembali secara paksa ke halaman pertama.

---

## 🛠️ Tech Stack & Dependencies

| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Framework Core** | Laravel 11.x | PHP MVC Web Framework |
| **Frontend Styling**| Tailwind CSS 3.x | Custom Utility-First Styling |
| **Interactive UI** | jQuery 3.7.x & Select2 | Dropdown Search & AJAX Check |
| **Database** | MySQL / MariaDB | Relational Database Engine |
| **Spreadsheet Engine** | Maatwebsite / Laravel Excel | Import/Export Processing |
| **Authentication** | Laravel Breeze / Custom Guard | Role-Based Access Control |

---

## 🚀 Panduan Instalasi Lokal

### 1. Prasyarat Sistem
* PHP `>= 8.2`
* Composer `>= 2.x`
* Node.js `>= 18.x` & NPM
* MySQL / MariaDB Server

### 2. Kloning Repositori
```bash
git clone [https://github.com/USERNAME/lp3mt_app.git](https://github.com/USERNAME/lp3mt_app.git)
cd lp3mt_app