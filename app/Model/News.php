<?php

namespace App\Model;

use App\SEO\HasSEOMeta;
use App\SEO\WithSEOMeta;
use App\Model\Helpers\Publishable;
use App\Model\Helpers\HasUrlPresenter;
use App\Model\UrlPresenters\UrlPresenter;
use App\Model\UrlPresenters\NewsUrlPresenter;
use Core\Media\Media;
use Core\Database\Helpers\Sluggable;
use Orchid\Filters\Filterable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Cartalyst\Tags\TaggableTrait;
use CyrildeWit\EloquentViewable\Viewable;
use CyrildeWit\EloquentViewable\Contracts\Viewable as ViewableContract;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Eloquent
 */
class News extends Model implements Searchable, ViewableContract, WithSEOMeta, HasUrlPresenter
{
    use Viewable,
        Sluggable,
        Filterable,
        HasSEOMeta,
        Publishable,
        TaggableTrait,
        Helpers\Categorizable,
        Helpers\HasOptionsAttribute,
        Helpers\HasFormattedContent,
        Deprecated\NewsDeprecated;

    /* Constants */
    public const PERMISSION_VIEW = 'platform.news.view';
    public const PERMISSION_TOUCH = 'platform.news.touch';
    public const PERMISSION_DELETE = 'platform.news.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'categories', 'media',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'image_slider',
        'is_featured',
        'in_slider',
        'status',
        'published_date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_date',
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
     * The post type name.
     *
     * @var string
     */
    protected $postType = 'news';

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (self $instance) {
            $instance->type = $instance->getPostType();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function newQuery()
    {
        return $this->postType
            ? parent::newQuery()->where('type', $this->postType)
            : parent::newQuery();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'image', 'id');
    }

    /**
     * @deprecated
     */
    public function getUrl()
    {
        return $this->getUrlAttribute()->link();
    }

    public function getImageUrl($conversion = '')
    {
        // If we have a string of image Url, just return that.
        if (is_string($this->image) && Str::startsWith($this->image, ['http', '/'])) {
            return $this->image;
        }

        if (!$this->exists || !isset($this->media)) {
            return '';
        }

        return optional($this->media)->getFullUrl($conversion) ?? '';
    }

    public function getImageUrlAttribute($value)
    {
        return $this->getImageUrl();
    }

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->title,
            $this->url->link
        );
    }

    public function getPostType(): string
    {
        return $this->postType;
    }

    public function getUrlAttribute(): UrlPresenter
    {
        return new NewsUrlPresenter($this);
    }
}
