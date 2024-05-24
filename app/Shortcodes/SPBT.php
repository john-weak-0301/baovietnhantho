<?php

namespace App\Shortcodes;

use App\Model\AdditionProduct;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class SPBT
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        $limit = (int) $shortcode->getParameter('items', 50);

        if ($currentProduct = request()->route('productName')) {
            $query = $currentProduct->additions();
        } elseif ($currentCategory = request()->route('productCategoryName')) {
            $query = $currentCategory->additions();
        } else {
            $query = AdditionProduct::query();
        }

        $products = $query
            ->where('status', 'publish')
            ->orderByDesc('order')
            ->latest()
            ->take($limit)
            ->get();

        if (blank($products)) {
            return '';
        }

        return view('shortcodes.spbt', compact('products'))->render();
    }
}
