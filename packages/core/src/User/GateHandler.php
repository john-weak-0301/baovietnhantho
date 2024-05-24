<?php

namespace Core\User;

use Core\User\Event\GetPermission;

class GateHandler
{
    /**
     * Handle the policy in the "Gate::before()" method.
     *
     * @param  User  $actor
     * @param  string  $ability
     * @param  mixed  $model
     * @return bool|null
     */
    public static function handle(User $actor, string $ability, $model = null)
    {
        // Fire an event so that core and extension policies can hook into
        // this permission query and explicitly grant or deny the
        // permission.
        $allowed = app('events')->until(
            new GetPermission($actor, $ability, $model)
        );

        if ($allowed !== null) {
            return (bool) $allowed;
        }

        // If no policy covered this permission query, we will only grant
        // the permission if the actor's groups have it. Otherwise, we will
        // not allow the user to perform this action.
        if ($actor->isSuperAdmin() || (!$model && $actor->hasPermission($ability))) {
            return true;
        }
    }
}
