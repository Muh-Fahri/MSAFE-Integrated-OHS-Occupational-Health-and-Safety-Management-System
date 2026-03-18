<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use App\Models\AuditLog;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/PermissionHelper.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('microsoft', \SocialiteProviders\Microsoft\Provider::class);
        });
        // Login success
        Event::listen(Login::class, function (Login $event) {
            AuditLog::insert([
                'timestamp' => now(),
                'user_id'   => $event->user->email,
                'action'    => 'LOGIN',
                'resource'  => 'auth/login',
                'status'    => 'Success',
                'details'   => json_encode(['user_id' => $event->user->id, 'guard' => $event->guard])
            ]);
        });

        // Logout
        Event::listen(Logout::class, function (Logout $event) {
            AuditLog::insert([
                'timestamp' => now(),
                'user_id'   => $event->user->email ?? 'Unknown',
                'action'    => 'LOGOUT',
                'resource'  => 'auth/logout',
                'status'    => 'Success',
                'details'   => json_encode(['user_id' => $event->user->id ?? null, 'guard' => $event->guard])
            ]);
        });

        // Login failed
        Event::listen(Failed::class, function (Failed $event) {
            AuditLog::insert([
                'timestamp' => now(),
                'user_id'   => $event->credentials['email'] ?? $event->credentials['username'] ?? 'Unknown',
                'action'    => 'LOGIN',
                'resource'  => 'auth/login',
                'status'    => 'Failed',
                'details'   => json_encode(['reason' => 'Invalid credentials', 'guard' => $event->guard])
            ]);
        });
    }
}
