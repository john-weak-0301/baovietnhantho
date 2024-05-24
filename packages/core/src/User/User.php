<?php

namespace Core\User;

use DomainException;
use Core\Media\Media;
use Core\Media\MediaLibrary;
use Core\User\Event\Deleted;
use Cog\Contracts\Ban\Bannable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media as SpatieMedia;
use Orchid\Platform\Models\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Core\User\User
 *
 * @mixin \Eloquent
 */
abstract class User extends Authenticatable implements Bannable, HasMedia
{
    use UserTrait;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_seen_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'preferences'       => 'json',
        'permissions'       => 'json',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        // Don't allow the root admin to be deleted.
        static::deleting(function (User $user) {
            if ($user->getKey() === 1) {
                throw new DomainException('Cannot delete the root admin');
            }
        });

        static::deleted(function (User $user) {
            $user->raise(new Deleted($user));
        });

        static::$dispatcher->dispatch(new ConfigureUserPreferences);
    }

    /**
     * Get the user's login providers.
     *
     * @return HasMany
     */
    public function loginProviders(): HasMany
    {
        return $this->hasMany(LoginProvider::class);
    }

    /**
     * Get the user's library media.
     *
     * @return HasManyThrough
     */
    public function mediaLibrary(): HasManyThrough
    {
        return $this->hasManyThrough(Media::class, MediaLibrary::class, 'user_id', 'model_id')
            ->where('media.model_type', MediaLibrary::class);
    }

    /**
     * Check whether the user has a certain permission based on their groups.
     *
     * @param  string  $permission
     * @param  null  $deprecated
     * @return bool
     */
    public function hasAccess(string $permission, $deprecated = null): bool
    {
        // Any users can able access to dashboard.
        if ($permission === 'platform.index') {
            return true;
        }

        return $this->hasPermission($permission);
    }

    /**
     * Register the media collections.
     *
     * @return void
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    /**
     * Register the media conversions
     *
     * @param  SpatieMedia|null  $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(SpatieMedia $media = null)
    {
        $this->addMediaConversion('thumbnail')
            ->fit('crop', 150, 150)
            ->nonQueued();
    }
}
