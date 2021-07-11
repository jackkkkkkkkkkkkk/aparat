<?php

namespace App\Providers;

use App\Observers\UserObserver;
use App\Observers\VideoObserver;
use App\User;
use App\Video;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(new UserObserver());
        Video::observe(new VideoObserver());
    }
}
