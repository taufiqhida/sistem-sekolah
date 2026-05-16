<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasis';

    protected $fillable = ['user_id', 'judul', 'pesan', 'tipe', 'url', 'dibaca_at'];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead(): void
    {
        $this->update(['dibaca_at' => now()]);
    }

    public function getIsBelumDibacaAttribute(): bool
    {
        return is_null($this->dibaca_at);
    }

    public static function kirim(int $userId, string $judul, string $pesan, string $tipe = 'info', ?string $url = null): self
    {
        return static::create([
            'user_id' => $userId,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'tipe'    => $tipe,
            'url'     => $url,
        ]);
    }
}
