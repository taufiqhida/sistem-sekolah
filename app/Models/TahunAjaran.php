<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'is_aktif'];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function semesterAktif()
    {
        return $this->hasOne(Semester::class)->where('is_aktif', true);
    }

    public static function getAktif()
    {
        return static::where('is_aktif', true)->first();
    }
}
