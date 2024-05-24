<?php

namespace Core\User\Command;

use Core\User\User;
use Core\User\UserRepository;
use Core\User\AssertHasPermission;

class UnbanUserHandler
{
    use AssertHasPermission;

    /**
     * The user repository.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Constructor.
     *
     * @param  UserRepository  $users
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Perform ban a user.
     *
     * @param  BanUser  $command
     * @return User
     *
     * @throws \Core\User\PermissionDeniedException
     */
    public function handle(BanUser $command)
    {
        $this->assertCan($actor = $command->actor, 'bannable');

        $user = $this->users->findOrFail($command->userId, $actor);

        if ($user->isBanned()) {
            $user->unban();
        }

        return $user;
    }
}
