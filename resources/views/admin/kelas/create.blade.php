@extends('layouts.app')
@section('title', 'Tambah Kelas')

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
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><i class="fas fa-plus-circle text-info mr-2"></i>Tambah Kelas Baru</h1>
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
</div>

<div class="card shadow" style="max-width:600px;">
    <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
        <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-school mr-2"></i>Data Kelas</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.kelas.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="small font-weight-bold text-gray-700">Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Contoh: X RPL 1" required>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="small font-weight-bold text-gray-700">Tingkat <span class="text-danger">*</span></label>
                        <select name="tingkat" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="X" {{ old('tingkat') === 'X' ? 'selected' : '' }}>X</option>
                            <option value="XI" {{ old('tingkat') === 'XI' ? 'selected' : '' }}>XI</option>
                            <option value="XII" {{ old('tingkat') === 'XII' ? 'selected' : '' }}>XII</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="small font-weight-bold text-gray-700">Jurusan <span class="text-danger">*</span></label>
                        <input type="text" name="jurusan" class="form-control" value="{{ old('jurusan') }}" placeholder="RPL, TKJ, AKL, dll" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="small font-weight-bold text-gray-700">Kapasitas</label>
                <input type="number" name="kapasitas" class="form-control" value="{{ old('kapasitas', 32) }}" min="1" max="50">
            </div>
            <button type="submit" class="btn btn-info btn-block">
                <i class="fas fa-save mr-2"></i>Simpan Kelas
            </button>
        </form>
    </div>
</div>
@endsection
