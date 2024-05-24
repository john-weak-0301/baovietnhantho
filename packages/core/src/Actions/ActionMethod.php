<?php

namespace Core\Actions;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ActionMethod
{
    /**
     * Determine the appropriate "handle" method for the given models.
     *
     * @param  Action  $action
     * @param  Model  $model
     * @return string
     */
    public static function determine(Action $action, Model $model): string
    {
        $method = 'handleFor'.Str::plural(class_basename($model));

        return method_exists($action, $method) ? $method : 'handle';
    }
}
