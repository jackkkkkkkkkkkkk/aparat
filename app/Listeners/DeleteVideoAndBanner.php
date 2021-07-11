<?php

namespace App\Listeners;

use App\Events\VideoDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class DeleteVideoAndBanner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  VideoDeleted  $event
     * @return void
     */
    public function handle(VideoDeleted $event)
    {
        Storage::disk('video')->delete($event->getVideo()->slug . '.mp4');
        Storage::disk('video')->delete($event->getVideo()->banner);
    }
}
