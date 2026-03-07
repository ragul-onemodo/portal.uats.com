<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripOcrResult extends Model
{
    //

    protected $fillable = [
        'trip_id',
        'plate',
        'confidence',
        'engine',
        'status',
        'raw_result',
        'processed_at',
    ];

    protected $casts = [
        'raw_result' => 'array',
        'processed_at' => 'datetime',
    ];
}
