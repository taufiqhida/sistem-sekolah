@extends('layouts.app')
@section('title', 'Detail Siswa')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><i class="fas fa-user-graduate text-primary mr-2"></i>Detail Siswa</h1>
    <div>
        <a href="{{ route('admin.siswas.edit', $siswa) }}" class="btn btn-warning"><i class="fas fa-edit mr-2"></i>Edit</a>
        <a href="{{ route('admin.siswas.index') }}" class="btn btn-outline-secondary ml-2"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
    </div>
</div>

<div class="row">
    {{-- Kartu Profil --}}
    <div class="col-lg-4">
        <div class="card shadow mb-4 text-center">
            <div class="card-body pt-4">
                @if($siswa->foto)
                    <img src="{{ asset('storage/'.$siswa->foto) }}" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #4e73df;">
                @else
                    <div style="width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,#4e73df,#224abe);display:inline-flex;align-items:center;justify-content:center;">
                        <i class="fas fa-user-graduate fa-3x text-white"></i>
                    </div>
                @endif
                <h5 class="mt-3 font-weight-bold">{{ $siswa->nama_lengkap }}</h5>
                <p class="text-muted mb-1"><i class="fas fa-id-badge mr-1"></i> NISN: {{ $siswa->nisn }}</p>
                @if($siswa->nis)<p class="text-muted mb-1 small">NIS: {{ $siswa->nis }}</p>@endif
                <span class="badge badge-{{ $siswa->status === 'Aktif' ? 'primary' : 'secondary' }} badge-pill px-3">{{ $siswa->status }}</span>
            </div>
        </div>

        {{-- Rekapitulasi Absensi --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-clipboard-check mr-2"></i>Rekap Absensi</h6>
            </div>
            <div class="card-body">
                @php $totalAbsensi = ($rekap['hadir']??0)+($rekap['sakit']??0)+($rekap['izin']??0)+($rekap['alpha']??0); @endphp
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="h4 font-weight-bold text-success">{{ $rekap['hadir'] ?? 0 }}</div>
                        <div class="small text-muted">Hadir</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 font-weight-bold text-warning">{{ $rekap['sakit'] ?? 0 }}</div>
                        <div class="small text-muted">Sakit</div>
                    </div>
                    <div class="col-6">
                        <div class="h4 font-weight-bold text-info">{{ $rekap['izin'] ?? 0 }}</div>
                        <div class="small text-muted">Izin</div>
                    </div>
                    <div class="col-6">
                        <div class="h4 font-weight-bold text-danger">{{ $rekap['alpha'] ?? 0 }}</div>
                        <div class="small text-muted">Alpha</div>
                    </div>
                </div>
                <hr>
                <div class="text-center text-muted small">Total: {{ $totalAbsensi }} pertemuan</div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        {{-- Info Pribadi --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle mr-2"></i>Informasi Personal</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted small" style="width:35%">Jenis Kelamin</td><td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><td class="text-muted small">Tempat, Tgl Lahir</td><td>{{ $siswa->tempat_lahir ?? '-' }}{{ $siswa->tanggal_lahir ? ', '.$siswa->tanggal_lahir->format('d M Y') : '' }}</td></tr>
                    <tr><td class="text-muted small">No HP</td><td>{{ $siswa->no_hp ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Nama Orang Tua</td><td>{{ $siswa->nama_ortu ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Alamat</td><td>{{ $siswa->alamat ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Email Login</td><td>{{ $siswa->user->email }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Riwayat Kelas --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-school mr-2"></i>Riwayat Kelas</h6>
            </div>
            <div class="card-body p-0">
                @if($siswa->siswaKelas->isEmpty())
                    <div class="text-center text-muted py-4">Belum ada data kelas.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr><th>#</th><th>Kelas</th><th>Semester</th><th>Tahun Ajaran</th></tr>
                            </thead>
                            <tbody>
                                @foreach($siswa->siswaKelas as $i => $sk)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $sk->kelas->nama ?? '-' }}</td>
                                    <td>{{ $sk->semester->nama ?? '-' }}</td>
                                    <td>{{ $sk->semester->tahunAjaran->nama ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Nilai --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-star mr-2"></i>Data Nilai</h6>
            </div>
            <div class="card-body p-0">
                @if($siswa->nilais->isEmpty())
                    <div class="text-center text-muted py-4">Belum ada data nilai.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="thead-light">
                                <tr><th>#</th><th>Mata Pelajaran</th><th>Tugas</th><th>UTS</th><th>UAS</th><th>Akhir</th></tr>
                            </thead>
                            <tbody>
                                @foreach($siswa->nilais as $i => $n)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $n->mataPelajaran->nama ?? '-' }}</td>
                                    <td>{{ $n->nilai_tugas ?? '-' }}</td>
                                    <td>{{ $n->nilai_uts ?? '-' }}</td>
                                    <td>{{ $n->nilai_uas ?? '-' }}</td>
                                    <td><strong>{{ $n->nilai_akhir ?? '-' }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
