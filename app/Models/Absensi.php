<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    protected $fillable = [
        'siswa_id', 'kelas_id', 'guru_id', 'mata_pelajaran_id',
        'semester_id', 'tanggal', 'status', 'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Hadir' => 'success',
            'Izin'  => 'primary',
            'Sakit' => 'warning',
            'Alfa'  => 'danger',
            default => 'secondary',
        };
    }
}
