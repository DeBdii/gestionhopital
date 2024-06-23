<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                if ($user->user_type == 'Administrator') {
                    return redirect('/admin/dashboard');
                } elseif ($user->user_type == 'Doctor') {
                    return redirect('/doctor/dashboard');
                } elseif ($user->user_type == 'Receptionist') {
                    return redirect('/receptionist/dashboard');
                } else {
                    return redirect('/dashboard');
                }
            }
        }

        return $next($request);
    }
}
