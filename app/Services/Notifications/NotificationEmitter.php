<?php

namespace App\Services\Notifications;

use App\Models\Notification;
use App\Jobs\ProcessNotificationJob;

class NotificationEmitter
{
    public function emit(
        string $sourceType,   // entity | device
        int $sourceId,
        string $event,
        array $payload = []
    ): void {
        $notification = Notification::create([
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'event' => $event,
            'payload' => $payload,
            'occurred_at' => now(),
        ]);

        ProcessNotificationJob::dispatch($notification);
    }
}
