<?php

namespace Core\Elements\Fields;

trait ResolveOldValue
{
    /**
     * Determines if current field has any errors.
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return optional(session('errors'))->has($this->getOldName()) ?? false;
    }

    /**
     * Returns old input value.
     *
     * @return mixed
     */
    public function getOldValue()
    {
        return old($this->getOldName());
    }

    /**
     * Return the old input name.
     *
     * @return string
     */
    public function getOldName(): string
    {
        return $this->getNameForInput();
    }
}
