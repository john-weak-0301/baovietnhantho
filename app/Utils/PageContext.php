<?php

namespace App\Utils;

use Illuminate\Support\Str;

class PageContext
{
    public function __construct()
    {
    }

    public function isHome()
    {
        return request()->is('/');
    }

    public function isNews()
    {
        return Str::contains(request()->route()->getName(), 'news')
            || request()->is('tin-tuc/*', 'tin-tuc');
    }

    public function isPages()
    {
        return Str::contains(request()->route()->getName(), 'page');
    }

    public function isProducts()
    {
        return Str::contains(request()->route()->getName(), 'product')
            || request()->is('san-pham/*', 'san-pham');
    }

    public function isService()
    {
        return Str::contains(request()->route()->getName(), 'services')
            || Str::contains(request()->route()->getName(), 'service');
    }

    public function isExpert()
    {
        return Str::contains(request()->route()->getName(), 'exps.home')
            || Str::contains(request()->route()->getName(), 'exps.category')
            || Str::contains(request()->route()->getName(), 'exp');
    }
}
