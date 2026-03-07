<?php

namespace App\Models;

use App\Traits\Blameable;
use App\Traits\DualSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityUser extends Model
{
    //

    use HasFactory, Blameable, DualSoftDeletes;
    protected $fillable = [
        'entity_id',
        'user_id',
    ];
}
