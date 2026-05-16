@extends('layouts.app')
@section('title', 'Data Kelas')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-school text-info mr-2"></i>Data Kelas</h1>
        <p class="mb-0 text-gray-600">Kelola kelas-kelas di SMK At Kausar</p>
    </div>
    <a href="{{ route('admin.kelas.create') }}" class="btn btn-info shadow-sm"><i class="fas fa-plus fa-sm mr-2"></i>Tambah Kelas</a>
</div>

<div class="row">
    @foreach($kelas->groupBy('tingkat') as $tingkat => $kelasList)
    <div class="col-12 mb-3">
        <h5 class="font-weight-bold text-gray-700">
            <span class="badge badge-dark mr-2">Tingkat {{ $tingkat }}</span>
        </h5>
        <div class="row">
            @foreach($kelasList as $k)
            <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
                <div class="card shadow border-left-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <div class="h5 font-weight-bold text-dark mb-0">{{ $k->nama }}</div>
                                <div class="small text-muted">{{ $k->jurusan }}</div>
                            </div>
                            <div style="width:44px;height:44px;background:linear-gradient(135deg,#36b9cc,#258391);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-school text-white"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <div>
                                <span class="badge badge-info">{{ $k->jumlah_siswa }} Siswa</span>
                                <span class="badge badge-light ml-1">Kapasitas: {{ $k->kapasitas }}</span>
                            </div>
                            <div>
                                <a href="{{ route('admin.kelas.edit', $k) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <form id="del-kelas-{{ $k->id }}" action="{{ route('admin.kelas.destroy', $k) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('del-kelas-{{ $k->id }}', '{{ $k->nama }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    @if($kelas->isEmpty())
    <div class="col-12">
        <div class="card shadow"><div class="card-body text-center py-5 text-muted">
            <i class="fas fa-school fa-3x mb-3 d-block text-gray-300"></i>
            Belum ada kelas. <a href="{{ route('admin.kelas.create') }}">Tambah kelas baru</a>
        </div></div>
    </div>
    @endif
</div>
@endsection
