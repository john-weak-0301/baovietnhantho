<?php

namespace Core\Actions;

use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Fluent;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Core\Screens\ScreenRequest;

class ActionRequest extends ScreenRequest
{
    /**
     * Get the action instance specified by the request.
     *
     * @return Action
     */
    public function action(): Action
    {
        return once(function () {
            return $this->availableActions()->first(function (Action $action) {
                return $action->uriKey() === $this->input('action');
            }) ?: abort($this->actionExists() ? 403 : 404);
        });
    }

    /**
     * Get the all actions for the request.
     *
     * @return Collection
     */
    protected function resolveActions(): Collection
    {
        return $this->screen()->resolveActions();
    }

    /**
     * Get the possible actions for the request.
     *
     * @return Collection
     */
    protected function availableActions(): Collection
    {
        return $this->screen()->availableActions($this);
    }

    /**
     * Determine if the specified action exists at all.
     *
     * @return bool
     */
    protected function actionExists(): bool
    {
        return $this->resolveActions()->contains(function (Action $action) {
            return $action->uriKey() === $this->input('action');
        });
    }

    /**
     * Get the selected models for the action in chunks.
     *
     * @param  int  $count
     * @param  Closure  $callback
     * @return mixed
     */
    public function chunks($count, Closure $callback)
    {
        $output = [];

        $this->toSelectedResourceQuery()
            ->when(!$this->forAllMatchingResources(), function (Builder $query) {
                $query->whereKey($this->selectedResources());
            })
            ->latest($this->model()->getKeyName())
            ->chunk($count, function ($chunk) use ($callback, &$output) {
                $output[] = $callback($this->mapChunk($chunk));
            });

        return $output;
    }

    /**
     * Get the query for the models that were selected by the user.
     *
     * @return Builder
     */
    protected function toSelectedResourceQuery(): Builder
    {
        $resource = $this->resource();

        if ($this->forAllMatchingResources()) {
            return $resource->query($this);
        }

        return $resource->newQueryWithoutScopes();
    }

    /**
     * Map the chunk of models into an appropriate state.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $chunk
     * @return ActionModelCollection
     */
    protected function mapChunk($chunk): ActionModelCollection
    {
        return ActionModelCollection::make($chunk);
    }

    /**
     * Validate the given fields.
     *
     * @return void
     */
    public function validateFields(): void
    {
        // TODO: ...

        /*$this->validate(collect($this->action()->fields())->mapWithKeys(function ($field) {
            return $field->getCreationRules($this);
        })->all());*/
    }

    /**
     * Resolve the fields for database storage using the request.
     *
     * @return array
     */
    public function resolveFieldsForStorage(): array
    {
        return collect($this->resolveFields()->getAttributes())->map(function ($attribute) {
            return $attribute instanceof UploadedFile ? $attribute->hashName() : $attribute;
        })->all();
    }

    /**
     * Resolve the fields using the request.
     *
     * @return ActionFields
     */
    public function resolveFields(): ActionFields
    {
        return new ActionFields(collect(), collect());

        /*return once(function () {
            $fields = new Fluent;

            $results = collect($this->action()->fields())->mapWithKeys(function ($field) use ($fields) {
                return [$field->attribute => $field->fillForAction($this, $fields)];
            });

            return new ActionFields(collect($fields->getAttributes()), $results->filter(function ($field) {
                return is_callable($field);
            }));
        });*/
    }

    /**
     * Get the key of model that lists the action on its dashboard.
     *
     * When running pivot actions, this is the key of the owning model.
     *
     * @param  Model  $model
     * @return int
     */
    public function actionableKey($model): int
    {
        return $model->getKey();
    }

    /**
     * Get the model instance that lists the action on its dashboard.
     *
     * When running pivot actions, this is the owning model.
     *
     * @return Model
     */
    public function actionableModel(): Model
    {
        return $this->model();
    }

    /**
     * Get the key of model that is the target of the action.
     *
     * When running pivot actions, this is the key of the target model.
     *
     * @param  Model  $model
     * @return int
     */
    public function targetKey($model): int
    {
        return $model->getKey();
    }

    /**
     * Get an instance of the target model of the action.
     *
     * @return Model
     */
    public function targetModel(): Model
    {
        return $this->model();
    }

    /**
     * Returns resources for the actions.
     *
     * @return array
     */
    public function selectedResources(): array
    {
        $resources = $this->input('resources');

        if (!is_array($resources)) {
            $resources = explode(',', $resources);
        }

        return array_filter(array_map('intval', $resources));
    }

    /**
     * Determine if the request is for all matching resources.
     *
     * @return bool
     */
    public function forAllMatchingResources(): bool
    {
        return $this->input('resources') === 'all';
    }
}
