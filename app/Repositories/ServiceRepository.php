<?php

namespace App\Repositories;

use App\Model\Service;
use Core\Database\Repository;

class ServiceRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Service::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'slug',
    ];

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['categories'];
}
