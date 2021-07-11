<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewUserRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $code;
    public $type;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param $type
     * @param $email
     * @param $phone
     */
    public function __construct(User $user,$type,$email=null,$phone=null)
    {
        $this->user=$user;
        $this->type=$type;
        $this->code=$user->createActivationCode($type,$email,$phone);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
