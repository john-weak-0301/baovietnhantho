<?php

namespace App\Shortcodes;

use App\Model\Product;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class CategoryProduct
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        $category = $this->parseIds(
            $shortcode->getParameter('cate')
        );

        $ids = $this->parseIds(
            $shortcode->getParameter('ids')
        );

        $limit = (int) $shortcode->getParameter('items', 3);

        $products = Product::query()
            ->where('status', 'publish')
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('slug', 'not like', '%bo-tro%');

                if ($category && !empty($category)) {
                    $query->whereIn('id', $category);
                }
            })
            ->when($ids, function ($query, $ids) {
                $query->whereIn('id', $ids);
            })
            ->latest()
            ->take($limit)
            ->get();

        return view('partials.products.solutions', compact('products'))->render();
    }

    /**
     * @param $string
     * @return array|int[]
     */
    protected function parseIds($string)
    {
        $string = preg_split('/[\s,]+/', $string, -1, PREG_SPLIT_NO_EMPTY);

        return array_unique(array_map('intval', $string));
    }
}
