<?php

namespace App\Observers;

use App\Video;
use Illuminate\Support\Facades\Storage;

class VideoObserver
{
    /**
     * Handle the video "created" event.
     *
     * @param \App\Video $video
     * @return void
     */
    public function created(Video $video)
    {
        //
    }

    /**
     * Handle the video "updated" event.
     *
     * @param \App\Video $video
     * @return void
     */
    public function updated(Video $video)
    {
        //
    }

    /**
     * Handle the video "deleted" event.
     *
     * @param \App\Video $video
     * @return void
     */
    public function forceDeleted(Video $video)
    {
//        Storage::disk('video')->delete($video->slug . '.mp4');
//        Storage::disk('video')->delete($video->slug . '_banner');
    }

    /**
     * Handle the video "restored" event.
     *
     * @param \App\Video $video
     * @return void
     */
    public function restored(Video $video)
    {
        //
    }

//    /**
//     * Handle the video "force deleted" event.
//     *
//     * @param \App\Video $video
//     * @return void
//     */
//    public function deleted(Video $video)
//    {
//        Storage::disk('video')->delete($video->slug);
//        Storage::disk('video')->delete($video->slug . '_banner');
//    }
}
