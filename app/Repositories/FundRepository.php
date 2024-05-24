<?php

namespace App\Repositories;

use App\Model\Fund;
use Core\Database\Repository;

class FundRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Fund::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
    ];
}
