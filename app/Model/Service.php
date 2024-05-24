<?php

namespace App\Model;

use App\SEO\HasSEOMeta;
use App\SEO\WithSEOMeta;
use Orchid\Filters\Filterable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Core\Database\Helpers\Sluggable;

/**
 * @mixin \Eloquent
 */
class Service extends Model implements Searchable, WithSEOMeta
{
    use Sluggable,
        Filterable,
        HasSEOMeta,
        Helpers\HasOptionsAttribute,
        Helpers\HasFormattedContent;

    /* Constants */
    public const TYPE_SERVICE = 'service';
    public const TYPE_HOME = 'service';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISH = 'publish';
    public const PERMISSION_VIEW = 'platform.services.view';
    public const PERMISSION_TOUCH = 'platform.services.touch';
    public const PERMISSION_DELETE = 'platform.services.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'categories',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'slug',
        'status', 'order', 'image',
    ];

    /**
     * //
     *
     * @var array
     */
    protected $allowedSorts = [
        'id', 'created_at', 'updated_at',
    ];

    /**
     * //
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
    ];

    /**
     * Sluggable configuration.
     *
     * @var array
     */
    protected $sluggable = [
        'slug' => 'title',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function (Service $service) {
            $service->content = $service->content ?? '';
        });

        static::saved(function () {
            cache()->delete('service.*');
        });
    }

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

    public function categories()
    {
        return $this->belongsToMany(ServiceCategory::class);
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult($this, $this->title, route('service', $this->slug));
    }
}
