<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait Loggable
{
    public static function bootLoggable()
    {
        static::created(function ($model) {
            ActivityLog::created($model->organization_id, static::class, $model);
        });

        static::updated(function ($model) {
            $old = $model->getOriginal();
            $new = $model->getAttributes();
            ActivityLog::updated($model->organization_id, static::class, $model, $old, $new);
        });

        static::deleted(function ($model) {
            ActivityLog::deleted($model->organization_id, static::class, $model->id);
        });
    }
}
