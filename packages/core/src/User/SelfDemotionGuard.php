<?php

namespace Core\User;

use Core\User\Event\Saving;
use Illuminate\Support\Arr;

class SelfDemotionGuard
{
    /**
     * Prevent an admin from removing their admin permission via the API.
     *
     * @param  Saving  $event
     * @throws PermissionDeniedException
     */
    public function handle(Saving $event)
    {
        // Non-admin users pose no problem.
        if (!$event->actor->isSuperAdmin()) {
            return;
        }

        // Only admins can demote users, which means demoting other users is
        // fine, because we still have at least one admin (the actor) left.
        if ($event->actor->id !== $event->user->id) {
            return;
        }

        $groups = Arr::get($event->data, 'relationships.groups.data');

        // If there is no group data (not even an empty array), this means
        // groups were not changed (and thus not removed) - we're fine!
        if (!isset($groups)) {
            return;
        }

        $adminGroups = array_filter($groups, function ($group) {
            return $group['id'] === Group::ADMINISTRATOR_ID;
        });

        // As long as the user is still part of the admin group, all is good.
        if ($adminGroups) {
            return;
        }

        // If we get to this point, we have to prohibit the edit.
        throw new PermissionDeniedException;
    }
}
