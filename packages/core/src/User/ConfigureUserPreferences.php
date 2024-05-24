<?php

namespace Core\User;

class ConfigureUserPreferences
{
    /**
     * Register a preference with a transformer and a default value.
     *
     * @param  string  $key
     * @param  callable  $transformer
     * @param  mixed  $default
     */
    public function add($key, callable $transformer = null, $default = null): void
    {
        User::addPreference($key, $transformer, $default);
    }
}
