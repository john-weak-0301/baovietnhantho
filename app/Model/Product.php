<?php

namespace App\Model;

use App\SEO\HasSEOMeta;
use App\SEO\WithSEOMeta;
use Orchid\Filters\Filterable;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Core\Database\Helpers\Sluggable;
use App\Model\Helpers\HasUrlPresenter;
use App\Model\UrlPresenters\UrlPresenter;
use App\Model\UrlPresenters\ProductUrlPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Model\Product
 *
 * @mixin \Eloquent
 */
class Product extends Model implements Searchable, WithSEOMeta, HasUrlPresenter
{
    use Sluggable,
        Filterable,
        HasSEOMeta,
        Helpers\Publishable,
        Helpers\HasFormattedContent,
        Helpers\HasOptionsAttribute;

    /* Constants */
    public const PERMISSION_VIEW = 'platform.products.view';
    public const PERMISSION_TOUCH = 'platform.products.touch';
    public const PERMISSION_DELETE = 'platform.products.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

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
        'title', 'slug', 'excerpt', 'content', 'image',
        'is_featured', 'category_id', 'status', 'order', 'type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options'            => 'json',
        'compare_attributes' => 'json',
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

        static::saved(function ($menu) {
            cache()->forget('products.*');
        });

        static::addGlobalScope('productType', function (Builder $builder) {
            $builder->where('type', $builder->getModel()->getProductType());
        });
    }

    /**
     * The product categories.
     *
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'product_categorized',
            'product_id',
            'category_id'
        );
    }

    /**
     * @return BelongsToMany
     * @deprecated
     */
    public function category()
    {
        return $this->categories()->limit(1);
    }

    /**
     * The author created the product.
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The related products.
     *
     * @return BelongsToMany
     */
    public function related(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'product_related', 'product_id', 'related_id');
    }

    /**
     * The additions products.
     *
     * @return BelongsToMany
     */
    public function additions(): BelongsToMany
    {
        return $this->belongsToMany(AdditionProduct::class, 'product_additions', 'product_id', 'addition_id');
    }

    /**
     * @return ProductCategory|null
     */
    public function getCategoryAttribute()
    {
        if ($this->exists) {
            return $this->categories->first();
        }

        return null;
    }

    /**
     * @deprecated
     */
    public function getUrl(): string
    {
        return $this->getUrlAttribute()->link();
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlAttribute(): UrlPresenter
    {
        return new ProductUrlPresenter($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchResult(): SearchResult
    {
        return new SearchResult($this, $this->title, $this->url->link());
    }

    /**
     * @return string|null
     */
    public function getProductType()
    {
        return $this->productType ?? null;
    }
}
