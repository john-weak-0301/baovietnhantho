<?php

namespace App\Http\Controllers;

use App\Model\ProductCategory;

class ProductCategoryController extends Controller
{
    public function __invoke(ProductCategory $category)
    {
        $products = $category
            ->products()
            ->where('status', 'publish')
            ->latest('published_date')
            ->orderByDesc('order')
            ->paginate(12);

        return view('product-category', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
