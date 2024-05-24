<?php

namespace App\Shortcodes;

use App\Utils\MenuFactory;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class AboutAsideMenu
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        return view('shortcodes.about-menu', [
            'title' => __('về chúng tôi'),
            'menu'  => MenuFactory::make('sidebar-about'),
        ]);
    }
}
