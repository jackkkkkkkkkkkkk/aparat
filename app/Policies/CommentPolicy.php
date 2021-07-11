<?php

namespace App\Policies;

use App\Comment;
use App\User;
use App\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @param User $user
     * @param Comment $comment
     * @param string $state
     */
    public function __construct()
    {

    }

    public function changeState(User $user, Comment $comment, string $state)
    {
        return (
            $user->id === Video::find($comment->video_id)->user_id
            &&
            (
                $state === Comment::STATE_ACCEPTED && $comment->state === Comment::STATE_READ
                ||
                $state === Comment::STATE_READ && $comment->state === Comment::STATE_PENDING
            )
        );
    }

    public function delete(User $user, Comment $comment)
    {
//        return $user->channelVideo()->where('id', $comment->video_id)->count();
        return Video::find($comment->video_id)->user_id === $user->id;
    }
}
