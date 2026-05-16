<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Auth::user()->siswa;
        if (!$siswa) abort(403);

        $semesterId = $request->get('semester_id', optional(Semester::getAktif())->id);
        $mapelId    = $request->get('mata_pelajaran_id');

        $absensis = Absensi::with(['mataPelajaran', 'kelas'])
            ->where('siswa_id', $siswa->id)
            ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
            ->when($mapelId,    fn($q) => $q->where('mata_pelajaran_id', $mapelId))
            ->orderByDesc('tanggal')
            ->paginate(20)->withQueryString();

        $rekap = $siswa->rekapAbsensi($semesterId);
        $semesters = Semester::with('tahunAjaran')->orderByDesc('is_aktif')->get();

        $mapels = Absensi::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->select('mata_pelajaran_id')
            ->distinct()
            ->get()
            ->pluck('mataPelajaran');

        return view('siswa.absensi.index', compact(
            'absensis', 'rekap', 'semesters', 'mapels', 'semesterId', 'mapelId'
        ));
    }
}
