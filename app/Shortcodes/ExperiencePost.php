<?php

namespace App\Shortcodes;

use App\Model\Experience;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class ExperiencePost
{
    public function __invoke(ShortcodeInterface $shortcode)
    {
        $category = $shortcode->getParameter('cate');
        $category = preg_split('/[\s,]+/', $category, -1, PREG_SPLIT_NO_EMPTY);
        $category = array_unique(array_map('intval', $category));

        $limit = (int) $shortcode->getParameter('items', 4);

        $posts = Experience::with('categories')
            ->whereHas('categories', function ($query) use ($category) {
                if ($category && !empty($category)) {
                    $query->whereIn('id', $category);
                }
            })
            ->latest()
            ->take($limit)
            ->get();

        return view('news.grids', compact('posts'))->render();
    }
}
