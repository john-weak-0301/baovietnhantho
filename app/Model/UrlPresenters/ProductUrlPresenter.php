<?php

namespace App\Model\UrlPresenters;

use App\Model\AdditionProduct;
use App\Model\Product;

class ProductUrlPresenter extends UrlPresenter
{
    /**
     * @var Product
     */
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function link(): string
    {
        if (!$this->product->slug) {
            return '#';
        }

        if ($this->product instanceof AdditionProduct || $this->product->type === 'addition') {
            return route('product.addition', $this->product->slug);
        }

        if ($category = $this->product->category) {
            return route('product', [$category->slug, $this->product->slug]);
        }

        return '#';
    }

    public function edit(): string
    {
        return route('platform.products.edit', $this->product, false);
    }

    public function editor(): string
    {
        return route('platform.products.editor', $this->product, false);
    }
}
