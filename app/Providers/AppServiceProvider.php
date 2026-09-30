<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        // Answer with a form error instead of a bare 429 page so the enquiry
        // form can show the message in place.
        RateLimiter::for('enquiries', fn (Request $request) => Limit::perMinutes(10, 5)
            ->by($request->ip())
            ->response(fn () => back()->withErrors([
                'form' => 'Too many enquiries were sent from this connection. Try again in a few minutes, or call us.',
            ])));
    }
}
