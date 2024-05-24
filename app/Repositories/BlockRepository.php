<?php

namespace App\Repositories;

use App\Model\Block;
use Core\Database\Repository;

class BlockRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Block::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'slug',
    ];
}
