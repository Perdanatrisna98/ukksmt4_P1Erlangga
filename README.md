<div align="center">

# Aplikasi Peminjaman Alat

Sistem manajemen peminjaman alat berbasis web dibangun menggunakan **Laravel 12**, **Filament v5**, dan **MySQL 8.4.3**.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3.30-777BB4?style=flat-square&logo=php&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-v5-FDAE4B?style=flat-square&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.4.3-4479A1?style=flat-square&logo=mysql&logoColor=white)

</div>

---

## Tentang Proyek

Aplikasi ini dibuat untuk memudahkan proses peminjaman alat secara digital, mencakup pengajuan, persetujuan, pemantauan, hingga pengembalian dengan perhitungan denda otomatis. 

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
| Auth & Permission | Filament Shield |

---

## Akun Untuk Login

Dibawah ini adalah akun yang sudah saya sediakan didalam server. Jika Anda ingin mengakses melalui laptop pribadi, silahkan silahkan clone project ini lalu ikuti cara dibawah.

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@app.co | 123456 |
| Petugas | petugas@app.co | 123456 |
| Peminjam | peminjam@app.co | 123456 |



---

## Cara Untuk Mengakses Melalui Lokal(Komputer Masing-Masing)

```
git clone https://github.com/Perdanatrisna98/ukksmt4_P1Erlangga
composer install
php artisan filament:upgrade
php artisan make:filament-user
#setelah make:filament-user, ikuti saja petunjuknya
```

## Fitur

**Semua Role**
- Login dan Logout

**Admin**
- CRUD User, Alat, Kategori
- CRUD Data Peminjaman & Pengembalian
- Log Aktivitas

**Petugas**
- Menyetujui Peminjaman
- Memantau Pengembalian
- Cetak Laporan

**Peminjam**
- Melihat Daftar Alat
- Mengajukan Peminjaman
- Mengembalikan Alat

---

## Progress Pengerjaan

| Tanggal | Aktivitas |
|---------|-----------|
| 14 Apr 2026 | Inisialisasi proyek, konfigurasi environment dan database |
| 28 Apr 2026 | Membuat Fitur Login, Logout, Manage Users |
| 29 Apr 2026 | Melanjutkan login sesuai role |
| 30 Apr 2026 | Membuat CRUD Kategori dan mengubah sedikit tampilan |


### Jalankan Server

Jika anda ingin akses web saya silahkan ketik dibrowser pc server atau menggunakan jaringan lab 2 seperti tabel dibawah ini.

| Keterangan | URL |
|-------|-----|
| Domain | ukksmt4.p1erlangga.test|
| IP/Port | 192.168.9.50:2008 |

---

## Update Setelah `git pull`

```bash
composer install
php artisan migrate
php artisan filament:upgrade
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---


## Evaluasi 

Evaluasi saya dalam setiap push, ketika saya mengerjakan.

**Fitur yang berjalan**
- Login & Logout
- Crud Users / Manage Users
- Login sesuai role
- Crud Kategori

**Akan di Buat**
- tidak ada

**Rencana pengembangan**
- Crud Alat


---

## Developer

| Nama | Peran | Sekolah |
|------|-------|---------|
| Erlangga Trisna Yudha Perdana | Siswa RPL | SMK MVP ARS Internasional |

---

<div align="center">
Dikembangkan untuk keperluan Uji Kompetensi Keahlian Semester 4 (UKKSMT4) Tahun Pelajaran 2025/2026.  
</div>