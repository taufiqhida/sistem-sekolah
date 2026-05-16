<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = ['tahun_ajaran_id', 'nama', 'is_aktif'];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        return $this->tahunAjaran->nama . ' ' . $this->nama;
    }

    public static function getAktif()
    {
        return static::where('is_aktif', true)->with('tahunAjaran')->first();
    }
}
