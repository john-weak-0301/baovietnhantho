<?php

namespace App\Repositories;

use App\Model\FundImport;
use Core\Database\Repository;

class FundImportRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = FundImport::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];
}
