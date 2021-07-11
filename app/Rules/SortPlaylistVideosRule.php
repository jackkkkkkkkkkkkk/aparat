<?php

namespace App\Rules;

use App\Playlist;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use const Grpc\STATUS_OUT_OF_RANGE;

class SortPlaylistVideosRule implements Rule
{
    /**
     * @var Playlist
     */
    private $playlist;

    /**
     * Create a new rule instance.
     *
     * @param Playlist $playlist
     */
    public function __construct(Playlist $playlist)
    {
        //
        $this->playlist = $playlist;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $video = $this->playlist->video()->pluck('videos.id')->toArray();
        sort($video);
        $data = array_map('intval', $value);
        sort($data);
        return $data === $video;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'لیست ویدیو ها معتبر نمی باشد';
    }
}
