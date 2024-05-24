<?php

namespace Core\User;

trait AssertHasPermission
{
    /**
     * Assert that given actor has a given ability.
     *
     * @param  User  $actor
     * @param  string  $ability
     * @param  mixed  $arguments
     * @throws PermissionDeniedException
     */
    protected function assertCan(User $actor, $ability, $arguments = []): void
    {
        $this->assertPermission($actor->can($ability, $arguments));
    }

    /**
     * Assert that given actor is guest.
     *
     * @param  User  $actor
     * @throws PermissionDeniedException
     */
    protected function assertGuest(User $actor): void
    {
        $this->assertPermission($actor->isGuest());
    }

    /**
     * Assert that given actor is user.
     *
     * @param  User  $actor
     * @throws PermissionDeniedException
     */
    protected function assertRegistered(User $actor): void
    {
        $this->assertPermission(!$actor->isGuest());
    }

    /**
     * Assert that given actor is supper admin.
     *
     * @param  User  $actor
     * @throws PermissionDeniedException
     */
    protected function assertSuperAdmin(User $actor): void
    {
        $this->assertPermission($actor->isSuperAdmin());
    }

    /**
     * @param  bool  $condition
     * @throws PermissionDeniedException
     */
    protected function assertPermission($condition): void
    {
        if (!$condition) {
            throw new PermissionDeniedException;
        }
    }
}
