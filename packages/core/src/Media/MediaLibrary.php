<?php

namespace Core\Media;

use Core\User\User;
use Core\Database\Model;
use Core\User\UserRepository;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media as SpatieMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MediaLibrary
 *
 * @mixin \Eloquent
 */
class MediaLibrary extends Model implements HasMedia
{
    use HasMediaTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media_libraries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * Returns the media's belongs to the user.
     *
     * @param  User  $user
     * @return static
     */
    public static function getForUser(User $user)
    {
        return static::firstOrCreate(['user_id' => $user->getKey()]);
    }

    /**
     * Get the owner of the media.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(get_class(UserRepository::newModel()), 'user_id');
    }

    /**
     * The media polymorphic relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    /**
     * Register the media collections.
     *
     * @return void
     */
    public function registerMediaCollections()
    {
        static::$dispatcher->dispatch(new RegisterMediaCollections($this));
    }

    /**
     * Register the media conversions.
     *
     * @param  SpatieMedia|null  $media
     * @return void
     *
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(SpatieMedia $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->fit(Manipulations::FIT_MAX, 150, 150)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 760, 520)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 960, 720)
            ->nonQueued();

        static::$dispatcher->dispatch(new RegisterMediaConversions($this));
    }
}
