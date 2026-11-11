<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Perlindungan ketat: jika dosen mencoba mengakses rute admin, tolak dengan 403
        if (auth()->check() && auth()->user()->role === 'dosen') {
            abort(403, 'Akses Terlarang.');
        }

        $isAdmin = (auth()->check() && in_array(auth()->user()->role, ['admin', 'admin_lppm']))
            || session('admin_logged_in')
            || in_array(session('user_role'), ['admin', 'admin_lppm']);

        if (!$isAdmin) {
            abort(403, 'Akses Terlarang.');
        }

        return $next($request);
    }
}
