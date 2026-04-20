<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    public function __construct(protected User $newUser) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "New user \"{$this->newUser->name}\" registered and is pending approval.",
            'url'     => route('admin.users.pending'),
            'user_id' => $this->newUser->id,
            'type'    => 'new_registration',
        ];
    }
}