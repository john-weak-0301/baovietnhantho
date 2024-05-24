<?php

namespace App\Repositories;

use App\Model\Page;
use Core\Database\Repository;

class PageRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Page::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'slug',
    ];

    /**
     * Returns the homepage, create if not exists..
     *
     * @return Page
     */
    public function getHomepage(): Page
    {
        return $this->newQuery()->firstOrCreate(['type' => Page::TYPE_HOME]);
    }
}
