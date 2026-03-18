<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = Auth::user();

        $throttleKey = Str::lower($request->username).'|'.$request->ip();

        // cek rate percobaan login
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // login attempt
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);

            // simpan data user di cookie untuk keperluan lockscreen
            $user = Auth::user();
            $userData = encrypt([
                'id'        => $user->id,
                'username'  => $user->username,
                'name'      => $user->name,
                'photo'     => $user->photo,
            ]);

            Cookie::queue('lockscreen_user', $userData, 60 * 24 * 7); // 7 days

            return redirect()->intended(route('dashboard'));
        }

        // login gagal - disuspend 1 menit
        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'username'      => 'Username atau password salah.',
        ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // clear lockscreen cookies
        $lockscreenCookie = Cookie::forget('lockscreen_user');
        $lockedCookie = Cookie::forget('locked_user');

        return redirect()->route('login')
            ->withCookie($lockscreenCookie)
            ->withCookie($lockedCookie);
    }

    /**
     * Handle manual lock screen
     */
    public function lock(Request $request)
    {
        // simpan url sebelumnya untuk redirect setelah unlock
        session(['url.intended' => url()->previous()]);

        // set cookie locked_user
        Cookie::queue('locked_user', 'true', 60 * 24); // 24 hours

        return redirect()->route('lockscreen.show');
    }
}
