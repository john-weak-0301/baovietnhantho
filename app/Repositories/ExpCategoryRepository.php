<?php

namespace App\Repositories;

use App\Model\ExperienceCategory;
use Core\Database\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ExpCategoryRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ExperienceCategory::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * {@inheritdoc}
     */
    protected function applyMainQuery(Request $request, $query): Builder
    {
        return $query;
    }
}
