<?php

namespace App\Dashboard\Layouts\Blocks;

use App\Model\Block;
use Core\Elements\Table\ID;
use Core\Elements\Table\LinkBuilder;
use Core\Elements\Table\Table;
use Core\Elements\Table\Column;

class BlockListTable extends Table
{
    /**
     * Define the table columns.
     *
     * @return Column[]
     */
    public function columns(): array
    {
        return [
            ID::make(),

            Column::make('raw_title', __('Block'))
                ->linkTo(function (LinkBuilder $linkBuilder, Block $block) {
                    $linkBuilder->route('dashboard.blocks.editor', $block->id);
                    $linkBuilder->openNewTab();
                    $linkBuilder->setAttribute('data-turbolinks', 'false');
                }),

            Column::make('link', __('ShortCode'))
                ->width('350px')
                ->displayUsing(function (Block $block) {
                    return view('platform.layouts.td.block-shortcode', compact('block'));
                }),

            Column::make('created_at', __('Ngày tạo'))
                ->width('120px')
                ->displayUsing(function (Block $block, $created_at) {
                    return optional($created_at)->diffForHumans();
                }),
        ];
    }
}
