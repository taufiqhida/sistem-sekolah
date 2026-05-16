<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount('siswaKelas')->orderBy('tingkat')->orderBy('nama')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:50|unique:kelas,nama',
            'tingkat'  => 'required|in:X,XI,XII',
            'jurusan'  => 'required|string|max:50',
            'kapasitas'=> 'required|integer|min:1|max:50',
        ]);

        Kelas::create($request->all());
        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas {$request->nama} berhasil ditambahkan.");
    }

    public function edit(Kelas $kela)
    {
        return view('admin.kelas.edit', ['kelas' => $kela]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama'    => ['required', Rule::unique('kelas', 'nama')->ignore($kela->id)],
            'tingkat' => 'required|in:X,XI,XII',
            'jurusan' => 'required|string|max:50',
        ]);

        $kela->update($request->all());
        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas berhasil diperbarui.");
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas berhasil dihapus.");
    }
}
