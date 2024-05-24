<?php

namespace App\SEO;

use Core\Database\Helpers\HasOptionsAttribute;

trait HasSEOMeta
{
    /**
     * @var bool
     */
    public $isSEOConfigured = false;

    /**
     * {@inheritdoc}
     */
    public function getSeoTitle()
    {
        return $this->title ?? $this->name ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function getSeoAttributes(): array
    {
        if (in_array(HasOptionsAttribute::class, class_uses_recursive($this), true)) {
            return $this->getOptions()->get('seotools') ?: [];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function isSEOConfigured(): bool
    {
        return (bool) $this->isSEOConfigured;
    }
}
