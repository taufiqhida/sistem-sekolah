@extends('layouts.app')
@section('title', 'Tahun Ajaran & Semester')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-calendar-alt text-primary mr-2"></i>Tahun Ajaran & Semester</h1>
        <p class="mb-0 text-gray-600">Kelola periode akademik sekolah</p>
    </div>
</div>

<div class="row">
    <!-- Form Tambah -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle mr-2"></i>Tambah Tahun Ajaran</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tahun-ajarans.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="small font-weight-bold text-gray-700">Tahun Ajaran</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: 2026/2027" required>
                        <small class="text-muted">Format: YYYY/YYYY</small>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_aktif" name="is_aktif">
                            <label class="custom-control-label" for="is_aktif">Set sebagai tahun ajaran aktif</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- List -->
    <div class="col-lg-8 mb-4">
        @foreach($tahunAjarans as $ta)
        <div class="card shadow mb-3">
            <div class="card-header py-3 d-flex align-items-center justify-content-between"
                 style="background:{{ $ta->is_aktif ? 'linear-gradient(135deg,#4e73df,#224abe)' : 'white' }};border-bottom:2px solid #f8f9fc;">
                <div>
                    <h6 class="m-0 font-weight-bold {{ $ta->is_aktif ? 'text-white' : 'text-primary' }}">
                        <i class="fas fa-calendar mr-2"></i>Tahun Ajaran {{ $ta->nama }}
                        @if($ta->is_aktif)
                        <span class="badge badge-warning ml-2">AKTIF</span>
                        @endif
                    </h6>
                </div>
                <div class="d-flex" style="gap:0.5rem;">
                    @if(!$ta->is_aktif)
                    <form action="{{ route('admin.tahun-ajarans.aktif', $ta) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning">
                            <i class="fas fa-star mr-1"></i>Set Aktif
                        </button>
                    </form>
                    @endif
                    <form id="del-ta-{{ $ta->id }}" action="{{ route('admin.tahun-ajarans.destroy', $ta) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-ta-{{ $ta->id }}', 'tahun ajaran {{ $ta->nama }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="row">
                    @foreach($ta->semesters as $sem)
                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded"
                             style="background:{{ $sem->is_aktif ? '#d4edda' : '#f8f9fc' }};border:1px solid {{ $sem->is_aktif ? '#c3e6cb' : '#e3e6f0' }};">
                            <div>
                                <div class="font-weight-bold text-dark">Semester {{ $sem->nama }}</div>
                                @if($sem->is_aktif)
                                <span class="badge badge-success small">Semester Aktif</span>
                                @endif
                            </div>
                            @if(!$sem->is_aktif)
                            <form action="{{ route('admin.semesters.aktif', $sem) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-check mr-1"></i>Aktifkan
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        @if($tahunAjarans->isEmpty())
        <div class="card shadow"><div class="card-body text-center py-5 text-muted">
            <i class="fas fa-calendar-times fa-3x mb-3 d-block text-gray-300"></i>
            Belum ada tahun ajaran
        </div></div>
        @endif
    </div>
</div>
@endsection
