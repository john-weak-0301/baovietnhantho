<?php

namespace Core\Database;

use Core\User\User;

trait DispatchEventsTrait
{
    /**
     * Dispatch all events for an entity.
     *
     * @param  object  $model
     * @param  User  $actor
     */
    public function dispatchEventsFor($model, User $actor = null): void
    {
        if (!in_array(EventGeneratorTrait::class, class_uses_recursive($model), true)) {
            return;
        }

        foreach ($model->releaseEvents() as $event) {
            $event->actor = $actor;

            app('events')->dispatch($event);
        }
    }
}
