<?php

use Zend\Escaper\Escaper;

/**
 * Escaping for HTML blocks.
 *
 * @param  string  $text
 * @return string
 */
function esc_html($text)
{
    if (is_string($text)) {
        $text = _check_invalid_utf8($text);
    }

    return e($text);
}

/**
 * Escaping for HTML attributes.
 *
 * @param  mixed  $value
 * @return mixed
 */
function esc_attr($value)
{
    $value = _check_invalid_utf8($value);

    return htmlspecialchars($value, ENT_NOQUOTES);
}

/**
 * Escape single quotes, htmlspecialchar " < > &, and fix line endings.
 *
 * @param  string  $text  The text to be escaped.
 * @return string Escaped text.
 */
function esc_js($text)
{
    $safe_text = _check_invalid_utf8($text);
    $safe_text = app(Escaper::class)->escapeJs($safe_text);

    $safe_text = str_replace("\r", '', $safe_text);
    $safe_text = str_replace("\n", '\\n', addslashes($safe_text));

    return $safe_text;
}

/**
 * Checks and cleans a URL.
 *
 * @param  string  $url
 * @return string
 */
function esc_url($url)
{
    return rawurlencode($url);
}

/**
 * Normalize EOL characters and strip duplicate whitespace.
 *
 * @param  string  $str  The string to normalize.
 * @return string The normalized string.
 */
function normalize_whitespace($str)
{
    $str = trim($str);

    $str = str_replace("\r", "\n", $str);
    $str = preg_replace(['/\n+/', '/[ \t]+/'], ["\n", ' '], $str);

    return $str;
}

/**
 * Properly strip all HTML tags including script and style
 *
 * This differs from strip_tags() because it removes the contents of
 * the `<script>` and `<style>` tags. E.g. `strip_tags( '<script>something</script>' )`
 * will return 'something'. wp_strip_all_tags will return ''
 *
 * @param  string  $string  String containing HTML tags
 * @param  bool  $remove_breaks  Optional. Whether to remove left over line breaks and white space chars
 *
 * @return string The processed string.
 */
function strip_all_tags($string, $remove_breaks = false)
{
    $string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);

    $string = strip_tags($string);

    if ($remove_breaks) {
        $string = preg_replace('/[\r\n\t ]+/', ' ', $string);
    }

    return trim($string);
}

/**
 * Sanitizes a string from user input or from the database.
 *
 * - Checks for invalid UTF-8,
 * - Converts single `<` characters to entities
 * - Strips all tags
 * - Removes line breaks, tabs, and extra whitespace
 * - Strips octets
 *
 * @param  string  $str  String to sanitize.
 * @return string Sanitized string.
 */
function sanitize_text_field($str)
{
    return _sanitize_text_fields($str);
}

/**
 * Sanitizes a multiline string from user input or from the database.
 *
 * The function is like sanitize_text_field(), but preserves
 * new lines (\n) and other whitespace, which are legitimate
 * input in textarea elements.
 *
 * @param  string  $str  String to sanitize.
 * @return string Sanitized string.
 */
function sanitize_textarea_field($str)
{
    return _sanitize_text_fields($str, true);
}

/**
 * Internal helper function to sanitize a string from user input or from the db.
 *
 * @param  mixed  $str  String to sanitize.
 * @param  bool  $keep_newlines  optional Whether to keep newlines. Default: false.
 * @return string Sanitized string.
 *
 * @access private
 */
