<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->hasRole('admin') && !$request->is('admin/*')) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->hasRole('guru') && !$request->is('guru/*')) {
                return redirect()->route('guru.dashboard');
            }

            if ($user->hasRole('siswa') && !$request->is('siswa/*')) {
                return redirect()->route('siswa.dashboard');
            }
        }

        return $next($request);
    }
}
