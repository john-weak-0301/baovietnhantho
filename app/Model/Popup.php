<?php

namespace App\Model;

use Orchid\Filters\Filterable;

/**
 * App\Model\Page
 *
 * @mixin \Eloquent
 */
class Popup extends Model
{
    use Filterable;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISH = 'publish';

    public const PERMISSION_VIEW = 'platform.popups.view';
    public const PERMISSION_TOUCH = 'platform.popups.touch';
    public const PERMISSION_DELETE = 'platform.popups.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pop_ups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title1',
        'title2',
        'description',
        'order',
        'layout',
        'image',
        'cta_link',
        'cta_text',
        'pages',
        'show_all',
        'show_products',
        'show_posts',
        'show_pages',
        'show_service',
        'show_expert',
        'show_home_page',
        'show_more_links',
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array
     */
    protected $allowedFilters = [
        'id',
    ];

    /**
     * Returns the page status.
     *
     * @return array
     */
    public static function getStatus(): array
    {
        return [
            self::STATUS_PUBLISH => __('Publish'),
            self::STATUS_PENDING => __('Pending'),
        ];
    }
}
