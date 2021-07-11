<?php

namespace App\Listeners;

use App\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Events\AccessTokenCreated;

class ActiveUnregisteredUser
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
     * @param AccessTokenCreated $event
     * @return void
     * @throws Exception
     */
    public function handle(AccessTokenCreated $event)
    {
        $user = User::withTrashed()->find($event->userId);
        /** @var User $user */
        if ($user->trashed()) {
            try {
                DB::beginTransaction();
                $user->restore();
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
}
