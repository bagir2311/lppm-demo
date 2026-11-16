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
        // Jika belum login sama sekali, arahkan ke login admin
        if (!auth()->check() && !session('admin_logged_in') && !session('user_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses panel admin.');
        }

        $user = auth()->user();

        // Perlindungan ketat: jika dosen atau reviewer mencoba mengakses rute admin, tolak dengan 403
        if ($user && in_array($user->role, ['dosen', 'reviewer'])) {
            abort(403, 'Akses Terlarang. Panel ini hanya dapat diakses oleh Administrator dan Staf LPPM.');
        }

        // Role yang berwenang mengakses modul admin: admin, admin_lppm, staf, staff
        $allowedRoles = ['admin', 'admin_lppm', 'staf', 'staff'];
        $hasAccess = ($user && in_array($user->role, $allowedRoles))
            || session('admin_logged_in')
            || in_array(session('user_role'), $allowedRoles);

        if (!$hasAccess) {
            abort(403, 'Akses Terlarang. Anda tidak memiliki izin untuk mengakses modul ini.');
        }

        return $next($request);
    }
}
