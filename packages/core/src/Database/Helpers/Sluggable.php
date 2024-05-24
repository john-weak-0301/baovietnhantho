<?php

namespace Core\Database\Helpers;

use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Cviebrock\EloquentSluggable\Sluggable as EloquentSluggable;

/**
 * @method static \Illuminate\Database\Eloquent\Builder whereSlug(string $slug)
 */
trait Sluggable
{
    use EloquentSluggable, SluggableScopeHelpers;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        if (property_exists($this, 'sluggable')) {
            return array_map(function ($source) {
                return is_array($source) ? $source : [
                    'source'    => $source,
                    'maxLength' => 100,
                    'separator' => '-',
                    'unique'    => true,
                ];
            }, $this->sluggable);
        }

        return [];
    }
}
