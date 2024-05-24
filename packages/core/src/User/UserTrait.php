<?php

namespace Core\User;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Cog\Laravel\Ban\Traits\Bannable;
use Core\Database\EventGeneratorTrait;
use Core\Database\ScopeVisibilityTrait;

trait UserTrait
{
    use Bannable,
        HasMediaTrait,
        HasPermissions,
        EventGeneratorTrait,
        ScopeVisibilityTrait;

    /**
     * An array of registered user preferences. Each preference is defined with
     * a key, and its value is an array containing the following keys:
     *
     * 'transformer': a callback that confines the value of the preference
     * 'default': a default value if the preference isn't set
     *
     * @var array
     */
    protected static $preferences = [];

    /**
     * Rename the user.
     *
     * @param  string  $username
     * @return $this
     */
    public function rename($username)
    {
        if ($username !== $this->username) {
            $oldUsername = $this->username;

            $this->username = $username;

            $this->raise(new Event\Renamed($this, $oldUsername));
        }

        return $this;
    }

    /**
     * Change the user avatar.
     *
     * @param  string|\Symfony\Component\HttpFoundation\File\UploadedFile  $file
     * @return $this
     */
    public function changeAvatar($file)
    {
        if (is_string($file) && strpos($file, 'http') === 0) {
            $this->addMediaFromUrl($file, ['image/jpeg', 'image/png'])->toMediaCollection('avatar');
        } else {
            $this->addMedia($file)->toMediaCollection('avatar');
        }

        $this->raise(new Event\AvatarChanged($this));

        return $this;
    }

    /**
     * Request that the user's email be changed.
     *
     * @param  string  $email
     * @return $this
     */
    public function requestEmailChange($email)
    {
        if ($email !== $this->email) {
            $this->raise(new Event\EmailChangeRequested($this, $email));
        }

        return $this;
    }

    /**
     * Change the user's email.
     *
     * @param  string  $email
     * @return $this
     */
    public function changeEmail($email)
    {
        if ($email !== $this->email) {
            $this->email = $email;

            $this->raise(new Event\EmailChanged($this));
        }

        return $this;
    }

    /**
     * Check if a given password matches the user's password.
     *
     * @param  string  $password
     * @return bool
     */
    public function checkPassword($password): bool
    {
        $valid = static::$dispatcher->until(new Event\CheckingPassword($this, $password));

        if ($valid !== null) {
            return (bool) $valid;
        }

        return Hash::check($password, $this->password);
    }

    /**
     * Change the user's password.
     *
     * @param string $password
     * @return $this
     */
    public function changePassword($password)
    {
        $this->password = Hash::make($password);

        $this->raise(new Event\PasswordChanged($this));

        return $this;
    }

    /**
     * Activate the user's account.
     *
     * @return $this
     */
    public function activate()
    {
        if (!$this->hasVerifiedEmail()) {
            $this->email_verified_at = $this->freshTimestamp();

            $this->raise(new Verified($this));
        }

        return $this;
    }

    /**
     * Set the user as being last seen just now.
     *
     * @return $this
     */
    public function updateLastSeen()
    {
        $this->last_seen_at = Carbon::now();

        return $this;
    }

    /**
     * Get the number of unread notifications for the user.
     *
     * @return int
     */
    public function getUnreadNotificationCount(): int
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Get the number of new, unseen notifications for the user.
     *
     * @return int
     */
    public function getNewNotificationCount(): int
    {
        return $this->unreadNotifications()->get()->filter(function ($notification) {
            return $notification->created_at > $this->read_notifications_at ?: 0;
        })->count();
    }

    /**
     * Get the URL of the user's avatar.
     *
     * @param  string  $size
     * @return string
     */
    public function getAvatarUrl($size = '')
    {
        return once(function () use ($size) {
            return $this->getFirstMediaUrl('avatar', $size);
        });
    }

    /**
     * Get the URL of the user's avatar.
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->getAvatarUrl('thumbnail');
    }

    /**
     * Get the user's display name.
     *
     * @return string|mixed
     */
    public function getDisplayNameAttribute(): string
    {
        return static::$dispatcher->until(new Event\GetDisplayName($this)) ?: $this->name;
    }

    /**
     * Check whether or not the user should receive an alert for a notification
     * type.
     *
     * @param  string  $type
     * @return bool
     */
    public function shouldAlert($type): bool
    {
        return (bool) $this->getPreference(static::getNotificationPreferenceKey($type, 'alert'));
    }

    /**
     * Check whether or not the user should receive an email for a notification
     * type.
     *
     * @param  string  $type
     * @return bool
     */
    public function shouldEmail($type): bool
    {
        return (bool) $this->getPreference(static::getNotificationPreferenceKey($type, 'email'));
    }

    /**
     * Check whether or not the user is a guest.
     *
     * @return bool
     */
    public function isGuest(): bool
    {
        return false;
    }

    /**
     * Get the value of a preference for this user.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function getPreference($key, $default = null)
    {
        return Arr::get($this->preferences, $key, $default);
    }

    /**
     * Set the value of a preference for this user.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setPreference($key, $value)
    {
        if (isset(static::$preferences[$key])) {
            /** @noinspection PhpUndefinedFieldInspection */
            $preferences = $this->preferences;

            $transformer = static::$preferences[$key]['transformer'] ?? null;

            /** @var callable $transformer */
            $preferences[$key] = $transformer ? $transformer($value) : $value;

            $this->preferences = $preferences;
        }

        return $this;
    }

    /**
     * Get the values of all registered preferences for this user, by
     * transforming their stored preferences and merging them with the defaults.
     *
     * @param  string  $value
     * @return array
     */
    public function getPreferencesAttribute($value): array
    {
        $defaults = array_map(function ($value) {
            return $value['default'];
        }, static::$preferences);

        $user = Arr::only(
            (array) json_decode($value, true),
            array_keys(static::$preferences)
        );

        return array_merge($defaults, $user);
    }

    /**
     * Register a preference with a transformer and a default value.
     *
     * @param  string  $key
     * @param  callable  $transformer
     * @param  mixed  $default
     */
    public static function addPreference($key, callable $transformer = null, $default = null): void
    {
        static::$preferences[$key] = compact('transformer', 'default');
    }

    /**
     * Get the key for a preference which flags whether or not the user will
     * receive a notification for $type via $method.
     *
     * @param  string  $type
     * @param  string  $method
     * @return string
     */
    public static function getNotificationPreferenceKey($type, $method): string
    {
        return 'notify_'.$type.'_'.$method;
    }
}
