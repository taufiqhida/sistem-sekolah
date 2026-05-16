@extends('layouts.app')
@section('title', 'Dashboard Guru')

@section('sidebar-menu')
<li class="nav-item active">
    <a class="nav-link" href="{{ route('guru.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
</li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Kegiatan</div>
<li class="nav-item">
    <a class="nav-link" href="{{ route('guru.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Input Absensi</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('guru.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Input Nilai</span></a>
</li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Laporan</div>
<li class="nav-item">
    <a class="nav-link" href="{{ route('guru.absensi.rekap') }}"><i class="fas fa-fw fa-chart-bar"></i><span>Rekap Absensi</span></a>
</li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
            <i class="fas fa-chalkboard-teacher text-success mr-2"></i>Dashboard Guru
        </h1>
        <p class="mb-0 text-gray-600">Selamat datang, <strong>{{ $guru->nama_lengkap }}</strong>!</p>
    </div>
</div>

<div class="row animate-fadeup">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kelas Diajar</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $assignments->pluck('kelas_id')->unique()->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#1cc88a,#13855c);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-school fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Siswa Diajar</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalSiswa }}</div>
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
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Absensi Hari Ini</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $absensiHariIni }}</div>
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

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-chart-line mr-2"></i>Grafik Absensi 7 Hari Terakhir</h6>
            </div>
            <div class="card-body">
                <canvas id="chartGuru" style="height:260px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-list mr-2"></i>Jadwal Mengajar</h6>
            </div>
            <div class="card-body p-0">
                @forelse($assignments as $a)
                <div class="d-flex align-items-center px-3 py-3 border-bottom">
                    <div class="mr-3">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#1cc88a,#13855c);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-book text-white small"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="font-weight-bold small text-dark">{{ $a->mataPelajaran->nama }}</div>
                        <div class="small text-muted">{{ $a->kelas->nama }}</div>
                    </div>
                    <a href="{{ route('guru.absensi.input', [$a->kelas_id, $a->mata_pelajaran_id]) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-plus-circle"></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-calendar-times fa-2x mb-2 d-block text-gray-300"></i>
                    Tidak ada jadwal
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const d = @json($chartData);
    new Chart(document.getElementById('chartGuru').getContext('2d'), {
        type: 'line',
        data: {
            labels: d.map(x => x.label),
            datasets: [
                { label: 'Hadir', data: d.map(x => x.hadir), borderColor: '#1cc88a', backgroundColor: 'rgba(28,200,138,0.15)', borderWidth: 3, tension: 0.4, fill: true },
                { label: 'Alfa', data: d.map(x => x.alfa), borderColor: '#e74a3b', backgroundColor: 'transparent', borderWidth: 2, tension: 0.4, borderDash: [5,5] }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
        }
    });
});
</script>
@endpush
