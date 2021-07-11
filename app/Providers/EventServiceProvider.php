<?php

namespace App\Providers;

use App\Events\NewUserRegistered;
use App\Events\UploadNewVideo;
use App\Events\VideoDeleted;
use App\Events\VideoViewed;
use App\Listeners\ActiveUnregisteredUser;
use App\Listeners\AddViewForVideo;
use App\Listeners\DeleteVideoAndBanner;
use App\Listeners\ProcessUploadedVideo;
use App\Listeners\SendMailActivation;
use App\Listeners\SendSMSActivation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Events\AccessTokenCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewUserRegistered::class => [
            SendMailActivation::class,
            SendSMSActivation::class
        ],
        UploadNewVideo::class => [
            ProcessUploadedVideo::class
        ],
        VideoViewed::class => [
            AddViewForVideo::class
        ],
        AccessTokenCreated::class => [
            ActiveUnregisteredUser::class
        ],
        VideoDeleted::class => [
            DeleteVideoAndBanner::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
