<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireRole
{
    public function handle(Request $request, Closure $next, string $roles): mixed
    {
        $user = $request->user();
        $allowed = array_filter(array_map('trim', explode('|', $roles)));

        if ($user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return $next($request);
        }

        if (! $user || empty($allowed) || ! $user->hasAnyRole($allowed)) {
            abort(403);
        }

        return $next($request);
    }
}

