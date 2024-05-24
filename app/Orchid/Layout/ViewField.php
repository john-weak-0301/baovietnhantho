<?php

namespace App\Orchid\Layout;

use Orchid\Screen\Field;

class ViewField extends Field
{
    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    public $inlineAttributes = [
        'accept',
        'accesskey',
        'autocomplete',
        'autofocus',
        'checked',
        'disabled',
        'form',
        'formaction',
        'formenctype',
        'formmethod',
        'formnovalidate',
        'formtarget',
        'language',
        'lineNumbers',
        'list',
        'max',
        'maxlength',
        'min',
        'name',
        'pattern',
        'placeholder',
        'readonly',
        'required',
        'size',
        'src',
        'step',
        'tabindex',
        'type',
        'value',
    ];

    /**
     * @param  string|null  $name
     *
     * @return ViewField
     */
    public static function make(string $name = null): self
    {
        return (new static())->name($name);
    }

    public function view(string $view)
    {
        $this->view = $view;

        return $this;
    }
}
