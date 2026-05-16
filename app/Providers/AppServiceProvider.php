<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function (Login $event): void {
            activity('auth')
                ->causedBy($event->user)
                ->event('login')
                ->withProperties([
                    'ip' => request()?->ip(),
                    'user_agent' => request()?->userAgent(),
                ])
                ->log('User login');
        });

        Event::listen(Logout::class, function (Logout $event): void {
            if (! $event->user) {
                return;
            }

            activity('auth')
                ->causedBy($event->user)
                ->event('logout')
                ->withProperties([
                    'ip' => request()?->ip(),
                    'user_agent' => request()?->userAgent(),
                ])
                ->log('User logout');
        });

        Activity::created(static function (): void {
            $overflowCount = Activity::query()->count() - 50;

            if ($overflowCount <= 0) {
                return;
            }

            $overflowIds = Activity::query()
                ->orderBy('id')
                ->limit($overflowCount)
                ->pluck('id');

            if ($overflowIds->isNotEmpty()) {
                Activity::query()->whereIn('id', $overflowIds)->delete();
            }
        });
    }
}
