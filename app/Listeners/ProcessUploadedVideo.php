<?php

namespace App\Listeners;

use App\Events\UploadNewVideo;
use App\Jobs\ConvertUploadedVideoJob;
use Illuminate\Support\Facades\Storage;

class ProcessUploadedVideo
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
     * @param UploadNewVideo $event
     * @return void
     */
    public function handle(UploadNewVideo $event)
    {
        $bannerId = null;
        $slug = uniqueId($event->getVideo()->id);
        if ($event->getRequest()['banner_id']) {
            $bannerId = $slug . '_banner';
            Storage::disk('video')->move('/temp/' . $event->getRequest()['banner_id'], $bannerId);
        }
        $event->getVideo()->update([
            'slug' => $slug,
            'banner' => $bannerId,
        ]);
        $videoId = $event->getRequest()['video_id'];
        ConvertUploadedVideoJob::dispatch($event->getVideo(), $videoId, $slug, $event->getRequest()['enable_watermark']);
    }
}
