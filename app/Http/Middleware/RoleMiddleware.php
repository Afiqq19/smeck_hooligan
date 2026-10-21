<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles)) {
            // Redirect each role to their respective authorized area
            return match ($request->user()->role) {
                'super_admin' => redirect()->route('superadmin.dashboard'),
                'admin_chapter' => redirect()->route('chapter.dashboard'),
                default => redirect()->route('member.dashboard'),
            };
        }

        return $next($request);
    }
}
