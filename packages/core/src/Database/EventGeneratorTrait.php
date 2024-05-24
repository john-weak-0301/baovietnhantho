<?php

namespace Core\Database;

trait EventGeneratorTrait
{
    /**
     * Store the pending events.
     *
     * @var array
     */
    protected $pendingEvents = [];

    /**
     * Raise a new event.
     *
     * @param  mixed  $event
     */
    public function raise($event): void
    {
        $this->pendingEvents[] = $event;
    }

    /**
     * Return and reset all pending events.
     *
     * @return array
     */
    public function releaseEvents(): array
    {
        $events = $this->pendingEvents;

        $this->pendingEvents = [];

        return $events;
    }
}
