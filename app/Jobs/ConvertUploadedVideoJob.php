<?php

namespace App\Jobs;

use App\Video;
use FFMpeg;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Filesystem\Media;
use ProtoneMedia\LaravelFFMpeg\MediaOpener;

class ConvertUploadedVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Video
     */
    private $video;
    /**
     * @var string
     */
    private $videoId;
    /**
     * @var string
     */
    private $slug;
    /**
     * @var bool
     */
    private $watermark;

    /**
     * Create a new job instance.
     *
     * @param Video $video
     * @param string $videoId
     * @param string $slug
     * @param bool $watermark
     */
    public function __construct(Video $video, string $videoId, string $slug, bool $watermark)
    {
        $this->video = $video;
        $this->videoId = $videoId;
        $this->slug = $slug;
        $this->watermark = $watermark;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tempPath = 'temp/' . $this->videoId;
        if ($this->video->trashed() || !Video::where('id', $this->video->id)->count()) {
            Storage::disk('video')->delete($tempPath);
            return;
        }
        $videoFile = FFMpeg::fromDisk('video')->open($tempPath);
        if ($this->watermark) {
            $filter = new CustomFilter("drawtext=text='http\\://www.webamooz.net': box=1: boxborderw=5: fontsize=25: x=10: y=(h - text_h - 10):
            boxcolor=white@.5");
            $videoFile = $videoFile->addFilter($filter);
        }

        $format = new X264('libmp3lame');
        $convertedVideo = $videoFile->export()
            ->toDisk('video')
            ->inFormat($format)
            ->save($this->slug . '.mp4');

        $duration = $videoFile->getDurationInSeconds();
        $this->video->update([
            'duration' => $duration,
            'state' => Video::STATE_CONVERTED
        ]);
        //move video and banner
//        $convertedVideo->save($this->slug . '.mp4');
        Storage::disk('video')->delete($tempPath);
    }
}
