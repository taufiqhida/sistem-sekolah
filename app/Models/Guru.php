<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nip', 'nama_lengkap', 'jenis_kelamin',
        'tanggal_lahir', 'alamat', 'no_hp', 'foto', 'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guruKelasMapels()
    {
        return $this->hasMany(GuruKelasMapel::class);
    }

    public function kelas()
    {
        return $this->hasManyThrough(Kelas::class, GuruKelasMapel::class, 'guru_id', 'id', 'id', 'kelas_id');
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
}
