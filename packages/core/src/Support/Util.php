<?php

namespace Core\Support;

use Illuminate\Support\Str;

class Util
{
    /**
     * Humanize the given value into a proper name.
     *
     * @param  string|object  $value
     * @return string
     */
    public static function humanize($value): string
    {
        if (is_object($value)) {
            return Str::title(class_basename(get_class($value)));
        }

        return Str::title(Str::snake($value, ' '));
    }

    /**
     * Returns class string by given an array of classes.
     *
     * @param  array  $classes  The array of class.
     * @return string
     */
    public static function escHtmlClasses($classes)
    {
        $classes = array_filter(array_unique((array) $classes));

        if (empty($classes)) {
            return '';
        }

        return implode(' ', array_map('esc_attr', $classes));
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes  The HTML attributes.
     * @return string
     */
    public static function buildHtmlAttributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = static::buildAttributeElement($key, $value);

            if ($element !== null) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' '.implode(' ', $html) : '';
    }

    /**
     * Build a single attribute element.
     *
     * @param  string  $key
     * @param  string|mixed  $value
     * @return string|null
     */
    protected static function buildAttributeElement($key, $value)
    {
        // For numeric keys we will assume that the value is a boolean attribute
        // where the presence of the attribute represents a true value and the
        // absence represents a false value.
        if (is_numeric($key)) {
            return $value;
        }

        // Treat boolean attributes as HTML properties.
        if (is_bool($value) && 'value' !== $key) {
            return $value ? $key : '';
        }

        if (is_array($value) && 'class' === $key) {
            return 'class="'.static::escHtmlClasses($value).'"';
        }

        if ($value !== null) {
            return $key.'="'.esc_attr($value).'"';
        }

        return null;
    }
}
