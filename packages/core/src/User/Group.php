<?php

namespace Core\User;

use MyCLabs\Enum\Enum;

class Group extends Enum
{
    /**
     * The ID of the administrator group.
     */
    public const ADMINISTRATOR_ID = 1;

    /**
     * The ID of the mod group.
     */
    public const MODERATOR_ID = 2;

    /**
     * The ID of the member group.
     */
    public const MEMBER_ID = 3;

    /**
     * The ID of the guest group.
     */
    public const GUEST_ID = 4;
}
