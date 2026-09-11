# TeKaPe.id — Tempa Karakteristik & Pengetahuan

![TeKaPe.id Banner](https://raw.githubusercontent.com/recreativeid/TeKaPe.id/main/public/favicon.ico)

> **Platform Bimbingan Belajar & Try Out Online Modern (TWK, TIU, TKP Kedinasan & CPNS)**  
> Dibangun dengan arsitektur bersih, mobile-first & responsive desktop SaaS, didukung framework **CodeIgniter 4** dan database **MySQL**.

---

## 🌟 Tentang TeKaPe.id

**TeKaPe.id** adalah aplikasi web edukasional modern berkonsep *clean educational SaaS* yang memfasilitasi persiapan tes seleksi CPNS dan Sekolah Kedinasan di Indonesia. Aplikasi ini mendukung **3 role pengguna utama**:
1. **Admin**: Mengelola bank soal, master data siswa, jadwal sesi bimbingan belajar, dan konfigurasi platform.
2. **Guru / Tentor**: Mengakses ruang kerja (*workspace*) khusus penyusunan paket soal Free & Premium dengan sistem keamanan verifikasi password berlapis.
3. **Murid / Siswa**: Mengerjakan try out Computer-Based Test (CBT), memantau grafik tren performa nilai secara visual, dan mengakses jadwal bimbingan belajar online.

---

## 🚀 Fitur Unggulan

- 🛡️ **Otentikasi 3 Role Terintegrasi**: Login terpisah untuk Admin, Tentor, dan Murid dengan opsi pemulihan via WhatsApp resmi.
- 🔒 **Proteksi Verifikasi Password Lapis Kedua**: Keamanan ekstra saat Admin atau Tentor membuka menu *Kelola Soal*.
- 📝 **Modul Kelola Soal Lengkap**:
  - Pilihan paket soal **Free** (Akses Terbuka) dan **Premium** (Berlangganan).
  - Manajemen kategori soal TWK (Tes Wawasan Kebangsaan), TIU (Tes Inteligensia Umum), dan TKP (Tes Karakteristik Pribadi).
  - Formula penilaian dinamis (sistem poin pilihan ganda & isian).
- 📊 **Dashboard Interaktif & Visualisasi**:
  - Grafik tren nilai try out interaktif berbasis Canvas native API.
  - Ringkasan statistik cepat dan kartu sesi bimbingan hari ini.
- ⏱️ **CBT Exam Engine**: Ujian try out online dengan *real-time countdown timer*, navigasi soal, dan kalkulasi skor otomatis.
- 📱💻 **Desain Responsif Adaptif**: Tampilan mobile-first yang nyaman dengan *bottom navigation bar*, serta adaptasi otomatis ke tata letak desktop multi-kolom (*top navigation bar*).

---

## 🛠️ Tech Stack

- **Backend Framework**: CodeIgniter 4 (PHP 8+)
- **Database**: MySQL / MariaDB (XAMPP)
- **Frontend**: HTML5, Vanilla CSS3 (Design Tokens System), Vanilla JavaScript (Canvas API)
- **Styling Palette**: Dark Navy (`#16192E`), Warm Cream (`#F7F4EE`), Warm Amber (`#C47426`), Soft Sage Green (`#E4EFE3`)

---

## 💻 Panduan Instalasi Lokal

### Prasyarat
- **XAMPP** dengan PHP versi 7.4 atau 8.0+
- **MySQL / MariaDB** aktif di XAMPP

### Langkah Instalasi

1. **Clone Repositori**:
   ```bash
   git clone https://github.com/recreativeid/TeKaPe.id.git
   cd TeKaPe.id
   ```

2. **Konfigurasi Database MySQL**:
   - Buka XAMPP Control Panel dan pastikan service **MySQL** serta **Apache** aktif.
   - Buat database baru bernama `tekape_db` melalui phpMyAdmin atau terminal MySQL:
     ```sql
     CREATE DATABASE tekape_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```
   - Import skema awal dari file dump yang telah disediakan:
     ```bash
     mysql -u root -p tekape_db < tekape_db.sql
     ```
   - Sesuaikan konfigurasi port dan kredensial database pada file `.env`:
     ```ini
     database.default.hostname = localhost
     database.default.database = tekape_db
     database.default.username = root
     database.default.password = ''
     database.default.DBDriver = MySQLi
     database.default.port = 3306  # atau 3307 jika menggunakan port kustom XAMPP
     ```

3. **Jalankan Migrasi & Seeder (Alternatif jika tidak import SQL)**:
   ```bash
   php spark migrate
   php spark db:seed TeKaPeSeeder
   ```

4. **Jalankan Server Lokal**:
   ```bash
   php spark serve --port 8080
   ```
   Buka peramban Anda di: **`http://localhost:8080`**

---

## 🔑 Akun Default untuk Pengujian

| Role | Username | Password | Keterangan |
|---|---|---|---|
| **Admin** | `admin` | `admin123` | Password proteksi kelola soal: `admin123` |
| **Tentor** | `tentor` | `tentor123` | Password proteksi kelola soal: `tentor123` |
| **Murid** | `simon` | `murid123` | Akun siswa dengan riwayat try out & status premium |

---

## ℹ️ Catatan Mengenai GitHub Pages

> **PENTING**: **GitHub Pages adalah layanan hosting situs web statis (HTML/CSS/JS)**.  
> GitHub Pages **tidak mendukung eksekusi backend PHP ataupun database MySQL**, sehingga aplikasi penuh CodeIgniter tidak dapat berjalan langsung di `username.github.io`.  
> Untuk mendeploy aplikasi ini secara live di internet:
> 1. Gunakan web hosting berbasis PHP/MySQL seperti **cPanel Hosting** (Niagahoster, DomaiNesia, IDCloudHost, Hostinger).
> 2. Atau platform cloud seperti **Railway / Render / Fly.io** menggunakan konfigurasi Docker PHP + MySQL.

---

## 📄 Lisensi
Hak Cipta © 2026 TeKaPe.id. Dikelola oleh [recreativeid](https://github.com/recreativeid).
