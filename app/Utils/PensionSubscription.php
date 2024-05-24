<?php

namespace App\Utils;

use MyCLabs\Enum\Enum;

class PensionSubscription extends Enum
{
    public const MONTHLY = 12;
    public const QUARTERLY = 4;
    public const HALF_YEARLY = 2;
    public const YEARLY = 1;
}
