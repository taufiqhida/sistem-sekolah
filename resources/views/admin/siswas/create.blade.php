@extends('layouts.app')
@section('title', 'Tambah Siswa')

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
    <div>
        <h1 class="h3 mb-1 text-gray-800 font-weight-bold"><i class="fas fa-user-plus text-primary mr-2"></i>Tambah Siswa Baru</h1>
    </div>
    <a href="{{ route('admin.siswas.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
</div>

<form action="{{ route('admin.siswas.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user mr-2"></i>Data Pribadi</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">NISN <span class="text-danger">*</span></label>
                                <input type="text" name="nisn" class="form-control" value="{{ old('nisn') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">NIS</label>
                                <input type="text" name="nis" class="form-control" value="{{ old('nis') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-gray-700">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ old('alamat') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">No HP</label>
                                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">Nama Orang Tua</label>
                                <input type="text" name="nama_ortu" class="form-control" value="{{ old('nama_ortu') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-gray-700">Status</label>
                        <select name="status" class="form-control">
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-lock mr-2"></i>Akun Login</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold text-gray-700">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required minlength="6">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-school mr-2"></i>Kelas & Semester</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="small font-weight-bold text-gray-700">Kelas <span class="text-danger">*</span></label>
                        <select name="kelas_id" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold text-gray-700">Semester <span class="text-danger">*</span></label>
                        <select name="semester_id" class="form-control" required>
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semesters as $s)
                            <option value="{{ $s->id }}" {{ $s->is_aktif ? 'selected' : '' }}>
                                {{ $s->tahunAjaran->nama }} {{ $s->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-camera mr-2"></i>Foto Profil</h6>
                </div>
                <div class="card-body text-center">
                    <div id="preview-container" class="mb-3">
                        <div id="preview-placeholder" style="width:120px;height:120px;border-radius:50%;background:#f8f9fc;border:2px dashed #d1d3e2;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            <i class="fas fa-user fa-3x text-gray-300"></i>
                        </div>
                        <img id="preview-img" src="" style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin:0 auto;display:none;border:2px solid #4e73df;">
                    </div>
                    <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                    <label for="foto" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-upload mr-2"></i>Upload Foto
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block py-3">
                <i class="fas fa-save mr-2"></i><strong>Simpan Siswa</strong>
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(evt) {
        document.getElementById('preview-placeholder').style.display = 'none';
        const img = document.getElementById('preview-img');
        img.src = evt.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
