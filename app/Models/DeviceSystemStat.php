<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceSystemStat extends Model
{
    //

    use HasFactory, \App\Traits\Blameable, \App\Traits\DualSoftDeletes;

    protected $fillable = [
        'device_id',
        'firmware_version',
        'firmware_build',
        'os_name',
        'os_version',
        'kernel_version',
        'cpu_model',
        'cpu_cores',
        'total_ram_mb',
        'total_storage_mb',
        'cpu_usage_percent',
        'ram_usage_percent',
        'storage_usage_percent',
        'uptime_seconds',
        'connected_sensors',
        'network_interfaces',
        'cpu_temperature',
        'last_reported_at',
    ];

    protected $casts = [
        'connected_sensors' => 'array',
        'network_interfaces' => 'array',
        'last_reported_at' => 'datetime',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
