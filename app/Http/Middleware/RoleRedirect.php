<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->hasRole('superadmin')) {
            return redirect('/dashboard/superadmin');
        }

        if ($user->hasRole('seller')) {
            return redirect('/dashboard/seller');
        }

        if ($user->hasRole('user')) {
            return redirect('/dashboard/user');
        }

        return $next($request);
    }
}
