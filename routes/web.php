<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Guru;
use App\Http\Controllers\Siswa;
use App\Models\Notifikasi;

// ======================================================
// ROOT: redirect by role
// ======================================================
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    $user = auth()->user();
    if ($user->hasRole('admin'))  return redirect()->route('admin.dashboard');
    if ($user->hasRole('guru'))   return redirect()->route('guru.dashboard');
    if ($user->hasRole('siswa'))  return redirect()->route('siswa.dashboard');
    return redirect()->route('login');
})->name('home');

// ======================================================
// ADMIN ROUTES
// ======================================================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Siswa
    Route::resource('siswas', Admin\SiswaController::class);

    // Guru
    Route::resource('gurus', Admin\GuruController::class);

    // Kelas (Laravel pluralizes 'kelas' → 'kelas' unchanged)
    Route::resource('kelas', Admin\KelasController::class)->parameters(['kelas' => 'kela']);

    // Mata Pelajaran
    Route::resource('mata-pelajarans', Admin\MataPelajaranController::class);

    // Tahun Ajaran
    Route::get('/tahun-ajarans', [Admin\TahunAjaranController::class, 'index'])->name('tahun-ajarans.index');
    Route::post('/tahun-ajarans', [Admin\TahunAjaranController::class, 'store'])->name('tahun-ajarans.store');
    Route::post('/tahun-ajarans/{tahunAjaran}/aktif', [Admin\TahunAjaranController::class, 'setAktif'])->name('tahun-ajarans.aktif');
    Route::post('/semesters/{semester}/aktif', [Admin\TahunAjaranController::class, 'setSemesterAktif'])->name('semesters.aktif');
    Route::delete('/tahun-ajarans/{tahunAjaran}', [Admin\TahunAjaranController::class, 'destroy'])->name('tahun-ajarans.destroy');

    // Assign Guru
    Route::get('/assign-guru', [Admin\AssignGuruController::class, 'index'])->name('assign-guru.index');
    Route::post('/assign-guru', [Admin\AssignGuruController::class, 'store'])->name('assign-guru.store');
    Route::delete('/assign-guru/{guruKelasMapel}', [Admin\AssignGuruController::class, 'destroy'])->name('assign-guru.destroy');
});

// ======================================================
// GURU ROUTES
// ======================================================
Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'role:guru'])
    ->group(function () {

    Route::get('/dashboard', [Guru\DashboardController::class, 'index'])->name('dashboard');

    // Absensi
    Route::get('/absensi', [Guru\AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/input/{kelas}/{mapel}', [Guru\AbsensiController::class, 'input'])->name('absensi.input');
    Route::post('/absensi', [Guru\AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/rekap', [Guru\AbsensiController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/export', [Guru\AbsensiController::class, 'exportExcel'])->name('absensi.export');

    // Nilai
    Route::get('/nilai', [Guru\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/input/{kelas}/{mapel}', [Guru\NilaiController::class, 'input'])->name('nilai.input');
    Route::post('/nilai', [Guru\NilaiController::class, 'store'])->name('nilai.store');
});

// ======================================================
// SISWA ROUTES
// ======================================================
Route::prefix('siswa')
    ->name('siswa.')
    ->middleware(['auth', 'role:siswa'])
    ->group(function () {

    Route::get('/dashboard', [Siswa\DashboardController::class, 'index'])->name('dashboard');

    // Nilai & Raport
    Route::get('/nilai', [Siswa\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/raport', [Siswa\NilaiController::class, 'cetakRaport'])->name('nilai.raport');

    // Absensi
    Route::get('/absensi', [Siswa\AbsensiController::class, 'index'])->name('absensi.index');

    // Notifikasi
    Route::get('/notifikasi', function () {
        $notifikasis = auth()->user()->notifikasis()->latest()->paginate(20);
        return view('siswa.notifikasi.index', compact('notifikasis'));
    })->name('notifikasi.index');

    Route::post('/notifikasi/{notifikasi}/baca', function (Notifikasi $notifikasi) {
        $notifikasi->markAsRead();
        return back();
    })->name('notifikasi.baca');

    Route::post('/notifikasi/baca-semua', function () {
        auth()->user()->notifikasis()->whereNull('dibaca_at')->update(['dibaca_at' => now()]);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    })->name('notifikasi.baca-semua');
});
