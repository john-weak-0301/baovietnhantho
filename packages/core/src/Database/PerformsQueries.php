<?php

namespace Core\Database;

use App\Model\Helpers\Publishable;
use Core\User\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Builder as ScoutBuilder;
use Orchid\Filters\Filterable;
use Orchid\Filters\HttpFilter;

trait PerformsQueries
{
    /**
     * Build an "index" query for the given resource.
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @param  string  $search
     * @param  string  $withTrashed
     * @return Builder
     */
    public function mainQuery(
        Request $request,
        Builder $query,
        $search = null,
        $withTrashed = TrashedStatus::DEFAULT
    ): Builder {
        return $this->applyFilters(
            $request,
            $this->initializeQuery($request, $query, $search, $withTrashed)
        )->tap(function (Builder $query) use ($request) {
            $this->applyOrderings(
                $this->applyMainQuery($request, $query->with(static::$with))
            );
        });
    }

    /**
     * Initialize the given index query.
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @param  string  $search
     * @param  string  $withTrashed
     * @return Builder
     */
    protected function initializeQuery(Request $request, $query, $search, $withTrashed): Builder
    {
        if (empty(trim($search))) {
            return $this->applySoftDeleteConstraint($query, $withTrashed);
        }

        return static::usesScout()
            ? $this->initializeQueryUsingScout($request, $query, $search, $withTrashed)
            : $this->applySearch($this->applySoftDeleteConstraint($query, $withTrashed), $search);
    }

    /**
     * Apply the search query to the query.
     *
     * @param  Builder  $query
     * @param  string  $search
     * @return Builder
     */
    protected function applySearch($query, $search): Builder
    {
        return $query->where(function (Builder $query) use ($search) {
            $model = $query->getModel();

            $connectionType = $query->getModel()->getConnection()->getDriverName();

            $canSearchPrimaryKey = is_numeric($search)
                                   && in_array($model->getKeyType(), ['int', 'integer'], true)
                                   && ($connectionType !== 'pgsql' || $search <= 2147483647);

            if ($canSearchPrimaryKey) {
                $query->orWhere($model->getQualifiedKeyName(), $search);
            }

            $likeOperator = $connectionType === 'pgsql' ? 'ilike' : 'like';

            foreach (static::searchableColumns() as $column) {
                if (in_array(Translatable::class, class_uses_recursive($model), true) &&
                    $model->isTranslationAttribute($column)) {
                    $query->orWhereTranslationLike($column, '%'.$search.'%');
                } else {
                    $query->orWhere($model->qualifyColumn($column), $likeOperator, '%'.$search.'%');
                }
            }
        });
    }

    /**
     * Initialize the given index query using Laravel Scout.
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @param  string  $search
     * @param  string  $withTrashed
     * @return Builder
     */
    protected function initializeQueryUsingScout(Request $request, $query, $search, $withTrashed): Builder
    {
        /* @noinspection PhpUndefinedMethodInspection */
        $keys = tap(
            $this->applySoftDeleteConstraint(static::newModel()->search($search), $withTrashed),
            function ($scoutBuilder) use ($request) {
                $this->applyScoutQuery($request, $scoutBuilder);
            }
        )->take(200)->get()->map->getKey();

        /** @noinspection PhpUndefinedMethodInspection */
        return $this->applySoftDeleteConstraint(
            $query->whereIn($query->getModel()->getQualifiedKeyName(), $keys->all()),
            $withTrashed
        );
    }

    /**
     * Scope the given query for the soft delete state.
     *
     * @param  mixed  $query
     * @param  string  $withTrashed
     * @return mixed
     */
    protected function applySoftDeleteConstraint($query, $withTrashed)
    {
        return static::softDeletes()
            ? (new ApplySoftDeleteConstraint)->__invoke($query, $withTrashed)
            : $query;
    }

    /**
     * Apply any applicable filters to the query.
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    protected function applyFilters(Request $request, $query): Builder
    {
        if ($this->modelHasFilterable()) {
            /* @noinspection PhpUndefinedMethodInspection */
            $query->filters(new HttpFilter($request));
        }

        return $query;
    }

    /**
     * Determines if model has Filterable trait.
     *
     * @return bool
     */
    protected function modelHasFilterable(): bool
    {
        return once(function () {
            return in_array(Filterable::class, class_uses_recursive(static::$model), true);
        });
    }

    /**
     * Apply any applicable orderings to the query.
     *
     * @param  Builder  $query
     * @return Builder
     */
    protected function applyOrderings(Builder $query): Builder
    {
        return empty($query->orders)
            ? $query->latest($query->getModel()->getQualifiedKeyName())
            : $query;
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  Request  $request
     * @param  Builder  $query
     * @return Builder
     */
    protected function applyMainQuery(Request $request, $query): Builder
    {
        if (in_array(Publishable::class, class_uses_recursive($query->getModel()))) {
            $query->withUnpublished();
        }

        return $query;
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  Request  $request
     * @param  ScoutBuilder  $query
     * @return ScoutBuilder
     */
    protected function applyScoutQuery(Request $request, $query): ScoutBuilder
    {
        return $query;
    }

    /**
     * Scope a query to only include records that are visible to a user.
     *
     * @param  Builder  $query
     * @param  User  $user
     * @return Builder
     */
    public function applyVisibleTo(Builder $query, User $user = null)
    {
        $model = $query->getModel();

        if (in_array(ScopeVisibilityTrait::class, class_uses_recursive($model), true)) {
            /* @noinspection PhpUndefinedMethodInspection */
            $query->whereVisibleTo($user);
        }

        return $query;
    }
}
