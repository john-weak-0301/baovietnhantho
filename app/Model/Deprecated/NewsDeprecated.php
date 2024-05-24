<?php

namespace App\Model\Deprecated;

trait NewsDeprecated
{
    /**
     * @deprecated
     */
    public static function byCategory($slug)
    {
        return static::query()
            ->whereHas('categories', function ($query) use ($slug) {
                $query->whereSlug($slug);
            })
            ->latest('published_date')
            ->orderByDesc('order')
            ->paginate(5);
    }

    public static function inSlider()
    {
        return static::query()
            ->where('in_slider', true)
            ->latest()
            ->limit(3)
            ->get();
    }

    public static function feature()
    {
        return static::query()
            ->where('is_featured', true)
            ->latest()
            ->limit(3)
            ->get();
    }
}
