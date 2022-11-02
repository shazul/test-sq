<?php

namespace Pimeo\Repositories\Traits;

use Illuminate\Support\Facades\Auth;

trait LogActions
{
    public static function bootLogActions()
    {
        static::creating(function ($model) {
            $model->created_by = self::getLoggedInUserId();

            return $model;
        });

        static::saving(function ($model) {
            $model->updated_by = self::getLoggedInUserId();

            return $model;
        });
    }

    private static function getLoggedInUserId()
    {
        $id = null;

        if (Auth::check()) {
            $id = Auth::user()->getAuthIdentifier();
        }

        return $id;
    }
}
