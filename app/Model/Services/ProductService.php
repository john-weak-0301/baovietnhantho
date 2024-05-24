<?php

namespace App\Model\Services;

use App\Model\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function getBySlug($slug, $categorySlug = null): Product
    {
        return Product::withoutGlobalScope('productType')
            ->when($categorySlug, function (Builder $query, $slug) {
                $query->whereHas('categories', function (Builder $subquery) use ($slug) {
                    $subquery->where('slug', $slug);
                });
            })
            ->where('status', 'publish')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getFeaturedProducts($limit = 5): Collection
    {
        return Product::has('categories')
            ->where('status', 'publish')
            ->where('is_featured', true)
            ->orderByDesc('order')
            ->latest('published_date')
            ->take($limit)
            ->get();
    }
}
