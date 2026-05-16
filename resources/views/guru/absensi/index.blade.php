@extends('layouts.app')
@section('title', 'Pilih Kelas untuk Absensi')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('guru.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Kegiatan</div>
<li class="nav-item active"><a class="nav-link" href="{{ route('guru.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Input Absensi</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('guru.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Input Nilai</span></a></li>
<hr class="sidebar-divider">
<li class="nav-item"><a class="nav-link" href="{{ route('guru.absensi.rekap') }}"><i class="fas fa-fw fa-chart-bar"></i><span>Rekap Absensi</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-clipboard-list text-success mr-2"></i>Input Absensi</h1>
        <p class="mb-0 text-gray-600">Pilih kelas dan mata pelajaran untuk input absensi</p>
    </div>
    <a href="{{ route('guru.absensi.rekap') }}" class="btn btn-outline-success">
        <i class="fas fa-chart-bar mr-2"></i>Lihat Rekap
    </a>
</div>

@if($semesterAktif)
<div class="alert alert-success d-flex align-items-center mb-4" style="border-radius:12px;border:none;">
    <i class="fas fa-calendar-check fa-lg mr-3"></i>
    <div>Semester Aktif: <strong>{{ $semesterAktif->tahunAjaran->nama }} {{ $semesterAktif->nama }}</strong></div>
</div>
@else
<div class="alert alert-warning mb-4" style="border-radius:12px;border:none;">
    <i class="fas fa-exclamation-triangle mr-2"></i>Tidak ada semester aktif. Hubungi Admin untuk mengaktifkan semester.
</div>
@endif

<div class="row">
    @forelse($assignments as $a)
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card shadow border-left-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <div class="h5 font-weight-bold text-dark mb-1">{{ $a->mataPelajaran->nama }}</div>
                        <span class="badge badge-success">{{ $a->kelas->nama }}</span>
                        <span class="badge badge-light ml-1">KKM: {{ $a->mataPelajaran->kkm }}</span>
                    </div>
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,#1cc88a,#13855c);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-clipboard-list text-white"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('guru.absensi.input', [$a->kelas_id, $a->mata_pelajaran_id]) }}"
                       class="btn btn-success btn-block">
                        <i class="fas fa-plus-circle mr-2"></i>Input Absensi Hari Ini
                    </a>
                    <a href="{{ route('guru.absensi.input', [$a->kelas_id, $a->mata_pelajaran_id]) }}"
                       class="btn btn-outline-success btn-block btn-sm mt-2">
                        <i class="fas fa-history mr-2"></i>Input Tanggal Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow"><div class="card-body text-center py-5 text-muted">
            <i class="fas fa-clipboard-list fa-3x mb-3 d-block text-gray-300"></i>
            Anda belum di-assign ke kelas dan mata pelajaran manapun.<br>
            Hubungi Admin untuk pengaturan jadwal.
        </div></div>
    </div>
    @endforelse
</div>
@endsection
