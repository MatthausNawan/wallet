<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class CustomChannel
{

    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toCustom($notifiable);
    }
}
