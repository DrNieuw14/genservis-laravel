<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Events\NewNotificationEvent;

class NotificationHelper
{
    public static function send($userId, $type, $title, $message)
    {
        $notif = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'is_read' => 0
        ]);

        event(new NewNotificationEvent($notif));
    }
}