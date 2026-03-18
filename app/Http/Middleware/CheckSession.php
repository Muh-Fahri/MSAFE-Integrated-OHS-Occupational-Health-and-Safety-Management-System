<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // PENTING: Jangan redirect lockscreen ke lockscreen (infinite loop)
        if ($request->routeIs('lockscreen.show') || $request->routeIs('lockscreen.unlock')) {
            return $next($request);
        }
    
        // Cek apakah user authenticated
        if (Auth::check()) {
            // Cek apakah ada locked cookie (manual lock)
            if (Cookie::get('locked_user')) {
                return redirect()->route('lockscreen.show');
            }
        
            // Session masih aktif, lanjut
            return $next($request);
        }
    
        // Cek apakah ada user data di cookie (session expired tapi cookie masih ada)
        if (Cookie::get('lockscreen_user')) {
            // Session expired, tapi user pernah login -> lockscreen
            return redirect()->route('lockscreen.show');
        }
    
        // User belum login sama sekali -> ke login
        return redirect()->route('login');
    }
}
