<?php

namespace App\Model;

use App\SEO\HasSEOMeta;
use App\SEO\WithSEOMeta;
use App\Model\Helpers\HasUrlPresenter;
use App\Model\UrlPresenters\UrlPresenter;
use App\Model\UrlPresenters\PageUrlPresenter;
use Core\Database\Helpers\Sluggable;
use Orchid\Filters\Filterable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * App\Model\Page
 *
 * @mixin \Eloquent
 */
class Page extends Model implements WithSEOMeta, HasUrlPresenter, Searchable
{
    use Sluggable,
        Filterable,
        HasSEOMeta,
        Helpers\HasFormattedContent,
        Helpers\HasOptionsAttribute;

    /* Constants */
    public const TYPE_PAGE = 'page';
    public const TYPE_HOME = 'home';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISH = 'publish';

    public const PERMISSION_VIEW = 'platform.pages.view';
    public const PERMISSION_TOUCH = 'platform.pages.touch';
    public const PERMISSION_DELETE = 'platform.pages.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'slug',
        'status', 'order', 'type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
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

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (Page $page) {
            $page->type = $page->type ?: self::TYPE_PAGE;

            $page->content = $page->content ?? '';

            if ($page->type === self::TYPE_HOME && !$page->title) {
                $page->title = 'Index';
            }
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

    /**
     * {@inheritdoc}
     */
    public function getUrlAttribute(): UrlPresenter
    {
        return new PageUrlPresenter($this);
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->title,
            $this->url->link
        );
    }

    public function popups()
    {
        return $this->belongsToMany(Popup::class, 'pop_up_pages');
    }

    public static function getPages($popupId)
    {
        $pages = static::whereHas('popups', function ($query) use ($popupId) {
            $query->where('popup_id', $popupId);
        })->get();

        return $pages->mapWithKeys(function ($item) {
            return [$item->id => $item->title];
        })->toArray();
    }
}
