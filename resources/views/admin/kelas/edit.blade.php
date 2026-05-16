@extends('layouts.app')
@section('title', 'Edit Kelas')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><i class="fas fa-edit text-warning mr-2"></i>Edit Kelas</h1>
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="card shadow" style="max-width:600px;">
    <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
        <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-school mr-2"></i>Edit Data Kelas — {{ $kelas->nama }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="small font-weight-bold text-gray-700">Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $kelas->nama) }}" placeholder="Contoh: X RPL 1" required>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="small font-weight-bold text-gray-700">Tingkat <span class="text-danger">*</span></label>
                        <select name="tingkat" class="form-control @error('tingkat') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            <option value="X"   {{ old('tingkat', $kelas->tingkat) === 'X'   ? 'selected' : '' }}>X</option>
                            <option value="XI"  {{ old('tingkat', $kelas->tingkat) === 'XI'  ? 'selected' : '' }}>XI</option>
                            <option value="XII" {{ old('tingkat', $kelas->tingkat) === 'XII' ? 'selected' : '' }}>XII</option>
                        </select>
                        @error('tingkat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="small font-weight-bold text-gray-700">Jurusan <span class="text-danger">*</span></label>
                        <input type="text" name="jurusan" class="form-control @error('jurusan') is-invalid @enderror"
                               value="{{ old('jurusan', $kelas->jurusan) }}" placeholder="RPL, TKJ, AKL, dll" required>
                        @error('jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="small font-weight-bold text-gray-700">Kapasitas</label>
                <input type="number" name="kapasitas" class="form-control @error('kapasitas') is-invalid @enderror"
                       value="{{ old('kapasitas', $kelas->kapasitas) }}" min="1" max="50">
                @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-warning btn-block text-white">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
