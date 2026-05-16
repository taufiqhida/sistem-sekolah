@extends('layouts.app')
@section('title', 'Input Nilai')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('guru.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Kegiatan</div>
<li class="nav-item"><a class="nav-link" href="{{ route('guru.absensi.index') }}"><i class="fas fa-fw fa-clipboard-list"></i><span>Input Absensi</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('guru.nilai.index') }}"><i class="fas fa-fw fa-star"></i><span>Input Nilai</span></a></li>
<hr class="sidebar-divider">
<li class="nav-item"><a class="nav-link" href="{{ route('guru.absensi.rekap') }}"><i class="fas fa-fw fa-chart-bar"></i><span>Rekap Absensi</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-star text-warning mr-2"></i>Input Nilai</h1>
        <p class="mb-0 text-gray-600">
            <strong>{{ $assignment->kelas->nama }}</strong> &mdash; <strong>{{ $assignment->mataPelajaran->nama }}</strong>
            &bull; KKM: <strong>{{ $assignment->mataPelajaran->kkm }}</strong>
        </p>
    </div>
    <a href="{{ route('guru.nilai.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
</div>

<div class="alert alert-info mb-4" style="border-radius:12px;border:none;">
    <i class="fas fa-info-circle mr-2"></i>
    Nilai akhir dihitung otomatis: <strong>(Tugas + UTS + UAS) / 3</strong>. Kosongkan kolom yang belum ada nilainya.
</div>

<form action="{{ route('guru.nilai.store') }}" method="POST">
    @csrf
    <input type="hidden" name="mata_pelajaran_id" value="{{ $assignment->mata_pelajaran_id }}">

    <div class="card shadow">
        <div class="card-header py-3 d-flex align-items-center justify-content-between" style="background:white;border-bottom:2px solid #f8f9fc;">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-users mr-2"></i>Daftar Siswa
                <span class="badge badge-warning ml-2">{{ $siswaKelas->count() }} siswa</span>
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="4%">#</th>
                            <th>Nama Siswa</th>
                            <th class="text-center" width="12%">Tugas (0-100)</th>
                            <th class="text-center" width="12%">UTS (0-100)</th>
                            <th class="text-center" width="12%">UAS (0-100)</th>
                            <th class="text-center" width="10%">Nilai Akhir</th>
                            <th width="18%">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswaKelas as $idx => $sk)
                        @php $existing = $existingNilai[$sk->siswa_id] ?? null; @endphp
                        <tr>
                            <td class="text-muted small">{{ $idx + 1 }}</td>
                            <td>
                                <div class="font-weight-bold">{{ $sk->siswa->nama_lengkap }}</div>
                                <div class="small text-muted">{{ $sk->siswa->nisn }}</div>
                            </td>
                            <td>
                                <input type="number" name="nilais[{{ $sk->siswa_id }}][nilai_tugas]"
                                       class="form-control form-control-sm text-center nilai-input"
                                       value="{{ $existing?->nilai_tugas }}"
                                       min="0" max="100" step="0.5"
                                       data-row="{{ $idx }}">
                            </td>
                            <td>
                                <input type="number" name="nilais[{{ $sk->siswa_id }}][nilai_uts]"
                                       class="form-control form-control-sm text-center nilai-input"
                                       value="{{ $existing?->nilai_uts }}"
                                       min="0" max="100" step="0.5"
                                       data-row="{{ $idx }}">
                            </td>
                            <td>
                                <input type="number" name="nilais[{{ $sk->siswa_id }}][nilai_uas]"
                                       class="form-control form-control-sm text-center nilai-input"
                                       value="{{ $existing?->nilai_uas }}"
                                       min="0" max="100" step="0.5"
                                       data-row="{{ $idx }}">
                            </td>
                            <td class="text-center">
                                <span id="avg-{{ $idx }}" class="font-weight-bold h5 mb-0
                                    {{ $existing?->nilai_akhir >= $assignment->mataPelajaran->kkm ? 'text-success' : ($existing?->nilai_akhir ? 'text-danger' : 'text-muted') }}">
                                    {{ $existing?->nilai_akhir ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <input type="text" name="nilais[{{ $sk->siswa_id }}][catatan]"
                                       class="form-control form-control-sm"
                                       value="{{ $existing?->catatan }}"
                                       placeholder="Opsional">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <button type="submit" class="btn btn-warning px-4">
                <i class="fas fa-save mr-2"></i>Simpan Nilai & Kirim Notifikasi
            </button>
            <a href="{{ route('guru.nilai.index') }}" class="btn btn-outline-secondary ml-2">Batal</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const kkm = {{ $assignment->mataPelajaran->kkm }};

document.querySelectorAll('.nilai-input').forEach(function(input) {
    input.addEventListener('input', function() {
        const row = this.dataset.row;
        const inputs = document.querySelectorAll(`.nilai-input[data-row="${row}"]`);
        const vals = Array.from(inputs).map(i => parseFloat(i.value)).filter(v => !isNaN(v));
        const avgEl = document.getElementById('avg-' + row);
        if (vals.length > 0) {
            const avg = (vals.reduce((a,b) => a+b, 0) / vals.length).toFixed(1);
            avgEl.textContent = avg;
            avgEl.className = 'font-weight-bold h5 mb-0 ' + (parseFloat(avg) >= kkm ? 'text-success' : 'text-danger');
        } else {
            avgEl.textContent = '-';
            avgEl.className = 'font-weight-bold h5 mb-0 text-muted';
        }
    });
});
</script>
@endpush
