<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $currentRole = session('user_role', 'guest');

        // If session admin_logged_in is active, grant admin_lppm role
        if (session()->has('admin_logged_in') && !in_array('admin_lppm', $roles)) {
            // Admin can access
            return $next($request);
        }

        if (!in_array($currentRole, $roles) && !session()->has('admin_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Anda tidak memiliki wewenang role untuk halaman ini.');
        }

        return $next($request);
    }
}
