<?php

namespace App\Shortcodes;

use App\Model\Services\ProductService;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class FeaturedProducts
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        $products = app(ProductService::class)->getFeaturedProducts(
            $shortcode->getParameter('items', 3)
        );

        return view('partials.products.grids', compact('products'))->render();
    }
}
