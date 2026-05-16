@extends('layouts.app')
@section('title', 'Dashboard Siswa')

@section('sidebar-menu')
<li class="nav-item active">
    <a class="nav-link" href="{{ route('siswa.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
</li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik Saya</div>
<li class="nav-item">
    <a class="nav-link" href="{{ route('siswa.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Nilai Raport</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('siswa.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Rekap Absensi</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('siswa.notifikasi.index') }}">
        <i class="fas fa-fw fa-bell"></i><span>Notifikasi</span>
        @php $nb = auth()->user()->notifikasisBelumDibaca()->count(); @endphp
        @if($nb > 0)
        <span class="badge badge-danger badge-pill ml-1">{{ $nb }}</span>
        @endif
    </a>
</li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Profil</div>
<li class="nav-item">
    <a class="nav-link" href="{{ route('profile.show') }}"><i class="fas fa-fw fa-user-circle"></i><span>Profil Saya</span></a>
</li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
            <i class="fas fa-user-graduate text-info mr-2"></i>Dashboard Siswa
        </h1>
        <p class="mb-0 text-gray-600">
            Selamat datang, <strong>{{ $siswa->nama_lengkap }}</strong>!
            @if($siswaKelas)
            &mdash; Kelas <strong>{{ $siswaKelas->kelas->nama }}</strong>
            @endif
        </p>
    </div>
    <div class="d-none d-sm-inline-block">
        <span class="badge badge-info py-2 px-3" style="font-size:0.85rem;border-radius:20px;">
            <i class="fas fa-calendar mr-1"></i>{{ now()->isoFormat('dddd, D MMMM Y') }}
        </span>
    </div>
</div>

<!-- Stats Row -->
<div class="row animate-fadeup">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Nilai</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $rataRataNilai > 0 ? $rataRataNilai : '-' }}</div>
                    </div>
                    <div class="col-auto">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#36b9cc,#258391);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-star fa-2x text-white"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Hadir</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $rekap['hadir'] }}</div>
                    </div>
                    <div class="col-auto">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#1cc88a,#13855c);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-check-circle fa-2x text-white"></i>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Izin / Sakit</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $rekap['izin'] + $rekap['sakit'] }}</div>
                    </div>
                    <div class="col-auto">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#f6c23e,#dda20a);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-notes-medical fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Alfa</div>
                        <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $rekap['alfa'] }}</div>
                    </div>
                    <div class="col-auto">
                        <div style="width:56px;height:56px;background:linear-gradient(135deg,#e74a3b,#be2617);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-times-circle fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kehadiran Progress -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-chart-pie mr-2"></i>Statistik Kehadiran</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="font-weight-bold">Persentase Kehadiran</span>
                    <span class="font-weight-bold text-{{ $persentaseHadir >= 75 ? 'success' : 'danger' }}">{{ $persentaseHadir }}%</span>
                </div>
                <div class="progress mb-4" style="height:20px;">
                    <div class="progress-bar bg-{{ $persentaseHadir >= 75 ? 'success' : 'danger' }}"
                         style="width:{{ $persentaseHadir }}%;border-radius:10px;font-weight:600;">
                        {{ $persentaseHadir }}%
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-3">
                        <div class="p-2 rounded" style="background:#d4edda;">
                            <div class="font-weight-bold text-success h5 mb-0">{{ $rekap['hadir'] }}</div>
                            <div class="small text-success">Hadir</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded" style="background:#d1ecf1;">
                            <div class="font-weight-bold text-info h5 mb-0">{{ $rekap['izin'] }}</div>
                            <div class="small text-info">Izin</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded" style="background:#fff3cd;">
                            <div class="font-weight-bold text-warning h5 mb-0">{{ $rekap['sakit'] }}</div>
                            <div class="small text-warning">Sakit</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="p-2 rounded" style="background:#f8d7da;">
                            <div class="font-weight-bold text-danger h5 mb-0">{{ $rekap['alfa'] }}</div>
                            <div class="small text-danger">Alfa</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi Terbaru -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex align-items-center justify-content-between" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-bell mr-2"></i>Notifikasi Terbaru</h6>
                <a href="{{ route('siswa.notifikasi.index') }}" class="btn btn-sm btn-outline-info">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($notifikasiBelumDibaca as $notif)
                <div class="d-flex align-items-start px-3 py-3 border-bottom {{ $notif->is_belum_dibaca ? 'bg-light' : '' }}">
                    <div class="mr-3 mt-1">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#4e73df,#224abe);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-bell text-white small"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="font-weight-bold small text-dark">{{ $notif->judul }}</div>
                        <div class="small text-muted">{{ Str::limit($notif->pesan, 80) }}</div>
                        <div class="small text-gray-500 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-check-circle fa-2x mb-2 d-block text-success"></i>
                    Tidak ada notifikasi baru
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body py-3">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('siswa.nilai.index') }}" class="btn btn-outline-info btn-block py-3">
                            <i class="fas fa-star fa-lg mb-1 d-block"></i><strong>Lihat Nilai</strong>
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('siswa.absensi.index') }}" class="btn btn-outline-success btn-block py-3">
                            <i class="fas fa-clipboard-list fa-lg mb-1 d-block"></i><strong>Rekap Absensi</strong>
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('siswa.nilai.raport') }}" class="btn btn-outline-danger btn-block py-3">
                            <i class="fas fa-file-pdf fa-lg mb-1 d-block"></i><strong>Cetak Raport PDF</strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
