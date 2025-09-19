<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            if ($role === 'admin' && !$request->is('admin/*')) {
                return redirect()->route('admin.dashboard');
            }

            if ($role === 'user' && !$request->is('user/*')) {
                return redirect()->route('user.dashboard');
            }
        }
        return $next($request);
    }
}
