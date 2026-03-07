<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRuleRecipient extends Model
{
    //
    use Blameable, HasFactory;

    protected $fillable = [
        'notification_rule_id',
        'recipient_type',
        'recipient_value',
    ];
}
