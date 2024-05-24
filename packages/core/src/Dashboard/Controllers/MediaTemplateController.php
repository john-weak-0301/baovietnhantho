<?php

namespace Core\Dashboard\Controllers;

class MediaTemplateController
{
    public function __invoke()
    {
        if (app()->bound('debugbar')) {
            app('debugbar')->disable();
        }

        ob_start();
        wp_print_media_templates();

        return response(trim(ob_get_clean()));
    }
}
