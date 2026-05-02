<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// PERBAIKAN: Gunakan Facade URL yang benar di bawah ini
use Illuminate\Support\Facades\URL; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Memaksa skema HTTPS agar tidak terkena blocked:mixed-content
        if (config('app.env') === 'production' || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }
}