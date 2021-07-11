<?php

namespace App\Events;

use App\Video;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadNewVideo
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Video
     */
    private $video;
    /**
     * @var array
     */
    private $request;


    /**
     * Create a new event instance.
     *
     * @param Video $video
     * @param $request
     */
    public function __construct(Video $video,array $request)
    {
        //
        $this->video = $video;
        $this->request = $request;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return Video
     */
    public function getVideo(): Video
    {
        return $this->video;
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
    }


}
