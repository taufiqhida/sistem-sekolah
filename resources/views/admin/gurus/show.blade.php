@extends('layouts.app')
@section('title', 'Detail Guru')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><i class="fas fa-chalkboard-teacher text-success mr-2"></i>Detail Guru</h1>
    <div>
        <a href="{{ route('admin.gurus.edit', $guru) }}" class="btn btn-warning"><i class="fas fa-edit mr-2"></i>Edit</a>
        <a href="{{ route('admin.gurus.index') }}" class="btn btn-outline-secondary ml-2"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card shadow mb-4 text-center">
            <div class="card-body pt-4">
                @if($guru->foto)
                    <img src="{{ asset('storage/'.$guru->foto) }}" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #1cc88a;">
                @else
                    <div style="width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,#1cc88a,#17a673);display:inline-flex;align-items:center;justify-content:center;">
                        <i class="fas fa-user-tie fa-3x text-white"></i>
                    </div>
                @endif
                <h5 class="mt-3 font-weight-bold">{{ $guru->nama_lengkap }}</h5>
                <p class="text-muted mb-1"><i class="fas fa-id-card mr-1"></i> NIP: {{ $guru->nip ?? '-' }}</p>
                <span class="badge badge-{{ $guru->status === 'Aktif' ? 'success' : 'secondary' }} badge-pill px-3">{{ $guru->status }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-info-circle mr-2"></i>Informasi Personal</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted small" style="width:35%">Jenis Kelamin</td><td>{{ $guru->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><td class="text-muted small">Tanggal Lahir</td><td>{{ $guru->tanggal_lahir ? $guru->tanggal_lahir->format('d M Y') : '-' }}</td></tr>
                    <tr><td class="text-muted small">No HP</td><td>{{ $guru->no_hp ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Alamat</td><td>{{ $guru->alamat ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Email Login</td><td>{{ $guru->user->email }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-chalkboard mr-2"></i>Penugasan Mengajar</h6>
            </div>
            <div class="card-body p-0">
                @if($guru->guruKelasMapels->isEmpty())
                    <div class="text-center text-muted py-4"><i class="fas fa-info-circle mr-2"></i>Belum ada penugasan mengajar.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guru->guruKelasMapels as $i => $gkm)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $gkm->kelas->nama ?? '-' }}</td>
                                    <td>{{ $gkm->mataPelajaran->nama ?? '-' }}</td>
                                    <td>{{ $gkm->semester->nama ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
