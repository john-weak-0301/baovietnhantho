<?php

namespace Core\User\Event;

use Core\User\User;

class RegisteringFromProvider
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $provider;

    /**
     * @var array
     */
    public $payload;

    /**
     * @param  User  $user
     * @param  string  $provider
     * @param  array  $payload
     */
    public function __construct(User $user, string $provider, array $payload)
    {
        $this->user     = $user;
        $this->provider = $provider;
        $this->payload  = $payload;
    }
}
