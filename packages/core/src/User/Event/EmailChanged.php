<?php

namespace Core\User\Event;

use Core\User\User;

class EmailChanged
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param  User  $user
     * @param  User  $actor
     */
    public function __construct(User $user, User $actor = null)
    {
        $this->user  = $user;
        $this->actor = $actor;
    }
}
