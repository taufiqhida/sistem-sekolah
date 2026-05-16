@extends('layouts.app')
@section('title', 'Nilai Raport')

@section('sidebar-menu')
<li class="nav-item">
    <a class="nav-link" href="{{ route('siswa.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
</li>
<hr class="sidebar-divider">
<div class="sidebar-heading text-white-50">Akademik Saya</div>
<li class="nav-item active">
    <a class="nav-link" href="{{ route('siswa.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Nilai Raport</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('siswa.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Rekap Absensi</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('siswa.notifikasi.index') }}"><i class="fas fa-fw fa-bell"></i><span>Notifikasi</span></a>
</li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold">
            <i class="fas fa-star text-warning mr-2"></i>Nilai Raport
        </h1>
        <p class="mb-0 text-gray-600">Nilai akademik semester ini</p>
    </div>
    <div class="d-flex" style="gap:0.5rem;">
        <form method="GET" class="d-flex" style="gap:0.5rem;">
            <select name="semester_id" class="form-control" onchange="this.form.submit()">
                @foreach($semesters as $s)
                <option value="{{ $s->id }}" {{ $semesterId == $s->id ? 'selected' : '' }}>
                    {{ $s->tahunAjaran->nama }} {{ $s->nama }}
                </option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('siswa.nilai.raport', ['semester_id' => $semesterId]) }}"
           class="btn btn-danger shadow-sm">
            <i class="fas fa-file-pdf mr-2"></i>Cetak Raport
        </a>
    </div>
</div>

@if($rataRata)
<div class="alert alert-info d-flex align-items-center mb-4" style="border-radius:12px;border:none;">
    <i class="fas fa-chart-line fa-2x mr-3 text-info"></i>
    <div>
        <strong>Rata-rata Nilai Keseluruhan:</strong>
        <span class="h5 ml-2 {{ $rataRata >= 75 ? 'text-success' : 'text-danger' }}">{{ number_format($rataRata, 1) }}</span>
        <span class="ml-2 badge {{ $rataRata >= 90 ? 'badge-success' : ($rataRata >= 75 ? 'badge-primary' : 'badge-danger') }}">
            @if($rataRata >= 90) Sangat Baik
            @elseif($rataRata >= 80) Baik
            @elseif($rataRata >= 70) Cukup
            @else Perlu Perbaikan
            @endif
        </span>
    </div>
</div>
@endif

<div class="card shadow">
    <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
        <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-table mr-2"></i>Tabel Nilai</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mata Pelajaran</th>
                        <th>KKM</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Nilai Akhir</th>
                        <th class="text-center">Predikat</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $idx => $nilai)
                    @php $lulus = ($nilai->nilai_akhir ?? 0) >= $nilai->mataPelajaran->kkm; @endphp
                    <tr>
                        <td class="text-muted small">{{ $idx + 1 }}</td>
                        <td>
                            <div class="font-weight-bold">{{ $nilai->mataPelajaran->nama }}</div>
                            <div class="small text-muted">{{ $nilai->guru->nama_lengkap }}</div>
                        </td>
                        <td><span class="badge badge-secondary">{{ $nilai->mataPelajaran->kkm }}</span></td>
                        <td class="text-center">{{ $nilai->nilai_tugas ?? '-' }}</td>
                        <td class="text-center">{{ $nilai->nilai_uts ?? '-' }}</td>
                        <td class="text-center">{{ $nilai->nilai_uas ?? '-' }}</td>
                        <td class="text-center">
                            @if($nilai->nilai_akhir)
                            <span class="font-weight-bold h5 mb-0 {{ $lulus ? 'text-success' : 'text-danger' }}">
                                {{ $nilai->nilai_akhir }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($nilai->predikat)
                            <span class="badge badge-{{ $nilai->predikat === 'A' ? 'success' : ($nilai->predikat === 'B' ? 'primary' : ($nilai->predikat === 'C' ? 'warning' : 'danger')) }} px-3 py-1" style="font-size:1rem;">
                                {{ $nilai->predikat }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $lulus ? 'badge-success' : 'badge-danger' }}">
                                {{ $lulus ? 'Lulus' : 'Remedial' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fas fa-star fa-3x mb-3 d-block text-gray-300"></i>
                            Belum ada data nilai untuk semester ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
