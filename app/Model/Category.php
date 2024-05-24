<?php

namespace App\Model;

use App\SEO\HasSEOMeta;
use App\SEO\WithSEOMeta;
use Orchid\Filters\Filterable;
use Core\Database\Helpers\Sluggable;

/**
 * @mixin \Eloquent
 */
class Category extends Model implements WithSEOMeta
{
    use Sluggable,
        Filterable,
        HasSEOMeta;

    /* Constants */
    public const PERMISSION_VIEW = 'platform.categories.view';
    public const PERMISSION_TOUCH = 'platform.categories.touch';
    public const PERMISSION_DELETE = 'platform.categories.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'slug',
    ];

    /**
     * Sluggable configuration.
     *
     * @var array
     */
    protected $sluggable = [
        'slug' => 'name',
    ];

    /**
     * The namespace.
     *
     * @var string
     */
    protected $namespaceType = 'news';

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (self $instance) {
            $instance->namespace = $instance->getNamespace();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function newQuery()
    {
        return $this->namespaceType
            ? parent::newQuery()->where('namespace', $this->namespaceType)
            : parent::newQuery();
    }

    /**
     * The model belongs to current category.
     */
    public function categoriable()
    {
        return $this->morphTo();
    }

    public function getNamespace(): string
    {
        return $this->namespaceType;
    }
}
