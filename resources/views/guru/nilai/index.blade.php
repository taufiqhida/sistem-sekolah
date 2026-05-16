@extends('layouts.app')
@section('title', 'Input Nilai Siswa')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('guru.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Kegiatan</div>
<li class="nav-item"><a class="nav-link" href="{{ route('guru.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Input Absensi</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('guru.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Input Nilai</span></a></li>
<hr class="sidebar-divider">
<li class="nav-item"><a class="nav-link" href="{{ route('guru.absensi.rekap') }}"><i class="fas fa-fw fa-chart-bar"></i><span>Rekap Absensi</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-star text-warning mr-2"></i>Input Nilai Siswa</h1>
        <p class="mb-0 text-gray-600">Pilih kelas dan mata pelajaran untuk input nilai</p>
    </div>
</div>

<div class="row">
    @forelse($assignments as $a)
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow border-left-warning h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <div class="h5 font-weight-bold text-dark mb-1">{{ $a->mataPelajaran->nama }}</div>
                        <span class="badge badge-info">{{ $a->kelas->nama }}</span>
                        <span class="badge badge-warning ml-1">KKM: {{ $a->mataPelajaran->kkm }}</span>
                    </div>
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#f6c23e,#dda20a);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-star text-white"></i>
                    </div>
                </div>
                <a href="{{ route('guru.nilai.input', [$a->kelas_id, $a->mata_pelajaran_id]) }}"
                   class="btn btn-warning btn-block mt-3">
                    <i class="fas fa-pencil-alt mr-2"></i>Input / Edit Nilai
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow"><div class="card-body text-center py-5 text-muted">
            <i class="fas fa-star fa-3x mb-3 d-block text-gray-300"></i>
            Anda belum di-assign ke kelas manapun.
        </div></div>
    </div>
    @endforelse
</div>
@endsection
