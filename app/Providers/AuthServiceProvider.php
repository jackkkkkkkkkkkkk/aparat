<?php

namespace App\Providers;

use App\Comment;
use App\Playlist;
use App\Policies\CommentPolicy;
use App\Policies\PlaylistPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoPolicy;
use App\User;
use App\Video;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Video::class => VideoPolicy::class,
        User::class => UserPolicy::class,
        Comment::class => CommentPolicy::class,
        Playlist::class => PlaylistPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(2));
        //
    }
}
