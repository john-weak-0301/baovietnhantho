<?php

namespace Core\Media;

class RegisterMediaCollections
{
    /**
     * The media library instance.
     *
     * @var MediaLibrary
     */
    protected $media;

    /**
     * Constructor.
     *
     * @param  MediaLibrary  $media
     */
    public function __construct(MediaLibrary $media)
    {
        $this->media = $media;
    }
}
