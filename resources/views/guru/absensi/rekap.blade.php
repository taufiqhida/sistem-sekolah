@extends('layouts.app')
@section('title', 'Rekap Absensi')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('guru.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Kegiatan</div>
<li class="nav-item"><a class="nav-link" href="{{ route('guru.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Input Absensi</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('guru.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Input Nilai</span></a></li>
<hr class="sidebar-divider">
<li class="nav-item active"><a class="nav-link" href="{{ route('guru.absensi.rekap') }}"><i class="fas fa-fw fa-chart-bar"></i><span>Rekap Absensi</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-chart-bar text-success mr-2"></i>Rekap Absensi</h1>
        <p class="mb-0 text-gray-600">Riwayat absensi yang telah diinput</p>
    </div>
    <a href="{{ route('guru.absensi.export', request()->query()) }}" class="btn btn-success shadow-sm">
        <i class="fas fa-file-excel mr-2"></i>Export Excel
    </a>
</div>

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
            <select name="kelas_id" class="form-control" style="min-width:150px;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>
            <select name="mata_pelajaran_id" class="form-control" style="min-width:180px;">
                <option value="">Semua Mapel</option>
                @foreach($assignments as $a)
                <option value="{{ $a->mata_pelajaran_id }}" {{ $mapelId == $a->mata_pelajaran_id ? 'selected' : '' }}>
                    {{ $a->mataPelajaran->nama }}
                </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-success"><i class="fas fa-filter mr-1"></i>Filter</button>
            @if(request()->hasAny(['semester_id','kelas_id','mata_pelajaran_id']))
            <a href="{{ route('guru.absensi.rekap') }}" class="btn btn-outline-secondary"><i class="fas fa-times mr-1"></i>Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
        <h6 class="m-0 font-weight-bold text-success">Riwayat Absensi <span class="badge badge-success ml-2">{{ $absensiList->total() }}</span></h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiList as $idx => $a)
                    <tr>
                        <td class="text-muted small">{{ $absensiList->firstItem() + $idx }}</td>
                        <td class="font-weight-bold small">{{ $a->siswa->nama_lengkap }}</td>
                        <td><span class="badge badge-info small">{{ $a->kelas->nama }}</span></td>
                        <td class="small">{{ $a->mataPelajaran->nama }}</td>
                        <td class="small">{{ $a->tanggal->isoFormat('D MMM Y') }}</td>
                        <td class="text-center">
                            <span class="status-{{ strtolower($a->status) }}">{{ $a->status }}</span>
                        </td>
                        <td class="small text-muted">{{ $a->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-clipboard-list fa-3x mb-3 d-block text-gray-300"></i>
                            Belum ada data absensi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($absensiList->hasPages())
    <div class="card-footer bg-white">{{ $absensiList->links() }}</div>
    @endif
</div>
@endsection
