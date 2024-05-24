<?php

namespace App\Utils;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ContentParser
{
    public static function parseAnchors($content)
    {
        $anchors = static::parseBlocks($content, 'cgb/block-common-anchor');

        return $anchors
            ->pluck('attrs')
            ->reject(function ($anchor) {
                return empty($anchor['anchor']);
            })->unique('anchor')->map(function (array $anchor) {
                $anchor['anchorURL'] = $anchor['anchorURL'] ?? null;

                return $anchor + ['id' => Str::slug($anchor['anchor'])];
            })->filter();
    }

    public static function parseTOC($content): string
    {
        $flatHeadings = static::parseBlocks($content, 'core/heading')->map(function ($block) {
            $text = trim($block['innerHTML'] ?? $block['innerContent']);

            preg_match('/<(h2|h3|h4|h5|h6)[^>]*>/', $text, $matches);

            $level = (int) str_replace('h', '', $matches[1]);
            $text  = Str::ucfirst(clean($text));

            return compact('level', 'text');
        })->all();

        if (empty($flatHeadings)) {
            return '';
        }

        $toc        = '';
        $last_level = 0;

        foreach ($flatHeadings as $h) {
            $text  = trim($h['text']);
            $level = $h['level'];

            if ($level > $last_level) {
                $toc .= '<ol>';
            } else {
                $toc .= str_repeat('</li></ol>', $last_level - $level);
                $toc .= '</li>';
            }

            $id  = Str::slug($text);
            $toc .= "<li data-level='{$level}'><a href='#toc-{$id}'>{$text}</a>";

            $last_level = $level;
        }

        $toc .= str_repeat('</li></ol>', $last_level);

        return $toc;
    }

    public static function parseBlocks($content, $blockName): Collection
    {
        $blocks = array_filter(parse_blocks($content), function ($block) {
            return empty($block['name']);
        });

        $anchors = collect();

        foreach ($blocks as $block) {
            if ($block['blockName'] === $blockName) {
                $anchors->push($block);
                continue;
            }

            if (!empty($block['innerBlocks']) && in_array($block['blockName'], ['core/section', 'core/group'])) {
                foreach ($block['innerBlocks'] as $innerBlock) {
                    if ($innerBlock['blockName'] === $blockName) {
                        $anchors->push($innerBlock);
                        continue;
                    }
                }
            }
        }

        return $anchors;
    }
}
