<?php

namespace App\Shortcodes;

use App\Model\Block as Model;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class Block
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        if (!$block = $shortcode->getParameter('id')) {
            return '';
        }

        if (!$block = Model::find((int) $block)) {
            return '';
        }

        return do_shortcode(
            do_blocks($block->rendered_content)
        );
    }
}
