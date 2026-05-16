@extends('layouts.app')
@section('title', 'Data Guru')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-chalkboard-teacher text-success mr-2"></i>Data Guru</h1>
        <p class="mb-0 text-gray-600">Kelola seluruh data guru SMK At Kausar</p>
    </div>
    <a href="{{ route('admin.gurus.create') }}" class="btn btn-success shadow-sm">
        <i class="fas fa-plus fa-sm mr-2"></i>Tambah Guru
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body py-3">
        <form method="GET" class="form-inline flex-wrap" style="gap:0.5rem;">
            <div class="input-group" style="min-width:250px;flex:1;">
                <div class="input-group-prepend"><span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-gray-400"></i></span></div>
                <input type="text" name="search" class="form-control border-left-0" placeholder="Cari nama, NIP..." value="{{ request('search') }}">
            </div>
            <select name="status" class="form-control" style="min-width:130px;">
                <option value="">Semua Status</option>
                <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Non-Aktif" {{ request('status') === 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
            </select>
            <button type="submit" class="btn btn-success"><i class="fas fa-filter mr-1"></i>Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.gurus.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times mr-1"></i>Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card shadow">
    <div class="card-header py-3 d-flex align-items-center justify-content-between" style="background:white;">
        <h6 class="m-0 font-weight-bold text-success">Daftar Guru <span class="badge badge-success ml-2">{{ $gurus->total() }}</span></h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Guru</th>
                        <th>NIP</th>
                        <th>JK</th>
                        <th>No HP</th>
                        <th>Status</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $idx => $guru)
                    <tr>
                        <td class="text-muted small">{{ $gurus->firstItem() + $idx }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3" style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#1cc88a,#13855c);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark">{{ $guru->nama_lengkap }}</div>
                                    <div class="small text-muted">{{ $guru->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="small">{{ $guru->nip ?? '-' }}</td>
                        <td><span class="badge {{ $guru->jenis_kelamin === 'L' ? 'badge-primary' : 'badge-danger' }}">{{ $guru->jenis_kelamin }}</span></td>
                        <td class="small">{{ $guru->no_hp ?? '-' }}</td>
                        <td><span class="badge {{ $guru->status === 'Aktif' ? 'badge-success' : 'badge-secondary' }}">{{ $guru->status }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.gurus.show', $guru) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.gurus.edit', $guru) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form id="del-guru-{{ $guru->id }}" action="{{ route('admin.gurus.destroy', $guru) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-guru-{{ $guru->id }}', '{{ $guru->nama_lengkap }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-chalkboard-teacher fa-3x mb-3 d-block text-gray-300"></i>
                            Tidak ada data guru ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($gurus->hasPages())
    <div class="card-footer bg-white">{{ $gurus->links() }}</div>
    @endif
</div>
@endsection
