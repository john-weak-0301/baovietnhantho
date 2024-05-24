<?php

namespace App\Repositories;

use Core\Database\Repository;
use App\Model\ProductCategory;
use Illuminate\Support\Str;

class ProductCategoryRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = ProductCategory::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'slug',
    ];

    /**
     * Check a area name is exists or not.
     *
     * @param  string  $name  The tag name (without slugify).
     * @return bool
     */
    public function exists($name): bool
    {
        return (bool) $this->newQuery()
            ->where('name', $name = trim(clean($name)))
            ->where('slug', Str::slug($name))
            ->limit(1)
            ->count();
    }
}
