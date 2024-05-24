<?php

namespace Core\Elements\Table;

use Core\Elements\Link;

class LinkBuilder extends Link
{
    /**
     * Create link builder.
     *
     * @param  string|null  $text
     * @return static
     */
    public static function createForBuilder(string $text = null)
    {
        return new static(null, $text);
    }
}
