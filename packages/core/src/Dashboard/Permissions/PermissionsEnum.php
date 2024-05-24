<?php

namespace Core\Dashboard\Permissions;

use MyCLabs\Enum\Enum;

abstract class PermissionsEnum extends Enum
{
    abstract public static function getLabels(): array;

    public static function getLabelFor($key): ?string
    {
        if (!static::isValid($key)) {
            return null;
        }

        return static::getLabels()[$key] ?? null;
    }

    public function getLabel(): ?string
    {
        return static::getLabelFor($this->getValue());
    }
}
