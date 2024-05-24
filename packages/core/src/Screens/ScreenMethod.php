<?php

namespace Core\Screens;

class ScreenMethod
{
    /**
     * Determine the appropriate "handle" method for the given models.
     *
     * @param  string  $method
     * @param  Screen  $screen
     * @return string
     */
    public static function determine(string $method, Screen $screen): ?string
    {
        $method = 'handle'.ucfirst($method);

        return method_exists($screen, $method) ? $method : null;
    }
}
