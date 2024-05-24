<?php

namespace App\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class BCTN
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        return view('shortcodes.bctn')->render();
    }
}
