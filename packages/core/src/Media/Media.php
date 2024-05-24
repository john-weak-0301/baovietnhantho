<?php

namespace Core\Media;

use Core\User\UserRepository;
use Core\Database\ScopeVisibilityTrait;
use Spatie\MediaLibrary\Models\Media as SpatieMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

/**
 * Class Media
 *
 * @mixin \Eloquent
 */
class Media extends SpatieMedia
{
    use ScopeVisibilityTrait;

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (Media $media) {
            $media->uuid = (string) Str::orderedUuid();
        });
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
     * Get the library this media belongs to.
     *
     * @return MorphTo
     */
    public function library(): MorphTo
    {
        return $this->morphTo(MediaLibrary::class, 'model_type', 'model_id');
    }

    /**
     * //
     *
     * @param  string  $default
     * @return string|null
     */
    public function url($default = null): ?string
    {
        return $this->getFullUrl() ?? $default;
    }

    /**
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        return $this->url();
    }

    /**
     * @return string|null
     */
    public function getRelativeUrlAttribute(): ?string
    {
        return $this->getUrl();
    }
}
