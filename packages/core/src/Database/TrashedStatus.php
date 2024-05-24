<?php

namespace Core\Database;

use MyCLabs\Enum\Enum;

class TrashedStatus extends Enum
{
    /* Constanst */
    public const DEFAULT = '';
    public const WITH = 'with';
    public const ONLY = 'only';

    /**
     * Returns the trashed status from boolean.
     *
     * @param  bool  $withTrashed
     * @return string
     */
    public static function fromBoolean($withTrashed): string
    {
        return $withTrashed ? self::WITH : self::DEFAULT;
    }
}
