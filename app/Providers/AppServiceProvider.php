<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendWelcomeEmail;

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
        // Force HTTPS in production to avoid mixed content errors behind proxies
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Send welcome email on registration
        Event::listen(Registered::class, SendWelcomeEmail::class);
        
        Vite::prefetch(concurrency: 3);
    }
}
