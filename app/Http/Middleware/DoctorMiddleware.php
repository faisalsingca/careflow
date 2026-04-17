<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DoctorMiddleware
{
    /**
     * Handle an incoming request.
     */
   public function handle(Request $request, Closure $next): Response
{
    if (!Auth::check()) return redirect('/login');
    if (!in_array(Auth::user()->role, ['admin', 'doctor'])) {
        abort(403, 'Doctor or Admin access required.');
    }
    return $next($request);
}
}