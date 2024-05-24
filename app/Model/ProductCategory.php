<?php

namespace App\Model;

use App\SEO\HasSEOMeta;
use App\SEO\WithSEOMeta;
use App\Model\Helpers\HasOptionsAttribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Kalnoy\Nestedset\NodeTrait;
use Orchid\Filters\Filterable;
use Core\Database\Helpers\Sluggable;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

/**
 * @mixin \Eloquent
 * @mixin \Kalnoy\Nestedset\QueryBuilder
 */
class ProductCategory extends Model implements WithSEOMeta, Searchable
{
    use Filterable,
        HasSEOMeta,
        HasOptionsAttribute;

    use Sluggable, NodeTrait {
        NodeTrait::replicate as replicateNode;
        Sluggable::replicate as replicateSluggable;
    }

    /* Constants */
    public const PERMISSION_VIEW = 'platform.products_categories.view';
    public const PERMISSION_TOUCH = 'platform.products_categories.touch';
    public const PERMISSION_DELETE = 'platform.products_categories.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_categories';

    /**
     * Sluggable configuration.
     *
     * @var array
     */
    protected $sluggable = [
        'slug' => 'name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'parent_id',
        'content',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        '_lft', '_rgt',
    ];

    /**
     * {@inheritdoc}
     */
    public function replicate(array $except = null)
    {
        $instance = $this->replicateNode($except);

        (new SlugService())->slug($instance, true);

        return $instance;
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_categorized',
            'category_id',
            'product_id'
        );
    }

    public function additions(): BelongsToMany
    {
        return $this->belongsToMany(
            AdditionProduct::class,
            'product_additions',
            'product_id',
            'addition_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->name,
            route('product.category', $this->slug)
        );
    }
}
