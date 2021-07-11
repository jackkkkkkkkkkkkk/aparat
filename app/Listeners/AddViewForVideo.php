<?php

namespace App\Listeners;

use App\Events\VideoViewed;
use App\ViewVideo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddViewForVideo
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
     * @param VideoViewed $event
     * @return void
     */
    public function handle(VideoViewed $event)
    {
        $video = $event->getVideo();
        if ($user = auth('api')->user()) {
            $viewed = $user->view()->where(['video_id' => $video->id])->where('video_views.created_at', '>', now()->subDays(1))->count();
            if (!$viewed) {
                ViewVideo::create([
                    'video_id' => $video->id,
                    'user_id' => $user->id,
                    'user_ip'=>client_ip()
                ]);
            }
        } else {
            $viewed = ViewVideo::where(['video_id' => $video->id, 'user_ip' => client_ip(), ['created_at', '>', now()->subDays(1)]])->count();
            if (!$viewed) {
                ViewVideo::create([
                    'video_id' => $video->id,
                    'user_ip' => client_ip()
                ]);
            }
        }
    }
}
