<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Notifications implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;
    public $numberNotification;
    public $avatar;
    public $reviewId;
    public $notificationOfUser;
    public $notificationId;
    public $action;
    public function __construct($message, $numberNotification, $avatar, $reviewId, $notificationOfUser, $notificationId, $action)
    {
        $this->message = $message;
        $this->numberNotification = $numberNotification;
        $this->avatar = $avatar;
        $this->reviewId = $reviewId;
        $this->notificationOfUser = $notificationOfUser;
        $this->notificationId = $notificationId;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notifications');
    }
}
