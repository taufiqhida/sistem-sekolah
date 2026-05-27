<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $home = route('admin.dashboard');
        } elseif ($user->hasRole('guru')) {
            $home = route('guru.dashboard');
        } elseif ($user->hasRole('siswa')) {
            $home = route('siswa.dashboard');
        } else {
            $home = '/';
        }

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended($home);
    }
}
