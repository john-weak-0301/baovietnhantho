<?php

namespace Core\Media;

use Spatie\MediaLibrary\Models\Media as SpatieMedia;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    /**
     * {@inheritdoc}
     */
    public function getPath(SpatieMedia $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /**
     * {@inheritdoc}
     */
    public function getPathForConversions(SpatieMedia $media): string
    {
        return $this->getBasePath($media).'/c/';
    }

    /**
     * {@inheritdoc}
     */
    public function getPathForResponsiveImages(SpatieMedia $media): string
    {
        return $this->getBasePath($media).'/sizes/';
    }

    /**
     * {@inheritdoc}
     */
    protected function getBasePath(SpatieMedia $media): string
    {
        return $media->uuid ?? md5($media->getKey());
    }
}
