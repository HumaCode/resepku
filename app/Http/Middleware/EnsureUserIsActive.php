<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_active === '0') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.',
                    'errors' => [
                        'username' => ['Akun Anda dinonaktifkan. Silakan hubungi administrator.']
                    ]
                ], 422);
            }

            return redirect()->route('login')->with('toast_error', 'Akun Anda dinonaktifkan. Silakan hubungi administrator.');
        }

        return $next($request);
    }
}
