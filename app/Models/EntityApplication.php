<?php

namespace App\Models;

use App\Traits\HasEntity;
use App\Traits\Blameable;
use App\Traits\DualSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityApplication extends Model
{

    use HasFactory, Blameable, DualSoftDeletes, HasEntity;
    protected $fillable = [
        'application_id',
        'entity_id',
        'company_reference',
        'is_active',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
