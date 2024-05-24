<?php

namespace App\Model;

use Core\User\User as Authenticatable;
use Orchid\Platform\Notifications\DashboardNotification;

/**
 * App\Model\User
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /**
     * @return User
     */
    public static function getAdmin()
    {
        return self::first() ?: new User();
    }

    public function sendDashboardNotify(
        string $title,
        string $message,
        string $action = null,
        string $type = DashboardNotification::INFO
    ) {
        if (!$this->isSuperAdmin()) {
            return;
        }

        $this->notify(new DashboardNotification(
            compact('title', 'message', 'action', 'type')
        ));
    }
}
