<?php

namespace App\Policies;

use App\Playlist;
use App\Services\VideoService;
use App\User;
use App\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlaylistPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function addVideo(User $user, Playlist $playlist, Video $video)
    {
        return $user->id === $playlist->user_id && $video->user_id === $user->id;
    }

    public function sort(User $user, Playlist $playlist)
    {
        return $user->id === $playlist->user_id;
    }

    public function show(User $user, Playlist $playlist)
    {
        return $user->id === $playlist->user_id;
    }
}
