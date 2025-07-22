<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    /**
     * Handle unauthenticated requests.
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson()) {
            // Untuk request API (expects JSON), kirimkan response JSON 401
            abort(response()->json(['message' => 'Unauthenticated.'], 401));
        }

        // Untuk akses web biasa, tetap redirect ke login
        redirect()->guest(route('login'));
    }
}
