<?php

namespace Core\Actions;

use DateTime;
use Throwable;
use Core\User\UserRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Auth\Authenticatable;

class ActionEvent extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return 'action_events';
    }

    /**
     * Get the user that initiated the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(get_class(UserRepository::newModel()));
    }

    /**
     * Get the target of the action for user interface linking.
     */
    public function target()
    {
        return $this->morphTo('target', 'target_type', 'target_id')->withTrashed();
    }

    /**
     * Create a new action event instance for a resource creation.
     *
     * @param  Authenticatable  $user
     * @param  Model  $model
     * @return Model
     */
    public static function forResourceCreate($user, $model): Model
    {
        return new static([
            'batch_id'        => (string) Str::orderedUuid(),
            'user_id'         => $user->getAuthIdentifier(),
            'name'            => 'Create',
            'actionable_type' => $model->getMorphClass(),
            'actionable_id'   => $model->getKey(),
            'target_type'     => $model->getMorphClass(),
            'target_id'       => $model->getKey(),
            'model_type'      => $model->getMorphClass(),
            'model_id'        => $model->getKey(),
            'fields'          => '',
            'status'          => 'finished',
            'exception'       => '',
        ]);
    }

    /**
     * Create a new action event instance for a resource update.
     *
     * @param  Authenticatable  $user
     * @param  Model  $model
     * @return Model
     */
    public static function forResourceUpdate($user, $model): Model
    {
        return new static([
            'batch_id'        => (string) Str::orderedUuid(),
            'user_id'         => $user->getAuthIdentifier(),
            'name'            => 'Update',
            'actionable_type' => $model->getMorphClass(),
            'actionable_id'   => $model->getKey(),
            'target_type'     => $model->getMorphClass(),
            'target_id'       => $model->getKey(),
            'model_type'      => $model->getMorphClass(),
            'model_id'        => $model->getKey(),
            'fields'          => '',
            'status'          => 'finished',
            'exception'       => '',
        ]);
    }

    /**
     * Create new action event instances for resource deletes.
     *
     * @param  Authenticatable  $user
     * @param  Collection  $models
     * @return Collection
     */
    public static function forResourceDelete($user, Collection $models): Collection
    {
        return static::forSoftDeleteAction('Delete', $user, $models);
    }

    /**
     * Create new action event instances for resource restorations.
     *
     * @param  Authenticatable  $user
     * @param  Collection  $models
     * @return Collection
     */
    public static function forResourceRestore($user, Collection $models): Collection
    {
        return static::forSoftDeleteAction('Restore', $user, $models);
    }

    /**
     * Create new action event instances for resource soft deletions.
     *
     * @param  string  $action
     * @param  Authenticatable  $user
     * @param  Collection  $models
     * @return Collection
     */
    public static function forSoftDeleteAction($action, $user, Collection $models): Collection
    {
        $batchId = (string) Str::orderedUuid();

        return $models->map(function (Model $model) use ($action, $user, $batchId) {
            return new static([
                'batch_id'        => $batchId,
                'user_id'         => $user->getAuthIdentifier(),
                'name'            => $action,
                'actionable_type' => $model->getMorphClass(),
                'actionable_id'   => $model->getKey(),
                'target_type'     => $model->getMorphClass(),
                'target_id'       => $model->getKey(),
                'model_type'      => $model->getMorphClass(),
                'model_id'        => $model->getKey(),
                'fields'          => '',
                'status'          => 'finished',
                'exception'       => '',
                'created_at'      => new DateTime,
                'updated_at'      => new DateTime,
            ]);
        });
    }

    /**
     * Create new action event instances for resource detachments.
     *
     * @param  Authenticatable  $user
     * @param  Model  $parent
     * @param  Collection  $models
     * @param  string  $pivotClass
     * @return Collection
     */
    public static function forResourceDetach($user, $parent, Collection $models, $pivotClass): Collection
    {
        $batchId = (string) Str::orderedUuid();

        return $models->map(function (Model $model) use ($user, $parent, $pivotClass, $batchId) {
            return new static([
                'batch_id'        => $batchId,
                'user_id'         => $user->getAuthIdentifier(),
                'name'            => 'Detach',
                'actionable_type' => $parent->getMorphClass(),
                'actionable_id'   => $parent->getKey(),
                'target_type'     => $model->getMorphClass(),
                'target_id'       => $model->getKey(),
                'model_type'      => $pivotClass,
                'model_id'        => null,
                'fields'          => '',
                'status'          => 'finished',
                'exception'       => '',
                'created_at'      => new DateTime,
                'updated_at'      => new DateTime,
            ]);
        });
    }

    /**
     * Create the action records for the given models.
     *
     * @param  ActionRequest  $request
     * @param  Action  $action
     * @param  string  $batchId
     * @param  Collection  $models
     * @param  string  $status
     * @return void
     */
    public static function createForModels(
        ActionRequest $request,
        Action $action,
        $batchId,
        Collection $models,
        $status = 'running'
    ): void {
        $models = $models->map(function (Model $model) use ($request, $action, $batchId, $status) {
            return array_merge(
                static::defaultAttributes($request, $action, $batchId, $status),
                [
                    'actionable_id' => $request->actionableKey($model),
                    'target_id'     => $request->targetKey($model),
                    'model_id'      => $model->getKey(),
                ]
            );
        });

        $models->chunk(50)->each(function ($models) {
            static::insert($models->all());
        });

        static::prune($models);
    }

    /**
     * Get the default attributes for creating a new action event.
     *
     * @param  ActionRequest  $request
     * @param  Action  $action
     * @param  string  $batchId
     * @param  string  $status
     * @return array
     *
     * @throws Throwable
     */
    public static function defaultAttributes(
        ActionRequest $request,
        Action $action,
        $batchId,
        $status = 'running'
    ): array {
        return [
            'batch_id'        => $batchId,
            'user_id'         => $request->user()->getAuthIdentifier(),
            'name'            => $action->name(),
            'actionable_type' => $request->actionableModel()->getMorphClass(),
            'target_type'     => $request->model()->getMorphClass(),
            'model_type'      => $request->actionableModel()->getMorphClass(),
            'fields'          => serialize($request->resolveFieldsForStorage()),
            'status'          => $status,
            'exception'       => '',
            'created_at'      => new DateTime,
            'updated_at'      => new DateTime,
        ];
    }

    /**
     * Prune the action events for the given types.
     *
     * @param  Collection  $models
     * @param  int  $limit
     */
    public static function prune($models, $limit = 25): void
    {
        $models->each(function ($model) use ($limit) {
            static::query()
                  ->where('actionable_id', $model['actionable_id'])
                  ->where('actionable_type', $model['actionable_type'])
                  ->whereNotIn('id', function (Builder $query) use ($model, $limit) {
                      $query->select('id')->fromSub(
                          static::select('id')->orderBy('id', 'desc')
                                ->where('actionable_id', $model['actionable_id'])
                                ->where('actionable_type', $model['actionable_type'])
                                ->limit($limit)->toBase(),
                          'action_events_temp'
                      );
                  })->delete();
        });
    }

    /**
     * Mark the given batch as running.
     *
     * @param  string  $batchId
     * @return int
     */
    public static function markBatchAsRunning($batchId): int
    {
        return static::query()
                     ->where('batch_id', $batchId)
                     ->whereNotIn('status', ['finished', 'failed'])
                     ->update(['status' => 'running']);
    }

    /**
     * Mark the given batch as finished.
     *
     * @param  string  $batchId
     * @return int
     */
    public static function markBatchAsFinished($batchId): int
    {
        return static::query()
                     ->where('batch_id', $batchId)
                     ->whereNotIn('status', ['finished', 'failed'])
                     ->update(['status' => 'finished']);
    }

    /**
     * Mark a given action event record as finished.
     *
     * @param  string  $batchId
     * @param  Model  $model
     * @return int
     */
    public static function markAsFinished($batchId, $model): int
    {
        return static::updateStatus($batchId, $model, 'finished');
    }

    /**
     * Mark the given batch as failed.
     *
     * @param  string  $batchId
     * @param  Throwable  $e
     * @return int
     */
    public static function markBatchAsFailed($batchId, $e = null): int
    {
        return static::query()
                     ->where('batch_id', $batchId)
                     ->whereNotIn('status', ['finished', 'failed'])
                     ->update([
                         'status'    => 'failed',
                         'exception' => $e ? (string) $e : '',
                     ]);
    }

    /**
     * Mark a given action event record as failed.
     *
     * @param  string  $batchId
     * @param  Model  $model
     * @param  Throwable|string  $e
     * @return int
     */
    public static function markAsFailed($batchId, $model, $e = null): int
    {
        return static::updateStatus($batchId, $model, 'failed', $e);
    }

    /**
     * Update the status of a given action event.
     *
     * @param  string  $batchId
     * @param  Model  $model
     * @param  string  $status
     * @param  Throwable|string  $e
     * @return int
     */
    public static function updateStatus($batchId, $model, $status, $e = null): int
    {
        return static::query()
                     ->where('batch_id', $batchId)
                     ->where('model_type', $model->getMorphClass())
                     ->where('model_id', $model->getKey())
                     ->update(['status' => $status, 'exception' => (string) $e]);
    }
}
