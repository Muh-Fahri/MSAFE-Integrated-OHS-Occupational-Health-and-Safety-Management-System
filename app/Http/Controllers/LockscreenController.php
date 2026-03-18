<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LockscreenController extends Controller
{
    /**
     * Show lockscreen page
     */
    public function show()
    {
        // ambil data user dari cookie
        $userDataCookie = Cookie::get('lockscreen_user');

        if (! $userDataCookie) {
            // jika tidak ada cookie, redirect ke login
            return redirect()->route('login');
        }

        try {
            $userData = decrypt($userDataCookie);

            // ambil data user dari database
            $user = User::find($userData['id']);

            if (! $user) {
                // jika user tidak ditemukan, hapus cookie dan redirect ke login
                Cookie::queue(Cookie::forget('lockscreen_user'));

                return redirect()->route('login');
            }

            return view('auth.lockscreen', compact('user'));

        } catch (\Exception $e) {
            // cookie rusak atau dekripsi gagal redirect ke login
            Cookie::queue(Cookie::forget('lockscreen_user'));

            return redirect()->route('login');
        }
    }

    /**
     * Handle unlock request
     */
    public function unlock(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        // ambil data user dari cookie
        $userDataCookie = Cookie::get('lockscreen_user');

        if (! $userDataCookie) {
            return redirect()->route('login');
        }

        try {
            $userData = decrypt($userDataCookie);
            $user = User::find($userData['id']);

            if (! $user) {
                Cookie::queue(Cookie::forget('lockscreen_user'));

                return redirect()->route('login');
            }

            // verifikasi password
            if (Hash::check($request->password, $user->password)) {
                // Password correct, restore session
                Auth::login($user);
                $request->session()->regenerate();

                // hapus locked cookie
                Cookie::queue(Cookie::forget('locked_user'));

                // redirect ke intended url atau dashboard
                return redirect()->intended(route('dashboard'));
            }

            // password salah
            throw ValidationException::withMessages([
                'password' => 'Password salah.',
            ]);

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Cookie::queue(Cookie::forget('lockscreen_user'));

            return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        // Clear semua session dan cookie
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        $lockscreenCookie = Cookie::forget('lockscreen_user');
        $lockedCookie = Cookie::forget('locked_user');
    
        return redirect()->route('login')
            ->withCookie($lockscreenCookie)
            ->withCookie($lockedCookie);
    }
}
