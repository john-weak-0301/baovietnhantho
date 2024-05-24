<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Utils\ContentParser;

class AdditionProductController extends Controller
{
    public function __invoke(Product $product)
    {
        abort_if($product->type !== 'addition', 404);

        $anchors = ContentParser::parseAnchors($product->content);

        return view('product', compact('product', 'anchors'));
    }
}
