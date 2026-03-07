<?php

namespace App\Traits;
use Illuminate\Support\Facades\Auth;
trait Blameable
{

    public static function bootBlameable()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = Auth::id();
                $model->updated_at = now();
            }
        });

        static::deleting(function ($model) {
            if (auth()->check() && !$model->isForceDeleting()) {
                $model->deleted_by = auth()->id();
                $model->saveQuietly();
            }
        });
    }
}