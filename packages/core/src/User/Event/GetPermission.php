<?php

namespace Core\User\Event;

use Core\User\User;

class GetPermission
{
    /**
     * @var User
     */
    public $actor;

    /**
     * @var string
     */
    public $ability;

    /**
     * @var \Core\Database\Model|object|string
     */
    public $model;

    /**
     * @param  User  $actor
     * @param  string  $ability
     * @param  mixed  $model
     */
    public function __construct(User $actor, $ability, $model)
    {
        $this->actor   = $actor;
        $this->ability = $ability;
        $this->model   = $model;
    }
}
