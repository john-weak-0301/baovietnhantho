<?php

namespace App\Orchid\Screens;

use Core\Screens\Screen;
use App\Orchid\Layout\Link;

abstract class AbstractScreen extends Screen
{
    public function addLink($name, $route = null): Link
    {
        return tap(Link::name($name), function (Link $link) use ($route) {
            if ($route) {
                $link->link(route($route));
            }
        });
    }
}
