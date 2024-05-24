<?php

namespace App\Model\Helpers;

use App\Model\Scopes\PublishedScope;

trait Publishable
{
    /**
     * Boot the Publishable trait for a model.
     *
     * @return void
     */
    public static function bootPublishable(): void
    {
        static::addGlobalScope(new PublishedScope);
    }

    /**
     * Initialize the soft deleting trait for an instance.
     *
     * @return void
     */
    public function initializePublishable(): void
    {
        $this->dates[] = $this->getPublishedAtColumn();
    }

    /**
     * Publish a model instance.
     *
     * @return bool|null
     */
    public function publish(): ?bool
    {
        // If the publishing event does not return false, we will proceed with this
        // publish operation. Otherwise, we bail out so the developer will stop
        // the publish totally. We will clear the deleted timestamp and save.
        if ($this->fireModelEvent('publishing') === false) {
            return false;
        }

        $this->{$this->getPublishedAtColumn()} = null;

        // Once we have saved the model, we will fire the "published" event so this
        // developer will do anything they need to after a publish operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('published', false);

        return $result;
    }

    /**
     * Determine if the model instance has been published.
     *
     * @return bool
     */
    public function published(): bool
    {
        return $this->{$this->getPublishedAtColumn()} !== null;
    }

    /**
     * Get the name of the "published_at" column.
     *
     * @return string
     */
    public function getPublishedAtColumn(): string
    {
        return defined('static::PUBLISHED_AT') ? static::PUBLISHED_AT : 'published_date';
    }

    /**
     * Get the fully qualified "published_at" column.
     *
     * @return string
     */
    public function getQualifiedPublishedAtColumn(): string
    {
        return $this->qualifyColumn($this->getPublishedAtColumn());
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        if (request()->is('dashboard/*')) {
            return $this->where($this->getRouteKeyName(), $value)->withUnpublished()->first();
        }

        return parent::resolveRouteBinding($value);
    }
}
