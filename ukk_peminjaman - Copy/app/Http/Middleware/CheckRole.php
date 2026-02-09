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
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login?
        if (! $request->user()) {
            return redirect('login');
        }

        // 2. Cek apakah role user sesuai dengan yang diminta?
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // 3. Jika tidak sesuai, tolak akses
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
