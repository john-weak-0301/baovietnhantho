<?php

namespace App\Repositories;

use App\Model\BranchService;
use Core\Database\Repository;

class BranchServiceRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = BranchService::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];
}
