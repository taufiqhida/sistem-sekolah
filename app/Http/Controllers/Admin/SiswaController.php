<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\SiswaKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'siswaKelas.kelas', 'siswaKelas.semester']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%")
                  ->orWhere('nisn', 'like', "%{$request->search}%")
                  ->orWhere('nis', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('kelas_id')) {
            $semesterAktif = Semester::getAktif();
            $query->whereHas('siswaKelas', fn($q) =>
                $q->where('kelas_id', $request->kelas_id)
                  ->when($semesterAktif, fn($q2) => $q2->where('semester_id', $semesterAktif->id))
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $siswas   = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();
        $kelasList = Kelas::orderBy('nama')->get();

        return view('admin.siswas.index', compact('siswas', 'kelasList'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $semesters = Semester::with('tahunAjaran')->orderByDesc('is_aktif')->get();
        return view('admin.siswas.create', compact('kelasList', 'semesters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn'          => 'required|string|unique:siswas,nisn',
            'nis'           => 'nullable|string|unique:siswas,nis',
            'nama_lengkap'  => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'kelas_id'      => 'required|exists:kelas,id',
            'semester_id'   => 'required|exists:semesters,id',
            'foto'          => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-siswa', 'public');
        }

        $user = User::create([
            'name'     => $request->nama_lengkap,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('siswa');

        $siswa = Siswa::create([
            'user_id'       => $user->id,
            'nisn'          => $request->nisn,
            'nis'           => $request->nis,
            'nama_lengkap'  => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir'  => $request->tempat_lahir,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
            'nama_ortu'     => $request->nama_ortu,
            'foto'          => $fotoPath,
            'status'        => $request->status ?? 'Aktif',
        ]);

        SiswaKelas::create([
            'siswa_id'    => $siswa->id,
            'kelas_id'    => $request->kelas_id,
            'semester_id' => $request->semester_id,
        ]);

        return redirect()->route('admin.siswas.index')
            ->with('success', "Siswa {$siswa->nama_lengkap} berhasil ditambahkan.");
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['user', 'siswaKelas.kelas', 'siswaKelas.semester.tahunAjaran',
                      'absensis.mataPelajaran', 'nilais.mataPelajaran']);
        $rekap = $siswa->rekapAbsensi();
        return view('admin.siswas.show', compact('siswa', 'rekap'));
    }

    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $semesters = Semester::with('tahunAjaran')->get();
        return view('admin.siswas.edit', compact('siswa', 'kelasList', 'semesters'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nisn'          => ['required', Rule::unique('siswas', 'nisn')->ignore($siswa->id)],
            'nis'           => ['nullable', Rule::unique('siswas', 'nis')->ignore($siswa->id)],
            'nama_lengkap'  => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'foto'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($siswa->foto) Storage::disk('public')->delete($siswa->foto);
            $siswa->foto = $request->file('foto')->store('foto-siswa', 'public');
        }

        $siswa->update($request->except(['foto', '_token', '_method', 'email', 'password']));
        if ($request->hasFile('foto')) $siswa->save();

        // Update nama di user
        $siswa->user->update(['name' => $request->nama_lengkap]);

        return redirect()->route('admin.siswas.index')
            ->with('success', "Data siswa berhasil diperbarui.");
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->foto) Storage::disk('public')->delete($siswa->foto);
        $siswa->user->delete();
        return redirect()->route('admin.siswas.index')
            ->with('success', "Siswa berhasil dihapus.");
    }
}
