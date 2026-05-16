@extends('layouts.app')
@section('title', 'Assign Guru ke Kelas')

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
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-link text-primary mr-2"></i>Assign Guru</h1>
        <p class="mb-0 text-gray-600">Penugasan guru ke kelas dan mata pelajaran</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-plus-circle mr-2"></i>Tambah Assignment</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.assign-guru.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="small font-weight-bold">Semester</label>
                        <select name="semester_id" class="form-control" required>
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ $sem->is_aktif ? 'selected' : '' }}>
                                {{ $sem->tahunAjaran->nama }} {{ $sem->nama }}{{ $sem->is_aktif ? ' (Aktif)' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Guru</label>
                        <select name="guru_id" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $g)
                            <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Kelas</label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Mata Pelajaran</label>
                        <select name="mata_pelajaran_id" class="form-control" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($mapels as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-link mr-2"></i>Assign Guru
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Assignment <span class="badge badge-primary ml-2">{{ $assignments->total() }}</span></h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th><th>Guru</th><th>Kelas</th><th>Mata Pelajaran</th><th>Semester</th><th class="text-center">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $idx => $a)
                            <tr>
                                <td class="text-muted small">{{ $assignments->firstItem() + $idx }}</td>
                                <td class="small font-weight-bold">{{ $a->guru->nama_lengkap }}</td>
                                <td><span class="badge badge-info">{{ $a->kelas->nama }}</span></td>
                                <td class="small">{{ $a->mataPelajaran->nama }}</td>
                                <td class="small text-muted">{{ $a->semester->tahunAjaran->nama }} {{ $a->semester->nama }}</td>
                                <td class="text-center">
                                    <form id="del-a-{{ $a->id }}" action="{{ route('admin.assign-guru.destroy', $a) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-a-{{ $a->id }}', 'assignment ini')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada assignment</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($assignments->hasPages())
            <div class="card-footer bg-white">{{ $assignments->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
