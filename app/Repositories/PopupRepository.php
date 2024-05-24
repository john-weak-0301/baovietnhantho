<?php

namespace App\Repositories;

use App\Model\Popup;
use Core\Database\Repository;

class PopupRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Popup::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
    ];
}
