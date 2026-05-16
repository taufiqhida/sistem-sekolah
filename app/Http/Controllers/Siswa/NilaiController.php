<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) abort(403);

        $semesterId = $request->get('semester_id', optional(Semester::getAktif())->id);

        $nilais = Nilai::with(['mataPelajaran', 'guru', 'semester.tahunAjaran'])
            ->where('siswa_id', $siswa->id)
            ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
            ->orderBy('mata_pelajaran_id')
            ->get();

        $semesters = Semester::with('tahunAjaran')->orderByDesc('is_aktif')->get();
        $rataRata  = $nilais->whereNotNull('nilai_akhir')->avg('nilai_akhir');

        return view('siswa.nilai.index', compact('nilais', 'semesters', 'semesterId', 'siswa', 'rataRata'));
    }

    public function cetakRaport(Request $request)
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) abort(403);

        $semesterId = $request->get('semester_id', optional(Semester::getAktif())->id);
        $semester   = Semester::with('tahunAjaran')->find($semesterId);

        $nilais = Nilai::with(['mataPelajaran', 'guru'])
            ->where('siswa_id', $siswa->id)
            ->where('semester_id', $semesterId)
            ->get();

        $rekap = $siswa->rekapAbsensi($semesterId);
        $siswaKelas = $siswa->siswaKelas()->with('kelas')->where('semester_id', $semesterId)->first();

        $pdf = Pdf::loadView('siswa.nilai.raport-pdf', compact(
            'siswa', 'nilais', 'semester', 'rekap', 'siswaKelas'
        ))->setPaper('A4', 'portrait');

        return $pdf->download("raport-{$siswa->nisn}-{$semester?->nama}.pdf");
    }
}
