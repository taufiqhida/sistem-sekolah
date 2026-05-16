<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjarans = TahunAjaran::with('semesters')->orderByDesc('id')->get();
        return view('admin.tahun-ajarans.index', compact('tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:tahun_ajarans,nama',
        ]);

        DB::transaction(function () use ($request) {
            if ($request->boolean('is_aktif')) {
                TahunAjaran::where('is_aktif', true)->update(['is_aktif' => false]);
            }
            $ta = TahunAjaran::create([
                'nama'     => $request->nama,
                'is_aktif' => $request->boolean('is_aktif'),
            ]);
            // Auto buat 2 semester
            Semester::create(['tahun_ajaran_id' => $ta->id, 'nama' => 'Ganjil', 'is_aktif' => $request->boolean('is_aktif')]);
            Semester::create(['tahun_ajaran_id' => $ta->id, 'nama' => 'Genap',  'is_aktif' => false]);
        });

        return redirect()->route('admin.tahun-ajarans.index')
            ->with('success', "Tahun ajaran berhasil ditambahkan.");
    }

    public function setAktif(TahunAjaran $tahunAjaran)
    {
        DB::transaction(function () use ($tahunAjaran) {
            TahunAjaran::query()->update(['is_aktif' => false]);
            Semester::query()->update(['is_aktif' => false]);
            $tahunAjaran->update(['is_aktif' => true]);
            $tahunAjaran->semesters()->where('nama', 'Ganjil')->update(['is_aktif' => true]);
        });

        return back()->with('success', "Tahun ajaran {$tahunAjaran->nama} diset aktif.");
    }

    public function setSemesterAktif(Semester $semester)
    {
        DB::transaction(function () use ($semester) {
            Semester::query()->update(['is_aktif' => false]);
            TahunAjaran::query()->update(['is_aktif' => false]);
            $semester->update(['is_aktif' => true]);
            $semester->tahunAjaran->update(['is_aktif' => true]);
        });

        return back()->with('success', "Semester {$semester->nama} diset aktif.");
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();
        return redirect()->route('admin.tahun-ajarans.index')
            ->with('success', "Tahun ajaran berhasil dihapus.");
    }
}
