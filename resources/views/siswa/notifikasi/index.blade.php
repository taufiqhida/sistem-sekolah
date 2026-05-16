@extends('layouts.app')
@section('title', 'Notifikasi')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('siswa.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik Saya</div>
<li class="nav-item"><a class="nav-link" href="{{ route('siswa.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Nilai Raport</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('siswa.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Rekap Absensi</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('siswa.notifikasi.index') }}"><i class="fas fa-fw fa-bell"></i><span>Notifikasi</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-bell text-primary mr-2"></i>Notifikasi</h1>
        <p class="mb-0 text-gray-600">Semua pemberitahuan akademik Anda</p>
    </div>
    <form action="{{ route('siswa.notifikasi.baca-semua') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-primary">
            <i class="fas fa-check-double mr-2"></i>Tandai Semua Dibaca
        </button>
    </form>
</div>

<div class="card shadow">
    <div class="card-body p-0">
        @forelse($notifikasis as $notif)
        <div class="d-flex align-items-start p-4 border-bottom {{ $notif->is_belum_dibaca ? 'bg-light' : '' }}">
            <div class="mr-4">
                <div style="width:48px;height:48px;background:linear-gradient(135deg,#4e73df,#224abe);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-bell text-white"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div class="font-weight-bold text-dark">
                        {{ $notif->judul }}
                        @if($notif->is_belum_dibaca)
                        <span class="badge badge-primary ml-2 small">Baru</span>
                        @endif
                    </div>
                    <div class="small text-muted">{{ $notif->created_at->isoFormat('D MMM Y, HH:mm') }}</div>
                </div>
                <div class="text-gray-700 mb-2">{{ $notif->pesan }}</div>
                <div class="d-flex" style="gap:0.5rem;">
                    @if($notif->url)
                    <a href="{{ $notif->url }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-external-link-alt mr-1"></i>Lihat
                    </a>
                    @endif
                    @if($notif->is_belum_dibaca)
                    <form action="{{ route('siswa.notifikasi.baca', $notif) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-check mr-1"></i>Tandai Dibaca
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="fas fa-bell-slash fa-3x mb-3 d-block text-gray-300"></i>
            Tidak ada notifikasi
        </div>
        @endforelse
    </div>
    @if($notifikasis->hasPages())
    <div class="card-footer bg-white">{{ $notifikasis->links() }}</div>
    @endif
</div>
@endsection
