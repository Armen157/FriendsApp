<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Friends implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $friendship_id;
    public $user_receiver_id;
    public $name;
    public $lastname;

    public function __construct($friendship_id,$user_receiver_id,$name,$lastname)
    {
        $this->friendship_id = $friendship_id;
        $this->user_receiver_id = $user_receiver_id;
        $this->name = $name;
        $this->lastname=$lastname;
    }

    public function broadcastOn()
    {
        return new channel('my-channel');
    }

    public function broadcastAs()
    {
        return 'Friends';
    }
}
