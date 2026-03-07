<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntityCamera extends Model
{
    //

    protected $fillable = [
        'entity_id',
        'name',
        'ip_address',
        'username',
        'password',
        'snapshot_url',
        'is_primary',
        'is_secondary',
        'is_active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_secondary' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Prevent invalid state
    protected static function booted()
    {
        static::saving(function ($camera) {
            if ($camera->is_primary && $camera->is_secondary) {
                throw new \LogicException(
                    'Camera cannot be both primary and secondary.'
                );
            }
        });
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? encrypt($value) : null;
    }

    public function getPasswordAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }
}
