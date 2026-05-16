<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%")
                  ->orWhere('nip', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $gurus = $query->orderBy('nama_lengkap')->paginate(15)->withQueryString();
        return view('admin.gurus.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.gurus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'           => 'nullable|string|unique:gurus,nip',
            'nama_lengkap'  => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'foto'          => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-guru', 'public');
        }

        $user = User::create([
            'name'              => $request->nama_lengkap,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('guru');

        Guru::create([
            'user_id'       => $user->id,
            'nip'           => $request->nip,
            'nama_lengkap'  => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
            'foto'          => $fotoPath,
            'status'        => $request->status ?? 'Aktif',
        ]);

        return redirect()->route('admin.gurus.index')
            ->with('success', "Guru {$request->nama_lengkap} berhasil ditambahkan.");
    }

    public function show(Guru $guru)
    {
        $guru->load(['guruKelasMapels.kelas', 'guruKelasMapels.mataPelajaran', 'guruKelasMapels.semester']);
        return view('admin.gurus.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        return view('admin.gurus.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip'          => ['nullable', Rule::unique('gurus', 'nip')->ignore($guru->id)],
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin'=> 'required|in:L,P',
            'foto'         => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($guru->foto) Storage::disk('public')->delete($guru->foto);
            $guru->foto = $request->file('foto')->store('foto-guru', 'public');
        }

        $guru->update($request->except(['foto', '_token', '_method', 'email', 'password']));
        if ($request->hasFile('foto')) $guru->save();

        $guru->user->update(['name' => $request->nama_lengkap]);
        if ($request->filled('email')) {
            $guru->user->update(['email' => $request->email]);
        }
        if ($request->filled('password')) {
            $guru->user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.gurus.index')
            ->with('success', "Data guru berhasil diperbarui.");
    }

    public function destroy(Guru $guru)
    {
        if ($guru->foto) Storage::disk('public')->delete($guru->foto);
        $guru->user->delete();
        return redirect()->route('admin.gurus.index')
            ->with('success', "Guru berhasil dihapus.");
    }
}
