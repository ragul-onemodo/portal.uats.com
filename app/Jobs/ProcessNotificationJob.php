<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Services\Notifications\NotificationProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Notification $notification)
    {
    }

    public function handle(NotificationProcessor $processor): void
    {
        $processor->handle($this->notification);
    }
}
