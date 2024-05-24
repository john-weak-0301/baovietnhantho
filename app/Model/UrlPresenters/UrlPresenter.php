<?php

namespace App\Model\UrlPresenters;

/**
 * @method string edit()
 * @method string editor()
 * @method string delete()
 * @property-read string link
 * @property-read string edit
 * @property-read string editor
 */
abstract class UrlPresenter
{
    /**
     * Return the public url.
     */
    abstract public function link(): string;

    /**
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        }

        return $this->{$key} ?? null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->link();
    }
}
