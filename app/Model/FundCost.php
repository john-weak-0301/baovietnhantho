<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;

class FundCost extends Model
{
    use Filterable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fund_costs';

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'imported_id',
        'quy_lkdv_id',
        'date',
        'value',
    ];

    public function fund()
    {
        return $this->belongsTo(Fund::class, 'quy_lkdv_id', 'id');
    }
}
