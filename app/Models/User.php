<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\Blameable;
use App\Traits\DualSoftDeletes;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Blameable, DualSoftDeletes, HasRoles, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('entity_users', function (Builder $builder) {

            // Entity id must be injected earlier (middleware)
            $entityId = app()->bound('currentEntityId')
                ? app('currentEntityId')
                : null;

            if (!$entityId) {
                return;
            }

            $builder->whereExists(function ($query) use ($entityId) {
                $query->selectRaw(1)
                    ->from('entity_users')
                    ->whereColumn('entity_users.user_id', 'users.id')
                    ->where('entity_users.entity_id', $entityId);
            });
        });
    }
    


}
