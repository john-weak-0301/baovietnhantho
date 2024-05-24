<?php

namespace Core\User\Command;

use Core\User\User;
use Core\User\UserRepository;
use Core\User\AssertHasPermission;

class BanUserHandler
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
        $actor = $command->actor;
        $data  = $command->data;

        $user = $this->users->findOrFail($command->userId, $actor);

        $this->assertCan($actor, 'bannable', $user);

        $user->ban([
            'comment'         => $data['comment'] ?? null,
            'expired_at'      => $data['expired_at'] ?? null,
            'created_by_id'   => $actor->getKey(),
            'created_by_type' => $actor->getMorphClass(),
        ]);

        return $user;
    }
}
