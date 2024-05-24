<?php

namespace App\Model\Helpers;

use Embed\Embed;
use Illuminate\Support\HtmlString;

/**
 * Trait HasFormattedContent
 *
 * @property string $content
 * @property string $content_filtered
 *
 * @package App\Model\Helpers
 */
trait HasFormattedContent
{
    /**
     * Returns the formatted content.
     *
     * @return HtmlString
     */
    public function getContent(): HtmlString
    {
        if ($this->content && !$this->content_filtered) {
            $this->renderRawContent();
            $this->save();
        }

        return the_content($this->content_filtered);
    }

    /**
     * Getter the "content_formatted" attribute.
     *
     * @return string
     */
    public function getContentFormattedAttribute(): string
    {
        return $this->getContent()->toHtml();
    }

    public function setContent($content)
    {
        $this->content = $content;

        $this->renderRawContent();

        return $this;
    }

    protected function renderRawContent()
    {
        static $regex = '/<!-- wp:core-embed\/.*?-->\s*?<figure class="wp-block-embed.*?".*?<div class="wp-block-embed__wrapper">\s*?(.*?)\s*?<\/div><\/figure>/';

        $contents = preg_replace_callback($regex, function ($matches) {
            $embed = rescue(function () use ($matches) {
                return Embed::create($matches[1]);
            });

            if ($embed === null || empty($embed->code)) {
                return $matches[0];
            }

            $url = preg_replace('#/#', '\/', preg_quote($matches[1], '#'));

            // Replace URL with OEmbed HTML
            return preg_replace("/>\s*?$url\s*?</", ">$embed->code<", $matches[0]);
        }, $this->content);

        return $this->content_filtered = $contents;
    }
}
