<?php

namespace App\Repositories;

use App\Model\Consultant;
use Core\Database\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ConsultantRepository extends Repository
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Consultant::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'customer_name',
    ];

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = [
        'counselor'
    ];

    /**
     * {@inheritdoc}
     */
    protected function applyMainQuery(Request $request, $query): Builder
    {
        if ($request->status === 'pending') {
            $query->whereNull('reserved_at');
        } elseif ($request->status === 'processed') {
            $query->whereNotNull('reserved_at');
        }

        return $query;
    }
}
