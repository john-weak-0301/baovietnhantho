<?php

namespace App\Shortcodes;

use App\Model\Product;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class RelatedProducts
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        $limit = $shortcode->getParameter('items', 3);

        $currentProduct = request()->route('productName');

        if (!$currentProduct && $id = $shortcode->getParameter('id')) {
            $currentProduct = Product::find($id);
        }

        $products = null;

        if ($currentProduct && $currentProduct instanceof Product) {
            $products = $currentProduct->related()
                ->orderByDesc('order')
                ->latest()
                ->take($limit)
                ->get();
        }

        return view('partials.products.grids', compact('products'))->render();
    }
}
