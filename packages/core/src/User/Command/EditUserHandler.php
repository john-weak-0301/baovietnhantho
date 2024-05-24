<?php

namespace Core\User\Command;

use Core\User\User;
use Core\User\Event\Saving;
use Core\User\AssertHasPermission;
use Core\Database\DispatchEventsTrait;

class EditUserHandler
{
    use DispatchEventsTrait,
        AssertHasPermission;

    /**
     * Handle update user data.
     *
     * @param  EditUser  $command
     * @return User|int
     *
     * @throws \Core\User\PermissionDeniedException
     */
    public function handle(EditUser $command)
    {
        $user       = $command->user;
        $actor      = $command->actor;
        $attributes = $command->data;

        $isSelf  = $actor->id === $user->id;
        $canEdit = $actor->can('edit', $user);

        $validate = [];

        // Perform change the username.
        if (isset($attributes['username'])) {
            $this->assertPermission($canEdit);

            $user->rename($attributes['username']);
        }

        // Perform change the email address.
        if (isset($attributes['email'])) {
            if ($isSelf) {
                $user->requestEmailChange($attributes['email']);

                if ($attributes['email'] !== $user->email) {
                    $validate['email'] = $attributes['email'];
                }
            } else {
                $this->assertPermission($canEdit);
                $user->changeEmail($attributes['email']);
            }
        }

        // Only admin can set user email is activated.
        if (!empty($attributes['isEmailConfirmed']) && $actor->isSuperAdmin()) {
            $user->activate();
        }

        // Perform change the user password.
        if (isset($attributes['password'])) {
            $this->assertPermission($canEdit);

            $user->changePassword($attributes['password']);

            $validate['password'] = $attributes['password'];
        }

        if (!empty($attributes['preferences'])) {
            $this->assertPermission($isSelf);

            foreach ($attributes['preferences'] as $key => $value) {
                $user->setPreference($key, $value);
            }
        }

        event(new Saving($user, $actor, $attributes));

        // $this->validator->setUser($user);
        // $this->validator->assertValid(array_merge($user->getDirty(), $validate));

        $user->save();

        $this->dispatchEventsFor($user, $actor);

        return $user;
    }
}