function _sanitize_text_fields($str, $keep_newlines = false)
{
    if (is_object($str) || is_array($str)) {
        return '';
    }

    $str = (string) $str;

    $filtered = _check_invalid_utf8($str);

    if (strpos($filtered, '<') !== false) {
        $filtered = _pre_kses_less_than($filtered);

        // This will strip extra whitespace for us.
        $filtered = strip_all_tags($filtered);

        // Use html entities in a special case to make sure no later
        // newline stripping stage could lead to a functional tag
        $filtered = str_replace("<\n", "&lt;\n", $filtered);
    }

    if (!$keep_newlines) {
        $filtered = preg_replace('/[\r\n\t ]+/', ' ', $filtered);
    }

    $filtered = trim($filtered);

    $found = false;
    while (preg_match('/%[a-f0-9]{2}/i', $filtered, $match)) {
        $filtered = str_replace($match[0], '', $filtered);
        $found    = true;
    }

    if ($found) {
        // Strip out the whitespace that may now exist after removing the octets.
        $filtered = trim(preg_replace('/ +/', ' ', $filtered));
    }

    return $filtered;
}

/**
 * Checks for invalid UTF8 in a string.
 *
 * @param  string  $string  The text which is to be checked.
 * @param  bool  $strip  Optional. Whether to attempt to strip out invalid UTF8. Default is false.
 * @return string The checked text.
 */
function _check_invalid_utf8($string, $strip = false)
{
    $string = (string) $string;

    if ('' === $string) {
        return '';
    }

    // Check for support for utf8 in the installed PCRE library once and store the result in a static,
    static $utf8_pcre = null;
    if (!isset($utf8_pcre)) {
        $utf8_pcre = @preg_match('/^./u', 'a');
    }

    // We can't demand utf8 in the PCRE installation, so just return the string in those cases,
    if (!$utf8_pcre) {
        return $string;
    }

    // preg_match fails when it encounters invalid UTF8 in $string.
    if (1 === @preg_match('/^./us', $string)) {
        return $string;
    }

    // Attempt to strip the bad chars if requested (not recommended).
    if ($strip && function_exists('iconv')) {
        return iconv('utf-8', 'utf-8', $string);
    }

    return '';
}

/**
 * Convert lone less than signs.
 *
 * KSES already converts lone greater than signs.
 *
 * @param  string  $text  Text to be converted.
 * @return string Converted text.
 */
function _pre_kses_less_than($text)
{
    return preg_replace_callback('%<[^>]*?((?=<)|>|$)%', function ($matches) {
        if (false === strpos($matches[0], '>')) {
            return esc_html($matches[0]);
        }

        return $matches[0];
    }, $text);
}

add_filter('pre_kses', '_pre_kses_less_than');

/**
 * Replaces double line-breaks with paragraph elements.
 *
 * A group of regex replaces used to identify text formatted with newlines and
 * replace double line-breaks with HTML paragraph tags. The remaining line-breaks
 * after conversion become <<br />> tags, unless $br is set to '0' or 'false'.
 *
 * @param  string  $pee  The text which has to be formatted.
 * @param  bool  $br  Optional. If set, this will convert all remaining line-breaks
 *                    after paragraphing. Default true.
 * @return string Text which has been converted into correct paragraph tags.
 */
