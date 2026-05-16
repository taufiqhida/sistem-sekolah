<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Semester;

class DashboardController extends Controller
{
    public function index()
    {
        $semesterAktif = Semester::getAktif();

        $totalSiswa = Siswa::where('status', 'Aktif')->count();
        $totalGuru  = Guru::where('status', 'Aktif')->count();
        $totalKelas = Kelas::count();

        // Persentase kehadiran bulan ini
        $bulanIni = now()->format('Y-m');
        $totalAbsensi = Absensi::when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanIni])
            ->count();
        $totalHadir = Absensi::when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulanIni])
            ->where('status', 'Hadir')
            ->count();

        $persentaseHadir = $totalAbsensi > 0 ? round(($totalHadir / $totalAbsensi) * 100, 1) : 0;

        // Chart: absensi 7 hari terakhir
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            $label   = now()->subDays($i)->format('d/m');
            $hadir   = Absensi::where('tanggal', $tanggal)->where('status', 'Hadir')->count();
            $alfa    = Absensi::where('tanggal', $tanggal)->where('status', 'Alfa')->count();
            $chartData[] = compact('label', 'hadir', 'alfa');
        }

        // Rekap per kelas
        $rekapKelas = Kelas::withCount([
            'siswaKelas as jumlah_siswa' => fn($q) => $semesterAktif
                ? $q->where('semester_id', $semesterAktif->id)
                : $q,
        ])->get();

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalKelas',
            'persentaseHadir', 'chartData', 'rekapKelas', 'semesterAktif'
        ));
    }
}
