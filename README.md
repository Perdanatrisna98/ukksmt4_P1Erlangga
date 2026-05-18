<div align="center">

# Aplikasi Peminjaman Alat

Sistem manajemen peminjaman alat berbasis web yang dibangun menggunakan **Laravel 12**, **Filament v5**, dan **MySQL 8**.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3.30-777BB4?style=flat-square&logo=php&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-v5-FDAE4B?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-8.4.3-4479A1?style=flat-square&logo=mysql&logoColor=white)

</div>

---

## Tentang Proyek

Aplikasi ini dibuat untuk memudahkan proses peminjaman alat secara digital, mulai dari pengajuan, persetujuan, pemantauan, hingga pengembalian alat dengan perhitungan denda otomatis.

| Info | Detail |
|------|--------|
| Konsentrasi Keahlian | Rekayasa Perangkat Lunak |
| Kode Soal | KM25.4.1.1 |
| Tahun Pelajaran | 2025/2026 |

---

## Stack Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 12 |
| Runtime | PHP 8.3.30 |
| Admin Panel | Filament v5 |
| Database | MySQL 8.4.3 |
| Authentication & Permission | Filament Shield |
| Export PDF | Filament Html2Media |
| Activity Log | Spatie Activity Log |

---

## Requirements

Sebelum menjalankan project, pastikan sudah menginstall:

- PHP 8.3+
- Composer
- MySQL 8+
- Node.js & NPM

---

## Akun Demo

> Akun berikut hanya digunakan untuk kebutuhan demo dan pengujian aplikasi.

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@app.co | 123456 |
| Petugas | petugas@app.co | 123456 |
| Peminjam | peminjam@app.co | 123456 |

---

## Instalasi Project

Clone repository berikut:

```bash
git clone https://github.com/Perdanatrisna98/ukksmt4_P1Erlangga
cd ukksmt4_P1Erlangga
````

Install dependency:

```bash
composer install
```

Copy file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Atur koneksi database pada file `.env`

Upgrade Filament:

```bash
php artisan filament:upgrade
```

Jalankan server:

```bash
php artisan serve
```

---

## Fitur Aplikasi

### Semua Role

* Login & Logout

### Admin

* CRUD User
* CRUD Alat
* CRUD Kategori
* CRUD Peminjaman
* CRUD Pengembalian
* Manajemen Denda & Pelanggaran
* Activity Log
* Akses Semua Fitur

### Petugas

* Menyetujui Peminjaman
* Memantau Pengembalian
* Cetak Laporan

### Peminjam

* Melihat Daftar Alat
* Mengajukan Peminjaman
* Mengembalikan Alat

---

## Progress Pengerjaan

| Tanggal     | Aktivitas                                                |
| ----------- | -------------------------------------------------------- |
| 14 Apr 2026 | Inisialisasi project, konfigurasi environment & database |
| 28 Apr 2026 | Membuat fitur Login, Logout, dan Manage Users            |
| 29 Apr 2026 | Implementasi login sesuai role                           |
| 30 Apr 2026 | Membuat CRUD Kategori & perbaikan tampilan               |
| 1 May 2026  | Membuat CRUD Alat                                        |
| 2 May 2026  | Perbaikan tampilan aplikasi                              |
| 4 May 2026  | Membuat CRUD Peminjaman                                  |
| 6 May 2026  | Membuat CRUD Pengembalian                                |
| 8 May 2026  | Membuat fitur Denda & Pelanggaran                        |
| 11 May 2026 | Perbaikan tampilan & bug                                 |
| 12 May 2026 | Membuat Activity Log & Cetak Laporan                     |
| 18 May 2026 | Fixing Error di Fitur Cetak Laporan                      |

---

## Akses Lokal

Jika ingin mengakses aplikasi melalui jaringan lokal/lab sekolah:

| Keterangan | URL                     |
| ---------- | ----------------------- |
| Domain     | ukksmt4.p1erlangga.test |
| IP / Port  | 192.168.9.50:2008       |

> Pastikan domain lokal sudah diarahkan melalui file hosts atau menggunakan Laravel Herd/Valet.

---

## Update Setelah `git pull`

```bash
composer install
php artisan migrate
php artisan filament:upgrade
php artisan optimize:clear
```

---

## Evaluasi Pengembangan

### Fitur yang Sudah Berjalan

* Login & Logout
* Manage Users
* Login sesuai role
* CRUD Kategori
* CRUD Alat
* CRUD Peminjaman
* CRUD Pengembalian
* Denda & Pelanggaran
* Activity Log
* Cetak Laporan

### Rencana Pengembangan

* Testing seluruh fitur
* Optimasi tampilan aplikasi

---

## Developer

| Nama                          | Peran     | Sekolah                   |
| ----------------------------- | --------- | ------------------------- |
| Erlangga Trisna Yudha Perdana | Siswa RPL | SMK MVP ARS Internasional |

---

## License

Project ini dibuat untuk kebutuhan pembelajaran dan Uji Kompetensi Keahlian (UKK) Semester 4 Tahun Pelajaran 2025/2026.

---

<div align="center">

Dikembangkan untuk keperluan Uji Kompetensi Keahlian Semester 4 (UKKSMT4) Tahun Pelajaran 2025/2026.

</div>