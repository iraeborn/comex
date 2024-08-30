<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Http\Libs\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Facades\Validator;
use Laravel\Telescope\TelescopeServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('white_space', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        Validator::extend('special_chars', function ($attr, $value) {
            return preg_match('/\A(?!.*[:;]-\))[ -~]+\z/', $value);
        });

        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        
        $this->app->singleton(Pdf::class, function ($app) {
            return new Pdf($app->make(DomPDFPDF::class));
        });
    }
}
