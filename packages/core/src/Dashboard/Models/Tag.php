<?php

namespace Core\Dashboard\Models;

use Cartalyst\Tags\IlluminateTag;
use Core\Database\Helpers\Sluggable;

/**
 * @mixin \Eloquent
 */
class Tag extends IlluminateTag
{
    use Sluggable;

    /**
     * @var array
     */
    protected $sluggable = [
        'slug' => 'name',
    ];

    /**
     * {@inheritdoc}
     */
    public function tagged()
    {
        return $this->hasMany(Tagged::class, 'tag_id');
    }
}
