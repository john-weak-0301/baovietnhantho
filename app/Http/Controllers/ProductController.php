<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Utils\ContentParser;

class ProductController extends Controller
{
    public function __invoke($category, Product $product = null)
    {
        if ($product === null) {
            return redirect(sprintf('/san-pham/danh-muc/%s', $category), 301);
        }

        /*if ($product->category->slug !== $category) {
            return redirect($product->url->link());
        }*/

        $relatedProducts = $product
            ->related()
            ->orderByDesc('order')
            ->latest()
            ->take(3)
            ->get();

        $anchors = ContentParser::parseAnchors($product->content);

        return view('product', compact('product', 'relatedProducts', 'anchors'));
    }
}
