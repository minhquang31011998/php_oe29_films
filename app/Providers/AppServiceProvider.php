<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Rate\RateRepository;
use App\Repositories\Rate\RateRepositoryInterface;
use App\Repositories\Channel\ChannelRepository;
use App\Repositories\Channel\ChannelRepositoryInterface;
use App\Repositories\Movie\MovieRepository;
use App\Repositories\Movie\MovieRepositoryInterface;
use App\Repositories\Playlist\PlaylistRepository;
use App\Repositories\Playlist\PlaylistRepositoryInterface;

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
        $this->app->singleton(
            ChannelRepositoryInterface::class,
            ChannelRepository::class
        );
        $this->app->singleton(
            MovieRepositoryInterface::class,
            MovieRepository::class
        );
        $this->app->singleton(
            PlaylistRepositoryInterface::class,
            PlaylistRepository::class
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
