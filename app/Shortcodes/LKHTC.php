<?php

namespace App\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class LKHTC
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        return view('shortcodes.lkhtc');
    }
}
