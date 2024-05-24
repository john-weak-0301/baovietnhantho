<?php

namespace App\Model;

use Orchid\Filters\Filterable;

class BranchService extends Model
{
    use Filterable;

    /* Constants */
    public const PERMISSION_VIEW = 'platform.branchs_services.view';
    public const PERMISSION_TOUCH = 'platform.branchs_services.touch';
    public const PERMISSION_DELETE = 'platform.branchs_services.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branchs_services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name', 'description',
    ];

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function (self $service) {
            $service->branch()->detach();
        });
    }

    public function branch()
    {
        return $this->belongsToMany(Branch::class, 'branchs_serviceables');
    }

    public static function getServices($branchId)
    {
        $services = static::whereHas('branch', function ($query) use ($branchId) {
            $query->where('id', $branchId);
        })->get();

        return $services->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        })->toArray();

    }

    /**
     * Select all categories, except current.
     *
     * @return array
     */
    public static function getAllServices()
    {
        $services = static::all();

        return $services->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        })->toArray();
    }
}
