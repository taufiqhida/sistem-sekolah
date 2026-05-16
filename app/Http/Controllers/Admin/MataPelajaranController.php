<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapels = MataPelajaran::orderBy('nama')->paginate(15);
        return view('admin.mata-pelajarans.index', compact('mapels'));
    }

    public function create()
    {
        return view('admin.mata-pelajarans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:mata_pelajarans,kode',
            'nama' => 'required|string|max:100',
            'kkm'  => 'required|integer|min:0|max:100',
        ]);

        MataPelajaran::create($request->all());
        return redirect()->route('admin.mata-pelajarans.index')
            ->with('success', "Mata pelajaran berhasil ditambahkan.");
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('admin.mata-pelajarans.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $request->validate([
            'kode' => ['required', Rule::unique('mata_pelajarans', 'kode')->ignore($mataPelajaran->id)],
            'nama' => 'required|string|max:100',
            'kkm'  => 'required|integer|min:0|max:100',
        ]);

        $mataPelajaran->update($request->all());
        return redirect()->route('admin.mata-pelajarans.index')
            ->with('success', "Mata pelajaran berhasil diperbarui.");
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();
        return redirect()->route('admin.mata-pelajarans.index')
            ->with('success', "Mata pelajaran berhasil dihapus.");
    }
}
