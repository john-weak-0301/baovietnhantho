<?php

namespace App\Model\Helpers;

use App\Model\UrlPresenters\UrlPresenter;

/**
 * @property-read UrlPresenter $url
 */
interface HasUrlPresenter
{
    public function getUrlAttribute(): UrlPresenter;
}
