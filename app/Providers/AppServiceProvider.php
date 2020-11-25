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
use App\Repositories\Source\SourceRepository;
use App\Repositories\Source\SourceRepositoryInterface;
use App\Repositories\Type\TypeRepository;
use App\Repositories\Type\TypeRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Video\VideoRepository;
use App\Repositories\Video\VideoRepositoryInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;

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
        $this->app->singleton(
            SourceRepositoryInterface::class,
            SourceRepository::class
        );
        $this->app->singleton(
            TypeRepositoryInterface::class,
            TypeRepository::class
        );
        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->singleton(
            VideoRepositoryInterface::class,
            VideoRepository::class
        );
        $this->app->singleton(
            CommentRepositoryInterface::class,
            CommentRepository::class
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
