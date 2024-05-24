<?php

namespace Core\Database\Helpers;

use Illuminate\Database\Eloquent\Builder;

trait HasOptionsAttribute
{
    /**
     * Initialize the trait.
     *
     * @return void
     */
    public function initializeHasOptionsAttribute(): void
    {
        $this->casts[$this->getOptionsName()] = 'json';
    }

    /**
     * Returns the options attribute.
     *
     * @return OptionsAttributes
     */
    public function getOptions(): OptionsAttributes
    {
        return $this->getOptionsAttribute();
    }

    /**
     * Retrive the options attribute.
     *
     * @return OptionsAttributes
     */
    public function getOptionsAttribute(): OptionsAttributes
    {
        return OptionsAttributes::createForModel($this, $this->getOptionsName());
    }

    /**
     * Add scope `withOption($name, $value)` to the query builder.
     *
     * @param  Builder  $builder
     * @param  string|array  $name
     * @param  mixed|null  $value
     * @return Builder
     */
    public function scopeWithOptions(Builder $builder, $name, $value = null): Builder
    {
        $args = is_array($name) ? $name : [$name => $value];

        foreach ($args as $_name => $_value) {
            $builder->where("{$this->getOptionsName()}->{$_name}", $_value);
        }

        return $builder;
    }

    /**
     * Return the options column name.
     *
     * @return string
     */
    protected function getOptionsName(): string
    {
        return $this->optionsAttribute ?? 'options';
    }
}
