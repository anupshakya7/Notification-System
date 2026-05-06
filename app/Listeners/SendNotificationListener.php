<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class SendNotificationListener
{
    public function handle(NotificationCreated $event): void
    {
        $notification = $event->notification;

        Redis::publish('notifications', json_encode([
            'id' => $notification->id,
            'user_id' => $notification->user_id,
            'type' => $notification->type,
            'payload' => $notification->payload,
        ]));
    }
}
