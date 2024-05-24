<?php

namespace Core\User;

use Core\Database\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginProvider extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'login_providers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider', 'identifier',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'last_login_at',
    ];

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    public const UPDATED_AT = 'last_login_at';

    /**
     * Get the user that the login provider belongs to.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(get_class(UserRepository::newModel()));
    }

    /**
     * Get the user associated with the provider so that they can be logged in.
     *
     * @param  string  $provider
     * @param  string  $identifier
     * @return User|null
     */
    public static function logIn(string $provider, string $identifier): ?User
    {
        $instance = static::query()->where(compact('provider', 'identifier'))->first();

        if ($instance !== null) {
            $instance->touch();

            return $instance->user;
        }

        return null;
    }
}
