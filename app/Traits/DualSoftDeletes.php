<?php

namespace App\Traits;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

trait DualSoftDeletes
{
    use SoftDeletes;

    /**
     * Boot the trait and add global scope for "deleted" flag.
     */
    protected static function bootDualSoftDeletes()
    {
        static::addGlobalScope('deleted_flag', function (Builder $builder) {
            $builder->where($builder->getModel()->getTable() . '.deleted', '=', DB::raw('0'));
        });


        static::deleting(function ($model) {
            if (!$model->isForceDeleting()) {
                $model->deleted = 1;
                $model->saveQuietly();
            }
        });

        static::restoring(function ($model) {
            $model->deleted = 0;
            $model->saveQuietly();
        });
    }

    public function trashedFlagged(): bool
    {
        return (bool) $this->deleted;
    }

    public static function withDeletedFlag()
    {
        return static::query()->withoutGlobalScope('deleted_flag');
    }

    public static function onlyDeletedFlag()
    {
        return static::query()
            ->withoutGlobalScope('deleted_flag')
            ->where((new static)->getTable() . '.deleted', true);
    }
}
