<?php

namespace Core\Actions;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

trait CallsQueuedActions
{
    use InteractsWithQueue, SerializesModels;

    /**
     * The action class name.
     *
     * @var Action
     */
    public $action;

    /**
     * The method that should be called on the action.
     *
     * @var string
     */
    public $method;

    /**
     * The resolved fields.
     *
     * @var ActionFields
     */
    public $fields;

    /**
     * The batch ID of the action event records.
     *
     * @var string
     */
    public $batchId;

    /**
     * Call the action using the given callback.
     *
     * @param  callable  $callback
     * @return void
     */
    protected function callAction($callback): void
    {
        ActionEvent::markBatchAsRunning($this->batchId);

        $action = $this->setJobInstanceIfNecessary($this->action);

        $callback($action);

        if (!$this->job->hasFailed() && !$this->job->isReleased()) {
            ActionEvent::markBatchAsFinished($this->batchId);
        }
    }

    /**
     * Set the job instance of the given class if necessary.
     *
     * @param  mixed  $instance
     * @return mixed
     */
    protected function setJobInstanceIfNecessary($instance)
    {
        if (in_array(InteractsWithQueue::class, class_uses_recursive(get_class($instance)), true)) {
            $instance->setJob($this->job);
        }

        return $instance;
    }

    /**
     * Get the display name for the queued job.
     *
     * @return string
     */
    public function displayName(): string
    {
        return get_class($this->action);
    }
}
