<?php

namespace App\Repositories;

use App\Model\ServiceCategory;
use Core\Database\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ServiceCategoryRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ServiceCategory::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];
}
