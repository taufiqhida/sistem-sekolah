<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Tentukan redirect setelah login berdasarkan role
        Fortify::redirects('login', fn () => match (true) {
            auth()->user()?->hasRole('admin') => route('admin.dashboard'),
            auth()->user()?->hasRole('guru')  => route('guru.dashboard'),
            auth()->user()?->hasRole('siswa') => route('siswa.dashboard'),
            default                           => '/',
        });
    }
}
