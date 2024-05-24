<?php

namespace Core\User;

use Orchid\Platform\Models\Role;

class Guest extends User
{
    /**
     * Override the ID of this user, as a guest does not have an ID.
     *
     * @var int
     */
    public $id = 0;

    /**
     * {@inheritdoc}
     */
    public function isGuest(): bool
    {
        return true;
    }

    /**
     * Get the guest's group, containing only the 'guests' group model.
     *
     * @return Role[]
     */
    public function getRolesAttribute()
    {
        if (!isset($this->attributes['roles'])) {
            $this->attributes['roles'] = $this->relations['roles'] = Role::whereKey(Group::GUEST_ID)->get();
        }

        return $this->attributes['roles'];
    }
}
