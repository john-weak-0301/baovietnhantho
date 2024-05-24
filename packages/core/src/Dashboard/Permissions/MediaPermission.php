<?php

namespace Core\Dashboard\Permissions;

class MediaPermission extends PermissionsEnum
{
    public const BROWSE_MEDIA = 'platform.system.media.browser';

    public static function getLabels(): array
    {
        return [
            self::BROWSE_MEDIA => __('Duyệt Files'),
        ];
    }
}
