<?php

namespace Core\Actions;

use Throwable;
use Illuminate\Support\Facades\Queue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;

class DispatchAction
{
    /**
     * Dispatch the given action.
     *
     * @param  ActionRequest  $request
     * @param  Action  $action
     * @param  string  $method
     * @param  Collection  $models
     * @param  ActionFields  $fields
     * @return mixed|null
     *
     * @throws Throwable
     */
    public static function forModels(
        ActionRequest $request,
        Action $action,
        $method,
        Collection $models,
        ActionFields $fields
    ) {
        if ($models->isEmpty()) {
            return null;
        }

        if ($action instanceof ShouldQueue) {
            return static::queueForModels($request, $action, $method, $models);
        }

        return Transaction::run(function ($batchId) use ($fields, $request, $action, $method, $models) {
            if (!$action->withoutActionEvents) {
                ActionEvent::createForModels($request, $action, $batchId, $models);
            }

            return $action->withBatchId($batchId)->{$method}($fields, $models);
        }, function ($batchId) use ($action) {
            if (!$action->withoutActionEvents) {
                ActionEvent::markBatchAsFinished($batchId);
            }
        });
    }

    /**
     * Dispatch the given action in the background.
     *
     * @param  ActionRequest  $request
     * @param  Action  $action
     * @param  string  $method
     * @param  Collection  $models
     * @return mixed
     *
     * @throws Throwable
     */
    protected static function queueForModels(ActionRequest $request, Action $action, $method, Collection $models)
    {
        return Transaction::run(function ($batchId) use ($request, $action, $method, $models) {
            if (!$action->withoutActionEvents) {
                ActionEvent::createForModels($request, $action, $batchId, $models, 'waiting');
            }

            Queue::connection(static::connection($action))->pushOn(
                static::queue($action),
                new CallQueuedAction(
                    $action,
                    $method,
                    $request->resolveFields(),
                    $models,
                    $batchId
                )
            );
        });
    }

    /**
     * Extract the queue connection for the action.
     *
     * @param  Action  $action
     * @return string|null
     */
    protected static function connection($action) :?string
    {
        return $action->connection ?? null;
    }

    /**
     * Extract the queue name for the action.
     *
     * @param  Action  $action
     * @return string|null
     */
    protected static function queue($action): ?string
    {
        return $action->queue ?? null;
    }
}
