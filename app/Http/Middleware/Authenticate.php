<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah ada token di session
        if (!session()->has('token')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
