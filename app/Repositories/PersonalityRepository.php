<?php

namespace App\Repositories;

use App\Model\Personality;
use Core\Database\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PersonalityRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Personality::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];
}
