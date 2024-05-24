<?php

namespace Core\User\Event;

use Core\User\User;

class GetDisplayName
{
    /**
     * @var User
     */
    public $user;

    /**
     * @param  User  $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
