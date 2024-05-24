<?php

namespace Core\User\Event;

use Core\User\User;

class CheckingPassword
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $password;

    /**
     * @param  User  $user
     * @param  string  $password
     */
    public function __construct($user, $password)
    {
        $this->user     = $user;
        $this->password = $password;
    }
}
