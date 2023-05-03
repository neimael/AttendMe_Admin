<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


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
        Validator::extend('time', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $value);
        });
    }
}
