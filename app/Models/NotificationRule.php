<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRule extends Model
{
    //

    use Blameable, HasFactory;

    protected $fillable = [
        'target_type',
        'target_id',
        'event',
        'channel',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function recipients()
    {
        return $this->hasMany(NotificationRuleRecipient::class);
    }
}
