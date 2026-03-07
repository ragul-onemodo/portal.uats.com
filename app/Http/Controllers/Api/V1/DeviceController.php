<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\DeviceStatsUpdated;
use App\Http\Controllers\Controller;
use App\Models\DeviceSystemStat;
use App\Services\Notifications\NotificationEmitter;
use Cache;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    //
    protected string $module = '';


    public function heartbeat(Request $request)
    {
        /** @var \App\Models\Device $device */
        $device = $request->attributes->get('device');



        if (!$device) {
            return response()->json(['error' => 'Device context missing'], 500);
        }


        $now = now();

        /*
         |------------------------------------------------------------
         | 1. Update core device heartbeat info
         |------------------------------------------------------------
         */
        $device->update([
            'last_heartbeat_at' => $now,
            'last_seen_at' => $now,
            'last_ip' => $request->ip(),
            'last_health_payload' => $request->except(['system_stats']) ?: null,
        ]);

        /*
         |------------------------------------------------------------
         | 2. Store / Update system stats (optional payload)
         |------------------------------------------------------------
         |
         | Expected payload structure:
         | {
         |   "system_stats": {
         |      "firmware_version": "...",
         |      "cpu_usage_percent": 12.5,
         |      "connected_sensors": [...]
         |   }
         | }
         */
        if ($request->has('system_stats') && is_array($request->system_stats)) {

            $stats = $request->system_stats;

            DeviceSystemStat::updateOrCreate(
                ['device_id' => $device->id],
                [
                    'firmware_version'      => data_get($stats, 'firmware.firmware_version'),
                    'firmware_build'        => data_get($stats, 'firmware.firmware_build'),

                    'os_name'               => data_get($stats, 'os.name'),
                    'os_version'            => data_get($stats, 'os.version'),
                    'kernel_version'        => data_get($stats, 'os.version'),

                    'cpu_model'             => data_get($stats, 'device.device_type'),
                    'cpu_cores'             => data_get($stats, 'cpu.cores'),
                    'cpu_usage_percent'     => data_get($stats, 'cpu.usage_percent'),

                    'total_ram_mb'          => data_get($stats, 'memory.total_mb'),
                    'ram_usage_percent'     => data_get($stats, 'memory.usage_percent'),

                    'total_storage_mb'      => data_get($stats, 'storage.total_mb'),
                    'storage_usage_percent' => data_get($stats, 'storage.usage_percent'),

                    'uptime_seconds'        => data_get($stats, 'os.uptime_seconds'),

                    'connected_sensors'     => data_get($stats, 'connected_sensors'),
                    'network_interfaces'    => data_get($stats, 'network'),
                    'cpu_temperature'       => data_get($stats, 'temperature.cpu_celsius'),

                    'last_reported_at'      => $now,
                ]
            );
        }


        $stats = $request->system_stats ?? [];

        event(new DeviceStatsUpdated($device, [
            'cpu_usage_percent'      => data_get($stats, 'cpu.usage_percent'),
            'ram_usage_percent'      => data_get($stats, 'memory.usage_percent'),
            'storage_usage_percent'  => data_get($stats, 'storage.usage_percent'),

            'uptime_seconds'         => data_get($stats, 'os.uptime_seconds')
                ?? $device->systemStats?->uptime_seconds,

            'status'                 => $device->status,
            'last_seen_at'           => now()->toIso8601String(),
        ]));




        /*
         |------------------------------------------------------------
         | 3. Lightweight response
         |------------------------------------------------------------
         */

        $camera = Cache::remember(
            "entity:{$device->entity_id}:camera_urls",
            now()->addMinutes(30),
            function () use ($device) {
                return [
                    'primary' => $device->entity->primaryCamera?->snapshot_url,
                    'secondary' => $device->entity->secondaryCamera?->snapshot_url,
                ];
            }
        );


        $emitter = new NotificationEmitter();

        // $emitter->emit(
        //     'device',
        //     $device->id,
        //     \App\Enums\NotificationEvent::DEVICE_ONLINE,
        //     [
        //         'status' => 'online',
        //         'message' => 'Device is now online',
        //         'device_name' => $device->device_name,
        //         'device_uid' => $device->device_uid,
        //         'timestamp' => $now->toIso8601String(),
        //     ]
        // );

        return response()->json([
            'status' => true,
            'device_uid' => $device->device_uid,
            'device_state' => $device->status,
            'server_time' => $now->toIso8601String(),

            'camera' => $camera,
        ]);
    }
}
