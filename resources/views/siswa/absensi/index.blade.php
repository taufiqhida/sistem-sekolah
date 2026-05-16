@extends('layouts.app')
@section('title', 'Rekap Absensi Saya')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('siswa.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik Saya</div>
<li class="nav-item"><a class="nav-link" href="{{ route('siswa.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Nilai Raport</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('siswa.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Rekap Absensi</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('siswa.notifikasi.index') }}"><i class="fas fa-fw fa-bell"></i><span>Notifikasi</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-clipboard-list text-info mr-2"></i>Rekap Absensi</h1>
        <p class="mb-0 text-gray-600">Riwayat kehadiran Anda</p>
    </div>
</div>

<!-- Rekap Summary -->
<div class="row mb-4">
    <div class="col-3">
        <div class="card border-left-success shadow py-2">
            <div class="card-body text-center py-2">
                <div class="h2 font-weight-bold text-success mb-0">{{ $rekap['hadir'] }}</div>
                <div class="small text-success">Hadir</div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card border-left-info shadow py-2">
            <div class="card-body text-center py-2">
                <div class="h2 font-weight-bold text-info mb-0">{{ $rekap['izin'] }}</div>
                <div class="small text-info">Izin</div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card border-left-warning shadow py-2">
            <div class="card-body text-center py-2">
                <div class="h2 font-weight-bold text-warning mb-0">{{ $rekap['sakit'] }}</div>
                <div class="small text-warning">Sakit</div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card border-left-danger shadow py-2">
            <div class="card-body text-center py-2">
                <div class="h2 font-weight-bold text-danger mb-0">{{ $rekap['alfa'] }}</div>
                <div class="small text-danger">Alfa</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card shadow mb-4">
    <div class="card-body py-3">
        <form method="GET" class="form-inline flex-wrap" style="gap:0.5rem;">
            <select name="semester_id" class="form-control" style="min-width:200px;">
                <option value="">Semua Semester</option>
                @foreach($semesters as $sem)
                <option value="{{ $sem->id }}" {{ $semesterId == $sem->id ? 'selected' : '' }}>
                    {{ $sem->tahunAjaran->nama }} {{ $sem->nama }}
                </option>
                @endforeach
            </select>
            <select name="mata_pelajaran_id" class="form-control" style="min-width:200px;">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($mapels as $m)
                <option value="{{ $m->id }}" {{ $mapelId == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-info"><i class="fas fa-filter mr-1"></i>Filter</button>
            @if(request()->hasAny(['semester_id','mata_pelajaran_id']))
            <a href="{{ route('siswa.absensi.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times mr-1"></i>Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Table -->
<div class="card shadow">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th class="text-center">Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $idx => $a)
                    <tr>
                        <td class="text-muted small">{{ $absensis->firstItem() + $idx }}</td>
                        <td class="font-weight-bold small">{{ $a->tanggal->isoFormat('dddd, D MMMM Y') }}</td>
                        <td class="small">{{ $a->mataPelajaran->nama }}</td>
                        <td><span class="badge badge-info">{{ $a->kelas->nama }}</span></td>
                        <td class="text-center">
                            <span class="status-{{ strtolower($a->status) }}">{{ $a->status }}</span>
                        </td>
                        <td class="small text-muted">{{ $a->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 d-block text-gray-300"></i>
                            Belum ada data absensi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($absensis->hasPages())
    <div class="card-footer bg-white">{{ $absensis->links() }}</div>
    @endif
</div>
@endsection
