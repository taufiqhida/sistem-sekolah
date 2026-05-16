<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan.');
        }

        $semesterAktif = Semester::getAktif();

        // Rekap absensi semester aktif
        $rekap = $siswa->rekapAbsensi($semesterAktif?->id);
        $totalAbsensi = array_sum($rekap);
        $persentaseHadir = $totalAbsensi > 0 ? round(($rekap['hadir'] / $totalAbsensi) * 100, 1) : 0;

        // Rata-rata nilai
        $nilais = Nilai::where('siswa_id', $siswa->id)
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->whereNotNull('nilai_akhir')
            ->pluck('nilai_akhir');
        $rataRataNilai = $nilais->isNotEmpty() ? round($nilais->avg(), 1) : 0;

        // Kelas aktif siswa
        $siswaKelas = $siswa->siswaKelas()
            ->with(['kelas', 'semester.tahunAjaran'])
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->first();

        // Notifikasi belum dibaca
        $notifikasiBelumDibaca = Auth::user()->notifikasisBelumDibaca()->latest()->take(5)->get();

        return view('siswa.dashboard', compact(
            'siswa', 'rekap', 'persentaseHadir', 'rataRataNilai',
            'siswaKelas', 'notifikasiBelumDibaca', 'semesterAktif'
        ));
    }
}
