<?php

use App\Formatter\ShortcodeManager;
use Embed\Embed;
use Illuminate\Support\HtmlString;

require_once __DIR__.'/blocks.php';

function do_shortcode($contents)
{
    if (empty($contents)) {
        return '';
    }

    return ShortcodeManager::getInstance()->parse($contents);
}

/**
 * //
 *
 * @param  DateTimeInterface  $date
 * @return string|null
 */
function format_date($date)
{
    if (!$date) {
        return '';
    }

    if ($date instanceof DateTimeInterface) {
        return $date->format('d/m/Y');
    }
}

/**
 * Returns the regexp for common whitespace characters.
 *
 * By default, spaces include new lines, tabs, nbsp entities, and the UTF-8 nbsp.
 * This is designed to replace the PCRE \s sequence.  In ticket #22692, that
 * sequence was found to be unreliable due to random inclusion of the A0 byte.
 *
 * @return string The spaces regexp.
 */
function wp_spaces_regexp()
{
    static $spaces = '';

    if (empty($spaces)) {
        /**
         * Filters the regexp for common whitespace characters.
         *
         * This string is substituted for the \s sequence as needed in regular
         * expressions. For websites not written in English, different characters
         * may represent whitespace. For websites not encoded in UTF-8, the 0xC2 0xA0
         * sequence may not be in use.
         *
         * @param string $spaces Regexp pattern for matching common whitespace characters.
         * @since 4.0.0
         *
         */
        $spaces = apply_filters('wp_spaces_regexp', '[\r\n\t ]|\xC2\xA0|&nbsp;');
    }

    return $spaces;
}

/**
 * Don't auto-p wrap shortcodes that stand alone
 *
 * Ensures that shortcodes are not wrapped in `<p>...</p>`.
 *
 * @param string $pee The content.
 * @return string The filtered content.
 * @global array $shortcode_tags
 *
 */
function shortcode_unautop($pee)
{
    $shortcode_tags = ShortcodeManager::getInstance()->all();

    if (empty($shortcode_tags) || !is_array($shortcode_tags)) {
        return $pee;
    }

    $tagregexp = implode('|', array_map('preg_quote', $shortcode_tags));
    $spaces = wp_spaces_regexp();

    // @formatter:off
    $pattern =
        '/'
        . '<p>'                              // Opening paragraph
        . '(?:' . $spaces . ')*+'            // Optional leading whitespace
        . '('                                // 1: The shortcode
        .     '\\['                          // Opening bracket
        .     "($tagregexp)"                 // 2: Shortcode name
        .     '(?![\\w-])'                   // Not followed by word character or hyphen
        // Unroll the loop: Inside the opening shortcode tag
        .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
        .     '(?:'
        .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
        .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
        .     ')*?'
        .     '(?:'
        .         '\\/\\]'                   // Self closing tag and closing bracket
        .     '|'
        .         '\\]'                      // Closing bracket
        .         '(?:'                      // Unroll the loop: Optionally, anything between the opening and closing shortcode tags
        .             '[^\\[]*+'             // Not an opening bracket
        .             '(?:'
        .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
        .                 '[^\\[]*+'         // Not an opening bracket
        .             ')*+'
        .             '\\[\\/\\2\\]'         // Closing shortcode tag
        .         ')?'
        .     ')'
        . ')'
        . '(?:' . $spaces . ')*+'            // optional trailing whitespace
        . '<\\/p>'                           // closing paragraph
        . '/';
    // @formatter:on

    return preg_replace($pattern, '$1', $pee);
}

/**
 * Display the post content.
 *
 * @param string $content
 * @return HtmlString
 */
function the_content($content)
{
    /**
     * Filters the post content.
     *
     * @param string $content Content of the current post.
     */
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);

    return new HtmlString($content);
}

function render_raw_content($content)
{
    static $regex = '/<!-- wp:core-embed\/.*?-->\s*?<figure class="wp-block-embed.*?".*?<div class="wp-block-embed__wrapper">\s*?(.*?)\s*?<\/div><\/figure>/';

    if (!$content) {
        return '';
    }

    return preg_replace_callback($regex, function ($matches) {
        $embed = rescue(function () use ($matches) {
            return Embed::create($matches[1]);
        });

        if ($embed === null || empty($embed->code)) {
            return $matches[0];
        }

        $url = preg_replace('#/#', '\/', preg_quote($matches[1], '#'));

        // Replace URL with OEmbed HTML
        return preg_replace("/>\s*?$url\s*?</", ">$embed->code<", $matches[0]);
    }, $content);
}
