<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperadminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Bypass untuk superadmin
        if (auth()->check() && auth()->user()->hasRole('superadmin')) {
            return $next($request);
        }
        
        // Check permission untuk user lain
        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'Unauthorized action.');
        }
        
        return $next($request);
    }
}
