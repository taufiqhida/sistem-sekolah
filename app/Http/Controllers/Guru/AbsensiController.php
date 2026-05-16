<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\GuruKelasMapel;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;

class AbsensiController extends Controller
{
    private function getGuru()
    {
        $guru = Auth::user()->guru;
        if (!$guru) abort(403, 'Data guru tidak ditemukan.');
        return $guru;
    }

    public function index(Request $request)
    {
        $guru = $this->getGuru();
        $semesterAktif = Semester::getAktif();

        $assignments = GuruKelasMapel::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->get();

        return view('guru.absensi.index', compact('assignments', 'semesterAktif'));
    }

    public function input(Request $request, int $kelasId, int $mapelId)
    {
        $guru = $this->getGuru();
        $semesterAktif = Semester::getAktif();
        $tanggal = $request->get('tanggal', now()->toDateString());

        // Verifikasi assignment
        $assignment = GuruKelasMapel::where([
            'guru_id'           => $guru->id,
            'kelas_id'          => $kelasId,
            'mata_pelajaran_id' => $mapelId,
        ])->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
          ->firstOrFail();

        // Siswa di kelas ini
        $siswaKelas = SiswaKelas::with('siswa')
            ->where('kelas_id', $kelasId)
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->get();

        // Absensi yang sudah ada
        $existingAbsensi = Absensi::where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mapelId)
            ->where('tanggal', $tanggal)
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->pluck('status', 'siswa_id');

        return view('guru.absensi.input', compact(
            'assignment', 'siswaKelas', 'existingAbsensi', 'tanggal', 'semesterAktif'
        ));
    }

    public function store(Request $request)
    {
        $guru = $this->getGuru();
        $semesterAktif = Semester::getAktif();

        $request->validate([
            'kelas_id'          => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'tanggal'           => 'required|date',
            'absensi'           => 'required|array',
            'absensi.*.status'  => 'required|in:Hadir,Izin,Sakit,Alfa',
        ]);

        foreach ($request->absensi as $siswaId => $data) {
            Absensi::updateOrCreate(
                [
                    'siswa_id'          => $siswaId,
                    'mata_pelajaran_id' => $request->mata_pelajaran_id,
                    'tanggal'           => $request->tanggal,
                ],
                [
                    'kelas_id'    => $request->kelas_id,
                    'guru_id'     => $guru->id,
                    'semester_id' => $semesterAktif?->id,
                    'status'      => $data['status'],
                    'keterangan'  => $data['keterangan'] ?? null,
                ]
            );
        }

        return redirect()->route('guru.absensi.index')
            ->with('success', "Absensi tanggal {$request->tanggal} berhasil disimpan.");
    }

    public function rekap(Request $request)
    {
        $guru = $this->getGuru();
        $semesterId = $request->get('semester_id', optional(Semester::getAktif())->id);
        $kelasId    = $request->get('kelas_id');
        $mapelId    = $request->get('mata_pelajaran_id');

        $assignments = GuruKelasMapel::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
            ->get();

        $absensiQuery = Absensi::with(['siswa', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
            ->when($kelasId,   fn($q) => $q->where('kelas_id', $kelasId))
            ->when($mapelId,   fn($q) => $q->where('mata_pelajaran_id', $mapelId));

        $absensiList = $absensiQuery->orderByDesc('tanggal')->paginate(30)->withQueryString();

        $semesters = \App\Models\Semester::with('tahunAjaran')->orderByDesc('is_aktif')->get();
        $kelasList = \App\Models\Kelas::orderBy('nama')->get();

        return view('guru.absensi.rekap', compact(
            'absensiList', 'assignments', 'semesters', 'kelasList', 'semesterId', 'kelasId', 'mapelId'
        ));
    }

    public function exportExcel(Request $request)
    {
        $guru       = $this->getGuru();
        $semesterId = $request->get('semester_id', optional(Semester::getAktif())->id);
        $kelasId    = $request->get('kelas_id');
        $mapelId    = $request->get('mata_pelajaran_id');

        return Excel::download(
            new AbsensiExport($guru->id, $semesterId, $kelasId, $mapelId),
            'absensi-' . now()->format('Ymd') . '.xlsx'
        );
    }
}
