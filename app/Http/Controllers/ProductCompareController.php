<?php

namespace App\Http\Controllers;

use App\Model\Product;
use Illuminate\Http\Request;

class ProductCompareController
{
    public function __invoke(Request $request)
    {
        $compareAttributes = config('press.compare_attributes');

        $sessionProducts = (array) session('compare-products', []);

        if ($request->has('p')) {
            $sessionProducts += array_unique([(int) $request->input('p')]);
        }

        $products = Product::has('categories')
            ->select('id', 'title', 'slug', 'compare_attributes')
            ->where('status', 'publish')
            ->orderByDesc('order')
            ->take(100)
            ->get();

        $products = $products->each(function (Product $product) {
            $attributes = [];

            foreach ((array) $product->compare_attributes as $key => $value) {
                $attributes[$key] = wpautop($value);
            }

            $product->compare_attributes = $attributes;
        });

        $productsWithCategory = $products->mapToGroups(function (Product $item, $key) {
            return [$item->category->name => $item];
        })->each(function ($products) {
            foreach ($products as $product) {
                $product->category = $product->categories[0];
            }
        });

        $compareProducts = $products
            ->whereIn('id', $sessionProducts)
            ->take(3)
            ->values();

        return view('product-compare', compact(
            'compareProducts',
            'compareAttributes',
            'productsWithCategory'
        ));
    }
}
