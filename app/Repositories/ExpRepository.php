<?php

namespace App\Repositories;

use App\Model\Experience;
use Core\Database\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ExpRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Experience::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];
}
