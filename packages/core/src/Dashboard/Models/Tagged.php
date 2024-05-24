<?php

namespace Core\Dashboard\Models;

use Cartalyst\Tags\IlluminateTagged;

class Tagged extends IlluminateTagged
{
    /**
     * Returns the polymorphic relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taggable()
    {
        return $this->morphTo();
    }
}