function wpautop($pee, $br = true)
{
    // phpcs:ignore
    static $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

    if (trim($pee) === '') {
        return '';
    }

    $pre_tags = [];

    // Just to make things a little easier, pad the end.
    $pee .= "\n";

    /*
     * Pre tags shouldn't be touched by autop.
     * Replace pre tags with placeholders and bring them back after autop.
     */
    if (strpos($pee, '<pre') !== false) {
        $pee_parts = explode('</pre>', $pee);
        $last_pee  = array_pop($pee_parts);
        $pee       = '';
        $i         = 0;

        foreach ($pee_parts as $pee_part) {
            $start = strpos($pee_part, '<pre');

            // Malformed html?
            if ($start === false) {
                $pee .= $pee_part;
                continue;
            }

            $name            = "<pre data-pre-tag-$i></pre>";
            $pre_tags[$name] = substr($pee_part, $start).'</pre>';

            $pee .= substr($pee_part, 0, $start).$name;
            $i++;
        }

        $pee .= $last_pee;
    }
    // Change multiple <br>s into two line breaks, which will turn into paragraphs.
    $pee = preg_replace('|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee);

    // Add a double line break above block-level opening tags.
    $pee = preg_replace('!(<'.$allblocks.'[\s/>])!', "\n\n$1", $pee);

    // Add a double line break below block-level closing tags.
    $pee = preg_replace('!(</'.$allblocks.'>)!', "$1\n\n", $pee);

    // Standardize newline characters to "\n".
    $pee = str_replace(["\r\n", "\r"], "\n", $pee);

    // Find newlines in all elements and add placeholders.
    $pee = _replace_in_html_tags($pee, ["\n" => ' <!-- wpnl --> ']);

    // Collapse line breaks before and after <option> elements so they don't get autop'd.
    if (strpos($pee, '<option') !== false) {
        $pee = preg_replace('|\s*<option|', '<option', $pee);
        $pee = preg_replace('|</option>\s*|', '</option>', $pee);
    }

    /*
     * Collapse line breaks inside <object> elements, before <param> and <embed> elements
     * so they don't get autop'd.
     */
    if (strpos($pee, '</object>') !== false) {
        $pee = preg_replace('|(<object[^>]*>)\s*|', '$1', $pee);
        $pee = preg_replace('|\s*</object>|', '</object>', $pee);
        $pee = preg_replace('%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee);
    }

    /*
     * Collapse line breaks inside <audio> and <video> elements,
     * before and after <source> and <track> elements.
     */
    if (strpos($pee, '<source') !== false || strpos($pee, '<track') !== false) {
        $pee = preg_replace('%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee);
        $pee = preg_replace('%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee);
        $pee = preg_replace('%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee);
    }

    // Collapse line breaks before and after <figcaption> elements.
    if (strpos($pee, '<figcaption') !== false) {
        $pee = preg_replace('|\s*(<figcaption[^>]*>)|', '$1', $pee);
        $pee = preg_replace('|</figcaption>\s*|', '</figcaption>', $pee);
    }

    // Remove more than two contiguous line breaks.
    $pee = preg_replace("/\n\n+/", "\n\n", $pee);

    // Split up the contents into an array of strings, separated by double line breaks.
    $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);

    // Reset $pee prior to rebuilding.
    $pee = '';

    // Rebuild the content as a string, wrapping every bit with a <p>.
    foreach ($pees as $tinkle) {
        $pee .= '<p>'.trim($tinkle, "\n")."</p>\n";
    }

    // Under certain strange conditions it could create a P of entirely whitespace.
    $pee = preg_replace('|<p>\s*</p>|', '', $pee);

    // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
    $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', '<p>$1</p></$2>', $pee);

    // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
    $pee = preg_replace('!<p>\s*(</?'.$allblocks.'[^>]*>)\s*</p>!', '$1', $pee);

    // In some cases <li> may get wrapped in <p>, fix them.
    $pee = preg_replace('|<p>(<li.+?)</p>|', '$1', $pee);

    // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
    $pee = preg_replace('|<p><blockquote([^>]*)>|i', '<blockquote$1><p>', $pee);
    $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);

    // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
    $pee = preg_replace('!<p>\s*(</?'.$allblocks.'[^>]*>)!', '$1', $pee);

    // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
    $pee = preg_replace('!(</?'.$allblocks.'[^>]*>)\s*</p>!', '$1', $pee);

    // Optionally insert line breaks.
    if ($br) {
        // Replace newlines that shouldn't be touched with a placeholder.
        $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', function ($matches) {
            return str_replace("\n", '<WPPreserveNewline />', $matches[0]);
        }, $pee);

        // Normalize <br>
        $pee = str_replace(['<br>', '<br/>'], '<br />', $pee);

        // Replace any new line characters that aren't preceded by a <br /> with a <br />.
        $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee);

        // Replace newline placeholders with newlines.
        $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
    }

    // If a <br /> tag is after an opening or closing block tag, remove it.
    $pee = preg_replace('!(</?'.$allblocks.'[^>]*>)\s*<br />!', '$1', $pee);

    // If a <br /> tag is before a subset of opening or closing block tags, remove it.
    $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
    $pee = preg_replace("|\n</p>$|", '</p>', $pee);

    // Replace placeholder <pre> tags with their original content.
    if (!empty($pre_tags)) {
        $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);
    }

    // Restore newlines in all elements.
    if (false !== strpos($pee, '<!-- wpnl -->')) {
        $pee = str_replace([' <!-- wpnl --> ', '<!-- wpnl -->'], "\n", $pee);
    }

    return $pee;
}

