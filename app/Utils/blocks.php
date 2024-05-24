<?php

use TorMorten\Eventy\Facades\Events;

require_once __DIR__.'/wp-classes/class-wp-block-type.php';
require_once __DIR__.'/wp-classes/class-wp-block-type-registry.php';

/**
 * Parses blocks out of a content string.
 *
 * @param  string  $content  Post content.
 * @return array Array of parsed block objects.
 */
function parse_blocks($content)
{
    if (!class_exists('WP_Block_Parser')) {
        require_once __DIR__.'/block-serialization-default-parser/parser.php';
    }

    return app(WP_Block_Parser::class)->parse($content);
}

/**
 * Parses dynamic blocks out of `post_content` and re-renders them.
 *
 * @param  string  $content  Post content.
 * @return string Updated post content.
 */
function do_blocks($content)
{
    $blocks = parse_blocks($content);
    $output = '';

    foreach ($blocks as $block) {
        $output .= render_block($block)."\n";
    }

    return trim($output);
}

/**
 * Renders a single block into a HTML string.
 *
 * @param  array  $block  A single parsed block object.
 * @return string String of rendered HTML.
 */
function render_block($block)
{
    $source_block = $block;

    /**
     * Filters the block being rendered in render_block(), before it's processed.
     *
     * @param  array  $block  The block being rendered.
     * @param  array  $source_block  An un-modified copy of $block, as it appeared in the source content.
     */
    $block = apply_filters('render_block_data', $block, $source_block);

    $block_type    = WP_Block_Type_Registry::get_instance()->get_registered($block['blockName']);
    $is_dynamic    = $block['blockName'] && null !== $block_type && $block_type->is_dynamic();
    $block_content = '';
    $index         = 0;

    foreach ($block['innerContent'] as $chunk) {
        $block_content .= is_string($chunk) ? trim($chunk) : render_block($block['innerBlocks'][$index++]);
    }

    if (!is_array($block['attrs'])) {
        $block['attrs'] = [];
    }

    if ($block['blockName'] === null && trim($block['innerHTML']) === '') {
        $block_content = "\n";
    } elseif ($block['blockName'] === 'core/paragraph' && trim($block['innerHTML']) === '<p></p>') {
        $block_content = '';
    } elseif ($is_dynamic) {
        $block_content = trim($block_type->render($block['attrs'], $block_content));
    }

    /**
     * Filters the content of a single block.
     *
     * @param  string  $block_content  The block content about to be appended.
     * @param  array  $block  The full block, including name and attributes.
     */
    return apply_filters('render_block', trim($block_content), $block);
}

/**
 * Registers a block type.
 *
 * @param  string|WP_Block_Type  $name  Block type name including namespace, or alternatively a
 *                                   complete WP_Block_Type instance. In case a WP_Block_Type
 *                                   is provided, the $args parameter will be ignored.
 * @param  array  $args  {
 *     Optional. Array of block type arguments. Any arguments may be defined, however the
 *     ones described below are supported by default. Default empty array.
 *
 * @type callable $render_callback Callback used to render blocks of this block type.
 * }
 * @return WP_Block_Type|false The registered block type on success, or false on failure.
 */
function register_block_type($name, $args = [])
{
    return WP_Block_Type_Registry::get_instance()->register($name, $args);
}

/**
 * Unregisters a block type.
 *
 * @param  string|WP_Block_Type  $name  Block type name including namespace, or alternatively a
 *                                   complete WP_Block_Type instance.
 * @return WP_Block_Type|false The unregistered block type on success, or false on failure.
 */
function unregister_block_type($name)
{
    return WP_Block_Type_Registry::get_instance()->unregister($name);
}
