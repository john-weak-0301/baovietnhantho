<?php

use TorMorten\Eventy\Facades\Events;

global $_current_filters;

if (!$_current_filters) {
    $_current_filters = [];
}

if (!function_exists('add_action')) {
    /**
     * Hooks a function on to a specific action.
     *
     * @param  string  $tag  The name of the action to which the $function_to_add is hooked.
     * @param  callable  $function_to_add  The name of the function you wish to be called.
     * @param  int  $priority  Optional. Used to specify the order in which the functions
     *                                  associated with a particular action are executed. Default 10.
     *                                  Lower numbers correspond with earlier execution,
     *                                  and functions with the same priority are executed
     *                                  in the order in which they were added to the action.
     * @param  int  $accepted_args  Optional. The number of arguments the function accepts. Default 1.
     * @return true Will always return true.
     */
    function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        Events::addAction($tag, $function_to_add, $priority, $accepted_args);

        return true;
    }
}

if (!function_exists('add_filter')) {
    /**
     * Hook a function or method to a specific filter action.
     *
     * @param  string  $tag  The name of the filter to hook the $function_to_add callback to.
     * @param  callable  $function_to_add  The callback to be run when the filter is applied.
     * @param  int  $priority  Optional. Used to specify the order in which the functions
     *                                  associated with a particular action are executed. Default 10.
     *                                  Lower numbers correspond with earlier execution,
     *                                  and functions with the same priority are executed
     *                                  in the order in which they were added to the action.
     * @param  int  $accepted_args  Optional. The number of arguments the function accepts. Default 1.
     * @return true
     */
    function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
        Events::addFilter($tag, $function_to_add, $priority, $accepted_args);

        return true;
    }
}

if (!function_exists('do_action')) {
    /**
     * Mock for the `do_action` function.
     *
     * @param  string  $tag  The name of the filter hook.
     * @param  mixed  ...$vars  Additional variables passed to the functions hooked to `$tag`.
     * @return void
     */
    function do_action($tag, ...$vars)
    {
        global $_current_filters;
        $_current_filters[] = $tag;

        /* @noinspection PhpMethodParametersCountMismatchInspection */
        Events::action($tag, ...$vars);

        array_pop($_current_filters);
    }
}

if (!function_exists('apply_filters')) {
    /**
     * Mock for the `apply_filters` function.
     *
     * @param  string  $tag  The name of the filter hook.
     * @param  mixed  $value  The value on which the filters hooked to `$tag` are applied on.
     * @param  mixed  ...$vars  Additional variables passed to the functions hooked to `$tag`.
     * @return mixed
     */
    function apply_filters($tag, $value, ...$vars)
    {
        global $_current_filters;
        $_current_filters[] = $tag;

        /* @noinspection PhpVoidFunctionResultUsedInspection */
        /* @noinspection PhpMethodParametersCountMismatchInspection */
        $filtered = Events::filter($tag, $value, ...$vars);

        array_pop($_current_filters);

        return $filtered;
    }
}

if (!function_exists('current_action')) {
    /**
     * Mock for the `current_action` function.
     *
     * @return null Alway returns null for now.
     */
    function current_action()
    {
        return current_filter();
    }
}

if (!function_exists('current_filter')) {
    /**
     * Mock for the `current_filter` function.
     *
     * @return null Alway returns null for now.
     */
    function current_filter()
    {
        global $_current_filters;

        return end($_current_filters);
    }
}

if (!function_exists('map_deep')) {
    /**
     * Maps a function to all non-iterable elements of an array or an object.
     *
     * This is similar to `array_walk_recursive()` but acts upon objects too.
     *
     * @param  mixed  $value  The array, object, or scalar.
     * @param  callable  $callback  The function to map onto $value.
     * @return mixed The value with the callback applied to all non-arrays and non-objects inside it.
     */
    function map_deep($value, $callback)
    {
        if (is_array($value)) {
            foreach ($value as $index => $item) {
                $value[$index] = map_deep($item, $callback);
            }
        } elseif (is_object($value)) {
            $object_vars = get_object_vars($value);
            foreach ($object_vars as $property_name => $property_value) {
                $value->$property_name = map_deep($property_value, $callback);
            }
        } else {
            $value = $callback($value);
        }

        return $value;
    }
}

if (!function_exists('wp_allowed_protocols')) {
    /**
     * Retrieve a list of protocols to allow in HTML attributes.
     *
     * @return string[] Array of allowed protocols. Defaults to an array containing
     *                  'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher',
     *                  'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'svn', 'tel', 'fax', 'xmpp', 'webcal', and 'urn'.
     *                  This covers all common link protocols, except for 'javascript' which should not be
     *                  allowed for untrusted users.
     * @see       esc_url()
     *
     * @staticvar array $protocols
     *
     * @see       wp_kses()
     */
    function wp_allowed_protocols()
    {
        static $protocols = [];

        if (empty($protocols)) {
            $protocols = [
                'http',
                'https',
                'ftp',
                'ftps',
                'mailto',
                'news',
                'irc',
                'gopher',
                'nntp',
                'feed',
                'telnet',
                'mms',
                'rtsp',
                'svn',
                'tel',
                'fax',
                'xmpp',
                'webcal',
                'urn',
            ];
        }

        return $protocols;
    }
}

/**
 * Outputs the html checked attribute.
 *
 * Compares the first two arguments and if identical marks as checked
 *
 * @param  mixed  $checked  One of the values to compare
 * @param  mixed  $current  (true) The other value to compare if not just true
 * @param  bool  $echo  Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function checked($checked, $current = true, $echo = true)
{
    return __checked_selected_helper($checked, $current, $echo, 'checked');
}

/**
 * Outputs the html selected attribute.
 *
 * Compares the first two arguments and if identical marks as selected
 *
 * @param  mixed  $selected  One of the values to compare
 * @param  mixed  $current  (true) The other value to compare if not just true
 * @param  bool  $echo  Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function selected($selected, $current = true, $echo = true)
{
    return __checked_selected_helper($selected, $current, $echo, 'selected');
}

/**
 * Outputs the html disabled attribute.
 *
 * Compares the first two arguments and if identical marks as disabled
 *
 * @param  mixed  $disabled  One of the values to compare
 * @param  mixed  $current  (true) The other value to compare if not just true
 * @param  bool  $echo  Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function disabled($disabled, $current = true, $echo = true)
{
    return __checked_selected_helper($disabled, $current, $echo, 'disabled');
}

/**
 * Outputs the html readonly attribute.
 *
 * Compares the first two arguments and if identical marks as readonly
 *
 * @param  mixed  $readonly  One of the values to compare
 * @param  mixed  $current  (true) The other value to compare if not just true
 * @param  bool  $echo  Whether to echo or just return the string
 * @return string html attribute or empty string
 */
function readonly($readonly, $current = true, $echo = true)
{
    return __checked_selected_helper($readonly, $current, $echo, 'readonly');
}

/**
 * Private helper function for checked, selected, disabled and readonly.
 *
 * Compares the first two arguments and if identical marks as $type
 *
 * @param  mixed  $helper  One of the values to compare
 * @param  mixed  $current  (true) The other value to compare if not just true
 * @param  bool  $echo  Whether to echo or just return the string
 * @param  string  $type  The type of checked|selected|disabled|readonly we are doing
 * @return string html attribute or empty string
 *
 * @access private
 */
function __checked_selected_helper($helper, $current, $echo, $type)
{
    if ((string) $helper === (string) $current) {
        $result = " $type='$type'";
    } else {
        $result = '';
    }

    if ($echo) {
        echo $result;
    }

    return $result;
}
