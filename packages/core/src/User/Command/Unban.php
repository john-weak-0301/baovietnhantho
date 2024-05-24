<?php

namespace Core\User\Command;

use Core\User\User;

class Unban
{
    /**
     * The ID of the user to unban.
     *
     * @var int
     */
    public $userId;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * Constructor.
     *
     * @param  int  $userId  The ID of the user to edit.
     * @param  User  $actor  The user performing the action.
     */
    public function __construct($userId, User $actor)
    {
        $this->userId = $userId;
        $this->actor  = $actor;
    }
}
