<?php

namespace App\Http\Composers;

use App\Utils\MenuFactory;

class FooterMenuComposer
{
    public function compose(): void
    {
        MenuFactory::make('footer-1', 'footerMenu1');
        MenuFactory::make('footer-2', 'footerMenu2');
        MenuFactory::make('footer-3', 'footerMenu3');
        MenuFactory::make('footer-4', 'footerMenu4');
    }
}
