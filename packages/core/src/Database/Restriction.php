<?php

namespace Core\Database;

use Illuminate\Support\Str;
use Illuminate\Contracts\Events\Dispatcher;

abstract class Restriction
{
    /**
     * The model class name to handle.
     *
     * @var string
     */
    protected static $model;

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ScopeModelVisibility::class, [$this, 'handle']);
    }

    /**
     * Handle ScopeModelVisibility events.
     *
     * @param  ScopeModelVisibility  $event
     */
    public function handle(ScopeModelVisibility $event)
    {
        if (!$event->query->getModel() instanceof static::$model) {
            return;
        }

        if (strpos($event->ability, 'view') === false) {
            return;
        }

        if (method_exists($this, $method = Str::camel('find'.substr($event->ability, 4)))) {
            $this->$method($event->actor, $event->query);
        }
    }
}
