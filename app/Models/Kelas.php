<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama', 'tingkat', 'jurusan', 'kapasitas'];

    public function siswas()
    {
        return $this->hasManyThrough(Siswa::class, SiswaKelas::class, 'kelas_id', 'id', 'id', 'siswa_id');
    }

    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class);
    }

    public function guruKelasMapels()
    {
        return $this->hasMany(GuruKelasMapel::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
