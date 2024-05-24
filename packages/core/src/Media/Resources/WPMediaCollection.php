<?php

namespace Core\Media\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WPMediaCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = WPMediaResource::class;

    /**
     * The additional data that should be added to the top-level resource array.
     *
     * @var array
     */
    public $with = [
        'success' => true,
    ];

    /**
     * //
     *
     * @return $this
     */
    public function onlyResourceArray()
    {
        static::withoutWrapping();

        $this->with = [];

        return $this;
    }
}
