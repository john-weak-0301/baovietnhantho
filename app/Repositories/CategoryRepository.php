<?php

namespace App\Repositories;

use App\Model\Category;
use Core\Database\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Category::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'slug',
    ];

    /**
     * {@inheritdoc}
     */
    protected function applyMainQuery(Request $request, $query): Builder
    {
        return $query->defaultSort('order', 'desc');
    }
}
