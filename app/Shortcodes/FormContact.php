<?php

namespace App\Shortcodes;

use App\Model\Province;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class FormContact
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        $province = rescue(function () {
            return Province::all();
        }, []);

        $style = sanitize_text_field(
            $shortcode->getParameter('style') ?? ''
        );

        return view('shortcodes.form-contact', ['province' => $province, 'style' => $style])->render();
    }
}
