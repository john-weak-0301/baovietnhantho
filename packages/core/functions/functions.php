<?php

use Core\Screens\Screen;
use Illuminate\Support\HtmlString;
use Propaganistas\LaravelPhone\PhoneNumber;

require_once __DIR__.'/wp/general.php';

if (!function_exists('wp_kses_version')) {
    require_once __DIR__.'/wp/kses.php';
}

if (!function_exists('wpautop')) {
    require_once __DIR__.'/wp/formatting.php';
}

require_once __DIR__.'/wp-media/media.php';
require_once __DIR__.'/wp-media/media-template.php';

/**
 * //
 *
 * @return Screen|null
 */
function current_screen()
{
    $screen = request()->route()->getController();

    if (!$screen instanceof Screen) {
        return null;
    }

    return $screen;
}

/**
 * //
 *
 * @param  string  $action
 * @param  bool  $absolute
 * @return string
 */
function current_screen_url($parameters = [], $absolute = true)
{
    if ($screen = current_screen()) {
        return action(get_class($screen).'@handle', $parameters ?: [], $absolute);
    }

    return url()->current();
}

/**
 * //
 *
 * @param  string  $action
 * @param  bool  $absolute
 * @return string
 */
function current_screen_action($action = '', $absolute = true)
{
    if ($screen = current_screen()) {
        return action(get_class($screen).'@handle', $action ?: [], $absolute);
    }

    return '';
}

if (!function_exists('fire')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    function fire(...$args)
    {
        return app('events')->dispatch(...$args);
    }
}

/**
 * //
 *
 * @param  mixed  ...$args
 * @return string
 */
function classnames(...$args)
{
    $data = array_reduce($args, function ($carry, $arg) {
        if (is_array($arg)) {
            return array_merge($carry, $arg);
        }

        $carry[] = $arg;

        return $carry;
    }, []);

    $classes = array_map(
        function ($key, $value) {
            $condition = $value;
            $return    = $key;

            if (is_int($key)) {
                $condition = null;
                $return    = $value;
            }

            $isArray          = is_array($return);
            $isObject         = is_object($return);
            $isStringableType = !$isArray && !$isObject;

            $isStringableObject = $isObject && method_exists($return, '__toString');

            if (!$isStringableType && !$isStringableObject) {
                return null;
            }

            if ($condition === null) {
                return $return;
            }

            return $condition ? $return : null;
        },
        array_keys($data),
        array_values($data)
    );

    $classes = array_filter($classes);

    return implode(' ', $classes);
}

/**
 * Clean a input data.
 *
 * @param  string  $input
 * @param  bool  $keep_newlines
 * @return string
 */
function clean($input, $keep_newlines = false)
{
    return _sanitize_text_fields($input, $keep_newlines);
}

/**
 * Display the phone number for human readable.
 *
 * @param  string  $number
 * @return string|null
 */
function format_phone_number($number): ?string
{
    return rescue(function () use ($number) {
        return PhoneNumber::make(esc_attr($number), 'VN')->formatNational();
    });
}

/**
 * Display the phone number for human readable.
 *
 * @param  string  $number
 * @return string|null
 */
function normalize_phone($number)
{
    return format_phone_number($number);
}

/**
 * Gets a SVG feather icon.
 *
 * @see https://feathericons.com
 *
 * @param  string  $icon
 * @param  string|int  $size
 * @param  string  $classes
 * @return HtmlString
 */
function get_feather_icon(string $icon, $size = '', $classes = '')
{
    if ($size) {
        $size = is_numeric($size) ? $size.'px' : $size;
        $size = sprintf('style="width: %1$s; height: %1$s;"', $size);
    }

    return new HtmlString(sprintf(
        '<svg class="feather%2$s" aria-hidden="true" role="img" focusable="false" %3$s>
            <use xlink:href="/vendor/dashboard/img/feather-sprite.svg#%1$s"/>
        </svg>',
        esc_attr($icon),
        esc_attr($classes ? ' '.$classes : ''),
        $size
    ));
}

/**
 * Retrieves a modified URL query string.
 *
 * You can rebuild the URL and append query variables to the URL query by using this function.
 * There are two ways to use this function; either a single key and value, or an associative array.
 *
 * Using a single key and value:
 *
 *     add_query_arg( 'key', 'value', 'http://example.com' );
 *
 * Using an associative array:
 *
 *     add_query_arg( array(
 *         'key1' => 'value1',
 *         'key2' => 'value2',
 *     ), 'http://example.com' );
 *
 * Omitting the URL from either use results in the current URL being used
 * (the value of `$_SERVER['REQUEST_URI']`).
 *
 * Values are expected to be encoded appropriately with urlencode() or rawurlencode().
 *
 * Setting any query variable's value to boolean false removes the key (see remove_query_arg()).
 *
 * Important: The return value of add_query_arg() is not escaped by default. Output should be
 * late-escaped with esc_url() or similar to help prevent vulnerability to cross-site scripting
 * (XSS) attacks.
 *
 * @param  string|array  $key  Either a query variable key, or an associative array of query variables.
 * @param  string  $value  Optional. Either a query variable value, or a URL to act upon.
 * @param  string  $url  Optional. A URL to act upon.
 * @return string New URL query string (unescaped).
 */
function add_query_arg($key, $value, $url = null)
{
    $args = func_get_args();

    if (is_array($args[0])) {
        if (count($args) < 2 || false === $args[1]) {
            $uri = url()->current();
        } else {
            $uri = $args[1];
        }
    } else {
        if (count($args) < 3 || false === $args[2]) {
            $uri = url()->current();
        } else {
            $uri = $args[2];
        }
    }

    if ($frag = strstr($uri, '#')) {
        $uri = substr($uri, 0, -strlen($frag));
    } else {
        $frag = '';
    }

    if (0 === stripos($uri, 'http://')) {
        $protocol = 'http://';
        $uri      = substr($uri, 7);
    } elseif (0 === stripos($uri, 'https://')) {
        $protocol = 'https://';
        $uri      = substr($uri, 8);
    } else {
        $protocol = '';
    }

    if (strpos($uri, '?') !== false) {
        list($base, $query) = explode('?', $uri, 2);
        $base .= '?';
    } elseif ($protocol || strpos($uri, '=') === false) {
        $base  = $uri.'?';
        $query = '';
    } else {
        $base  = '';
        $query = $uri;
    }

    parse_str($query, $qs);
    $qs = map_deep($qs, 'urlencode'); // this re-URL-encodes things that were already in the query string
    if (is_array($args[0])) {
        foreach ($args[0] as $k => $v) {
            $qs[$k] = $v;
        }
    } else {
        $qs[$args[0]] = $args[1];
    }

    foreach ($qs as $k => $v) {
        if ($v === false) {
            unset($qs[$k]);
        }
    }

    $ret = http_build_query($qs);
    $ret = trim($ret, '?');
    $ret = preg_replace('#=(&|$)#', '$1', $ret);
    $ret = $protocol.$base.$ret.$frag;
    $ret = rtrim($ret, '?');

    return $ret;
}

/**
 * Removes an item or items from a query string.
 *
 * @param  string|array  $key  Query key or keys to remove.
 * @param  bool|string  $query  Optional. When false uses the current URL. Default false.
 * @return string New URL query string.
 */
function remove_query_arg($key, $query = false)
{
    // Removing multiple keys.
    if (is_array($key)) {
        foreach ($key as $k) {
            $query = add_query_arg($k, false, $query);
        }

        return $query;
    }

    return add_query_arg($key, false, $query);
}
