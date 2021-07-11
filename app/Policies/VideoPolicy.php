<?php

namespace App\Policies;

use App\User;
use App\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    public function republish(User $user, Video $video)
    {
        return $video &&
            !$user->republishedVideo()->where('video_id', $video->id)->count() &&
            $video->user_id != $user->id;
    }

    public function like(User $user = null, Video $video = null)
    {
        return $video && $video->isAccepted();
    }

    public function delete(User $user, Video $video)
    {
        return $user->id === $video->user_id || $user->republishedVideo()->where('video_republishes.video_id', $video->id)->count();
    }

    public function update(User $user, Video $video)
    {
        return $video->user_id === $user->id;
    }
}
