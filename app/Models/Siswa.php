<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nisn', 'nis', 'nama_lengkap', 'jenis_kelamin',
        'tanggal_lahir', 'tempat_lahir', 'alamat', 'no_hp',
        'nama_ortu', 'foto', 'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswaKelas()
    {
        return $this->hasMany(SiswaKelas::class);
    }

    public function kelasAktif()
    {
        $semesterAktif = Semester::getAktif();
        if (!$semesterAktif) return null;
        return $this->hasOne(SiswaKelas::class)->where('semester_id', $semesterAktif->id)->with('kelas');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-avatar.png');
    }

    // Rekap absensi untuk semester tertentu
    public function rekapAbsensi($semesterId = null)
    {
        $query = $this->absensis();
        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }
        return [
            'hadir' => $query->clone()->where('status', 'Hadir')->count(),
            'izin'  => $query->clone()->where('status', 'Izin')->count(),
            'sakit' => $query->clone()->where('status', 'Sakit')->count(),
            'alfa'  => $query->clone()->where('status', 'Alfa')->count(),
        ];
    }
}
