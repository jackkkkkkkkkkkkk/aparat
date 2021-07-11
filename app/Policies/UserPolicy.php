<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function follow(User $following, User $followed)
    {
        return $followed->id != $following->id && !$following->following()->where('user_id2', $followed->id)->count();
    }

    public function unFollow(user $unFollowing, User $unFollowed)
    {
        return $unFollowing->id != $unFollowed->id && $unFollowing->following()->where('user_id2', $unFollowed->id)->count();
    }
}
