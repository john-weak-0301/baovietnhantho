<?php

namespace Core\Elements\Fields;

/**
 * Class Field.
 *
 * @method $this accesskey($value = true)
 * @method $this type($value = true)
 * @method $this class($value = true)
 * @method $this contenteditable($value = true)
 * @method $this contextmenu($value = true)
 * @method $this dir($value = true)
 * @method $this hidden($value = true)
 * @method $this id($value = true)
 * @method $this lang($value = true)
 * @method $this spellcheck($value = true)
 * @method $this style($value = true)
 * @method $this tabindex($value = true)
 * @method $this title($value = null)
 * @method $this options($value = true)
 * @method $this autocomplete($value = true)
 */
trait OrchidFieldCompat
{
    /**
     * The name of the view template.
     *
     * @var string
     */
    public $view;

    /**
     * The layout of the field.
     *
     * @var string
     */
    public $layout;

    /**
     * Required attributes.
     *
     * @var array
     */
    public $required = [];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    public $inlineAttributes = [];

    /**
     * Universal attributes are applied to almost all tags,
     * so they are allocated to a separate group so that they do not repeat for all tags.
     *
     * @var array
     */
    public $universalAttributes = [
        'accesskey',
        'class',
        'contenteditable',
        'contextmenu',
        'dir',
        'hidden',
        'id',
        'lang',
        'spellcheck',
        'style',
        'tabindex',
        'title',
        'xml:lang',
        'autocomplete',
    ];

    /**
     * Use vertical layout for the field.
     *
     * @return $this
     */
    public function vertical()
    {
        $this->layout = 'platform::partials.fields.vertical';

        return $this;
    }

    /**
     * Use horizontal layout for the field.
     *
     * @return $this
     */
    public function horizontal()
    {
        $this->layout = 'platform::partials.fields.horizontal';

        return $this;
    }

    /**
     * Get the name of the template.
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * Gets the field attribute.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function get($key, $value = null)
    {
        return $this->meta['attributes'][$key] ?? $value;
    }

    /**
     * Set the field attribute.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function set(string $key, $value)
    {
        $this->withMeta(['attributes' => [$key => $value]]);

        return $this;
    }

    /**
     * Return the field attributes.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        $attributes = $this->meta['attributes'] ?? [];

        $attributes['id']    = $this->getId();
        $attributes['name']  = $this->getNameForInput();
        $attributes['value'] = $this->value;
        $attributes['title'] = $this->name;

        return $attributes;
    }

    /**
     * Get the name of the template.
     *
     * @return array
     */
    public function getRequired(): array
    {
        return [];
    }

    /**
     * //
     *
     * @return $this
     */
    public function checkRequired()
    {
        return $this;
    }

    /**
     * //
     *
     * @param  string  $name
     * @param  array  $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        $arguments = array_map('value', $arguments);

        return $this->set($name, array_shift($arguments) ?? true);
    }
}
