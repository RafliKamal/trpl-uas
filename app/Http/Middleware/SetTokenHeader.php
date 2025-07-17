<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class SetTokenHeader
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('token')) {
            Http::withToken(Session::get('token'));
        }

        return $next($request);
    }
}