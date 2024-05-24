<?php

namespace App\Orchid\Screens\Experience\Layouts;

use App\Orchid\Filters\ExperienceCategoryFilter;

class ListTable extends \App\Orchid\Screens\News\Layouts\ListTable
{
    /**
     * @return array
     */
    public function filters()
    {
        return [
            new ExperienceCategoryFilter(),
        ];
    }
}
