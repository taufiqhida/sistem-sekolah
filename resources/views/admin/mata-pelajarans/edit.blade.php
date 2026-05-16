@extends('layouts.app')
@section('title', 'Edit Mata Pelajaran')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><i class="fas fa-edit text-warning mr-2"></i>Edit Mata Pelajaran</h1>
    <a href="{{ route('admin.mata-pelajarans.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-triangle mr-2"></i><strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="card shadow" style="max-width:500px;">
    <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
        <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-book mr-2"></i>Edit: {{ $mataPelajaran->nama }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.mata-pelajarans.update', $mataPelajaran) }}" method="POST">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="small font-weight-bold">Kode Mapel <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control text-uppercase @error('kode') is-invalid @enderror"
                               value="{{ old('kode', $mataPelajaran->kode) }}" placeholder="MTK" required maxlength="10">
                        @error('kode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="small font-weight-bold">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $mataPelajaran->nama) }}" placeholder="Matematika" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="small font-weight-bold">KKM <span class="text-danger">*</span></label>
                <input type="number" name="kkm" class="form-control @error('kkm') is-invalid @enderror"
                       value="{{ old('kkm', $mataPelajaran->kkm) }}" min="0" max="100" required>
                <small class="text-muted">Kriteria Ketuntasan Minimal (0-100)</small>
                @error('kkm')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-warning btn-block text-white">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
