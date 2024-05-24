<?php

namespace Core\User\Command;

use Core\User\User;

class BanUser
{
    /**
     * The ID of the user to ban.
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
     * The attributes to update on the user.
     *
     * @var array
     */
    public $data;

    /**
     * @param  int  $userId  The ID of the user to edit.
     * @param  User  $actor  The user performing the action.
     * @param  array  $data  The attributes to update on the user.
     */
    public function __construct($userId, User $actor, array $data)
    {
        $this->userId = $userId;
        $this->actor  = $actor;
        $this->data   = $data;
    }
}
