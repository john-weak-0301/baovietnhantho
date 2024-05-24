<?php

namespace App\Repositories;

use App\Model\Branch;
use Core\Database\Repository;

class BranchRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Branch::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'address'
    ];
}
