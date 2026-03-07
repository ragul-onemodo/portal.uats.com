<?php

namespace App\Models;

use App\Traits\Blameable;
use App\Traits\DualSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    //

    use HasFactory, Blameable, DualSoftDeletes;


    protected $fillable = [
        'name',
        'location',
        'is_active',
        'integration_enabled',
        'directory_path'
    ];


    public function primaryCamera()
    {
        return $this->hasOne(EntityCamera::class)
            ->where('is_primary', true);
    }

    public function secondaryCamera()
    {
        return $this->hasOne(EntityCamera::class)
            ->where('is_secondary', true);
    }
}
