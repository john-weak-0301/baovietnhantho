<?php

namespace Core\Screens;

use Core\Actions\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait ResolvesActions
{
    /**
     * Get the actions available on the entity.
     *
     * @return array
     */
    public function actions(): array
    {
        return [];
    }

    /**
     * Get the actions for the given request.
     *
     * @return Collection
     */
    public function resolveActions(): Collection
    {
        return collect(array_values($this->actions()));
    }

    /**
     * Get the actions that are available for the given request.
     *
     * @param  Request  $request
     * @return Collection
     */
    public function availableActions(Request $request = null): Collection
    {
        $request = $request ?? app(Request::class);

        return $this->resolveActions()->filter(function (Action $action) use ($request) {
            return $action->authorizedToSee($request);
        })->values();
    }
}
