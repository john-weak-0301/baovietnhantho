<?php

namespace App\Shortcodes;

use Core\Dashboard\Models\Menu as MenuModel;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class Menu
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        $menu = $shortcode->getParameters();

        $menuItems = MenuModel::where('type', $menu);
    }
}
