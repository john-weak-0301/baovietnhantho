<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \App\Model\Product $resource
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $product = $this->resource;

        return [
            'id' => $product->id,
            'type' => $product->getProductType(),
            'title' => $product->title,
            'url' => $product->url->link(),
            'image' => $product->image,
            'excerpt' => $product->excerpt,
        ];
    }
}
