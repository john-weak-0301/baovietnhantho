<?php

namespace App\Model;

use App\SEO\HasSEOMeta;
use App\SEO\WithSEOMeta;
use Orchid\Filters\Filterable;
use Core\Database\Helpers\Sluggable;

/**
 * @mixin \Eloquent
 */
class ServiceCategory extends Model implements WithSEOMeta
{
    use Filterable,
        Sluggable,
        HasSEOMeta;

    /* Constants */
    public const PERMISSION_VIEW = 'platform.services_categories.view';
    public const PERMISSION_TOUCH = 'platform.services_categories.touch';
    public const PERMISSION_DELETE = 'platform.services_categories.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'slug',
        'order', 'icon', 'show_in_menu',
    ];

    /**
     * //
     *
     * @var array
     */
    protected $allowedSorts = [
        'id', 'order',
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
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (self $categories) {
            $categories->description = $categories->description ?? '';
        });

        static::saved(function () {
            cache()->delete('service.*');
        });
    }

    public function service()
    {
        return $this->belongsToMany(Service::class);
    }

    public static function getCategory($serviceId)
    {
        $categories = static::whereHas('service', function ($query) use ($serviceId) {
            $query->where('service_id', $serviceId);
        })->get();

        return $categories->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        })->toArray();
    }

    /**
     * Select all categories, except current.
     *
     * @return array
     */
    public static function getAllCategories()
    {
        $categories = static::all();

        return $categories->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        })->toArray();
    }
}
