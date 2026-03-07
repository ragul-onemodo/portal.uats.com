<?php

namespace App\Models;

use App\Traits\Blameable;
use App\Traits\DualSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    //
    use HasFactory, Blameable, DualSoftDeletes;


    protected $fillable = [
        'name',
        'code',
        'description',
        'webhook_url',
        'is_active',
    ];
}
