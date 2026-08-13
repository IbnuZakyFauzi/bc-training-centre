<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MustChangePasswordMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->must_change_password && !$this->isPasswordChangeRoute($request)) {
            return redirect()->route('profile.edit')->with('warning', 'Anda wajib mengganti password default terlebih dahulu sebelum mengakses halaman lain.');
        }

        return $next($request);
    }

    private function isPasswordChangeRoute(Request $request): bool
    {
        return $request->routeIs('profile.update') || $request->routeIs('profile.edit') || $request->routeIs('logout');
    }
}
