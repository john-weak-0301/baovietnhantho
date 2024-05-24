<?php

namespace Core\User\OAuth;

use Laravel\Socialite\Contracts\User;

class Registration
{
    /**
     * @var array
     */
    protected $provided = [];

    /**
     * @var array
     */
    protected $suggested = [];

    /**
     * @var mixed
     */
    protected $payload;

    /**
     * Fill data from Socialite response.
     *
     * @param  User  $user
     */
    public function fillFromSocialite(User $user)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->setPayload($user->getRaw());

        $this->suggestEmail($user->getEmail());
        $this->suggestUsername($user->getNickname() ?: $user->getName());
        $this->suggest('name', $user->getName());

        if ($email = $user->getEmail()) {
            $this->provideTrustedEmail($email);
        }

        if ($avatar = $user->getAvatar()) {
            $this->provideAvatar($avatar);
        }
    }

    /**
     * @return array
     */
    public function getProvided(): array
    {
        return $this->provided;
    }

    /**
     * @return array
     */
    public function getSuggested(): array
    {
        return $this->suggested;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function provide(string $key, $value)
    {
        $this->provided[$key] = $value;

        return $this;
    }

    /**
     * @param  string  $email
     * @return $this
     */
    public function provideTrustedEmail(string $email)
    {
        return $this->provide('email', $email);
    }

    /**
     * @param  string  $url
     * @return $this
     */
    public function provideAvatar(string $url)
    {
        return $this->provide('avatar_url', $url);
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function suggest(string $key, $value)
    {
        $this->suggested[$key] = $value;

        return $this;
    }

    /**
     * @param  string  $username
     * @return $this
     */
    public function suggestUsername(string $username)
    {
        $username = preg_replace('/[^a-z0-9-_]/i', '', $username);

        return $this->suggest('username', $username);
    }

    /**
     * @param  string  $email
     * @return $this
     */
    public function suggestEmail(string $email)
    {
        return $this->suggest('email', $email);
    }

    /**
     * @param  mixed  $payload
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }
}
