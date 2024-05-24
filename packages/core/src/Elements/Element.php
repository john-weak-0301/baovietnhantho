<?php

namespace Core\Elements;

use JsonSerializable;
use Illuminate\Http\Request;

abstract class Element implements JsonSerializable
{
    use Metable, AuthorizedToSee;

    /**
     * The element's component.
     *
     * @var string
     */
    public $component;

    /**
     * Create a new element.
     *
     * @param  string|null  $component
     * @return void
     */
    public function __construct($component = null)
    {
        $this->component = $component ?? $this->component;
    }

    /**
     * Create a new element.
     *
     * @param  mixed  ...$arguments
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Determine if the element should be displayed for the given request.
     *
     * @param  Request  $request
     * @return bool
     */
    public function authorize(Request $request): bool
    {
        return $this->authorizedToSee($request);
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component(): string
    {
        return $this->component;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'component'       => $this->component(),
            'prefixComponent' => false,
        ], $this->meta());
    }
}
