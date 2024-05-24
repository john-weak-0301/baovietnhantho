<?php

namespace App\Repositories;

use App\Model\FundCost;
use Core\Database\Repository;

class FundCostRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = FundCost::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
    ];
}
