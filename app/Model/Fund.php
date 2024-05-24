<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;

class Fund extends Model
{
    use Filterable;

    /* Constants */
    public const PERMISSION_VIEW = 'platform.funds.view';
    public const PERMISSION_TOUCH = 'platform.funds.touch';
    public const PERMISSION_DELETE = 'platform.funds.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'funds';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'risks_of_investing',
        'desc_target',
        'desc_profit',
        'desc_invest',
        'order',
    ];

    public function fundCosts()
    {
        return $this->hasMany(FundCost::class, 'quy_lkdv_id', 'id');
    }
}
