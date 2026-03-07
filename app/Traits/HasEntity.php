<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasEntity
{
    /**
     * Boot the HasEntity trait.
     */
    protected static function bootHasEntity(): void
    {
        static::addGlobalScope('entity', function (Builder $builder) {

            // Skip if no auth (CLI, seeder, guest)
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();

            // Super admin should see everything
            if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                return;
            }

            // Resolve entity id
            $entityId = static::resolveEntityId();

            if ($entityId) {
                $builder->where(
                    $builder->getModel()->getTable() . '.entity_id',
                    $entityId
                );
            }
        });
    }

    /**
     * Scope: filter by entity manually.
     */
    public function scopeForEntity(Builder $query, int $entityId): Builder
    {
        return $query->where(
            $this->getTable() . '.entity_id',
            $entityId
        );
    }

    /**
     * Resolve current entity id from logged-in user.
     */
    protected static function resolveEntityId(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        return Auth::user()->entity_id ?? null;
    }
}
