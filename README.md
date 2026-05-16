# 🎓 Sistem Informasi Akademik & Absensi Siswa
### SMK At Kausar

Aplikasi web berbasis **Laravel 11** untuk mengelola data akademik sekolah: siswa, guru, absensi, dan nilai raport secara digital dengan sistem multi-role (Admin, Guru, Siswa).

---

## 📋 Daftar Isi

- [Fitur](#-fitur)
- [Teknologi](#-teknologi)
- [Instalasi Lokal (Laragon / XAMPP)](#-instalasi-lokal)
- [Instalasi di Hosting (cPanel)](#-instalasi-di-hosting-cpanel)
- [Akun Default](#-akun-default)
- [Troubleshooting](#-troubleshooting)

---

## ✨ Fitur

| Role | Fitur |
|------|-------|
| **Admin** | CRUD Siswa, Guru, Kelas, Mata Pelajaran, Tahun Ajaran, Assign Guru ke Kelas |
| **Guru** | Input Absensi harian, Input Nilai (Tugas/UTS/UAS), Rekap & Export Excel |
| **Siswa** | Dashboard kehadiran, Lihat nilai, Cetak raport PDF, Notifikasi |

---

## 🛠 Teknologi

- **Backend**: PHP 8.2+, Laravel 11, Jetstream (Livewire)
- **Frontend**: Blade, Bootstrap 4, SB Admin 2, Chart.js
- **Database**: MySQL 5.7+
- **Library**: Spatie Permission, DomPDF, Laravel Excel

---

## 💻 Instalasi Lokal

### Prasyarat
Pastikan sudah terinstal:
- [Laragon](https://laragon.org) (direkomendasikan) atau XAMPP
- PHP **8.2** atau lebih baru
- Composer
- Node.js & npm
- Git

---

### Langkah 1 — Clone Repository

```bash
cd C:/laragon/www
git clone https://github.com/taufiqhida/sistem-sekolah.git raffi
cd raffi
```

---

### Langkah 2 — Install Dependensi PHP

```bash
composer install
```

---

### Langkah 3 — Install Dependensi Node.js

```bash
npm install
npm run build
```

---

### Langkah 4 — Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Kemudian edit file `.env`:

```env
APP_NAME="Sistem Akademik SMK At Kausar"
APP_URL=http://localhost/raffi/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_smk_atkausar
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan Laragon**: username default adalah `root` tanpa password.  
> **Catatan XAMPP**: username default `root`, password kosong.

---

### Langkah 5 — Generate App Key

```bash
php artisan key:generate
```

---

### Langkah 6 — Buat Database

Buka **phpMyAdmin** (`http://localhost/phpmyadmin`) lalu buat database baru:

```sql
CREATE DATABASE db_smk_atkausar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Atau via terminal:

```bash
mysql -u root -e "CREATE DATABASE db_smk_atkausar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

### Langkah 7 — Migrasi & Seeder

```bash
php artisan migrate --seed
```

Perintah ini akan:
- Membuat semua tabel database (21 tabel)
- Mengisi data dummy: roles, 8 kelas, 10 mapel, 5 guru, 10 siswa

---

### Langkah 8 — Storage Link

```bash
php artisan storage:link
```

---

### Langkah 9 — Akses Aplikasi

Buka browser dan akses:

```
http://localhost/raffi/public
```

> **Tip Laragon**: Klik kanan icon Laragon → **Quick App** → pilih `raffi` untuk akses langsung.

---

## 🌐 Instalasi di Hosting (cPanel)

### Prasyarat Hosting
- PHP **8.2+** (aktifkan di cPanel → **Select PHP Version**)
- MySQL database
- Akses SSH atau File Manager cPanel
- Composer tersedia (atau upload manual)

---

### Langkah 1 — Upload File

**Opsi A — Via Git (SSH):**
```bash
cd ~/public_html
git clone https://github.com/taufiqhida/sistem-sekolah.git akademik
```

**Opsi B — Via File Manager:**
1. Download ZIP dari GitHub: `Code → Download ZIP`
2. Upload ke `public_html/akademik/` via File Manager cPanel
3. Extract ZIP

---

### Langkah 2 — Pindahkan Folder `public`

Agar aplikasi bisa diakses dari root domain/subdomain, pindahkan isi folder `public/` ke `public_html/`:

```bash
# Via SSH:
cp -r ~/public_html/akademik/public/* ~/public_html/
```

Kemudian edit file `~/public_html/index.php`, ubah path:

```php
// Sebelum:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Sesudah (sesuaikan dengan nama folder):
require __DIR__.'/../akademik/vendor/autoload.php';
$app = require_once __DIR__.'/../akademik/bootstrap/app.php';
```

> **Untuk subdomain** (misal `akademik.namadomain.com`): arahkan Document Root subdomain ke `public_html/akademik/public/` via **Subdomains** di cPanel.

---

### Langkah 3 — Install Composer (Via SSH)

```bash
cd ~/public_html/akademik
composer install --optimize-autoloader --no-dev
```

Jika Composer tidak tersedia, upload folder `vendor/` dari komputer lokal secara manual.

---

### Langkah 4 — Buat Database di cPanel

1. Buka **cPanel → MySQL Databases**
2. Buat database baru: `namauser_smkatkausar`
3. Buat user database dan set password
4. Tambahkan user ke database dengan **All Privileges**
5. Catat: nama database, username, password

---

### Langkah 5 — Konfigurasi `.env`

Upload file `.env` (buat dari `.env.example`) dengan isi berikut:

```env
APP_NAME="SMK At Kausar"
APP_ENV=production
APP_KEY=                          # akan diisi via artisan key:generate
APP_DEBUG=false
APP_URL=https://namadomain.com    # sesuaikan URL domain Anda

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=namauser_smkatkausar  # sesuaikan
DB_USERNAME=namauser_dbuser       # sesuaikan
DB_PASSWORD=passwordDatabase      # sesuaikan

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

### Langkah 6 — Generate Key & Migrasi (Via SSH)

```bash
cd ~/public_html/akademik

php artisan key:generate
php artisan migrate --seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### Langkah 7 — Permission Folder

```bash
chmod -R 755 ~/public_html/akademik/storage
chmod -R 755 ~/public_html/akademik/bootstrap/cache
```

---

### Langkah 8 — Konfigurasi `.htaccess`

Pastikan file `.htaccess` di `public/` sudah ada (sudah include dalam repo). Jika ada masalah redirect, tambahkan di awal `.htaccess`:

```apache
Options -MultiViews
RewriteEngine On
RewriteBase /
```

---

### Langkah 9 — Akses Aplikasi

```
https://namadomain.com
# atau jika subfolder:
https://namadomain.com/akademik/public
```

---

## 🔐 Akun Default

Setelah menjalankan seeder, gunakan akun berikut:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@smkatkausar.sch.id | password |
| **Guru** | budi@smkatkausar.sch.id | password |
| **Siswa** | siswa1@smkatkausar.sch.id | password |

> ⚠️ **Penting**: Ganti password segera setelah instalasi di production!

Untuk mengganti password via artisan tinker:
```bash
php artisan tinker
>>> \App\Models\User::where('email','admin@smkatkausar.sch.id')->update(['password' => bcrypt('passwordBaru123')]);
```

---

## 🔧 Troubleshooting

### ❌ Error: `Class "Spatie\Permission\..." not found`
```bash
composer dump-autoload
php artisan optimize:clear
```

---

### ❌ Error: Trait collision `HasRoles::teams`
Sudah di-handle di `app/Models/User.php` dengan alias. Pastikan file tidak ter-overwrite.

---

### ❌ Error: `500 | Server Error` setelah upload
```bash
php artisan config:clear
php artisan cache:clear
chmod -R 777 storage bootstrap/cache
```

---

### ❌ Gambar / Storage tidak muncul
```bash
php artisan storage:link
```
Jika gagal di shared hosting, buat symlink manual:
```bash
ln -s ~/public_html/akademik/storage/app/public ~/public_html/akademik/public/storage
```

---

### ❌ Error: `The stream or file ... could not be opened`
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

---

### ❌ Login redirect tidak sesuai role
Pastikan seeder sudah dijalankan dan role sudah terassign:
```bash
php artisan db:seed --force
```

---

### ❌ `php artisan` tidak tersedia di hosting
Gunakan path PHP lengkap:
```bash
/usr/local/bin/php artisan migrate --seed --force
```

---

## 📁 Struktur Direktori Penting

```
raffi/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Controller Admin
│   │   ├── Guru/           # Controller Guru
│   │   └── Siswa/          # Controller Siswa
│   ├── Models/             # Eloquent Models
│   └── Exports/            # Excel Export classes
├── database/
│   ├── migrations/         # 21 file migrasi
│   └── seeders/            # DatabaseSeeder
├── resources/views/
│   ├── admin/              # View halaman Admin
│   ├── guru/               # View halaman Guru
│   ├── siswa/              # View halaman Siswa
│   └── layouts/            # Layout utama (SB Admin 2)
├── routes/
│   └── web.php             # Semua route aplikasi
└── .env.example            # Template konfigurasi
```

---

## 📞 Informasi Proyek

- **Framework**: Laravel 11
- **Template**: SB Admin 2 (Bootstrap 4)
- **Repository**: https://github.com/taufiqhida/sistem-sekolah
- **Sekolah**: SMK At Kausar
