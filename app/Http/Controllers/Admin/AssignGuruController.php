<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuruKelasMapel;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Semester;
use Illuminate\Http\Request;

class AssignGuruController extends Controller
{
    public function index(Request $request)
    {
        $semesterId = $request->get('semester_id', optional(Semester::getAktif())->id);

        $assignments = GuruKelasMapel::with(['guru', 'kelas', 'mataPelajaran', 'semester'])
            ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
            ->orderBy('kelas_id')
            ->paginate(20);

        $gurus    = Guru::where('status', 'Aktif')->orderBy('nama_lengkap')->get();
        $kelas    = Kelas::orderBy('nama')->get();
        $mapels   = MataPelajaran::orderBy('nama')->get();
        $semesters= Semester::with('tahunAjaran')->orderByDesc('is_aktif')->get();

        return view('admin.assign-guru.index', compact(
            'assignments', 'gurus', 'kelas', 'mapels', 'semesters', 'semesterId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id'           => 'required|exists:gurus,id',
            'kelas_id'          => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'semester_id'       => 'required|exists:semesters,id',
        ]);

        $exists = GuruKelasMapel::where([
            'guru_id'           => $request->guru_id,
            'kelas_id'          => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'semester_id'       => $request->semester_id,
        ])->exists();

        if ($exists) {
            return back()->with('error', 'Assignment sudah ada!');
        }

        GuruKelasMapel::create($request->all());
        return back()->with('success', 'Guru berhasil di-assign ke kelas dan mata pelajaran.');
    }

    public function destroy(GuruKelasMapel $guruKelasMapel)
    {
        $guruKelasMapel->delete();
        return back()->with('success', 'Assignment berhasil dihapus.');
    }
}
