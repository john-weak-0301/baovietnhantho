<?php

namespace Core\User\Command;

use Exception;
use Core\User\User;
use Core\User\AssertHasPermission;
use Core\Database\DispatchEventsTrait;

class UploadAvatarHandler
{
    use AssertHasPermission,
        DispatchEventsTrait;

    /**
     * Handle upload user avatar.
     *
     * @param  UploadAvatar  $command
     * @return User
     *
     * @throws \Core\User\PermissionDeniedException
     */
    public function handle(UploadAvatar $command)
    {
        $user  = $command->user;
        $actor = $command->actor;

        if ($actor->id !== $user->id) {
            $this->assertCan($actor, 'edit', $user);
        }

        try {
            $user->changeAvatar($command->file);
        } catch (Exception $e) {
            report($e);
        }

        $user->save();
        $this->dispatchEventsFor($user);

        return $user;
    }
}
