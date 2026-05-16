@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('sidebar-menu')
<li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.dashboard') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>

<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Manajemen Data</div>

<li class="nav-item {{ request()->is('admin/siswas*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.siswas.index') }}">
        <i class="fas fa-fw fa-user-graduate"></i>
        <span>Data Siswa</span>
    </a>
</li>
<li class="nav-item {{ request()->is('admin/gurus*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.gurus.index') }}">
        <i class="fas fa-fw fa-chalkboard-teacher"></i>
        <span>Data Guru</span>
    </a>
</li>
<li class="nav-item {{ request()->is('admin/kelas*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.kelas.index') }}">
        <i class="fas fa-fw fa-school"></i>
        <span>Data Kelas</span>
    </a>
</li>
<li class="nav-item {{ request()->is('admin/mata-pelajarans*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}">
        <i class="fas fa-fw fa-book"></i>
        <span>Mata Pelajaran</span>
    </a>
</li>

<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik</div>

<li class="nav-item {{ request()->is('admin/assign-guru*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.assign-guru.index') }}">
        <i class="fas fa-fw fa-link"></i>
        <span>Assign Guru</span>
    </a>
</li>
<li class="nav-item {{ request()->is('admin/tahun-ajarans*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}">
        <i class="fas fa-fw fa-calendar-alt"></i>
        <span>Tahun Ajaran</span>
    </a>
</li>
@endsection

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4 animate-fadeup">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
            <i class="fas fa-tachometer-alt text-primary mr-2"></i>Dashboard Admin
        </h1>
        <p class="mb-0 text-gray-600">
            Selamat datang! Semester aktif:
            <strong>{{ $semesterAktif ? $semesterAktif->tahunAjaran->nama . ' ' . $semesterAktif->nama : 'Belum diset' }}</strong>
        </p>
    </div>
    <div class="d-none d-sm-inline-block">
        <span class="badge badge-primary py-2 px-3" style="font-size:0.9rem;border-radius:20px;">
            <i class="fas fa-calendar mr-1"></i>{{ now()->isoFormat('dddd, D MMMM Y') }}
        </span>
    </div>
</div>

<!-- Stats Row -->
<div class="row animate-fadeup">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa Aktif</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ number_format($totalSiswa) }}</div>
                    </div>
                    <div class="col-auto">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#4e73df,#224abe);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-user-graduate fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Guru Aktif</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ number_format($totalGuru) }}</div>
                    </div>
                    <div class="col-auto">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#1cc88a,#13855c);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-chalkboard-teacher fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Kelas</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ number_format($totalKelas) }}</div>
                    </div>
                    <div class="col-auto">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#36b9cc,#258391);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-school fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Kehadiran Bulan Ini</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $persentaseHadir }}%</div>
                        <div class="progress mt-2" style="height:6px;">
                            <div class="progress-bar bg-warning" style="width:{{ $persentaseHadir }}%"></div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#f6c23e,#dda20a);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-clipboard-check fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Attendance Chart -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex align-items-center justify-content-between" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-area mr-2"></i>Grafik Kehadiran 7 Hari Terakhir
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="chartAbsensi" style="height:300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kelas Summary -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list mr-2"></i>Rekap Per Kelas
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th class="text-center">Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rekapKelas as $k)
                            <tr>
                                <td>
                                    <span class="badge badge-light mr-1">{{ $k->tingkat }}</span>
                                    {{ $k->nama }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $k->jumlah_siswa }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.siswas.create') }}" class="btn btn-primary btn-block py-3">
                            <i class="fas fa-user-plus fa-lg mb-2 d-block"></i>
                            <strong>Tambah Siswa</strong>
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.gurus.create') }}" class="btn btn-success btn-block py-3">
                            <i class="fas fa-user-tie fa-lg mb-2 d-block"></i>
                            <strong>Tambah Guru</strong>
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.kelas.create') }}" class="btn btn-info btn-block py-3">
                            <i class="fas fa-plus-circle fa-lg mb-2 d-block"></i>
                            <strong>Tambah Kelas</strong>
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.assign-guru.index') }}" class="btn btn-warning btn-block py-3">
                            <i class="fas fa-link fa-lg mb-2 d-block"></i>
                            <strong>Assign Guru</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);

    const ctx = document.getElementById('chartAbsensi').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.map(d => d.label),
            datasets: [
                {
                    label: 'Hadir',
                    data: chartData.map(d => d.hadir),
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: '#1cc88a',
                    borderWidth: 2,
                    borderRadius: 8,
                },
                {
                    label: 'Alfa',
                    data: chartData.map(d => d.alfa),
                    backgroundColor: 'rgba(231, 74, 59, 0.7)',
                    borderColor: '#e74a3b',
                    borderWidth: 2,
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { font: { family: 'Inter', size: 12 } } },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
