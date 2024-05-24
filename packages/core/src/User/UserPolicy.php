<?php

namespace Core\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determines if given user can edit another user.
     *
     * @param  User  $actor
     * @param  User  $user
     * @return bool
     */
    public function edit(User $actor, User $user): bool
    {
        return $actor->id === $user->id || $actor->hasPermission('edit_user');
    }
}
