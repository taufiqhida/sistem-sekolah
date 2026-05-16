# Sistem Informasi Akademik & Absensi — SMK At Kausar

Aplikasi web berbasis **Laravel 11** + **MySQL** untuk mengelola data akademik, absensi, dan nilai raport siswa SMK At Kausar. Sistem ini mendukung multi-role (Admin, Guru, Siswa) dengan dashboard, laporan PDF/Excel, dan notifikasi.

---

## Konfigurasi yang Dikonfirmasi ✅

| Setting | Pilihan |
|---|---|
| Starter Kit | **Jetstream + Livewire** |
| Template Admin | **SB Admin 2** (Bootstrap 4) |
| Database | **db_smk_atkausar** |
| Bahasa | **Bahasa Indonesia** |
| URL | **http://localhost/raffi** |

---

## Proposed Changes

### Phase 1 — Laravel Project Setup

#### [NEW] Laravel 11 Project
- `composer create-project laravel/laravel .` di `c:\laragon\www\raffi`
- Install packages:
  - `laravel/breeze` — auth scaffolding
  - `spatie/laravel-permission` — multi-role RBAC
  - `barryvdh/laravel-dompdf` — cetak PDF raport
  - `maatwebsite/laravel-excel` — export Excel absensi
  - `intervention/image` — resize foto profil

---

### Phase 2 — Database & Migrations

#### Tabel Utama

| Tabel | Deskripsi |
|---|---|
| `users` | Auth (semua role) |
| `roles` | Admin / Guru / Siswa |
| `tahun_ajarans` | 2025/2026 dst |
| `semesters` | Ganjil / Genap per tahun ajaran |
| `kelas` | X RPL 1, XI RPL 1, dst |
| `mata_pelajarans` | Matematika, B.Indonesia, dst |
| `siswas` | Detail profil siswa |
| `gurus` | Detail profil guru |
| `guru_kelas_mapel` | Pivot: Guru ↔ Kelas ↔ MapPel |
| `siswa_kelas` | Pivot: Siswa ↔ Kelas per semester |
| `absensis` | Rekap per siswa per tanggal |
| `nilais` | Nilai per siswa per mapel per semester |
| `notifikasis` | Notifikasi sistem |

#### Relasi Kunci
```
Guru  ──── guru_kelas_mapel ──── Kelas
                 │
             MataPelajaran

Siswa ──── siswa_kelas ──── Kelas
             │
           Absensi / Nilai
```

---

### Phase 3 — Authentication & Authorization

#### [MODIFY] `routes/web.php`
- Route group per role menggunakan middleware `role:admin|guru|siswa`

#### [NEW] `app/Http/Middleware/RoleMiddleware.php`
- Redirect berdasarkan role setelah login

#### Spatie Permission Roles
- `admin` — akses penuh
- `guru` — absensi + nilai
- `siswa` — view-only

---

### Phase 4 — Admin Module

#### Controllers
- `AdminDashboardController` — statistik total siswa, guru, kelas, % kehadiran
- `SiswaController` — CRUD siswa + upload foto
- `GuruController` — CRUD guru + upload foto
- `KelasController` — CRUD kelas
- `MataPelajaranController` — CRUD mapel
- `SemesterController` — CRUD semester
- `TahunAjaranController` — CRUD tahun ajaran
- `AssignGuruController` — assign guru ke kelas+mapel
- `RekapAbsensiController` — view rekap kehadiran seluruh kelas

---

### Phase 5 — Guru Module

#### Controllers
- `GuruDashboardController` — statistik absensi hari ini, grafik kehadiran
- `AbsensiController` — input absensi per kelas per tanggal
- `NilaiController` — CRUD nilai siswa per mapel
- `ExportAbsensiController` — export Excel via Laravel Excel

---

### Phase 6 — Siswa Module

#### Controllers
- `SiswaDashboardController` — rata-rata nilai, rekap hadir/izin/sakit/alfa
- `NilaiSiswaController` — view nilai raport
- `AbsensiSiswaController` — view rekap absensi
- `ProfilSiswaController` — view + edit profil + foto

---

### Phase 7 — Fitur Tambahan

#### Export & Cetak
- `CetakRaportController` — generate PDF raport via DomPDF
- `ExportAbsensiController` — export Excel via Maatwebsite

#### Notifikasi
- Model `Notifikasi` dengan polymorphic relation
- Badge counter di navbar
- Toast alert (SweetAlert2)

#### Search & Filter
- Middleware query scope untuk filter kelas, semester, tahun ajaran
- Livewire atau AJAX untuk search real-time (opsional)

---

### Phase 8 — Views (Blade + AdminLTE)

#### Layout
- `layouts/admin.blade.php`
- `layouts/guru.blade.php`
- `layouts/siswa.blade.php`

#### Views per Role
- Dashboard masing-masing role dengan chart (Chart.js)
- Form CRUD dengan validasi
- Tabel dengan pagination
- Print preview PDF

---

## Tech Stack Final

| Layer | Teknologi |
|---|---|
| Backend | PHP 8.2, Laravel 11 |
| Frontend | Blade, Bootstrap 5 / AdminLTE 3 |
| Database | MySQL 8 |
| Auth | **Jetstream + Livewire** + Spatie Permission |
| PDF | barryvdh/laravel-dompdf |
| Excel | maatwebsite/laravel-excel |
| Charts | Chart.js (CDN) |
| Notifikasi | SweetAlert2 (CDN) |
| Foto Profil | Intervention Image v3 |

---

## Verification Plan

### Automated
- `php artisan test` — unit + feature tests untuk auth & permission
- `php artisan migrate:fresh --seed` — pastikan semua migration & seeder berjalan

### Manual Verification
1. Login sebagai Admin → cek semua CRUD berjalan
2. Login sebagai Guru → input absensi, nilai, export Excel
3. Login sebagai Siswa → cek tampilan nilai & absensi
4. Cetak raport PDF → pastikan format benar
5. Upload foto profil → pastikan tersimpan di storage

---

## Urutan Eksekusi

```
1. Buat project Laravel
2. Install semua packages
3. Setup .env + database
4. Buat migrations + seeders
5. Setup auth (Breeze) + Spatie Permission
6. Buat models + relasi
7. Buat controllers per module
8. Buat views + layout AdminLTE
9. Implementasi fitur export PDF/Excel
10. Implementasi notifikasi
11. Testing & polishing
```

> [!WARNING]
> Project ini besar dan akan dibuat secara bertahap. Setiap phase akan diverifikasi sebelum lanjut ke phase berikutnya.
