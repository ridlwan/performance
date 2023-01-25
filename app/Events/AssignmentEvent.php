<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssignmentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;
    public $type;

    public function __construct($user, $message, $type)
    {
        $this->user = $user;
        $this->message = $message;
        $this->type = $type;
    }

    public function broadcastOn()
    {
        return ['assignment-channel'];
    }

    public function broadcastAs()
    {
        return 'assignment-event';
    }
}
