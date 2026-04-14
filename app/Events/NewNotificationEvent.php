<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn(): PrivateChannel
    {
    return new PrivateChannel('notifications.' . $this->notification->user_id);
    }

    public function broadcastAs()
    {
        return 'new.notification';
    }

    // 🔥 ADD THIS (VERY IMPORTANT)
    public function broadcastWith()
    {
        return [
            'notification' => $this->notification
        ];
    }
}