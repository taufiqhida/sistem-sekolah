<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilais';

    protected $fillable = [
        'siswa_id', 'mata_pelajaran_id', 'guru_id', 'semester_id',
        'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'predikat', 'catatan',
    ];

    protected $casts = [
        'nilai_tugas'  => 'decimal:2',
        'nilai_uts'    => 'decimal:2',
        'nilai_uas'    => 'decimal:2',
        'nilai_akhir'  => 'decimal:2',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // Auto-hitung nilai akhir & predikat sebelum save
    public static function boot()
    {
        parent::boot();

        static::saving(function ($nilai) {
            if ($nilai->nilai_tugas !== null || $nilai->nilai_uts !== null || $nilai->nilai_uas !== null) {
                $avg = collect([$nilai->nilai_tugas, $nilai->nilai_uts, $nilai->nilai_uas])
                    ->filter()
                    ->avg();
                $nilai->nilai_akhir = round($avg, 2);
                $nilai->predikat = match (true) {
                    $avg >= 90 => 'A',
                    $avg >= 80 => 'B',
                    $avg >= 70 => 'C',
                    $avg >= 60 => 'D',
                    default    => 'E',
                };
            }
        });
    }
}
