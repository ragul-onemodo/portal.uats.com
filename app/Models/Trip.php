<?php

namespace App\Models;

use App\Traits\Blameable;
use App\Traits\DualSoftDeletes;
use App\Traits\HasEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use Blameable, DualSoftDeletes, HasEntity, HasFactory;

    //
    protected $fillable = [
        'trip_uuid',
        'device_id',
        'vechicle_number',
        'direction',
        'snapshot',
        'entity_id',
        'top_image',
        'device_ip',
        'weight',
        'other_images',
        'ocr_log',
        'application_data',
        'raw_data',
        'device_timestamp',
    ];

    public function ocrResults()
    {
        return $this->hasMany(TripOcrResult::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }
}
