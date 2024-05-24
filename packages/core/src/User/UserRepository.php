<?php

namespace Core\User;

use Core\Database\Repository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository extends Repository
{
    /**
     * Find a user by ID, optionally making sure it is visible to a certain
     * user, or throw an exception.
     *
     * @param  int  $id
     * @param  User  $actor
     * @return User
     *
     * @throws ModelNotFoundException
     */
    public function findOrFail($id, User $actor = null): User
    {
        $query = $this->newQuery()->where('id', $id);

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->applyVisibleTo($query, $actor)->firstOrFail();
    }

    /**
     * Find a user by an identification (username or email).
     *
     * @param  string  $identification
     * @return User|null
     */
    public function findByIdentification($identification): ?User
    {
        $field = filter_var($identification, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return $this->newQuery()->where($field, $identification)->first();
    }

    /**
     * Find a user by email.
     *
     * @param  string  $email
     * @return User|null
     */
    public function findByEmail($email): ?User
    {
        return $this->newQuery()->where('email', $email)->first();
    }

    /**
     * Get the ID of a user with the given username.
     *
     * @param  string  $username
     * @param  User|null  $actor
     * @return int|null
     */
    public function getIdForUsername($username, User $actor = null): ?int
    {
        $query = $this->newQuery()->where('username', 'like', $username);

        return $this->applyVisibleTo($query, $actor)->value('id');
    }

    /**
     * Find users by matching a string of words against their username,
     * optionally making sure they are visible to a certain user.
     *
     * @param  string  $string
     * @param  User|null  $actor
     * @return array
     */
    public function getIdsForUsername($string, User $actor = null): array
    {
        $string = $this->escapeLikeString($string);

        $query = User::where('username', 'like', '%'.$string.'%')
            ->orderByRaw('username = ? desc', [$string])
            ->orderByRaw('username like ? desc', [$string.'%']);

        return $this->applyVisibleTo($query, $actor)->pluck('id')->all();
    }

    /**
     * Escape special characters that can be used as wildcards in a LIKE query.
     *
     * @param  string  $string
     * @return string
     */
    protected function escapeLikeString($string): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $string);
    }

    /**
     * Get a fresh instance of the model represented by the resource.
     *
     * @return User
     */
    public static function newModel()
    {
        $model = config('auth.providers.users.model');

        return new $model;
    }
}
