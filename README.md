# Perpustakaan SMK Al-Munawwir IIBS

Sistem Informasi Perpustakaan berbasis web untuk SMK Al-Munawwir IIBS, dikembangkan di atas fondasi [BukuHub](https://github.com/ikhsan3adi/sistem-perpustakaan-qr-code) dengan penambahan fitur-fitur sesuai kebutuhan institusi.

## Dokumentasi BukuHub (Basis Proyek)

![Preview BukuHub](https://github.com/ikhsan3adi/sistem-perpustakaan-qr-code/raw/main/screenshots/home.png)

## Dokumentasi Perpustakaan SMK Al-Munawwir IIBS

> Tambahkan screenshot aplikasi Anda di sini setelah mengupload ke folder `screenshots/` di repositori.

<!-- Uncomment dan sesuaikan path berikut setelah upload screenshot:
![Dashboard](screenshots/dashboard.png)
![Kartu Perpustakaan](screenshots/kartu-perpustakaan.png)
![Kelola Kunjungan](screenshots/kunjungan.png)
![Kelola Kuis](screenshots/kuis.png)
![Leaderboard](screenshots/leaderboard.png)
![Notifikasi WhatsApp](screenshots/notifikasi-wa.png)
-->

## Fitur

### Fitur Dasar (dari BukuHub)
- Login, Register & Magic login link (via Email)
- Dashboard admin
- QR Code anggota
- QR Code peminjaman
- Sistem denda

### Fitur Pengembangan
- **Import Data Anggota** — impor data anggota secara massal melalui file
- **Kartu Perpustakaan** — generate dan cetak kartu perpustakaan anggota
- **Kelola Kunjungan** — rekam dan rekap kunjungan anggota perpustakaan
- **Kelola Kuis** — manajemen kuis oleh admin dan pengerjaan kuis di sisi anggota
- **Poin Reward & Leaderboard** — sistem poin untuk anggota aktif beserta papan peringkat
- **Notifikasi Pengingat Jatuh Tempo** — notifikasi otomatis via WhatsApp menggunakan Fonnte

## Framework dan Library Yang Digunakan

- [CodeIgniter 4](https://codeigniter.com/)
- [CodeIgniter Shield](https://codeigniter4.github.io/shield/)
- [Bootstrap 5](https://getbootstrap.com/)
- [Modernize Free Bootstrap 5 Admin Template](https://adminmart.com/product/modernize-free-bootstrap-5-admin-template/)
- [Tabler Icons](https://tabler-icons.io/)
- [Apex Charts](https://apexcharts.com/)
- [Endroid QR Code Generator](https://github.com/endroid/qr-code)
- [Mebjas Html5-QRCode Scanner](https://github.com/mebjas/html5-qrcode)
- [Fonnte](https://fonnte.com/) — notifikasi WhatsApp

## Cara Penggunaan

### Persyaratan

- [Composer](https://getcomposer.org/)
- PHP 8.1+ dan MySQL atau [XAMPP](https://www.apachefriends.org/download.html) versi 8.1+ dengan mengaktifkan extension `-intl` dan `-gd`
- (Opsional) Kamera/webcam untuk menjalankan QR scanner. Bisa juga menggunakan kamera HP dengan bantuan software DroidCam
- Token API [Fonnte](https://fonnte.com/) untuk fitur notifikasi WhatsApp

### Instalasi

- Unduh dan impor kode proyek ini ke dalam direktori proyek Anda (htdocs).
- Penting ⚠️. Jika belum memiliki file `.env`, salin/rename file `.env.example` menjadi `.env`.
- (Opsional) Konfigurasi file `.env` untuk mengatur parameter seperti koneksi database, token Fonnte, dan pengaturan lainnya sesuai dengan lingkungan pengembangan Anda.
- Penting ⚠️. Install dependencies yang diperlukan dengan menjalankan perintah berikut di terminal:

```shell
composer install
```

- Buat database `db_book_library` di phpMyAdmin / MySQL.
- Penting ⚠️. Jalankan migrasi database untuk membuat struktur tabel yang diperlukan:

```shell
php spark migrate --all
```

- Penting ⚠️. Jalankan perintah berikut untuk membuat akun `superadmin`:

```shell
php spark db:seed SuperAdminSeeder
```

> [!TIP]
>
> (Opsional) Isi database dengan data dummy / seeder.
>
> ```shell
> php spark db:seed Seeder # semua seeder
> php spark db:seed BookSeeder # buku
> php spark db:seed MemberSeeder # anggota
> php spark db:seed LoanSeeder # peminjaman, pengembalian & denda
> ```

- Jalankan website:

```shell
php spark serve
```

- Buka [http://localhost:8080](http://localhost:8080)
- Login dengan kredensial `superadmin` berikut:

```txt
username : superadmin
email    : superadmin@admin.com
password : superadmin
```

## Kredit

Proyek ini dikembangkan berdasarkan [BukuHub - Sistem Perpustakaan QR Code](https://github.com/ikhsan3adi/sistem-perpustakaan-qr-code) oleh [@ikhsan3adi](https://github.com/ikhsan3adi).

## Lisensi

Mengikuti lisensi repositori asal. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

## Authors

- [@ikhsan3adi](https://github.com/ikhsan3adi) — Pengembang BukuHub (basis proyek)
- [@gilangsetia](https://github.com/gilangsetia/) — Pengembang Perpustakaan SMK Al-Munawwir IIBS