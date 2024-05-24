<?php

namespace App\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class GT
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        return view('shortcodes.gt')->render();
    }
}
