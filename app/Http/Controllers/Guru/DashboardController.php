<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\GuruKelasMapel;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;
        if (!$guru) {
            abort(403, 'Data guru tidak ditemukan.');
        }

        $semesterAktif = Semester::getAktif();

        // Assignments guru di semester aktif
        $assignments = GuruKelasMapel::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->get();

        // Absensi hari ini
        $absensiHariIni = Absensi::where('guru_id', $guru->id)
            ->where('tanggal', now()->toDateString())
            ->count();

        // Total siswa yang diajar
        $kelasIds = $assignments->pluck('kelas_id')->unique();
        $totalSiswa = \App\Models\SiswaKelas::whereIn('kelas_id', $kelasIds)
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->count();

        // Chart absensi 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $label   = now()->subDays($i)->isoFormat('dd, D/M');
            $hadir   = Absensi::where('guru_id', $guru->id)->where('tanggal', $tanggal)->where('status', 'Hadir')->count();
            $alfa    = Absensi::where('guru_id', $guru->id)->where('tanggal', $tanggal)->where('status', 'Alfa')->count();
            $chartData[] = compact('label', 'hadir', 'alfa');
        }

        return view('guru.dashboard', compact(
            'guru', 'assignments', 'absensiHariIni',
            'totalSiswa', 'chartData', 'semesterAktif'
        ));
    }
}
