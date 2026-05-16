@extends('layouts.app')
@section('title', 'Edit Guru')

@section('sidebar-menu')
<li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Manajemen Data</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.siswas.index') }}"><i class="fas fa-fw fa-user-graduate"></i><span>Data Siswa</span></a></li>
<li class="nav-item active"><a class="nav-link" href="{{ route('admin.gurus.index') }}"><i class="fas fa-fw fa-chalkboard-teacher"></i><span>Data Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.kelas.index') }}"><i class="fas fa-fw fa-school"></i><span>Data Kelas</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.mata-pelajarans.index') }}"><i class="fas fa-fw fa-book"></i><span>Mata Pelajaran</span></a></li>
<hr class="sidebar-divider"><div class="sidebar-heading text-white-50">Akademik</div>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.assign-guru.index') }}"><i class="fas fa-fw fa-link"></i><span>Assign Guru</span></a></li>
<li class="nav-item"><a class="nav-link" href="{{ route('admin.tahun-ajarans.index') }}"><i class="fas fa-fw fa-calendar-alt"></i><span>Tahun Ajaran</span></a></li>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800 font-weight-bold"><i class="fas fa-user-edit text-warning mr-2"></i>Edit Guru</h1>
    <a href="{{ route('admin.gurus.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
</div>

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-triangle mr-2"></i><strong>Terjadi kesalahan:</strong>
    <ul class="mb-0 mt-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<form action="{{ route('admin.gurus.update', $guru) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-user mr-2"></i>Data Guru — {{ $guru->nama_lengkap }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="small font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                       value="{{ old('nama_lengkap', $guru->nama_lengkap) }}" required>
                                @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">NIP</label>
                                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror"
                                       value="{{ old('nip', $guru->nip) }}">
                                @error('nip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select name="jenis_kelamin" class="form-control" required>
                                    <option value="L" {{ old('jenis_kelamin', $guru->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $guru->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                       value="{{ old('tanggal_lahir', $guru->tanggal_lahir?->format('Y-m-d')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold">No HP</label>
                                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $guru->no_hp) }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $guru->alamat) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Status</label>
                        <select name="status" class="form-control">
                            <option value="Aktif" {{ old('status', $guru->status) === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ old('status', $guru->status) === 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-lock mr-2"></i>Ubah Akun Login <small class="text-muted">(kosongkan jika tidak diubah)</small></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $guru->user->email) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small font-weight-bold">Password Baru</label>
                                <input type="password" name="password" class="form-control" minlength="6" placeholder="Kosongkan jika tidak diubah">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3" style="background:white;border-bottom:2px solid #f8f9fc;">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-camera mr-2"></i>Foto Profil</h6>
                </div>
                <div class="card-body text-center">
                    @if($guru->foto)
                        <img id="preview-img" src="{{ asset('storage/'.$guru->foto) }}"
                             style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin:0 auto 12px;display:block;border:2px solid #1cc88a;">
                        <div id="preview-placeholder" style="display:none;"></div>
                    @else
                        <div id="preview-placeholder" style="width:120px;height:120px;border-radius:50%;background:#f8f9fc;border:2px dashed #d1d3e2;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="fas fa-user fa-3x text-gray-300"></i>
                        </div>
                        <img id="preview-img" src="" style="width:120px;height:120px;border-radius:50%;object-fit:cover;margin:0 auto 12px;display:none;border:2px solid #1cc88a;">
                    @endif
                    <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                    <label for="foto" class="btn btn-outline-success btn-sm"><i class="fas fa-upload mr-2"></i>Ganti Foto</label>
                </div>
            </div>
            <button type="submit" class="btn btn-warning btn-block py-3 text-white">
                <i class="fas fa-save mr-2"></i><strong>Simpan Perubahan</strong>
            </button>
        </div>
    </div>
</form>
@endsection
@push('scripts')
<script>
document.getElementById('foto').addEventListener('change', function(e) {
    const reader = new FileReader();
    reader.onload = function(evt) {
        document.getElementById('preview-placeholder').style.display = 'none';
        const img = document.getElementById('preview-img');
        img.src = evt.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(e.target.files[0]);
});
</script>
@endpush
