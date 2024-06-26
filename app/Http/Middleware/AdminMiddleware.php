<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type === 'Administrator') {
            return $next($request);
        }

        return redirect('/'); // Redirect to home or another route if not authorized
    }
}