<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\GuruKelasMapel;
use App\Models\SiswaKelas;
use App\Models\Semester;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
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

        return view('guru.nilai.index', compact('assignments', 'semesterAktif'));
    }

    public function input(Request $request, int $kelasId, int $mapelId)
    {
        $guru = $this->getGuru();
        $semesterAktif = Semester::getAktif();

        $assignment = GuruKelasMapel::with(['kelas', 'mataPelajaran'])
            ->where([
                'guru_id'           => $guru->id,
                'kelas_id'          => $kelasId,
                'mata_pelajaran_id' => $mapelId,
            ])
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->firstOrFail();

        $siswaKelas = SiswaKelas::with('siswa')
            ->where('kelas_id', $kelasId)
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->get();

        $existingNilai = Nilai::where('mata_pelajaran_id', $mapelId)
            ->when($semesterAktif, fn($q) => $q->where('semester_id', $semesterAktif->id))
            ->whereIn('siswa_id', $siswaKelas->pluck('siswa_id'))
            ->get()
            ->keyBy('siswa_id');

        return view('guru.nilai.input', compact(
            'assignment', 'siswaKelas', 'existingNilai', 'semesterAktif'
        ));
    }

    public function store(Request $request)
    {
        $guru = $this->getGuru();
        $semesterAktif = Semester::getAktif();

        $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'nilais'            => 'required|array',
        ]);

        foreach ($request->nilais as $siswaId => $data) {
            $nilai = Nilai::updateOrCreate(
                [
                    'siswa_id'          => $siswaId,
                    'mata_pelajaran_id' => $request->mata_pelajaran_id,
                    'semester_id'       => $semesterAktif?->id,
                ],
                [
                    'guru_id'      => $guru->id,
                    'nilai_tugas'  => $data['nilai_tugas'] ?? null,
                    'nilai_uts'    => $data['nilai_uts'] ?? null,
                    'nilai_uas'    => $data['nilai_uas'] ?? null,
                    'catatan'      => $data['catatan'] ?? null,
                ]
            );

            // Kirim notifikasi ke siswa
            $siswa = \App\Models\Siswa::find($siswaId);
            if ($siswa && $siswa->user_id) {
                Notifikasi::kirim(
                    $siswa->user_id,
                    'Nilai Baru Tersedia',
                    "Nilai {$nilai->mataPelajaran->nama} semester {$semesterAktif?->nama} telah diinput oleh guru.",
                    'info',
                    route('siswa.nilai.index')
                );
            }
        }

        return redirect()->route('guru.nilai.index')
            ->with('success', 'Nilai berhasil disimpan dan notifikasi dikirim.');
    }
}
