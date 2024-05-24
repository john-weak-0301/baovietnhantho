<?php

namespace Core\Database;

use Core\User\User;
use Core\User\Guest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method $this whereVisibleTo(User $actor = null)
 */
trait ScopeVisibilityTrait
{
    /**
     * Scope a query to only include records that are visible to a user.
     *
     * @param  Builder  $query
     * @param  User|null  $actor
     */
    public function scopeWhereVisibleTo(Builder $query, User $actor = null): void
    {
        if ($actor === null) {
            $actor = Auth::user() ?: once(function () {
                return new Guest;
            });
        }

        static::$dispatcher->dispatch(
            new ScopeModelVisibility($query, $actor, 'view')
        );
    }
}
