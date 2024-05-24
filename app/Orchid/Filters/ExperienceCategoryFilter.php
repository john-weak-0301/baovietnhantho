<?php

namespace App\Orchid\Filters;

use App\Model\ExperienceCategory;

class ExperienceCategoryFilter extends NewsCategoryFilter
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->model = new ExperienceCategory;
    }
}
