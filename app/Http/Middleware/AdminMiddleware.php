<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
{
public function handle($request, Closure $next): mixed
{
    if (Auth::check() && Auth::user()->is_admin == 1) {
        return $next($request);
    }

    abort(403); // o redirect('/login')
}

}