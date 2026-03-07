<?php

namespace App\Events;

use App\Models\Device;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceStatsUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public function __construct(
        public Device $device,
        public array $stats
    ) {
    }

    public function broadcastOn()
    {
        return new Channel('device.' . $this->device->id);
    }

    public function broadcastAs()
    {
        return 'stats.updated';
    }
}
