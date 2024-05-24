<?php

namespace App\Orchid\Fields;

use Orchid\Screen\Field;

class OpenEditor extends Field
{
    /**
     * View template show.
     *
     * @var string
     */
    public $view = 'platform.container.fields.open-editor';

    /**
     * @param  string  $link
     * @return static
     */
    public static function make($link = null): self
    {
        return (new static)
            ->link($link)
            ->set('name', '_openEditor');
    }

    public function link(string $link): self
    {
        $this->set('link', $link);

        return $this;
    }
}
