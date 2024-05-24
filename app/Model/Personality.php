<?php

namespace App\Model;

use Orchid\Filters\Filterable;
use Core\Database\Helpers\Sluggable;

/**
 * @mixin \Eloquent
 */
class Personality extends Model
{
    use Filterable, Sluggable;

    /* Constants */
    public const PERMISSION_VIEW = 'platform.personalities.view';
    public const PERMISSION_TOUCH = 'platform.personalities.touch';
    public const PERMISSION_DELETE = 'platform.personalities.delete';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personalities';

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
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function (Personality $personality) {
            $personality->description = $personality->description ?? '';
        });
    }

    public function counselor()
    {
        return $this->belongsToMany(Counselor::class);
    }

    public static function getPersonalities($counselorId)
    {
        $personalities = static::whereHas('counselor', function ($query) use ($counselorId) {
            $query->where('counselor_id', $counselorId);
        })->get();

        return $personalities->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        })->toArray();
    }

    /**
     * Select all categories, except current.
     *
     * @return array
     */
    public static function getAllPersonalities()
    {
        return static::all()->mapWithKeys(function ($item) {
            return [$item->id => $item->name];
        })->toArray();
    }
}
