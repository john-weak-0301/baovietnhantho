<?php

namespace Core\Database;

use Laravel\Scout\Searchable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;

abstract class Repository
{
    use PerformsQueries;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model;

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = [];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [];

    /**
     * The cached soft deleting statuses for various resources.
     *
     * @var array
     */
    protected static $softDeletes = [];

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return Model|mixed
     */
    public static function newModel()
    {
        $model = static::$model;

        if (!class_exists($model)) {
            throw new InvalidArgumentException('The repository must be provide a model class.');
        }

        return new $model;
    }

    /**
     * Determine if this resource is searchable.
     *
     * @return bool
     */
    public static function searchable(): bool
    {
        return !empty(static::$search) || static::usesScout();
    }

    /**
     * Determine if this resource uses Laravel Scout.
     *
     * @return bool
     */
    public static function usesScout(): bool
    {
        return in_array(Searchable::class, class_uses_recursive(static::newModel()), true);
    }

    /**
     * Get the searchable columns for the resource.
     *
     * @return array
     */
    public static function searchableColumns(): array
    {
        return empty(static::$search)
            ? [static::newModel()->getKeyName()]
            : static::$search;
    }

    /**
     * Determine if this resource uses soft deletes.
     *
     * @return bool
     */
    public static function softDeletes(): bool
    {
        if (isset(static::$softDeletes[static::$model])) {
            return static::$softDeletes[static::$model];
        }

        return static::$softDeletes[static::$model] = in_array(
            SoftDeletes::class,
            class_uses_recursive(static::newModel()),
            true
        );
    }

    /**
     * Get a new query builder for the underlying model.
     *
     * @return Builder
     */
    public function newQuery()
    {
        return static::newModel()->newQuery();
    }

    /**
     * Get a new, scopeless query builder for the underlying model.
     *
     * @return Builder
     */
    public function newQueryWithoutScopes()
    {
        return static::newModel()->newQueryWithoutScopes();
    }

    /**
     * Returns the query for the listing.
     *
     * @param  Request|null  $request
     * @param  null  $search
     * @param  string  $trashed
     * @return Builder
     */
    public function query(
        Request $request = null,
        $search = null,
        $trashed = TrashedStatus::DEFAULT
    ) {
        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        return $this->applyVisibleTo(
            $this->mainQuery(
                $request ?: request(),
                $this->newQuery(),
                $search,
                $trashed
            ),
            $request ? $request->user() : null
        );
    }
}
