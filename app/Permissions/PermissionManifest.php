<?php
namespace App\Permissions;


class PermissionManifest
{
    /**
     * Get all permissions grouped by module.
     */
    public static function all(): array
    {
        return [
            'dashboard' => self::dashboard(),
            'entity' => self::entity(),
            'user' => self::user(),
            'role' => self::role(),
            'device' => self::device(),
            'trip' => self::trip(),
            'application' => self::application(),
            'entityApplication' => self::entityApplication(),
            'setting.camera' => self::camera(),
            'setting.email' => self::email(),
            'setting.notification' => self::notification(),
        ];
    }

    public static function dashboard()
    {
        return [
            'view'
        ];
    }

    /**
     * Entity Module Permissions
     */

    public static function entity(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }

    public static function user(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }

    public static function role(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }


    public static function device(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }

    // app config
    public static function application(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }

    public static function entityApplication(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }
    public static function trip(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }
    public static function camera(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }
    public static function email(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }
    public static function notification(): array
    {
        return [
            'view',
            'create',
            'update',
            'delete',
        ];
    }
}