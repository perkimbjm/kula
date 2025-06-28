<?php

namespace App\Traits;

use App\Services\CustomAuditor;

trait Auditable
{
    /**
     * Boot the trait
     */
    public static function bootAuditable()
    {
        static::created(function ($model) {
            CustomAuditor::recordModelChange('created', $model);
        });

        static::updated(function ($model) {
            CustomAuditor::recordModelChange('updated', $model);
        });

        static::deleted(function ($model) {
            CustomAuditor::recordModelChange('deleted', $model);
        });
    }
}
