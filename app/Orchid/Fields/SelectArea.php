<?php

namespace App\Orchid\Fields;

use Orchid\Screen\Field;

class SelectArea extends Field
{
    /**
     * @var string
     */
    public $view = 'platform.fields.select-area';

    /**
     * @var array
     */
    public $attributes = [
        'class' => 'form-control',
    ];

    /**
     * @var array
     */
    public $inlineAttributes = [
        'disabled',
        'required',
        'form',
    ];

    /**
     * @param  string|null  $name
     * @return self
     */
    public static function make(string $name = null)
    {
        return (new static())->name($name);
    }
}
