<?php

namespace App\Shortcodes;

class MENUDSSP
{
    public function __invoke()
    {
        return view('header._product-items')->render();
    }
}
