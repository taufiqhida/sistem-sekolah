@extends('layouts.app')
@section('title', 'Profil Saya')

@section('sidebar-menu')
@auth
    @if(auth()->user()->hasRole('admin'))
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Manajemen Data</div>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
    @elseif(auth()->user()->hasRole('guru'))
        <li class="nav-item"><a class="nav-link" href="{{ route('guru.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Kegiatan</div>
        <li class="nav-item"><a class="nav-link" href="{{ route('guru.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Input Absensi</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('guru.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Input Nilai</span></a></li>
    @elseif(auth()->user()->hasRole('siswa'))
        <li class="nav-item"><a class="nav-link" href="{{ route('siswa.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Akademik Saya</div>
        <li class="nav-item"><a class="nav-link" href="{{ route('siswa.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Nilai Raport</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('siswa.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Rekap Absensi</span></a></li>
    @endif
    <hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Akun</div>
    <li class="nav-item active"><a class="nav-link" href="{{ route('profile.show') }}"><i class="fas fa-fw fa-user-circle"></i><span>Profil Saya</span></a></li>
@endauth
@endsection

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-user-circle text-primary mr-2"></i>Profil Saya</h1>
    <p class="text-gray-600 mb-0">Kelola informasi akun Anda</p>
</div>

<div class="row">
    {{-- Info Akun --}}
    <div class="col-lg-4 mb-4">
        <div class="card shadow text-center">
            <div class="card-body py-4">
                <div style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,#4e73df,#224abe);display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                    <i class="fas fa-user fa-2x text-white"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted small mb-2">{{ auth()->user()->email }}</p>
                @auth
                    @if(auth()->user()->hasRole('admin')) <span class="badge badge-primary badge-pill px-3">Administrator</span>
                    @elseif(auth()->user()->hasRole('guru')) <span class="badge badge-success badge-pill px-3">Guru</span>
                    @elseif(auth()->user()->hasRole('siswa')) <span class="badge badge-info badge-pill px-3">Siswa</span>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        {{-- Update Nama & Email --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-edit mr-2"></i>Update Informasi Profil</h6>
            </div>
            <div class="card-body">
                @if(session('status') === 'profile-information-updated')
                    <div class="alert alert-success py-2"><i class="fas fa-check-circle mr-2"></i>Profil berhasil diperbarui.</div>
                @endif
                <form action="{{ route('user-profile-information.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="small font-weight-bold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Simpan Profil</button>
                </form>
            </div>
        </div>

        {{-- Update Password --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-lock mr-2"></i>Ubah Password</h6>
            </div>
            <div class="card-body">
                @if(session('status') === 'password-updated')
                    <div class="alert alert-success py-2"><i class="fas fa-check-circle mr-2"></i>Password berhasil diperbarui.</div>
                @endif
                <form action="{{ route('user-password.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="small font-weight-bold">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold">Password Baru <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-key mr-2"></i>Ubah Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
