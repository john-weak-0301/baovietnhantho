<?php

namespace App\Model\Observers;

use App\Model\Product;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the news "creating" event.
     *
     * @param  Product  $product
     * @return void
     */
    public function creating(Product $product): void
    {
        $product->content = $product->content ?? '';

        if (!$product->author_id && $author = Auth::id()) {
            $product->author_id = $author;
        }
    }
}
