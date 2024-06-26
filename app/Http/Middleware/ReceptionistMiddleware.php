<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ReceptionistMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === 'Receptionist') {
            return $next($request);
        }

        return redirect('/'); // Redirect to home or another route if not authorized
    }
}