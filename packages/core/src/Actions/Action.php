<?php

namespace Core\Actions;

use Closure;
use JsonSerializable;
use Core\Support\Util;
use Core\Elements\Metable;
use Core\Elements\AuthorizedToSee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Action implements JsonSerializable
{
    use Metable;
    use AuthorizedToSee;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name;

    /**
     * The action's component.
     *
     * @var string
     */
    public $component = 'confirm-action-modal';

    /**
     * Indicates if need to skip log action events for models.
     *
     * @var bool
     */
    public $withoutActionEvents = false;

    /**
     * Determine where the action redirection should be without confirmation.
     *
     * @var bool
     */
    public $withoutConfirmation = false;

    /**
     * The current batch ID being handled by the action.
     *
     * @var string|null
     */
    public $batchId;

    /**
     * The callback used to authorize running the action.
     *
     * @var Closure|null
     */
    public $runCallback;

    /**
     * The number of models that should be included in each chunk.
     *
     * @var int
     */
    public static $chunkCount = 200;

    /**
     * Determine if the action is executable for the given request.
     *
     * @param  Request  $request
     * @param  Model  $model
     * @return bool
     */
    public function authorizedToRun($request, $model): bool
    {
        return $this->runCallback ? call_user_func($this->runCallback, $request, $model) : true;
    }

    /**a
     * Return a message response from the action.
     *
     * @param  string  $message
     * @return array
     */
    public static function message($message): array
    {
        return ['message' => $message];
    }

    /**
     * Return a dangerous message response from the action.
     *
     * @param  string  $message
     * @return array
     */
    public static function danger($message): array
    {
        return ['danger' => $message];
    }

    /**
     * Return a delete response from the action.
     *
     * @return array
     */
    public static function deleted(): array
    {
        return ['deleted' => true];
    }

    /**
     * Return a redirect response from the action.
     *
     * @param  string  $url
     * @return array
     */
    public static function redirect($url): array
    {
        return ['redirect' => $url];
    }

    /**
     * Return an open new tab response from the action.
     *
     * @param  string  $url
     * @return array
     */
    public static function openInNewTab($url): array
    {
        return ['openInNewTab' => $url];
    }

    /**
     * Return a download response from the action.
     *
     * @param  string  $url
     * @param  string  $name
     * @return array
     */
    public static function download($url, $name): array
    {
        return ['download' => $url, 'name' => $name];
    }

    /**
     * Execute the action for the given request.
     *
     * @param  ActionRequest  $request
     * @return mixed
     *
     * @throws MissingActionHandlerException
     */
    public function handleRequest(ActionRequest $request)
    {
        $method = ActionMethod::determine($this, $request->targetModel());

        if (!method_exists($this, $method)) {
            throw new MissingActionHandlerException('Action handler ['.get_class($this).'@'.$method.'] not defined.');
        }

        $wasExecuted = false;

        $fields = $request->resolveFields();

        $results = $request->chunks(
            static::$chunkCount,
            function (ActionModelCollection $models) use ($fields, $request, $method, &$wasExecuted) {
                $models = $models->filterForExecution($request);

                if (count($models) > 0) {
                    $wasExecuted = true;
                }

                return DispatchAction::forModels(
                    $request,
                    $this,
                    $method,
                    $models,
                    $fields
                );
            }
        );

        if (!$wasExecuted) {
            return static::danger(__('Sorry! You are not authorized to perform this action.'));
        }

        return $this->handleResult($fields, $results);
    }

    /**
     * Handle chunk results.
     *
     * @param  ActionFields  $fields
     * @param  array  $results
     *
     * @return mixed
     */
    public function handleResult(ActionFields $fields, $results)
    {
        return count($results) ? end($results) : null;
    }

    /**
     * Mark the action event record for the model as finished.
     *
     * @param  Model  $model
     * @return int
     */
    protected function markAsFinished($model): int
    {
        return $this->batchId ? ActionEvent::markAsFinished($this->batchId, $model) : 0;
    }

    /**
     * Mark the action event record for the model as failed.
     *
     * @param  Model  $model
     * @param  Throwable|string  $e
     * @return int
     */
    protected function markAsFailed($model, $e = null): int
    {
        return $this->batchId ? ActionEvent::markAsFailed($this->batchId, $model, $e) : 0;
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(): array
    {
        return [];
    }

    /**
     * Set the current batch ID being handled by the action.
     *
     * @param  string  $batchId
     * @return $this
     */
    public function withBatchId($batchId)
    {
        $this->batchId = $batchId;

        return $this;
    }

    /**
     * Set the callback to be run to authorize running the action.
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function canRun(Closure $callback)
    {
        $this->runCallback = $callback;

        return $this;
    }

    /**
     * Get the component name for the action.
     *
     * @return string
     */
    public function component(): string
    {
        return $this->component;
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name ?: Util::humanize($this);
    }

    /**
     * Get the URI key for the action.
     *
     * @return string
     */
    public function uriKey(): string
    {
        return Str::slug($this->name());
    }

    /**
     * Set the action to execute instantly.
     *
     * @return $this
     */
    public function withoutConfirmation()
    {
        $this->withoutConfirmation = true;

        return $this;
    }

    /**
     * Set the action to skip action events for models.
     *
     * @return $this
     */
    public function withoutActionEvents()
    {
        $this->withoutActionEvents = true;

        return $this;
    }

    /**
     * Prepare the action for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'component'           => $this->component(),
            'destructive'         => $this instanceof DestructiveAction,
            'name'                => $this->name(),
            'uriKey'              => $this->uriKey(),
            'fields'              => [],
            'withoutConfirmation' => $this->withoutConfirmation,
        ], $this->meta());
    }
}
