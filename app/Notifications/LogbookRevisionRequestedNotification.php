<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LogbookRevisionRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(public array $payload)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return $this->payload;
    }
}
