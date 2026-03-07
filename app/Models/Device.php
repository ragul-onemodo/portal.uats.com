<?php

namespace App\Models;

use App\Traits\HasEntity;
use App\Traits\Blameable;
use App\Traits\DualSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Str;

class Device extends Model
{

    use HasFactory, Blameable, DualSoftDeletes, HasEntity;
    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'device_name',
        'device_type',
        'device_uid',
        'entity_id',
        'api_key',
        'is_active',
        'last_heartbeat_at',
        'last_seen_at',
        'last_health_payload',
        'last_ip',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'is_active' => 'boolean',
        'last_heartbeat_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'last_health_payload' => 'array',
    ];

    /**
     * Relationships
     */

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    protected static function booted()
    {
        static::creating(function ($device) {

            if (empty($device->device_uid)) {
                do {
                    $device->device_uid = 'CG-' . strtoupper(Str::random(12));
                } while (
                    static::where('device_uid', $device->device_uid)->exists()
                );
            }
        });
    }

    /**
     * Computed device status
     *
     * online    : heartbeat <= 90s
     * degraded  : heartbeat <= 300s
     * offline   : heartbeat > 300s
     * never     : no heartbeat
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'disabled';
        }

        if (!$this->last_heartbeat_at) {
            return 'never';
        }

        $seconds = $this->last_heartbeat_at->diffInSeconds(now());

        if ($seconds <= 90) {
            return 'online';
        }

        if ($seconds <= 300) {
            return 'degraded';
        }

        return 'offline';
    }

    /**
     * Status badge helper (UI-safe)
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'online' => '<span class="badge bg-success">Online</span>',
            'degraded' => '<span class="badge bg-warning text-dark">Degraded</span>',
            'offline' => '<span class="badge bg-danger">Offline</span>',
            'disabled' => '<span class="badge bg-secondary">Disabled</span>',
            default => '<span class="badge bg-dark">Never Seen</span>',
        };
    }

    public function systemStats()
    {
        return $this->hasOne(DeviceSystemStat::class);
    }

}
