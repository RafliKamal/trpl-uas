<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user || !in_array($user->roleName, $roles)) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        return $next($request);
    }
}