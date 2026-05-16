@extends('layouts.app')
@section('title', 'Mata Pelajaran')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-book text-warning mr-2"></i>Mata Pelajaran</h1>
        <p class="mb-0 text-gray-600">Kelola daftar mata pelajaran</p>
    </div>
    <a href="{{ route('admin.mata-pelajarans.create') }}" class="btn btn-warning shadow-sm"><i class="fas fa-plus fa-sm mr-2"></i>Tambah Mapel</a>
</div>

<div class="card shadow">
    <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
        <h6 class="m-0 font-weight-bold text-warning">Daftar Mata Pelajaran <span class="badge badge-warning ml-2">{{ $mapels->total() }}</span></h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th class="text-center" width="10%">KKM</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mapels as $idx => $mapel)
                    <tr>
                        <td class="text-muted small">{{ $mapels->firstItem() + $idx }}</td>
                        <td><span class="badge badge-warning font-weight-bold" style="font-size:0.85rem;">{{ $mapel->kode }}</span></td>
                        <td class="font-weight-bold">{{ $mapel->nama }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $mapel->kkm >= 75 ? 'success' : 'warning' }}">{{ $mapel->kkm }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.mata-pelajarans.edit', $mapel) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <form id="del-mapel-{{ $mapel->id }}" action="{{ route('admin.mata-pelajarans.destroy', $mapel) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-mapel-{{ $mapel->id }}', '{{ $mapel->nama }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-book fa-3x mb-3 d-block text-gray-300"></i>
                            Belum ada mata pelajaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($mapels->hasPages())
    <div class="card-footer bg-white">{{ $mapels->links() }}</div>
    @endif
</div>
@endsection
