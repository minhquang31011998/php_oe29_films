<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Rate\RateRepository;
use App\Repositories\Rate\RateRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            RateRepositoryInterface::class,
            RateRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
