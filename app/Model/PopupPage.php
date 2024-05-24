<?php

namespace App\Model;

use Orchid\Filters\Filterable;

/**
 * App\Model\Page
 *
 * @mixin \Eloquent
 */
class PopupPage extends Model
{
    use Filterable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pop_up_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_id',
        'popup_id',
    ];
}
