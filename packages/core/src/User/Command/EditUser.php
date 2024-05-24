<?php

namespace Core\User\Command;

use Core\User\User;

class EditUser
{
    /**
     * The user to edit.
     *
     * @var int
     */
    public $user;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes to update on the user.
     *
     * @var array
     */
    public $data;

    /**
     * @param  User  $user  The user to edit.
     * @param  User  $actor  The user performing the action.
     * @param  array  $data  The attributes to update on the user.
     */
    public function __construct(User $user, User $actor, array $data)
    {
        $this->user  = $user;
        $this->actor = $actor;
        $this->data  = $data;
    }
}