/**
 * Replace characters or phrases within HTML elements only.
 *
 * @param  string  $haystack  The text which has to be formatted.
 * @param  array  $replace_pairs  In the form array('from' => 'to', ...).
 * @return string The formatted text.
 */
function _replace_in_html_tags($haystack, $replace_pairs)
{
    // Find all elements.
    $textarr = _wp_html_split($haystack);
    $changed = false;

    // Optimize when searching for one item.
    if (1 === count($replace_pairs)) {
        // Extract $needle and $replace.
        [$needle, $replace] = [array_keys($replace_pairs)[0], array_values($replace_pairs)[0]];

        // Loop through delimiters (elements) only.
        for ($i = 1, $c = count($textarr); $i < $c; $i += 2) {
            if (false !== strpos($textarr[$i], $needle)) {
                $textarr[$i] = str_replace($needle, $replace, $textarr[$i]);
                $changed     = true;
            }
        }
    } else {
        // Extract all $needles.
        $needles = array_keys($replace_pairs);

        // Loop through delimiters (elements) only.
        for ($i = 1, $c = count($textarr); $i < $c; $i += 2) {
            foreach ($needles as $needle) {
                if (false !== strpos($textarr[$i], $needle)) {
                    $textarr[$i] = strtr($textarr[$i], $replace_pairs);
                    $changed     = true;
                    // After one strtr() break out of the foreach loop and look at next element.
                    break;
                }
            }
        }
    }

    if ($changed) {
        $haystack = implode($textarr);
    }

    return $haystack;
}

/**
 * Separate HTML elements and comments from the text.
 *
 * @param  string  $input  The text which has to be formatted.
 * @return array The formatted text.
 */
function _wp_html_split($input)
{
    return preg_split(_get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE);
}

/**
 * Retrieve the regular expression for an HTML element.
 *
 * @return string The regular expression
 */
function _get_html_split_regex()
{
    static $regex;

    // @formatter:off
    if (!$regex) {
        // phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
        $comments =
            '!'             // Start of comment, after the <.
            . '(?:'         // Unroll the loop: Consume everything until --> is found.
            .     '-(?!->)' // Dash not followed by end of comment.
            .     '[^\-]*+' // Consume non-dashes.
            . ')*+'         // Loop possessively.
            . '(?:-->)?';   // End of comment. If not found, match all input.

        $cdata =
            '!\[CDATA\['    // Start of comment, after the <.
            . '[^\]]*+'     // Consume non-].
            . '(?:'         // Unroll the loop: Consume everything until ]]> is found.
            .     '](?!]>)' // One ] not followed by end of comment.
            .     '[^\]]*+' // Consume non-].
            . ')*+'         // Loop possessively.
            . '(?:]]>)?';   // End of comment. If not found, match all input.

        $escaped =
            '(?='             // Is the element escaped?
            .    '!--'
            . '|'
            .    '!\[CDATA\['
            . ')'
            . '(?(?=!-)'      // If yes, which type?
            .     $comments
            . '|'
            .     $cdata
            . ')';

        $regex =
            '/('                // Capture the entire match.
            .     '<'           // Find start of element.
            .     '(?'          // Conditional expression follows.
            .         $escaped  // Find end of escaped element.
            .     '|'           // ... else ...
            .         '[^>]*>?' // Find end of normal element.
            .     ')'
            . ')/';
        // phpcs:enable
    }
    // @formatter:on

    return $regex;
}
