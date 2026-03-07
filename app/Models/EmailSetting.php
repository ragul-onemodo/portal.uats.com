<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    //

    use Blameable, HasFactory;

    protected $fillable = [
        'mailer',

        // SMTP configuration
        'host',
        'port',
        'username',
        'password',
        'encryption',

        // Default sender
        'from_address',
        'from_name',

        // Extra provider options
        'options',

        // Status
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];


    /**
     * Get the active email settings
     */
    public static function active(): ?self
    {
        return self::where('is_active', true)->first();
    }
}
