<?php

namespace App\Model\Services;

use App\Model\Experience;

class ExperienceService
{
    public static function expInSlider($limit)
    {
        return Experience::with('categories')
            ->where('in_slider', true)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function feature($limit)
    {
        return Experience::with('categories')
            ->where('is_featured', true)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }
}
