@extends('layouts.app')

@section('title', 'Data Siswa')

@section('sidebar-menu')
<li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
</li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item active">
    <a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a>
</li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a>
</li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
            <i class="fas fa-user-graduate text-primary mr-2"></i>Data Siswa
        </h1>
        <p class="mb-0 text-gray-600">Kelola seluruh data siswa SMK At Kausar</p>
    </div>
    <a href="{{ route('admin.siswas.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm mr-2"></i>Tambah Siswa
    </a>
</div>

<!-- Filter & Search -->
<div class="card shadow mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.siswas.index') }}" class="form-inline flex-wrap" style="gap:0.5rem;">
            <div class="input-group" style="min-width:250px;flex:1;">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                </div>
                <input type="text" name="search" class="form-control border-left-0"
                       placeholder="Cari nama, NISN, NIS..." value="{{ request('search') }}">
            </div>
            <select name="kelas_id" class="form-control" style="min-width:160px;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>
            <select name="status" class="form-control" style="min-width:130px;">
                <option value="">Semua Status</option>
                <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Non-Aktif" {{ request('status') === 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                <option value="Lulus" {{ request('status') === 'Lulus' ? 'selected' : '' }}>Lulus</option>
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter mr-1"></i>Filter
            </button>
            @if(request()->hasAny(['search', 'kelas_id', 'status']))
            <a href="{{ route('admin.siswas.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times mr-1"></i>Reset
            </a>
            @endif
        </form>
    </div>
</div>

<!-- Table -->
<div class="card shadow">
    <div class="card-header py-3 d-flex align-items-center justify-content-between" style="background:white;">
        <h6 class="m-0 font-weight-bold text-primary">
            Daftar Siswa <span class="badge badge-primary ml-2">{{ $siswas->total() }}</span>
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Siswa</th>
                        <th>NISN / NIS</th>
                        <th>Kelas</th>
                        <th>JK</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $idx => $siswa)
                    <tr>
                        <td class="text-muted small">{{ $siswas->firstItem() + $idx }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mr-3" style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#4e73df,#224abe);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    @if($siswa->foto)
                                    <img src="{{ asset('storage/'.$siswa->foto) }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                    @else
                                    <i class="fas fa-user text-white"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark">{{ $siswa->nama_lengkap }}</div>
                                    <div class="small text-muted">{{ $siswa->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small font-weight-bold">{{ $siswa->nisn }}</div>
                            <div class="small text-muted">{{ $siswa->nis ?? '-' }}</div>
                        </td>
                        <td>
                            @php $sk = $siswa->siswaKelas->first(); @endphp
                            @if($sk && $sk->kelas)
                            <span class="badge badge-info">{{ $sk->kelas->nama }}</span>
                            @else
                            <span class="text-muted small">Belum diassign</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $siswa->jenis_kelamin === 'L' ? 'badge-primary' : 'badge-danger' }}">
                                {{ $siswa->jenis_kelamin === 'L' ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $siswa->status === 'Aktif' ? 'badge-success' : 'badge-secondary' }}">
                                {{ $siswa->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.siswas.show', $siswa) }}" class="btn btn-sm btn-info" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.siswas.edit', $siswa) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form id="del-siswa-{{ $siswa->id }}" action="{{ route('admin.siswas.destroy', $siswa) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('del-siswa-{{ $siswa->id }}', '{{ $siswa->nama_lengkap }}')"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-user-graduate fa-3x mb-3 d-block text-gray-300"></i>
                            Tidak ada data siswa ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($siswas->hasPages())
    <div class="card-footer bg-white">
        {{ $siswas->links() }}
    </div>
    @endif
</div>
@endsection
