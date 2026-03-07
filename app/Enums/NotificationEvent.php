<?php

namespace App\Enums;

final class NotificationEvent
{
    // Device
    public const DEVICE_OFFLINE = 'device_offline';
    public const DEVICE_ONLINE = 'device_online';

    public const SENSOR_DISCONNECTED = 'sensor_disconnected';
    public const SENSOR_RECONNECTED = 'sensor_reconnected';

    public const DEVICE_OVERHEATED = 'device_overheated';

    public const API_SERVER_ERROR = 'api_server_error';

    // Camera
    public const CAMERA_DISCONNECTED = 'camera_disconnected';
    public const CAMERA_ONLINE = 'camera_online';


    public static function deviceEvents(): array
    {
        return [
            self::DEVICE_OFFLINE,
            self::DEVICE_ONLINE,
            self::SENSOR_DISCONNECTED,
            self::SENSOR_RECONNECTED,
            self::DEVICE_OVERHEATED,
            self::API_SERVER_ERROR,
            self::CAMERA_DISCONNECTED,
            self::CAMERA_ONLINE,
        ];
    }


    
}
