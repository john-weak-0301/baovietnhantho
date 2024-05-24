<?php

namespace App\SEO;

interface WithSEOMeta
{
    /**
     * The SEO title.
     *
     * @return mixed
     */
    public function getSeoTitle();

    /**
     * Returns SEO metadata.
     *
     * @return array
     */
    public function getSeoAttributes(): array;

    /**
     * Indicator is SEO meta is configured.
     *
     * @return bool
     */
    public function isSEOConfigured(): bool;
}
