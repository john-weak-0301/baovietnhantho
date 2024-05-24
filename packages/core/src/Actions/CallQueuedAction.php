<?php

namespace Core\Actions;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class CallQueuedAction
{
    use CallsQueuedActions;

    /**
     * The Eloquent model collection.
     *
     * @var Collection
     */
    public $models;

    /**
     * Create a new job instance.
     *
     * @param  Action  $action
     * @param  string  $method
     * @param  ActionFields  $fields
     * @param  Collection  $models
     * @param  string  $batchId
     * @return void
     */
    public function __construct(Action $action, $method, ActionFields $fields, Collection $models, $batchId)
    {
        $this->action  = $action;
        $this->method  = $method;
        $this->fields  = $fields;
        $this->models  = $models;
        $this->batchId = $batchId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->callAction(function (Action $action) {
            return $action->withBatchId($this->batchId)->{$this->method}($this->fields, $this->models);
        });
    }

    /**
     * Call the failed method on the job instance.
     *
     * @param  Exception  $e
     * @return void
     */
    public function failed(Exception $e): void
    {
        ActionEvent::markBatchAsFailed($this->batchId, $e);

        if ($method = $this->failedMethodName()) {
            call_user_func([$this->action, $method], $this->fields, $this->models, $e);
        }
    }

    /**
     * Get the name of the "failed" method that should be called for the action.
     *
     * @return string
     */
    protected function failedMethodName(): string
    {
        if (($method = $this->failedMethodForModel()) &&
            method_exists($this->action, $method)) {
            return $method;
        }

        return method_exists($this, 'failed')
            ? 'failed' : null;
    }

    /**
     * Get the appropriate "failed" method name for the action's model type.
     *
     * @return string|null
     */
    protected function failedMethodForModel(): ?string
    {
        if ($this->models->isNotEmpty()) {
            return 'failedFor'.Str::plural(class_basename($this->models->first()));
        }

        return null;
    }
}
