<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\PemesananObserver;
use App\Models\Pemesanan;

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
        Pemesanan::observe(PemesananObserver::class);
    }
}
