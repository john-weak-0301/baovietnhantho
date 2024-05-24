<?php

namespace Core\User;

trait HasPermissions
{
    /**
     * Check whether or not the user is an administrator.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        // Give the first user in local is super admin.
        if ($this->getKey() === 1 && app()->environment('local')) {
            return true;
        }

        return $this->inRole(Group::ADMINISTRATOR_ID);
    }

    /**
     * Get a list of permissions that the user has.
     *
     * @return array|string[]
     */
    public function getPermissions(): array
    {
        return once(function () {
            $groupIds = [Group::GUEST_ID];

            // If a user's account hasn't been activated, they are essentially no
            // more than a guest. If they are activated, we can give them the
            // standard 'member' group, as well as any other groups they've been
            // assigned to.
            if ($this->email_verified_at) {
                $groupIds[] = Group::MEMBER_ID;
            }

            return $this->roles()
                ->orWhereIn('roles.id', $groupIds)
                ->pluck('permissions')
                ->prepend($this->permissions ?? [])
                ->map(function ($permissions) {
                    return array_keys(array_filter($permissions ?? []));
                })
                ->flatten(1)
                ->unique()
                ->all();
        });
    }

    /**
     * Check whether the user has a certain permission based on their groups.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Banned user cannot have any permissions.
        if ($this->isBanned()) {
            return false;
        }

        return in_array($permission, $this->getPermissions(), true);
    }
}
