@extends('layouts.app')
@section('title', 'Input Absensi')

@section('sidebar-menu')
<li class="nav-item">
    <a class="nav-link" href="{{ route('guru.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
</li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Kegiatan</div>
<li class="nav-item active">
    <a class="nav-link" href="{{ route('guru.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Input Absensi</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('guru.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Input Nilai</span></a>
</li>
<hr class="sidebar-divider">
<li class="nav-item">
    <a class="nav-link" href="{{ route('guru.absensi.rekap') }}"><i class="fas fa-fw fa-chart-bar"></i><span>Rekap Absensi</span></a>
</li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
            <i class="fas fa-clipboard-list text-success mr-2"></i>Input Absensi
        </h1>
        <p class="mb-0 text-gray-600">
            <strong>{{ $assignment->kelas->nama }}</strong> &mdash;
            <strong>{{ $assignment->mataPelajaran->nama }}</strong>
        </p>
    </div>
    <a href="{{ route('guru.absensi.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<form action="{{ route('guru.absensi.store') }}" method="POST">
    @csrf
    <input type="hidden" name="kelas_id" value="{{ $assignment->kelas_id }}">
    <input type="hidden" name="mata_pelajaran_id" value="{{ $assignment->mata_pelajaran_id }}">

    <div class="card shadow mb-4">
        <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-calendar-day mr-2"></i>Tanggal Absensi</h6>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="font-weight-bold small text-uppercase text-gray-600">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                           value="{{ $tanggal }}" max="{{ now()->toDateString() }}" required>
                </div>
                <div class="col-md-8 d-flex align-items-end">
                    <div class="alert alert-info mb-0 py-2 px-3 w-100" style="border-radius:10px;">
                        <i class="fas fa-info-circle mr-2"></i>
                        Absensi: <strong>{{ $assignment->kelas->nama }}</strong> &bull;
                        <strong>{{ $assignment->mataPelajaran->nama }}</strong> &bull;
                        Semester: <strong>{{ $semesterAktif ? $semesterAktif->nama : '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Absensi Batch -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between" style="background:white;border-bottom:2px solid #f8f9fc;">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-users mr-2"></i>Daftar Siswa
                <span class="badge badge-success ml-2">{{ $siswaKelas->count() }} siswa</span>
            </h6>
            <div>
                <button type="button" class="btn btn-sm btn-success mr-1" onclick="setAllStatus('Hadir')">
                    <i class="fas fa-check mr-1"></i>Semua Hadir
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="setAllStatus('Alfa')">
                    <i class="fas fa-times mr-1"></i>Semua Alfa
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th width="35%">Status Kehadiran</th>
                            <th width="20%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswaKelas as $idx => $sk)
                        @php $currentStatus = $existingAbsensi[$sk->siswa_id] ?? 'Hadir'; @endphp
                        <tr>
                            <td class="text-muted small">{{ $idx + 1 }}</td>
                            <td>
                                <div class="font-weight-bold">{{ $sk->siswa->nama_lengkap }}</div>
                                <div class="small text-muted">{{ $sk->siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </td>
                            <td class="small text-muted">{{ $sk->siswa->nisn }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @foreach(['Hadir' => 'success', 'Izin' => 'primary', 'Sakit' => 'warning', 'Alfa' => 'danger'] as $status => $color)
                                    <label class="btn btn-outline-{{ $color }} {{ $currentStatus === $status ? 'active' : '' }}"
                                           for="status_{{ $sk->siswa_id }}_{{ $status }}">
                                        <input type="radio"
                                               name="absensi[{{ $sk->siswa_id }}][status]"
                                               id="status_{{ $sk->siswa_id }}_{{ $status }}"
                                               value="{{ $status }}"
                                               {{ $currentStatus === $status ? 'checked' : '' }}
                                               style="display:none;" required>
                                        {{ $status }}
                                    </label>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <input type="text" name="absensi[{{ $sk->siswa_id }}][keterangan]"
                                       class="form-control form-control-sm"
                                       placeholder="Opsional">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-save mr-2"></i>Simpan Absensi
            </button>
            <a href="{{ route('guru.absensi.index') }}" class="btn btn-outline-secondary ml-2">Batal</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function setAllStatus(status) {
    document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
        if (radio.value === status) {
            radio.checked = true;
            radio.closest('.btn-group').querySelectorAll('label').forEach(l => l.classList.remove('active'));
            radio.parentElement.classList.add('active');
        }
    });
}
</script>
@endpush
