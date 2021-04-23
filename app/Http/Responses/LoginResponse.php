<?php

namespace App\Http\Responses;

use App\Models\User;
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
        $home = '/user/profile';

        if ($user->hasPermission('dashboard.view')) {
            $home = '/dashboard';
        }
        if ($user->is_admin) {
            $home = '/admin';
        }

        // first user installs authentication
        if (User::count() === 1) {
            $home = '/admin/auth/install';
        }

        return redirect()->intended($home);
    }
}
