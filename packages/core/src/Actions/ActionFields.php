<?php

namespace Core\Actions;

use Illuminate\Support\Fluent;
use Illuminate\Support\Collection;

class ActionFields extends Fluent
{
    /**
     * The post-storage callbacks for the fields.
     *
     * @var Collection
     */
    public $callbacks;

    /**
     * Create a new resolved fields instance.
     *
     * @param  Collection  $attributes
     * @param  Collection  $callbacks
     * @return void
     */
    public function __construct(Collection $attributes, Collection $callbacks)
    {
        parent::__construct($attributes->all());

        $this->callbacks = $callbacks;
    }

    /**
     * Get the post-storage callbacks for the fields.
     *
     * @return Collection
     */
    public function callbacks(): Collection
    {
        return $this->callbacks;
    }
}
